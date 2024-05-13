<?php

session_start();

require_once "config/koneksi.php";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? $_POST['remember'] : false; 
    
    if(empty($username) || empty($password)){
        echo '<script>alert("Username atau password tidak boleh kosong.");</script>';
        echo '<script>window.location = "login";</script>';
        exit;
    }
    
    // Validasi karakter khusus
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        echo '<script>alert("Username hanya boleh berisi huruf dan angka.");</script>';
        echo '<script>window.location = "login";</script>';
        exit;
    }

    $remember = isset($_POST['remember']) ? $_POST['remember'] : false; 

    $query_pelanggan = "SELECT * FROM pelanggan WHERE username = '$username'";
    $result_pelanggan = $conn->query($query_pelanggan);

    $query_admin = "SELECT * FROM admin WHERE username_admin = '$username'";
    $result_admin = $conn->query($query_admin);

    if ($result_admin->num_rows > 0) {
        $user_admin = $result_admin->fetch_assoc();
        $hashed_password_admin = password_hash($password, PASSWORD_DEFAULT);

        if (password_verify($password, $hashed_password_admin)) {
            $_SESSION['username_admin'] = $username;
            $_SESSION['nama_admin'] = $user_admin['nama_admin'];
            header("Location: view/admin/");
            exit;
        } else {
            // Password salah, tampilkan pesan error
            echo '<script>alert("Password salah.");</script>';
        }
    } elseif ($result_pelanggan->num_rows > 0) {
        $user_pelanggan = $result_pelanggan->fetch_assoc();
        $hashed_password_pelanggan = $user_pelanggan['password'];

        if (password_verify($password, $hashed_password_pelanggan)) {
            $_SESSION['username'] = $username;
            $_SESSION['kode_member'] = $user_pelanggan['kode_member'];
            $_SESSION['nama'] = $user_pelanggan['nama'];
            $_SESSION['alamat'] = $user_pelanggan['alamat'];
            $_SESSION['no_hp'] = $user_pelanggan['no_hp'];
            $_SESSION['kupon'] = $user_pelanggan['kupon'];
            if ($remember) {
                setcookie('username', $username, time() + (86400 * 30), "/");
            }
            echo '<script>alert("Berhasil Login!");</script>';
            header("Location: view/user/");
            exit;
        } else {
            echo '<script>alert("Password salah.");</script>';
            echo '<script>window.location = "login";</script>';
        }
    } else {
        echo '<script>alert("Username tidak ditemukan.");</script>';
        echo '<script>window.location = "login";</script>';
    }
}

?>
