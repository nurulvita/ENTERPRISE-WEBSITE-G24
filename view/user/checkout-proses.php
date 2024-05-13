<?php
include_once('../../config/koneksi.php');
session_start();

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (!isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    header('location: ../../login');
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : '';
$alamat = isset($_SESSION['alamat']) ? $_SESSION['alamat'] : '';
$no_hp = isset($_SESSION['no_hp']) ? $_SESSION['no_hp'] : '';

date_default_timezone_set('Asia/Makassar');
$tanggal = date("Y-m-d H:i:s");
$jenis_pemesanan = "Antar";
$status = "Menunggu";
$tanggal_pemesanan = date("Ymd");
$nomor_urut = rand(10, 99);
$nomor_pemesanan = 'P' . $tanggal_pemesanan . $nomor_urut;

$query_cek_pemesanan = "SELECT * FROM pemesanan WHERE no_pemesanan = '$nomor_pemesanan'";
$result_cek_pemesanan = mysqli_query($conn, $query_cek_pemesanan);

if (mysqli_num_rows($result_cek_pemesanan) == 0) {
    $query_keranjang = "SELECT k.*, p.nama_produk, p.harga FROM keranjang k JOIN produk p ON k.id_produk = p.id_produk WHERE k.username = '$username'";
    $result_keranjang = mysqli_query($conn, $query_keranjang);

    $total_barang = 0;
    $total_harga = 0;

    $detail_pemesanan_data = [];

    while ($row = mysqli_fetch_assoc($result_keranjang)) {
        $total_barang += $row['jumlah'];
        $total_harga += $row['harga'] * $row['jumlah'];

        $detail_pemesanan_data[] = [
            'produk_id' => $row['id_produk'],
            'username' => $username,
            'nama_produk' => $row['nama_produk'],
            'jumlah' => $row['jumlah'],
            'harga' => $row['harga']
        ];
    }

    $jumlah_produk_dibeli = 0;
    foreach ($detail_pemesanan_data as $detail) {
        $jumlah_produk_dibeli += $detail['jumlah'];
    }


    $jumlah_kupon = $jumlah_produk_dibeli;

    $query_insert_kupon = "UPDATE pelanggan SET kupon = kupon + $jumlah_kupon WHERE username = '$username'";
    mysqli_query($conn, $query_insert_kupon);

    $query_pemesanan = "INSERT INTO pemesanan (no_pemesanan, username, nama, alamat, total_barang, total_harga, tanggal, jenis_pemesanan, status) VALUES ('$nomor_pemesanan', '$username', '$nama', '$alamat', $total_barang, $total_harga + 7000, '$tanggal', '$jenis_pemesanan', '$status')";

    if (mysqli_query($conn, $query_pemesanan)) {
        foreach ($detail_pemesanan_data as $detail) {
            $query_detail_pemesanan = "INSERT INTO detail_pemesanan (no_pemesanan, produk_id, username, nama_produk, jumlah, harga) VALUES ('$nomor_pemesanan', {$detail['produk_id']}, '$username', '{$detail['nama_produk']}', {$detail['jumlah']}, {$detail['harga']})";
            mysqli_query($conn, $query_detail_pemesanan);
            
        }

        $query_hapus_keranjang = "DELETE FROM keranjang WHERE username = '$username'";
        mysqli_query($conn, $query_hapus_keranjang);

        $_SESSION['success_message'] = "Pemesanan berhasil!";
        header('location: checkout');
        exit();
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat memproses pemesanan: " . mysqli_error($conn);
        header('location: pemesanan');
        exit();
    }
}
?>
