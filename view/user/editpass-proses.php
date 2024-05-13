<?php
session_start();
include_once('../../config/koneksi.php');

if (!isset($_SESSION['username'])) {
    header('location: ../../login');
    exit();
}

$password_baru = mysqli_real_escape_string($conn, $_POST['password_baru']);
$konfirmasi_password = mysqli_real_escape_string($conn, $_POST['konfirmasi_password']);
$username = $_SESSION['username'];

// Validasi kata sandi baru
if ($password_baru !== $konfirmasi_password) {
    $response = array(
        'status' => 'error',
        'message' => 'Kata sandi baru dan konfirmasi tidak cocok.'
    );
    echo json_encode($response);
    exit();
}

$password_hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);

$update_query = "UPDATE pelanggan SET password = '$password_hash_baru' WHERE username = '$username'";
$update_result = mysqli_query($conn, $update_query);

if ($update_result) {
    $response = array(
        'status' => 'success',
        'message' => 'Kata sandi berhasil diperbarui.'
    );
    echo json_encode($response);
    exit();
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat memperbarui kata sandi. Silakan coba lagi.'
    );
    echo json_encode($response);
    exit();
}
?>
