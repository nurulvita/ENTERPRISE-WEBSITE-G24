<?php
include_once('../../config/koneksi.php');
session_start();

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (!isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    header('location: ../../login.php');
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'layouts/head.php' ?>
    <style>
        .status-menunggu,
        .status-dikonfirmasi,
        .status-diantar,
        .status-selesai {
            display: inline-block;
            padding: 5px 10px;
            border: none;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: default;
            margin-bottom: 5px;
        }

        .status-menunggu {
            background-color: orange;
            color: white;
        }

        .status-dikonfirmasi {
            background-color: green;
            color: white;
        }

        .status-diantar {
            background-color: purple;
            color: white;
        }

        .status-selesai {
            background-color: blue;
            color: white;
        }

        .btn-lihat-faktur {
            display: inline-block;
            padding: 10px 10px;
            border: none;
            /* border-radius: 20px; */
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            margin-bottom: 5px;
            background-color: #007bff;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-lihat-faktur:hover {
            background-color: #0056b3;
            color: white;
        }

    </style>
</head>

<body>

    <div class="page-loader"></div>

    <div class="wrapper">

        <header>

            <!-- ======================== Navigation ======================== -->
            <?php include 'layouts/header.php' ?>

        </header>

        <!-- ========================  Cek Pesanan ======================== -->

        <section class="page">

            <!-- ===  Page header === -->

            <div class="page-header" style="background-image:url(../../assets/images/bg/curvedbg.png)">

                <div class="container">
                    <h2 class="title">Lihat Pesanan</h2>
                    <p>Detail pesanan Anda</p>
                </div>

            </div>

            <!-- ===  Pesanan === -->

            <div class="checkout">

                <div class="container">

                    <div class="clearfix">

                        <!-- ========================  Note block ======================== -->

                        <div class="cart-wrapper">

                            <div class="note-block">

                                <div class="row">
                                    <!-- === left content === -->

                                    <div class="col-md-12">

                                        <div class="white-block">

                                            <div class="h4">Detail Pesanan</div>

                                            <hr />

                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Nomor Pemesanan</th>
                                                            <th>Nama</th>
                                                            <th>Alamat</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php

                                                        $query_pemesanan = "SELECT no_pemesanan, nama, alamat, status FROM pemesanan WHERE username = '$username'";
                                                        $result_pemesanan = mysqli_query($conn, $query_pemesanan);
                                                        while ($row = mysqli_fetch_assoc($result_pemesanan)) {
                                                            $status_class = '';
                                                            switch ($row['status']) {
                                                                case 'Menunggu':
                                                                    $status_class = 'status-menunggu';
                                                                    break;
                                                                case 'Dikonfirmasi':
                                                                    $status_class = 'status-dikonfirmasi';
                                                                    break;
                                                                case 'Diantar':
                                                                    $status_class = 'status-diantar';
                                                                    break;
                                                                case 'Selesai':
                                                                    $status_class = 'status-selesai';
                                                                    break;
                                                                default:
                                                                    $status_class = '';
                                                                    break;
                                                            }
                                                            echo "<tr>";
                                                            echo "<td>" . $row['no_pemesanan'] . "</td>";
                                                            echo "<td>" . $row['nama'] . "</td>";
                                                            echo "<td>" . $row['alamat'] . "</td>";
                                                            echo "<td><button class='" . $status_class . "' disabled>" . $row['status'] . "</button></td>";
                                                            
                                                            if ($row['status'] === 'Selesai') {
                                                                echo "<td><a href='lihatfaktur?no_pemesanan=" . $row['no_pemesanan'] . "' class='btn-lihat-faktur'>Lihat Faktur</a></td>";
                                                            } else {
                                                                echo "<td></td>";
                                                            }
                                                            echo "</tr>";
                                                        }
                                                        ?>
                                                        
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!--/container-->
            </div> <!--/checkout-->

        </section>

        <!-- ================== Footer  ================== -->

        <footer>
            <?php include 'layouts/footer.php' ?>
        </footer>

    </div> <!--/wrapper-->

    <?php include 'layouts/script.php' ?>


</body>

</html>
