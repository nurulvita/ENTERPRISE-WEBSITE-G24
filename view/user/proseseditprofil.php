<?php
session_start();
include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    header('location: ../../login');
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $nama_baru = $_POST['nama'];
    $no_hp_baru = $_POST['no_hp'];
    $alamat_baru = $_POST['alamat'];

    $update_query = "UPDATE pelanggan SET nama='$nama_baru', no_hp='$no_hp_baru', alamat='$alamat_baru' WHERE username='$username'";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        header("location: profil");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["foto_profil"]["name"])) {
    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($_FILES["foto_profil"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($_FILES["foto_profil"]["name"] != "") {
        $check = getimagesize($_FILES["foto_profil"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        if ($_FILES["foto_profil"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file)) {
                $query = "UPDATE pelanggan SET foto_profil = '" . basename($_FILES["foto_profil"]["name"]) . "' WHERE username = '$username'";
                mysqli_query($conn, $query);
                header('Location: profil');
                exit();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        header('Location: profil');
        exit();
    }
}
?>
