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


if(isset($_GET['id_produk'])) {
    $id_produk = $_GET['id_produk'];

    $query = "SELECT * FROM produk WHERE id_produk = $id_produk";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nama_produk = $row['nama_produk'];
        $gambar_produk = $row['gambar_produk'];
        $stokProduk = $row['stok'];
        $harga = $row['harga'];
        $kategori = $row['kategori'];
        $unit = $row['unit'];
    } else {
        echo "Produk tidak ditemukan.";
    }
} else {
    echo "ID Produk tidak ditemukan.";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['nama_produk'], $_POST['stok'], $_POST['kategori'], $_POST['unit'], $_POST['harga'], $_POST['id_produk'])) {
        
        $nama_produk = $_POST['nama_produk'];
        $kategori = $_POST['kategori'];
        $unit = $_POST['unit'];
        $harga = $_POST['harga'];
        $id_produk = $_POST['id_produk']; // ID produk yang akan diupdate

        $stokProduk = $_POST['stok'];

        if(isset($_FILES['foto_produk']) && $_FILES['foto_produk']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Proses gambar produk
            $foto_produk = $_FILES['foto_produk'];
            $nama_file = $foto_produk['name'];
            $ukuran_file = $foto_produk['size'];
            $tmp_file = $foto_produk['tmp_name'];
            $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
            $allowed_ext = array("jpg", "jpeg", "png");

            $lokasi_simpan = "../../fotoproduk/";

            if (!in_array($ext, $allowed_ext)) {
                echo "<script>alert('Ekstensi file tidak diizinkan');window.history.back();</script>";
                exit;
            }

            if (!move_uploaded_file($tmp_file, $lokasi_simpan . $nama_file)) {
                echo "Error: Gagal mengupload file";
                exit;
            }
        }

        $query = "UPDATE produk SET nama_produk='$nama_produk', stok='$stokProduk', kategori='$kategori', unit='$unit', harga='$harga'";

        if(isset($nama_file)) {
            $query .= ", gambar_produk='$nama_file'";
        }

        $query .= " WHERE id_produk=$id_produk";

        $result = $conn->query($query);

        if ($result) {
            header("Location: produk-list");
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

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link rel="stylesheet" href="src/plugins/src/filepond/filepond.min.css">
    <link rel="stylesheet" href="src/plugins/src/filepond/FilePondPluginImagePreview.min.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/src/tagify/tagify.css">
    
    <link rel="stylesheet" type="text/css" href="src/assets/css/light/forms/switches.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/css/light/editors/quill/quill.snow.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/css/light/tagify/custom-tagify.css">
    <link href="src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" type="text/css" href="src/assets/css/dark/forms/switches.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/css/dark/editors/quill/quill.snow.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/css/dark/tagify/custom-tagify.css">
    <link href="src/plugins/css/dark/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    
    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link rel="stylesheet" href="src/assets/css/light/apps/ecommerce-create.css">
    <link rel="stylesheet" href="src/assets/css/dark/apps/ecommerce-create.css">
    <!--  END CUSTOM STYLE FILE  -->

    <style>
        .custom-file-input {
            cursor: pointer;
            padding: 7px 12px;
            width: 100%;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background-color: #fff;
        }

        .custom-file-label {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .custom-file-input:hover {
            background-color: #f8f9fa;
            border-color: #b1b7c3;
        }

        .custom-file-input:focus {
            outline: none;
            box-shadow: none;
            border-color: #80bdff;
        }

        .custom-file-input:valid ~ .custom-file-label::after {
            content: "File telah dipilih";
        }

    </style>
</head>
<body class="">
    
    <!-- BEGIN LOADER -->
    <?php include 'layouts/load.php'?>
    <!--  END LOADER -->

    <?php include 'layouts/navbar.php'?>

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <?php include 'layouts/sidebar.php'?>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">

            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">
                    <form id="edit-produk" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                        <div class="row mb-4 layout-spacing layout-top-spacing">
                            <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="widget-content widget-content-area ecommerce-create-section">
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="inputEmail3" placeholder="Nama Produk" name="nama_produk" value="<?php echo $nama_produk; ?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8">
                                            <label for="product-images">Masukkan Gambar</label>
                                            <div class="multiple-file-upload">
                                                <input type="file" class="custom-file-input form-control" name="foto_produk" onchange="displayImage(this)">
                                                <span id="gambar-error" style="color: red;"></span>
                                            </div>
                                        </div>

                                        <img id="preview-image" src="../../fotoproduk/<?php echo $gambar_produk; ?>" alt="Preview Image" style="max-width: 50%; max-height: 300px; margin-top: 10px;">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-xxl-12 col-xl-8 col-lg-8 col-md-7 mt-xxl-0 mt-4">
                                        <div class="widget-content widget-content-area ecommerce-create-section">
                                            <div class="row">
                                            <div class="col-xxl-12 mb-4">
                                                <label for="stock">Stok</label>
                                                <select class="form-select" id="stock" name="stok">
                                                    <option <?php if ($stokProduk == "tersedia") echo 'selected="selected"'; ?>>Tersedia</option>
                                                    <option <?php if ($stokProduk == "tidak tersedia") echo 'selected="selected"'; ?>>Tidak Tersedia</option>
                                                </select>
                                            </div>
                                                <div class="col-xxl-12 col-md-6 mb-4">
                                                    <label for="category">Kategori</label>
                                                    <select class="form-select" id="category" name="kategori">
                                                        <option selected>Pilih kategori</option>
                                                        <option <?php if ($kategori == "galon") echo 'selected="selected"'; ?>>Galon</option>
                                                        <option <?php if ($kategori == "aksesoris") echo 'selected="selected"'; ?>>Aksesoris</option>
                                                    </select>
                                                </div>
                                                <div class="col-xxl-12 col-md-6 mb-4">
                                                    <label for="unit">Unit</label>
                                                    <select class="form-select" id="unit" name="unit">
                                                        <option value="per pcs" <?php if ($unit == "per pcs") echo 'selected="selected"'; ?>>Per Pcs</option>
                                                        <option value="per galon" <?php if ($unit == "per galon") echo 'selected="selected"'; ?>>Per Galon</option>
                                                        <option value="per bungkus" <?php if ($unit == "per bungkus") echo 'selected="selected"'; ?>>Per Bungkus</option>
                                                        <option value="per item" <?php if ($unit == "per item") echo 'selected="selected"'; ?>>Per Item</option>
                                                    </select>
                                                </div>

                                                <div class="col-xxl-12 col-md-6 mb-4">
                                                    <label for="price">Harga</label>
                                                    <input type="text" class="form-control" id="price" name="harga" value="<?php echo $harga; ?>">
                                                </div>
                                                <input type="hidden" name="id_produk" value="<?php echo $id_produk; ?>">
                                                <div class="container">
                                                    <div class="row justify-content-center">
                                                        <div class="col-auto mt-4">
                                                            <a href="produk-list" class="btn btn-danger">Kembali</a>
                                                            <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <?php include 'layouts/footer.php'?>
    <!-- END MAIN CONTAINER -->
    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="src/assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="src/bootstrap/js/popper.min.js"></script>
    <script src="src/bootstrap/js/bootstrap.min.js"></script>
    <script src="src/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="src/assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="src/assets/js/custom.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="src/plugins/src/filepond/filepond.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImagePreview.min.js"></script>
    <script src="src/plugins/src/filepond/jquery.filepond.js"></script>
    <script src="src/plugins/src/filepond/custom-filepond.js"></script>
    <script src="src/plugins/src/tagify/tagify.js"></script>
    <script src="src/plugins/src/tagify/custom-tagify.js"></script>
    <script src="src/plugins/js/quill/quill.js"></script>
    <script src="src/plugins/js/quill/custom-quill.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

    <script>
function displayImage(input) {
    var file = input.files[0];
    
    var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;

    if (!allowedExtensions.exec(input.value)) {
        document.getElementById('gambar-error').innerHTML = 'Ekstensi file gambar tidak valid. Hanya file JPG, JPEG, dan PNG yang diperbolehkan.';
        input.value = '';
        document.getElementById('preview-image').style.display = 'none';
        return false;
    } else {
        document.getElementById('gambar-error').innerHTML = '';
    }

    var reader = new FileReader();
    reader.onload = function (e) {
        document.getElementById('preview-image').src = e.target.result;
        document.getElementById('preview-image').style.display = 'block';
    }
    reader.readAsDataURL(file);
}
</script>
</body>
</html>
