<?php
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['no_pemesanan'])) {
        $no_pemesanan = $_POST['no_pemesanan'];

        $query_delete_detail = "DELETE FROM detail_pemesanan WHERE no_pemesanan = '$no_pemesanan'";
        $result_delete_detail = mysqli_query($conn, $query_delete_detail);

        $query_delete_pemesanan = "DELETE FROM pemesanan WHERE no_pemesanan = '$no_pemesanan'";
        $result_delete_pemesanan = mysqli_query($conn, $query_delete_pemesanan);

        if ($result_delete_detail && $result_delete_pemesanan) {
            $response = array(
                'status' => 'success',
                'message' => 'Pesanan berhasil dihapus.'
            );
            echo json_encode($response);
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Gagal menghapus pesanan.'
            );
            echo json_encode($response);
        }
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Nomor pemesanan tidak diterima.'
        );
        echo json_encode($response);
    }
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    );
    echo json_encode($response);
}
?>
