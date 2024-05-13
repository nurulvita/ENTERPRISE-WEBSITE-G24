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
    <!--  END NAVBAR  -->

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
                    <form id="tambah-produk-form" action="tambah_produk.php" method="POST" enctype="multipart/form-data">
                        <div class="row mb-4 layout-spacing layout-top-spacing">

                        <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">

                            <div class="widget-content widget-content-area ecommerce-create-section">

                                <div class="row mb-4">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="inputEmail3" placeholder="Nama Produk" name="nama_produk" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="product-images">Masukkan Gambar</label>
                                        <div class="multiple-file-upload">
                                            <input type="file" class="custom-file-input form-control" name="foto_produk" onchange="displayImage(this)" required>
                                            <span id="gambar-error" style="color: red;"></span>
                                        </div>
                                    </div>

                                    <img id="preview-image" src="#" alt="Preview Image" style="max-width: 50%; max-height: 300px; margin-top: 10px;">
                                    
                                </div>

                            </div>
                            
                        </div>

                        
                        <div class="col-xxl-3 col-xl-12 col-lg-12 col-md-12 col-sm-12">

                            <div class="row">
                                <div class="col-xxl-12 col-xl-8 col-lg-8 col-md-7 mt-xxl-0 mt-4">
                                    <div class="widget-content widget-content-area ecommerce-create-section">
                                        <div class="row">

                                            <div class="col-xxl-12 mb-4">
                                                <label for="stock_status">Status Stok</label>
                                                <div class="input-group">
                                                    <select class="form-select" id="stock_status" name="stok" required>
                                                        <option value="tersedia">Tersedia</option>
                                                        <option value="tidak tersedia">Tidak Tersedia</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xxl-12 col-md-6 mb-4">
                                                <label for="category">Kategori</label>
                                                <select class="form-select" id="category" name="kategori" required>
                                                    <option value="" disabled>Pilih...</option>
                                                    <option value="galon">Galon</option>
                                                    <option value="aksesoris">Aksesoris</option>
                                                    <option value="lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                            <div class="col-xxl-12 col-md-6 mb-4">
                                                <label for="product-unit">Unit</label>
                                                <select class="form-select" id="product-unit" name="unit" required>
                                                    <option value="per pcs">Per Pcs</option>
                                                    <option value="per galon">Per Galon</option>
                                                    <option value="per bungkus">Per Bungkus</option>
                                                    <option value="per item">Per Item</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-12 col-xl-4 col-lg-4 col-md-5 mt-4">
                                    <div class="widget-content widget-content-area ecommerce-create-section">
                                        <div class="row">
                                            <div class="col-sm-12 mb-4">
                                                <label for="regular-price">Harga</label>
                                                <input type="text" class="form-control" id="regular-price" value="" name="harga" required>
                                            </div>
                                            <div class="col-sm-12">
                                                <button name="submit" type="submit" class="btn btn-success w-100">Tambah Produk</button>
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

            <!--  BEGIN FOOTER  -->
            <?php include 'layouts/footer.php'?>
            <!--  END FOOTER  -->

        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <script src="src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="src/plugins/src/waves/waves.min.js"></script>
    <script src="layouts/vertical-light-menu/app.js"></script>
    <script src="src/plugins/src/highlight/highlight.pack.js"></script>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="src/plugins/src/editors/quill/quill.js"></script>
    <script src="src/plugins/src/filepond/filepond.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginFileValidateType.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImagePreview.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImageCrop.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImageResize.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImageTransform.min.js"></script>
    <script src="src/plugins/src/filepond/filepondPluginFileValidateSize.min.js"></script>

    <script src="src/plugins/src/tagify/tagify.min.js"></script>

    <script src="src/assets/js/apps/ecommerce-create.js"></script>

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
    <!-- END PAGE LEVEL SCRIPTS -->
</body>
</html>