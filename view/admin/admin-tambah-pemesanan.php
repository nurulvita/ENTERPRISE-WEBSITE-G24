<?php
session_start();
include_once('../../config/koneksi.php');

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (!isset($_COOKIE['username_admin']) && !isset($_SESSION['username_admin'])) {
    header('location: ../../login.php');
    exit();
}

if(isset($_POST['hapus_produk'])) {
    $id = $_POST['hapus_produk'];
    $query_hapus_produk = "DELETE FROM admin_keranjang WHERE id = $id";
    if(mysqli_query($conn, $query_hapus_produk)) {
        ?>
        <script>
            alert("Produk berhasil dihapus dari keranjang.");
        </script>
        <?php
        header('location: admin-tambah-pemesanan');
        exit; 
    } else {
        ?>
        <script>
            alert("Gagal menghapus produk dari keranjang.");
        </script>
        <?php
    }
    exit; 
}


if (isset($_POST["update_jumlah"])) {
    $id_produk = $_POST["id_produk"];
    $jumlah = $_POST["jumlah"];

    $query_update_jumlah = "UPDATE admin_keranjang SET jumlah = $jumlah WHERE id_produk = $id_produk";
    $result_update_jumlah = mysqli_query($conn, $query_update_jumlah);

    if ($result_update_jumlah) {
        echo json_encode(array("status" => "success"));
        exit();
    } else {

        echo json_encode(array("status" => "error", "message" => "Gagal memperbarui jumlah produk dalam keranjang."));
        exit();
    }
}

