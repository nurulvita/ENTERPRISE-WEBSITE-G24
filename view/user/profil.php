<?php
// Mulai sesi
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

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }

        .btn-icon {
            background-color: #007bff;
            color: #fff;
        }

        .btn-icon:hover {
            background-color: #0056b3;
        }

        .btn-icon i {
            margin-right: 5px;
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
                                            <img src="<?php echo $foto_profil; ?>" alt="foto_profil" class="profile-image" name="foto_profil">

                                            <a href="" role="tab" aria-selected="false" style="pointer-events: none" class="ms-4">
                                                <i class="icon icon-gift" style="margin-left: 10px;"></i>
                                                <span class="ms-1">Kupon</span>
                                                <span class="badge bg-primary rounded-pill ms-2"><?php echo $kupon; ?></span>
                                            </a>
                                            <!-- <a href="gantikatasandi.php" role="tab" aria-selected="false">Ganti Kata Sandi</a> -->
                                        </div>

                                    </div>
                                </div>

                                <h4 class="mb-0 ms-3">Informasi Akun</h4>
                                <div class="card-body">
                                    <hr class="horizontal gray-light my-2">
                                    <ul class="list-group">
                                        <li class="list-group-item border-0 ps-0 text-m"><strong class="text-dark">Kode Member:</strong> &nbsp; <?php echo $kode_member; ?></li>
                                        <li class="list-group-item border-0 ps-0 pt-0 text-m"><strong class="text-dark">Nama:</strong> &nbsp; <?php echo $nama; ?></li>
                                        <li class="list-group-item border-0 ps-0 text-m"><strong class="text-dark">No Hp:</strong> &nbsp; <?php echo $no_hp; ?></li>
                                        <li class="list-group-item border-0 ps-0 text-m"><strong class="text-dark">Alamat:</strong> &nbsp; <?php echo $alamat; ?></li>
                                    </ul>
                                </div>
                                <button class="btn btn-main" onclick="window.location.href='editprofil'">
                                    <i class="icon icon-pencil"></i> Edit Profil
                                </button>
                                <button class="btn btn-main" onclick="window.location.href='editpassword'">
                                    <i class="icon icon-lock"></i> Ganti Kata Sandi
                                </button>
                                <a href="pesanan" class="btn btn-main">Lihat Pesanan</a>
                                <!-- <a href="../../logout.php" class="btn btn-main">Log Out</a> -->
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
                        <!-- <input type="email" value="" placeholder="Masukan Email Anda" class="form-control" id="emailInput" required />
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
