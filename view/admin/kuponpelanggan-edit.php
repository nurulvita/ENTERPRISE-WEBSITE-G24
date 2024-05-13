<?php
session_start();
include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username_admin']) && !isset($_SESSION['username_admin'])) {
    header('location: ../../login');
    exit();
}


if(isset($_GET['id_pelanggan'])) {
    $id_pelanggan = $_GET['id_pelanggan'];

    $query = "SELECT * FROM pelanggan WHERE id_pelanggan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_pelanggan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row_pelanggan = $result->fetch_assoc();
        $nama_pelanggan = $row_pelanggan['nama'];
        $kode_member = $row_pelanggan['kode_member'];
        $kupon = $row_pelanggan['kupon'];
    } else {
        echo "Pelanggan tidak ditemukan.";
    }
} else {
    echo "ID Pelanggan tidak ditemukan.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['kupon'], $_POST['id_pelanggan'])) {
        $kupon = $_POST['kupon'];
        $id_pelanggan = $_POST['id_pelanggan']; // ID pelanggan yang akan diupdate

        $query = "UPDATE pelanggan SET kupon=? WHERE id_pelanggan=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $kupon, $id_pelanggan);
        $result = $stmt->execute();

        if ($result) {
            header("Location: pelanggan-list");
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error: Semua input harus diisi";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>G-24 | Dashboard Admin </title>
    <link rel="icon" type="image/x-icon" href="src/assets/img/G-24.ico"/>
    <link href="layouts/vertical-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="layouts/vertical-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="layouts/vertical-light-menu/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link href="src/assets/css/light/scrollspyNav.css" rel="stylesheet" type="text/css" />
    <link href="src/assets/css/dark/scrollspyNav.css" rel="stylesheet" type="text/css" />
</head>
<body class="layout-boxed" data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100">
    
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

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="container">
                <div class="container">
                    <br>
                    <form id="kuponpelanggan-edit" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div id="flLoginForm" class="col-lg-12 layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">                                
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Form Edit Pelanggan</h4>
                                        </div>                                                                        
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
                                    <form class="row g-3">
                                        <div class="col-12">
                                            <label class="visually-hidden" for="inlineFormInputGroupUsername"></label>   
                                            <div class="col-12">
                                                <label for="inputNama" class="form-label">Nama Lengkap</label>
                                                <input type="text" class="form-control" id="inputnama" name="nama" placeholder="Nama Kosong" value="<?php echo $nama_pelanggan; ?>" readonly>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-12">
                                            <label for="inputAddress" class="form-label">Kode Member</label>
                                            <input type="text" class="form-control" id="inputkode" name="kodemember" placeholder="Kode Kosong" value="<?php echo $kode_member; ?>" readonly>
                                        </div>
                                        <br>
                                        <div class="col-12">
                                            <label for="inputnohp" class="form-label">Kupon</label>
                                            <input type="number" class="form-control" id="inputkupon" name="kupon" placeholder="Masukkan Jumlah Kupon" value="<?php echo $kupon; ?>" oninput="validasiKupon(this)">
                                        </div>
                                        <br>
                                        <input type="hidden" name="id_pelanggan" value="<?php echo $id_pelanggan; ?>">
                                        <div class="container">
                                            <div class="row justify-content-center">
                                                <div class="col-12">
                                                    <a href="pelanggan-list" class="btn btn-danger">Kembali</a>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
  
                </div>
            </div>

            <!--  BEGIN FOOTER  -->
            <?php include 'layouts/footer.php'?>
            <!--  END FOOTER  -->
            
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="src/plugins/src/waves/waves.min.js"></script>
    <script src="layouts/vertical-light-menu/app.js"></script>
    <script src="src/plugins/src/highlight/highlight.pack.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="src/assets/js/scrollspyNav.js"></script>

    <script>
        function validasiKupon(input) {
            // Konversi nilai input menjadi bilangan bulat
            var nilai = parseInt(input.value);

            // Jika nilai kurang dari 0, atur nilainya menjadi 0
            if (nilai < 0) {
                input.value = 0;
            }
        }
    </script>
</body>
</html>