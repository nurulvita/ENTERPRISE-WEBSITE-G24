<?php
session_start();
include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    header('location: ../../login');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $rating = $_POST['rating']; 

    $query = "INSERT INTO ulasan (username, deskripsi, rating) VALUES ('$username', '$deskripsi', '$rating')";

    if (mysqli_query($conn, $query)) {
        header('location: kontak');
        exit();
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
} else {
    header('location: beranda');
    exit();
}

mysqli_close($conn);
?>
