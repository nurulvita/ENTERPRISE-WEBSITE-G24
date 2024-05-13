<?php
include_once('../../config/koneksi.php');

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM blog_post WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row_blog = mysqli_fetch_assoc($result);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $judul = htmlspecialchars($_POST['judul']);
        $konten = htmlspecialchars($_POST['konten']);
        $showPublicly = isset($_POST['showPublicly']) ? 1 : 0;

        // Validasi gambar
        if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['gambar']['tmp_name'];
            $file_info = pathinfo($_FILES['gambar']['name']);
            $file_ext = strtolower($file_info["extension"]);
            $allowed_extensions = array('jpg', 'jpeg', 'png');

            // Memeriksa ekstensi file
            if (!in_array($file_ext, $allowed_extensions)) {
                echo "<script>alert('Ekstensi file gambar tidak valid. Hanya file JPG, JPEG, dan PNG yang diperbolehkan.'); window.location='edit-blog.php?id=".$id."';</script>";
                exit();
            }

            // Memeriksa kesalahan selama pengunggahan
            if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
                echo "<script>alert('Terjadi kesalahan saat mengunggah gambar.'); window.location='edit-blog.php?id=".$id."';</script>";
                exit();
            }

            // Memeriksa apakah gambar benar-benar gambar
            $image_info = getimagesize($file_tmp);
            if ($image_info === FALSE) {
                echo "<script>alert('File yang diunggah bukan gambar.'); window.location='edit-blog.php?id=".$id."';</script>";
                exit();
            }
            
            // Jika semua validasi berhasil, simpan gambar
            $gambar = "produk_" . uniqid() . "." . $file_ext;
            move_uploaded_file($file_tmp, "../../blog_post/" . $gambar);

            // Hapus gambar lama jika ada
            if(!empty($row_blog['gambar'])) {
                unlink('../../blog_post/' . $row_blog['gambar']);
            }
        } else {
            $gambar = $row_blog['gambar'];
        }

        $update_query = "UPDATE blog_post SET judul=?, konten=?, showPublicly=?, gambar=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "ssisi", $judul, $konten, $showPublicly, $gambar, $id);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: blog-list?message=success");
            exit();
        } else {
            header("Location: blog-list?message=error");
            exit();
        }
    }
} else {
    header("Location: admin-blog-list");
    exit();
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
    <link rel="stylesheet" type="text/css" href="src/plugins/src/tagify/tagify.css">

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
    <link rel="stylesheet" href="src/assets/css/light/apps/blog-create.css">
    <link rel="stylesheet" href="src/assets/css/dark/apps/blog-create.css">
    <!--  END CUSTOM STYLE FILE  -->
    <style>

        textarea {
            width: 100%;
            height: 300px;
            padding: 10px; 
            resize: vertical; 
            box-sizing: border-box;
            border: 1px solid #ced4da; 
            border-radius: 6px;
            font-size: 14px; 
            line-height: 1.5; 
        
        }

    </style>
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
        <div class="search-overlay"></div>

            <!--  BEGIN SIDEBAR  -->
            <?php include 'layouts/sidebar.php'?>
         <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">

            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">

                        <form id="edit-blog" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>" method="post" enctype="multipart/form-data">
                            <div class="row mb-4 layout-spacing layout-top-spacing">
                                <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="widget-content widget-content-area blog-create-section">
                                        <div class="row mb-4">
                                            <div class="col-sm-12">
                                                <label for="post-title">Judul</label>
                                                <input type="text" class="form-control" id="post-title" placeholder="Masukkan Judul" name="judul" value="<?php echo $row_blog['judul']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="_blog mb-4">
                                            <div class="col-sm-12">
                                                <label for="blog-description">Konten</label>
                                                <!-- Ganti div dengan textarea yang memiliki id blog-description -->
                                                <textarea name="konten" style="height: 300px; width: 100%;"><?php echo $row_blog['konten']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-xxl-0 mt-4">
                                    <div class="widget-content widget-content-area blog-create-section">
                                        <div class="row">
                                            <div class="col-xxl-12 mb-4">
                                                <div class="switch form-switch-custom switch-inline form-switch-primary">
                                                    <input name="showPublicly" class="switch-input" type="checkbox" role="switch" id="showPublicly" <?php echo ($row_blog['showPublicly'] == '1') ? 'checked' : ''; ?>>
                                                    <label class="switch-label" for="showPublicly">Publish</label>
                                                </div>
                                            </div>
                                            <div class="col-xxl-12 col-md-12 mb-4">
                                                <label for="product-images">Gambar Cover</label>
                                                <div class="multiple-file-upload">
                                                    <!-- <img id="preview-image" src="#" alt="Preview Image" style="max-width: 100%; display: none;"> -->
                                                    <input type="file" class="custom-file-input form-control" name="gambar" id="gambar" onchange="displayImage(this)">
                                                    <span id="gambar-error" style="color: red;"></span>
                                                </div>
                                                <?php
                                                if(!empty($row_blog['gambar'])) {
                                                    $gambar_url = "../../blog_post/" . $row_blog['gambar'];
                                                    echo '<img id="preview-image" src="' . $gambar_url . '" alt="Preview Image" style="max-width: 100%;">';
                                                } else {
                                                    echo '<img id="preview-image" src="#" alt="Preview Image" style="max-width: 100%; display: none;">';
                                                }
                                                ?>
                                            </div>

                                            <div class="col-xxl-12 col-sm-4 col-12 mx-auto">
                                            <button type="submit" class="btn btn-success w-100" onclick="return confirmEdit()">Edit Blog</button>

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
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <!-- <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> -->
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
        <!-- Pastikan jQuery dimuat sebelum skrip inisialisasi TinyMCE -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Inisialisasi TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#blog-description',
            plugins: 'autoresize',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image',
            autoresize_bottom_margin: 16,
            height: 300
        });
    </script>


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