?>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    date_default_timezone_set('Asia/Makassar');

    $tanggal = date("Y-m-d H:i:s");
    $status = "Dikonfirmasi";

    $nomor_pemesanan = 'P' . date("Ymd") . rand(10, 99);

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']); 
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $jenis_pemesanan = mysqli_real_escape_string($conn, $_POST['jenis_pemesanan']); 

    $query_total = "SELECT SUM(harga * jumlah) AS total_harga, SUM(jumlah) AS total_barang FROM admin_keranjang";
    $result_total = mysqli_query($conn, $query_total);

    if (mysqli_num_rows($result_total) > 0) {
        $row_total = mysqli_fetch_assoc($result_total);
        $total_harga = $row_total['total_harga'];
        $total_barang = $row_total['total_barang'];

        if ($total_harga > 0 && $total_barang > 0) {
            $ongkos_kirim = 0;

            if ($jenis_pemesanan == "Antar") {
                $ongkos_kirim = 7000; 
                $status = "Diantar";
                $total_harga += $ongkos_kirim;
            } else if ($jenis_pemesanan == "Beli Langsung") {
                $status = "Selesai";
            }

            $query_insert_pemesanan = "INSERT INTO pemesanan (no_pemesanan, username, nama, alamat, total_barang, total_harga, tanggal, jenis_pemesanan, status) 
                                    VALUES ('$nomor_pemesanan', '$username', '$nama', '$alamat', $total_barang, $total_harga, '$tanggal', '$jenis_pemesanan', '$status')";

            if (mysqli_query($conn, $query_insert_pemesanan)) {
                $id_pemesanan = mysqli_insert_id($conn);
                $query_insert_detail = "INSERT INTO detail_pemesanan (no_pemesanan, produk_id, username, nama_produk, jumlah, harga) 
                                    SELECT '$nomor_pemesanan', admin_keranjang.id_produk, '$username', produk.nama_produk, admin_keranjang.jumlah, admin_keranjang.harga 
                                    FROM admin_keranjang 
                                    INNER JOIN produk ON admin_keranjang.id_produk = produk.id_produk";

                if (mysqli_query($conn, $query_insert_detail)) {
                    $query_delete = "DELETE FROM admin_keranjang";
                    if (mysqli_query($conn, $query_delete)) {
                        echo "<script>alert('Data pemesanan berhasil disimpan');</script>";
                        echo "<script>window.location.href = 'admin-pemesanan';</script>";
                    } else {
                        echo "<script>alert('Data pemesanan berhasil disimpan, tetapi terjadi kesalahan saat mengosongkan keranjang: " . mysqli_error($conn) . "');</script>";
                        echo "<script>window.location.href = 'admin-tambah-pemesanan';</script>";
                    }
                } else {
                    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                }
            } else {
                echo "<script>alert('Terjadi Kesalahan Harap Periksa Kembali!');</script>";
                echo "<script>window.location.href = 'admin-tambah-pemesanan';</script>";
            }
        } else {
            echo "<script>alert('Tidak bisa menyimpan pemesanan karena keranjang kosong.');</script>";
            echo "<script>window.location.href = 'admin-tambah-pemesanan';</script>";
        }
    } else {
        echo "<script>alert('Tidak bisa menyimpan pemesanan karena keranjang kosong.');</script>";
        echo "<script>window.location.href = 'admin-tambah-pemesanan';</script>";
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>G-24 | Dashboard Admin </title>
    <link rel="icon" type="image/x-icon" href="../../src/assets/img/G-24.ico"/>
    <link href="layouts/vertical-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="layouts/vertical-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="layouts/vertical-light-menu/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="src/plugins/src/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="src/plugins/src/filepond/filepond.min.css">
    <link rel="stylesheet" href="src/plugins/src/filepond/FilePondPluginImagePreview.min.css">

    <link href="src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
    <link href="src/plugins/css/light/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <link href="src/assets/css/light/apps/invoice-add.css" rel="stylesheet" type="text/css" />

    <link href="src/plugins/css/dark/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
    <link href="src/plugins/css/dark/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <link href="src/assets/css/dark/apps/invoice-add.css" rel="stylesheet" type="text/css" />

    <!--  END CUSTOM STYLE FILE  -->
    
</head>
<body class="layout-boxed">
    
    <!-- BEGIN LOADER -->
    <?php include 'layouts/load.php'?>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <?php include 'layouts/navbar.php'?>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="cs-overlay"></div>
        <div class="search-overlay"></div>

            <!--  BEGIN SIDEBAR  -->
            <?php include 'layouts/sidebar.php'?>
         <!--  END SIDEBAR  -->

        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="middle-content container-xxl p-0 pt-4">
                    
                    <!-- Form dan Tabel Produk -->
                    <div class="row form-tabel-produk">
                        <div class="col-xxl-12">
                            <div class="table-responsive">
                                <!-- Tabel Produk -->
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Harga</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $query_produk = "SELECT p.id_produk, p.nama_produk, p.harga, COALESCE(k.jumlah, 0) AS jumlah
                                                    FROM produk p
                                                    LEFT JOIN admin_keranjang k ON p.id_produk = k.id_produk";
                                    $result_produk = mysqli_query($conn, $query_produk);

                                    if (mysqli_num_rows($result_produk) > 0) {
                                        while ($row = mysqli_fetch_assoc($result_produk)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row['nama_produk']; ?></td>
                                                <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                                <td>
                                                    <?php if ($row['jumlah'] == 0): ?>
                                                        <form action="proses-tambah-produk" method="post">
                                                            <input type="hidden" name="produk_id" value="<?php echo $row['id_produk']; ?>">
                                                            <button type="submit" class="btn btn-primary">Add</button>
                                                        </form>
                                                    <?php else: ?>
                                                        <button class="btn btn-secondary btn-minus" onclick="kurangiJumlah(<?php echo $row['id_produk']; ?>)">-</button>
                                                        <span class="btn btn-light" id="jumlah<?php echo $row['id_produk']; ?>"><?php echo $row['jumlah']; ?></span>
                                                        <button class="btn btn-primary btn-plus" onclick="tambahJumlah(<?php echo $row['id_produk']; ?>)">+</button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='3'>Tidak ada produk tersedia.</td></tr>";
                                    }
                                    ?>
                                </tbody>

                                </table>
 
                            </div>
                        </div>
                    </div>


                    <!-- Form Input -->
                    <form method="POST">

                    <div class="row invoice layout-top-spacing layout-spacing invoice-data" >
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="doc-container">
                                <div class="row">
                                <?php
                                $query_admin_keranjang = "SELECT * FROM admin_keranjang";
                                $result_admin_keranjang = mysqli_query($conn, $query_admin_keranjang);
                                ?>

                                <div class="col-xl-12">
                                    <div class="invoice-content">
                                        <div class="invoice-detail-body">
                                            <!-- Form -->
                                            <div class="invoice-detail-header ">
                                                <div class="row justify-content-between">
                                                    <div class="col-xl-5 invoice-address-client">
                                                        <h4>Faktur Untuk:-</h4>
                                                        <div class="invoice-address-client-fields">
                                                            <!-- Select Box Kode Member -->
                                                            <div class="form-group row">
                                                                <label for="kode-member" class="col-sm-3 col-form-label col-form-label-sm">Member</label>
                                                                <div class="col-sm-9">
                                                                <select class="form-control form-control-sm" id="kode-member" name="username" required>
                                                                    <option value="" disabled selected>Pilih Member</option>
                                                                    <option value="NON-MEMBER">NON-MEMBER</option>
                                                                    <?php
                                                                    $query_member = "SELECT username FROM pelanggan";
                                                                    $result_member = mysqli_query($conn, $query_member);
                                                                    while ($row_member = mysqli_fetch_assoc($result_member)) {
                                                                        echo "<option value='" . $row_member['username'] . "'>" . $row_member['username'] . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                                </div>
                                                            </div>
                                                            <!-- Input Form -->
                                                            <div class="form-group row">
                                                                <label for="client-name" class="col-sm-3 col-form-label col-form-label-sm">Nama</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="client-name" name="nama" placeholder="Masukkan Nama">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="client-address" class="col-sm-3 col-form-label col-form-label-sm">Alamat</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="client-address" name="alamat" placeholder="Masukkan Alamat">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="jenis-pemesanan" class="col-sm-3 col-form-label col-form-label-sm">Jenis Pemesanan</label>
                                                                <div class="col-sm-9">
                                                                <select class="form-control form-control-sm" id="jenis-pemesanan" name="jenis_pemesanan" required>
                                                                    <option value="" disabled selected>Pilih Jenis Pemesanan</option>
                                                                    <option value="Antar">Antar</option>
                                                                    <option value="Beli Langsung">Beli Langsung</option>
                                                                </select>

                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Form -->
                                            <!-- Table Produk -->
                                            <div class="invoice-detail-items">
                                                <div class="table-responsive">
                                                    <table class="table item-table">
                                                        <thead>
                                                            <tr>
                                                                <th class=""></th>
                                                                <th>Barang</th>
                                                                <th class="">Harga</th>
                                                                <th class="">Kuantitas</th>
                                                                <th class="text-right">Total Harga</th>
                                                            </tr>
                                                            <tr aria-hidden="true" class="mt-3 d-block table-row-hidden"></tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if (mysqli_num_rows($result_admin_keranjang) > 0) {
                                                                while ($row_admin_keranjang = mysqli_fetch_assoc($result_admin_keranjang)) {
                                                                    // echo "<tr id='produk_" . $row_admin_keranjang['id_produk'] . "'>"; 
                                                                    echo "<td class='delete-item-row' onclick=\"hapusProduk('" . $row_admin_keranjang['id'] . "')\">";

                                                                    echo "<ul class='table-controls'>";
                                                                    echo "<li>";
                                                                    echo "<a href='javascript:void(0);' class='delete-item' data-toggle='tooltip' data-placement='top' title='Delete'>";
                                                                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-x-circle'>";
                                                                    echo "<circle cx='12' cy='12' r='10'></circle>";
                                                                    echo "<line x1='15' y1='9' x2='9' y2='15'></line>";
                                                                    echo "<line x1='9' y1='9' x2='15' y2='15'></line>";
                                                                    echo "</svg>";
                                                                    echo "</a>";
                                                                    echo "</li>";
                                                                    echo "</ul>";
                                                                    echo "</td>";
                                                                    echo "<td class='description'>";
                                                                    $query_produk = "SELECT nama_produk FROM produk WHERE id_produk = " . $row_admin_keranjang['id_produk'];
                                                                    $result_produk = mysqli_query($conn, $query_produk);
                                                                    if (mysqli_num_rows($result_produk) > 0) {
                                                                        $row_produk = mysqli_fetch_assoc($result_produk);
                                                                        echo $row_produk['nama_produk'];
                                                                    } else {
                                                                        echo "Tidak ada produk";
                                                                    }
                                                                    echo "</td>";
                                                                    echo "<td class='rate'>";
                                                                    echo "<span id='price' class='price'>Rp " . number_format($row_admin_keranjang['harga'], 0, ',', '.') . "</span>";
                                                                    echo "</td>";
                                                                    echo "<td class='text-right qty'>";
                                                                    echo "<span class='quantity'>" . $row_admin_keranjang['jumlah'] . "</span>";
                                                                    echo "</td>";
                                                                    echo "<td class='text-right amount'>";
                                                                    echo "<span class='editable-amount'></span> ";
                                                                    echo "<span class='total-amount'>Rp " . number_format($row_admin_keranjang['harga'] * $row_admin_keranjang['jumlah'], 0, ',', '.') . "</span>";
                                                                    echo "</td>";
                                                                    echo "</tr>";
                                                                    
                                                                    

                                                                }
                                                            } else {
                                                                echo "<tr><td colspan='5'>Keranjang kosong.</td></tr>";
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- End Table Produk -->
                                        </div>
                                    </div>
                                </div>

                                    <!-- End Form Input -->

                                    <!-- Tombol Aksi -->
                                    <div class="col-xl-12">
                                        <div class="invoice-actions-btn">
                                            <div class="invoice-action-btn">
                                                <div class="row">
                                                    <!-- <div class="col-xl-12 col-md-4"> -->
                                                        <button type="submit" class="btn btn-success btn-download" name="simpan_pemesanan">Save</button>
                                                    <!-- </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Tombol Aksi -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>

    <!-- Footer -->
    <?php include 'layouts/footer.php'?>
    <!-- End Footer -->
    </div>


    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="src/plugins/src/global/vendors.min.js"></script>
    <script src="src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="src/plugins/src/waves/waves.min.js"></script>
    <script src="layouts/vertical-light-menu/app.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <script src="src/plugins/src/filepond/filepond.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginFileValidateType.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImagePreview.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImageCrop.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImageResize.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImageTransform.min.js"></script>
    <script src="src/plugins/src/filepond/filepondPluginFileValidateSize.min.js"></script>
    <script src="src/plugins/src/flatpickr/flatpickr.js"></script>
    <script src="src/assets/js/apps/invoice-add.js"></script>
    <script>
        document.getElementById("no-pemesanan").value = "<?php echo $nomor_pemesanan; ?>";
    </script>
    
<script>
    function tambahJumlah(id_produk) {
        var jumlahSpan = document.getElementById('jumlah' + id_produk);
        var jumlah = parseInt(jumlahSpan.innerText);
        jumlah++;
        updateJumlah(id_produk, jumlah);
    }

    function kurangiJumlah(id_produk) {
        var jumlahSpan = document.getElementById('jumlah' + id_produk);
        var jumlah = parseInt(jumlahSpan.innerText);
        if (jumlah > 1) {
            jumlah--;
            updateJumlah(id_produk, jumlah);
        }
    }
    function updateJumlah(id_produk, jumlah) {
    $.ajax({
        url: 'admin-tambah-pemesanan', 
        type: 'POST',
        dataType: 'json',
        data: {
            update_jumlah: true,
            id_produk: id_produk,
            jumlah: jumlah
        },
        success: function(response) {
            if (response.status === 'success') {
                var jumlahSpan = document.getElementById('jumlah' + id_produk);
                jumlahSpan.innerText = jumlah;
                setTimeout(function() {
                    window.location.reload();
                }, 0);
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}
</script>

    <script>
        function hapusProduk(id) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                var form = document.createElement('form');
                form.method = 'post';
                form.action = '';
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'hapus_produk';
                input.value = id;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }

    document.addEventListener("DOMContentLoaded", function() {
               const currentDate = new Date();

                const options = {
                    timeZone: "Asia/Makassar",
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit",
                    hour: "2-digit",
                    minute: "2-digit",
                    second: "2-digit"
                };

                const formattedDateTime = currentDate.toLocaleString("en-US", options);

                document.getElementById("kalender").value = formattedDateTime;
        const formattedDate = currentDate.toISOString().slice(0, 10).replace(/-/g, "");
        const randomNumber = Math.floor(Math.random() * 90 + 10); 
        const invoiceNumber = `P${formattedDate}${randomNumber}`;
        
        document.getElementById("number").value = invoiceNumber;
    });
    
    function formatRupiah(angka) {
        var number_string = angka.toString(),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/g);
        
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return 'Rp ' + rupiah;
    }
    $('.product-select').change(function() {
        var price = $(this).children('option:selected').data('price');
        
        $('#price').val(formatRupiah(price));
    });

    // Fungsi untuk menghitung total amount
    function calculateTotal(row) {
        var price = parseFloat(row.find('.price').val().replace(/\D/g, ''));
        var quantity = parseInt(row.find('.quantity').val());
        var total = price * quantity;
        row.find('.total-amount').text(formatRupiah(total));
    }

        $(document).ready(function() {
            // Fungsi untuk mengonversi angka menjadi format mata uang Rupiah
            function formatRupiah(angka) {
                var reverse = angka.toString().split('').reverse().join(''),
                    ribuan = reverse.match(/\d{1,3}/g);
                ribuan = ribuan.join('.').split('').reverse().join('');
                return 'Rp ' + ribuan;
            }


            // Fungsi untuk menghitung total amount
            function calculateTotal(row) {
                var price = parseFloat(row.find('.price').val().replace(/\D/g, '')); 
                var quantity = parseInt(row.find('.quantity').val());
                var total = price * quantity;
                row.find('.total-amount').text(formatRupiah(total));
            }


            $('.quantity').on('input', function() {
                var row = $(this).closest('tr');
                calculateTotal(row); 
            });
        });
        
    $('.delete-item').click(function() {
        var row = $(this).closest('tr');
        row.remove();
    });

    document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("buat-pemesanan").addEventListener("click", function() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "admin-tambah-pemesanan", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText);
            }
        };
        
        xhr.send("simpan_pemesanan=true");
    });
});

