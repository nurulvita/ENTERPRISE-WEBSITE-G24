<?php

session_start();
include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username_admin']) && !isset($_SESSION['username_admin'])) {
    header('location: ../../login.php');
    exit();
}
if (isset($_SESSION['username_admin'])) {
    $username_session = $_SESSION['username_admin'];
} else {
    $username_session = "Sesi belum terbuat";
}

$sql = "SELECT total_harga, tanggal, total_barang FROM pemesanan";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tanggal = date('Y-m-d', strtotime($row['tanggal']));
        $pendapatan = $row['total_harga'];
        $barang = $row['total_barang'];

        if (isset($data[$tanggal])) {
            $data[$tanggal]['pendapatan'] += $pendapatan;
            $data[$tanggal]['barang'] += $barang;
        } else {
            $data[$tanggal] = [
                'pendapatan' => $pendapatan,
                'barang' => $barang
            ];
        }
    }
}

$sql2 = "SELECT nama_produk, jumlah FROM detail_pemesanan";
$result2 = $conn->query($sql2);

$data2 = [];
if ($result2->num_rows > 0) {
    while ($row2 = $result2->fetch_assoc()) {
        $nama_produk = $row2['nama_produk'];
        $jumlah = $row2['jumlah'];

        if (isset($data2[$nama_produk])) {
            $data2[$nama_produk]['jumlah'] += $jumlah;
        } else {
            $data2[$nama_produk] = [
                'nama_produk' => $nama_produk,
                'jumlah' => $jumlah
            ];
        }
    }
}
$data2 = array_values($data2);
function sortByJumlah($a, $b) {
    return $b['jumlah'] - $a['jumlah'];
}
usort($data2, 'sortByJumlah');


$sql3 = "SELECT MONTHNAME(tanggal) AS bulan, jenis_pemesanan, COUNT(*) AS jumlah_pemesanan FROM pemesanan WHERE jenis_pemesanan IN ('Antar', 'Beli Langsung') GROUP BY MONTHNAME(tanggal), jenis_pemesanan ORDER BY MONTH(tanggal), jenis_pemesanan";
$result3 = $conn->query($sql3);

$data3 = [];
while ($row3 = $result3->fetch_assoc()) { 
    $bulan = $row3['bulan']; 
    $jenis_pemesanan = $row3['jenis_pemesanan']; 
    $jumlah = $row3['jumlah_pemesanan']; 

    if (!isset($data3[$bulan])) {
        $data3[$bulan] = [
            'bulan' => $bulan,
            'Antar' => 0,
            'Beli Langsung' => 0
        ];
    }

    $data3[$bulan][$jenis_pemesanan] = $jumlah; 
}

$data3JSON = json_encode(array_values($data3));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>G-24 | Dashboard Admin </title>
    <link rel="icon" type="image/x-icon" href="src/assets/img/G-24.ico"/>
    <!-- Bootstrap CSS -->
    <link href="src/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet">
    <link href="layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet">
    <!-- ApexCharts CSS -->
    <link href="src/plugins/src/apex/apexcharts.css" rel="stylesheet">
</head>
<body class="layout-boxed">
    
    <?php include 'layouts/navbar.php'; ?>
    
    <div class="main-container" id="container">
        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <?php include 'layouts/sidebar.php'; ?>
        
        <div id="content" class="main-content">
        <div class="container">

            <br>
            <div class="col-xl-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4></h4> 
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <!-- Place the chart container -->
                        <div id="mixed-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4></h4> 
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <!-- Place the chart container -->
                        <div id="s-bar" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4></h4> 
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <!-- Place the chart container -->
                        <div id="s-col" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'layouts/footer.php'?>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Perfect Scrollbar JS -->
    <script src="src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <!-- Mousetrap JS -->
    <script src="src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <!-- Waves JS -->
    <script src="src/plugins/src/waves/waves.min.js"></script>
    <!-- ApexCharts JS -->
    <script src="src/plugins/src/apex/apexcharts.min.js"></script>
    <!-- Your Custom Scripts -->
    <script src="layouts/vertical-light-menu/app.js"></script>

    <script>
    // Convert PHP arrays to JavaScript arrays
    let data = <?= json_encode($data) ?>;

    // Buat array untuk tanggal, pendapatan, dan jumlah pengguna
    let tanggal = [];
    let pendapatan = [];
    let barang = [];

    // Iterasi melalui data dan masukkan ke dalam array yang sesuai
    for (const tanggalData in data) {
        if (data.hasOwnProperty(tanggalData)) {
            const element = data[tanggalData];
            tanggal.push(tanggalData);
            pendapatan.push(element.pendapatan);
            barang.push(element.barang);
        }
    }

    // Konfigurasi grafik
    var options = {
        chart: {
            height: 350,
            type: 'line',
            toolbar: {
                show: false,
            }
        },
        series: [{
            name: 'Total Pendapatan',
            type: 'column',
            data: pendapatan
        }, {
            name: 'Jumlah Barang',
            type: 'line',
            data: barang
        }],
        stroke: {
            width: [0, 4]
        },
        title: {
            text: 'Traffic Pendapatan Perhari'
        },
        labels: tanggal,
        xaxis: {
            type: 'date'
        },
        yaxis: [{
            title: {
                text: 'Total Pendapatan (Rp)',
            },
        },{
            opposite: true,
            title: {
                text: 'Jumlah Barang'
            }
        }]
    };
    var chart = new ApexCharts(document.querySelector("#mixed-chart"), options);
    chart.render();
</script>


<script>
    let data2 = <?= json_encode($data2) ?>;

    // Mendefinisikan arrays untuk nama produk dan jumlah
    let nama_produk = [];
    let jumlah = [];

    // Mengisi arrays dengan data yang sesuai
    data2.forEach(item => {
        nama_produk.push(item.nama_produk);
        jumlah.push(item.jumlah);
    });

    // Konfigurasi grafik
    var sBar = {
        chart: {
            height: 350,
            type: 'bar',
            toolbar: {
                show: false,
            }
        },
        plotOptions: {
            bar: {
                horizontal: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        series: [{
            name: 'Total Pembelian',
            data: jumlah 
        }],
        xaxis: {
            categories: nama_produk, 
        },
        title: {
            text: 'Produk terlaris'
        }
    };

    // Menginisialisasi grafik
    var chart = new ApexCharts(document.querySelector("#s-bar"), sBar);

    // Render grafik
    chart.render();
</script>

<script>
    var data3 = <?php echo $data3JSON; ?>;

    var categories = data3.map(function(item) {
        return item.bulan;
    });

    var antarData = data3.map(function(item) {
        return item.Antar;
    });

    var langsungData = data3.map(function(item) {
        return item["Beli Langsung"];
    });

    var sCol = {
    chart: {
        height: 350,
        type: 'bar',
        toolbar: {
        show: false,
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'  
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    series: [{
        name: 'Langsung',
        data: langsungData
    }, {
        name: 'Antar',
        data: antarData
    }],
    xaxis: {
        categories: categories,
    },
    yaxis: {
        title: {
            text: 'Jumlah'
        }
    },
    fill: {
        opacity: 1

    },
    tooltip: {
        y: {
            formatter: function (val) {
                return "Dipilih " + val + " kali"
            }
        }
    },
    title: {
            text: 'Jenis pemesanan terlaris'
    }
    }

    var chart = new ApexCharts(
    document.querySelector("#s-col"),
    sCol
    );

    chart.render();

</script>

</body>
</html>
