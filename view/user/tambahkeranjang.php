<?php
// Mulai sesi
session_start();
include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    header('location: ../../login.php');
    exit();
}

if(isset($_POST['id_produk'])) {
    $id_produk = $_POST['id_produk'];

    $username = $_SESSION['username'];

    $query_check = "SELECT * FROM keranjang WHERE username = '$username' AND id_produk = $id_produk";
    $result_check = mysqli_query($conn, $query_check);

    if(mysqli_num_rows($result_check) > 0) {
        $row_produk = mysqli_fetch_assoc($result_check);
        $jumlah = $row_produk['jumlah'] + 1;

        $query_update = "UPDATE keranjang SET jumlah = $jumlah WHERE username = '$username' AND id_produk = $id_produk";
        $result_update = mysqli_query($conn, $query_update);

        if($result_update) {
            header('location: keranjang?status=success');
            exit();
        } else {
            header('location: produk?status=error');
            exit();
        }
    } else {

        $query_produk = "SELECT * FROM produk WHERE id_produk = $id_produk";
        $result_produk = mysqli_query($conn, $query_produk);

        if(mysqli_num_rows($result_produk) > 0) {
            $row_produk = mysqli_fetch_assoc($result_produk);
            $harga_produk = $row_produk['harga'];
            $gambar_produk = $row_produk['gambar_produk'];

            $jumlah = 1;

            $query_tambah_keranjang = "INSERT INTO keranjang (username, id_produk, jumlah, harga, gambar_produk) VALUES ('$username', $id_produk, $jumlah, $harga_produk, '$gambar_produk')";
            $result_tambah_keranjang = mysqli_query($conn, $query_tambah_keranjang);

            if($result_tambah_keranjang) {
                header('location: keranjang?status=success');
                exit();
            } else {
                header('location: produk?status=error');
                exit();
            }
        } else {
            header('location: produk?status=notfound');
            exit();
        }
    }
} else {
    header('location: produk');
    exit();
}
?>
