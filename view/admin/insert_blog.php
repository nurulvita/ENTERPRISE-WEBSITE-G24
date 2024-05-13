<?php
include '../../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $konten = htmlspecialchars($_POST['konten']);
    $gambar = $_FILES['gambar']['name'];
    $showPublicly = isset($_POST['showPublicly']) ? 1 : 0;

    $fileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

    $fileMimeType = $_FILES['gambar']['type'];

    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    $allowedMimeTypes = array('image/jpeg', 'image/png', 'image/gif');
    if (!in_array($fileType, $allowedExtensions) || !in_array($fileMimeType, $allowedMimeTypes)) {
        echo '<script>alert("Maaf, hanya file gambar dengan ekstensi JPG, JPEG, PNG, atau GIF yang diperbolehkan.");</script>';
        header("Location: blog-tambah");
        exit();
    }

    $targetDir = "../../blog_post/";

    $targetFilePath = $targetDir . basename($gambar);

    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFilePath)) {
        $query = "INSERT INTO blog_post (judul, konten, gambar, showPublicly) 
                  VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $query);

        mysqli_stmt_bind_param($stmt, "sssi", $judul, $konten, $gambar, $showPublicly);

        if (mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Post telah berhasil dibuat!");</script>';
            header("Location: blog-list");
            exit();
        } else {
            echo '<script>alert("Error: ' . mysqli_stmt_error($stmt) . '");</script>';
            header("Location: blog-tambah");
            exit();
        }

        mysqli_stmt_close($stmt);
    } else {

        echo '<script>alert("Maaf, terjadi kesalahan saat mengunggah gambar.");</script>';
        header("Location: blog-tambah");
        exit();
    }

    mysqli_close($conn);
}
?>
