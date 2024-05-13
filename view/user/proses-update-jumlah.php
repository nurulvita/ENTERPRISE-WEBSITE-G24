<?php
// Mulai sesi
session_start();
include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    header('location: ../../login');
    exit();
}

if (!isset($_GET['no_pemesanan'])) {
    http_response_code(400);
    echo json_encode(array("message" => "Nomor pemesanan tidak tersedia."));
    exit();
}

$no_pemesanan = $_GET['no_pemesanan'];

$username_session = isset($_SESSION['username']) ? $_SESSION['username'] : "Sesi belum terbuat";

$data = json_decode(file_get_contents("php://input"));

if(isset($data->id_produk) && isset($data->jumlah)){
    $id_produk = $data->id_produk;
    $jumlah = $data->jumlah;

    $query_update_detail = "UPDATE detail_pemesanan 
                            SET jumlah = $jumlah 
                            WHERE no_pemesanan = '$no_pemesanan' AND produk_id = '$id_produk'";
    $result_update_detail = mysqli_query($conn, $query_update_detail);
    if (!$result_update_detail) {
        http_response_code(500);
        echo json_encode(array("message" => "Gagal memperbarui detail pemesanan."));
        exit();
    }

    $query_totals = "SELECT SUM(jumlah) AS total_barang, SUM(jumlah * harga) AS total_harga FROM detail_pemesanan WHERE no_pemesanan = '$no_pemesanan'";
    $result_totals = mysqli_query($conn, $query_totals);
    if (!$result_totals) {
        http_response_code(500);
        echo json_encode(array("message" => "Gagal menghitung total barang dan total harga."));
        exit();
    }
    $row_totals = mysqli_fetch_assoc($result_totals);
    $total_barang = $row_totals['total_barang'];
    $total_harga = $row_totals['total_harga'];

    $query_update_pemesanan = "UPDATE pemesanan SET total_barang = '$total_barang', total_harga = '$total_harga' WHERE no_pemesanan = '$no_pemesanan'";
    $result_update_pemesanan = mysqli_query($conn, $query_update_pemesanan);
    if (!$result_update_pemesanan) {
        http_response_code(500);
        echo json_encode(array("message" => "Gagal memperbarui total barang dan total harga."));
        exit();
    }

    echo json_encode(array("message" => "Jumlah produk berhasil diperbarui"));
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Gagal memperbarui jumlah produk. Data tidak lengkap."));
}
?>
