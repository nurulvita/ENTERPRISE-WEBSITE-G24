<?php

require_once "../../config/koneksi.php";
$upload_directory = '../../uploads/';

// Password default
$default_password = 'pelanggan123';

function generateKodeMember($conn) {
    $query = "SELECT MAX(SUBSTRING(kode_member, 4)) AS max_kode FROM pelanggan WHERE kode_member LIKE 'G24%'";
    $result = $conn->query($query);
    $data = $result->fetch_assoc();
    $max_kode = $data['max_kode'];

    if ($max_kode != null) {
        $next_kode = intval($max_kode) + 1;
    } else {
        $next_kode = 1;
    }

    $kode_member = 'G24' . sprintf('%05d', $next_kode);
    return $kode_member;
}

function cekUsername($conn, $username) {
    $query = "SELECT * FROM pelanggan WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : $default_password;
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $no_hp = isset($_POST['no_hp']) ? $_POST['no_hp'] : '';

    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
        $foto_profil_tmp = $_FILES['foto_profil']['tmp_name'];
        $foto_profil_name = $_FILES['foto_profil']['name'];
        $upload_directory = '../../uploads/';
        $foto_profil_path = $upload_directory . $foto_profil_name;

        if (move_uploaded_file($foto_profil_tmp, $foto_profil_path)) {
        } else {
            echo '<script>alert("Gagal mengunggah file.");</script>';
            exit;
        }
    } else {
        $default_image = 'profilkosong.webp'; 
        $foto_profil_path = $default_image;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $kode_member = generateKodeMember($conn);

    if (cekUsername($conn, $username)) {
        echo '<script>alert("Username sudah digunakan, silahkan gunakan username lain.");</script>';
        echo '<script>window.history.back();</script>';
    } else {
        $query = "INSERT INTO pelanggan (nama, alamat, no_hp, foto_profil, kupon, kode_member, username, password) 
                  VALUES (?, ?, ?, ?, 0, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssss", $nama, $alamat, $no_hp, $foto_profil_path, $kode_member, $username, $hashed_password);

        if ($stmt->execute()) {
            echo '<script>alert("Pendaftaran berhasil! Silakan login untuk melanjutkan.");</script>';
            echo '<script>window.location = "pelanggan-list";</script>';
        } else {
            echo '<script>alert("Error: Pendaftaran gagal.");</script>';
            echo '<script>window.history.back();</script>';
        }
    }
}

?>
