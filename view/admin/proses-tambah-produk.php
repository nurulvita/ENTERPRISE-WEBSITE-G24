<?php
include_once('../../config/koneksi.php');
session_start();

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (!isset($_COOKIE['username_admin']) && !isset($_SESSION['username_admin'])) {
    header('location: ../../login');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["produk_id"])) {
    $produk_id = $_POST["produk_id"];

    $query_harga_produk = "SELECT harga FROM produk WHERE id_produk = '$produk_id'";
    $result_harga_produk = mysqli_query($conn, $query_harga_produk);

    if (mysqli_num_rows($result_harga_produk) > 0) {
        $row_harga_produk = mysqli_fetch_assoc($result_harga_produk);
        $harga = $row_harga_produk['harga'];

        $query_cek_produk = "SELECT * FROM admin_keranjang WHERE id_produk = '$produk_id'";
        $result_cek_produk = mysqli_query($conn, $query_cek_produk);

        if (mysqli_num_rows($result_cek_produk) > 0) {
            $row = mysqli_fetch_assoc($result_cek_produk);
            $id = $row['id'];
            $jumlah = $row['jumlah'] + 1;
            $query_update_jumlah = "UPDATE admin_keranjang SET jumlah = $jumlah WHERE id = $id";
            mysqli_query($conn, $query_update_jumlah);
            header('Location: admin-tambah-pemesanan');
            // exit();
        } else {
            $query_insert_produk = "INSERT INTO admin_keranjang (id_produk, jumlah, harga) VALUES ('$produk_id', 1, $harga)";
            if (mysqli_query($conn, $query_insert_produk)) {
                echo '<script>alert("Produk berhasil ditambahkan ke keranjang.");</script>';
                header('Location: admin-tambah-pemesanan');
                exit();
            } else {
                echo "Gagal menambahkan produk ke keranjang: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Harga produk tidak ditemukan.";
    }
}
?>