</script>
<script>
    var buatPemesananBtn = document.getElementById('buat-pemesanan');

    buatPemesananBtn.addEventListener('click', function() {
        buatPemesananBtn.style.display = 'none';

        var formTabelProduk = document.querySelector('.form-tabel-produk');
        formTabelProduk.style.display = 'block';
    });
</script>

<script>

    function addProductRow(id, name, price) {
        var html = `
            <tr>
                <td class="delete-item-row">
                    <ul class="table-controls">
                        <li>
                            <a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </td>
                <td class="description">${name}</td>
                <td class="rate">
                    <input type="text" class="form-control form-control-sm price" value="${price}" readonly>
                </td>
                <td class="text-right qty">
                    <input type="number" class="form-control form-control-sm quantity" placeholder="Quantity">
                </td>
                <td class="text-right amount">
                    <span class="editable-amount"></span> 
                    <span class="total-amount"></span>
                </td>
            </tr>`;
        $('#produk-container').append(html);
    }

    $('.product-select').change(function() {
        var productId = $(this).val();
        var productName = $(this).find('option:selected').text();
        var productPrice = $(this).find('option:selected').data('price');
        addProductRow(productId, productName, productPrice);
    });
</script>
<script>
    document.getElementById('kode-member').addEventListener('change', function() {
    var username = this.value;

    if (username !== '') {
        fetch('getdata?username=' + username)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('client-name').value = data.nama;
                document.getElementById('client-address').value = data.alamat;
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    } else {
        document.getElementById('client-name').value = '';
        document.getElementById('client-address').value = '';
    }
});

</script>



</body>
</html>