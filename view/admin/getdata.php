<?php
// Sambungkan ke database
include '../../config/koneksi.php';

if (isset($_GET['username'])) {
    // Ambil username dari permintaan GET
    $username = $_GET['username'];

    if ($username === "NON-MEMBER") {
        echo json_encode(array('nama' => '', 'alamat' => ''));
        exit;
    }

    $sql = "SELECT nama, alamat FROM pelanggan WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Periksa apakah data ditemukan
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // Buat array assosiatif untuk data pelanggan
            $customerData = array(
                'nama' => $row['nama'],
                'alamat' => $row['alamat']
            );
            // Kembalikan data dalam format JSON
            echo json_encode($customerData);
        } else {
            echo json_encode(array('nama' => '', 'alamat' => ''));
        }
    } else {
        echo json_encode(array('error' => 'Terjadi kesalahan dalam mengambil data'));
    }
} else {
    echo json_encode(array('error' => 'Parameter username tidak ditemukan'));
}
?>
