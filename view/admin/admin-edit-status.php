<?php
include_once('../../config/koneksi.php');

if (isset($_POST['update_status'])) {
    $no_pemesanan = $_POST['no_pemesanan'];
    $status = $_POST['status'];

    $sql_update_status = "UPDATE pemesanan SET status = '$status' WHERE no_pemesanan = '$no_pemesanan'";
    if (mysqli_query($conn, $sql_update_status)) {
        echo json_encode(array("status" => "success"));
        exit;
    } else {
        echo json_encode(array("status" => "error", "message" => "Gagal memperbarui status pemesanan"));
        exit;
    }
}

?>
