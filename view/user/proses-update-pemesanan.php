<?php
include_once('../../config/koneksi.php');

if (isset($_POST["update_jumlah"])) {
    $id_produk = $_POST["id_produk"];
    $produk_id = $_POST["produk_id"];
    $jumlah = $_POST["jumlah"];

    $query_update_jumlah = "UPDATE detail_pemesanan SET jumlah = $jumlah WHERE produk_id = $id_produk AND no_pemesanan = '$no_pemesanan'";
    
    if (mysqli_query($conn, $query_update_jumlah)) {
        $sql_update_totals = "UPDATE pemesanan 
                             SET total_barang = (SELECT SUM(jumlah) FROM detail_pemesanan WHERE no_pemesanan = '$no_pemesanan'), 
                                 total_harga = (SELECT SUM(harga * jumlah) FROM detail_pemesanan WHERE no_pemesanan = '$no_pemesanan') 
                             WHERE no_pemesanan = '$no_pemesanan'";
        if(mysqli_query($conn, $sql_update_totals)) {
            echo json_encode(array("status" => "success"));
            exit();
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal memperbarui total barang dan total harga pada pemesanan."));
            exit();
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "Gagal memperbarui jumlah produk dalam keranjang."));
        exit();
    }
}
?>
