<?php
session_start();
include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    header('location: ../../login');
    exit();
}

if (isset($_SESSION['username'])) {
    $username_session = $_SESSION['username'];
} else {
    $username_session = "Sesi belum terbuat";
}

$username = $_SESSION['username'];
$query = "SELECT * FROM pelanggan WHERE username = '$username'";
$result = mysqli_query($conn, $query);

$row = mysqli_fetch_assoc($result);
$nama = $row['nama'];
$no_hp = $row['no_hp'];
$kode_member = $row['kode_member'];
$alamat = $row['alamat'];
$kupon = $row['kupon'];
$foto_profil = '../../uploads/' . $row['foto_profil'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'layouts/head.php'; ?>
    <!-- Tambahkan CSS atau kepala halaman lainnya jika diperlukan -->
</head>

<body>
    <div class="page-loader"></div>

    <div class="wrapper">

        <header>
            <?php include 'layouts/header.php'; ?>
        </header>

        <section class="page">
            <div class="page-header" style="background-image:url(../../assets/images/bg/curvedbg.png)">
                <div class="container">
                    <h2 class="title">Profil</h2>
                    <p>Ganti Kata Sandi</p>
                </div>
            </div>

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8" style="margin-top: 5vh; background-color: white;">
                        <div class="card">
                            <div class="card-body">
                                <form id="formGantiKataSandi" style="margin: 30px;">
                                    <div class="mb-3">
                                        <label for="password_baru" class="form-label">Kata Sandi Baru:</label>
                                        <input type="password" class="form-control" id="password_baru" name="password_baru" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="konfirmasi_password" class="form-label">Konfirmasi Kata Sandi Baru:</label>
                                        <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" required>
                                    </div>
                                    <div class="mb-3 d-grid gap-2" style="margin-top: 25px;">
                                        <button class="btn btn-clean-dark" onclick="window.location.href='profil'"  style="margin-top: 25px;">Kembali</button>
                                        <button type="submit" class="btn btn-primary" style="margin-top: 25px;">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <footer>
            <?php include 'layouts/footer.php'; ?>
        </footer>
    </div>

    <?php include 'layouts/script.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('formGantiKataSandi').addEventListener('submit', function(event) {

                var formData = new FormData(this);

                var xhr = new XMLHttpRequest();

                xhr.open('POST', 'editpass-proses', true);

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(response.message);
                            window.location.href = 'profil';
                        } else {
                            alert(response.message);
                        }
                    } else {
                        console.error('Error:', xhr.status);
                    }
                };

                xhr.send(formData);
            });
        });
    </script>
</body>

</html>
