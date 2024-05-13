<?php
session_start();
include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    header('location: ../../login.php');
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

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'layouts/head.php'; ?>
    <style>
        /* CSS */
        .profile-image {
            border-radius: 50%;
            height: 160px;
            width: 160px;
            margin: 20px;
            object-fit: cover;
            object-position: center;
        }

        .menu-item {
            margin-right: 20px;
            color: #000;
        }

    </style>
</head>

<body>
    <div class="page-loader"></div>

    <div class="wrapper">

        <header>
            <!-- ======================== Navigation ======================== -->
            <?php include 'layouts/header.php'; ?>
        </header>

        <section class="page">

            <div class="page-header" style="background-image:url(../../assets/images/bg/curvedbg.png)">
                <div class="container">
                    <h2 class="title">Profil</h2>
                    <p>Informasi Akun</p>
                </div>
            </div>

            <div class="container container-fluid">
                <div class="container-fluid py-4">
                    <div class="row">
                        <div class="col-12 col">
                            <div class="card h-100">
                                <div class="card-header pb-0 p-3" style="background: white;">
                                    <div class="row align-items-center">
                                        <div class="col-md-8 d-flex align-items-center">
                                            <img src="<?php echo $foto_profil; ?>" alt="profile_image" class="profile-image" name="foto_profil">
                                        </div>

                                    </div>
                                </div>

                                <h4 class="mb-0 ms-3">Informasi Akun</h4>
                                <div class="card-body">
                                    <hr class="horizontal gray-light my-2">
                                    <!-- Form untuk mengedit profil -->
                                    <form action="proseseditprofil.php" method="POST" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="kode_member" class="form-label">Kode Member</label>
                                            <input type="text" class="form-control" id="kode_member" name="kode_member" value="<?php echo $kode_member; ?>" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nama" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="no_hp" class="form-label">Nomor HP</label>
                                            <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo $no_hp; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="alamat" class="form-label">Alamat</label>
                                            <textarea class="form-control" id="alamat" name="alamat"><?php echo $alamat; ?></textarea>
                                        </div>
                                        <!-- Input untuk mengunggah gambar profil -->
                                        <div class="mb-3">
                                            <label for="profile_image" class="form-label">Gambar Profil</label>
                                            <input type="file" class="form-control" id="profile_image" name="foto_profil">
                                        </div>
                                        <button type="submit" class="btn btn-main" style="margin-top: 25px;">Simpan Perubahan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <!-- ========================  Berlangganan ======================== -->

        <section class="subscribe" style="margin-top: 15vh;">
            <div class="container">
                <div class="box">
                    <h2 class="title">Berlangganan</h2>
                    <div class="text">
                        <p>Berlangganan untuk diskon dan penawaran khusus.</p>
                    </div>
                    <div class="form-group">
                        <!-- <input type="username" value="" placeholder="Masukan Email Anda" class="form-control" id="emailInput" required />
                        <button class="btn btn-sm btn-main" onclick="subscribe()">Kirim</button> -->
                    </div>
                </div>
                <div id="notification"></div>
            </div>
        </section>

        <!-- ================== Footer  ================== -->

        <footer>
            <?php include 'layouts/footer.php'; ?>
        </footer>

    </div>
    <!--/wrapper-->

    <?php include 'layouts/script.php'; ?>

</body>

</html>
