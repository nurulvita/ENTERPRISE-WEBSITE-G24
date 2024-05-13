<?php
// Mulai sesi
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>G-24 | Dashboard Admin </title>
    <link rel="icon" type="image/x-icon" href="../../src/assets/img/G-24.ico" />
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
</head>

<body class="" data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="140">

    <!-- BEGIN LOADER -->
    <?php include 'layouts/load.php' ?>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <?php include 'layouts/navbar.php' ?>
    <!--  END NAVBAR  -->
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <?php include 'layouts/sidebar.php' ?>
        <!--  END SIDEBAR  -->

        <!-- Masukkan koneksi ke database Anda di sini -->
        <?php include '../../config/koneksi.php'; ?>

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="middle-content container-xxl p-0">
                    <div class="row layout-top-spacing">
                        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 mb-4">
                            <select class="form-select form-select" aria-label="Default select example" id="kategoriSelect">
                                <?php
                                $categories = ['Semua', 'galon', 'aksesoris', 'lainnya']; // Daftar kategori produk
                                foreach ($categories as $category) {
                                    // Tentukan apakah opsi ini yang harus dipilih berdasarkan kategori produk yang saat ini dipilih
                                    $selected = isset($_GET['kategori']) && $_GET['kategori'] == $category ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $category; ?>" <?php echo $selected; ?>><?php echo ucfirst($category); ?></option>
                                <?php } ?>
                            </select>
                        </div>

                    </div>

                    <div class="row" id="produkList">
                        <?php
                        $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : ''; // Ambil kategori dari URL
                        $query = "SELECT * FROM produk WHERE stok = 'tersedia'";
                        if ($kategori && $kategori != 'Semua') {
                            // Jika kategori yang dipilih bukan "Semua", tambahkan filter berdasarkan kategori
                            $query .= " AND kategori = '$kategori'";
                        }
                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <div class="col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card style-6">
                                        <img src="../../fotoproduk/<?php echo $row['gambar_produk']; ?>" class="card-img-top" alt="...">
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-12 mb-2">
                                                    <b><?php echo $row['nama_produk']; ?></b>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    Stok: <?php echo $row['stok']; ?>
                                                </div>
                                                <div class="col-12 text-end">
                                                    <p class="text-success mb-0">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            echo "<p>Tidak ada produk tersedia.</p>";
                        }
                        ?>
                    </div>

                </div>
            </div>
            <!--  BEGIN FOOTER  -->
            <?php include 'layouts/footer.php' ?>
            <!--  END FOOTER  -->
        </div>
        <!--  END CONTENT AREA  -->

        <!-- Tutup koneksi ke database jika diperlukan -->
        <?php mysqli_close($conn); ?>


    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <script src="src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="src/plugins/src/waves/waves.min.js"></script>
    <script src="layouts/vertical-light-menu/app.js"></script>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectElement = document.querySelector('.form-select');

            const urlParams = new URLSearchParams(window.location.search);
            const category = urlParams.get('kategori');

            for (const option of selectElement.options) {
                if (option.value === category) {
                    option.selected = true;
                    break;
                }
            }
        });
    </script>
    <script>
        var kategoriSelect = document.getElementById('kategoriSelect');

        kategoriSelect.addEventListener('change', function() {
            var kategori = kategoriSelect.value;

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get-produk?kategori=' + encodeURIComponent(kategori), true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('produkList').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        });
    </script>

</body>

</html>
