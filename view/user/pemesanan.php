<?php
session_start();
include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    header('location: ../../login');
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

$query = "SELECT k.*, p.nama_produk, p.gambar_produk, p.harga FROM keranjang k JOIN produk p ON k.id_produk = p.id_produk WHERE k.username = '$username'";
$result = mysqli_query($conn, $query);

$total_belanja = 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'layouts/head.php' ?>

    <style>
        /* CSS styles for delivery time selection */
        .delivery-time select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .delivery-time label {
            font-size: 16px;
            font-weight: bold;
        }

        .image img {
            height: 100%;
            width: 100%;
            max-width: 40%;
            object-fit: cover;
            display: block;
            margin: 10px;
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

        <!-- ========================  Checkout ======================== -->

        <section class="page">

            <!-- ===  Page header === -->
            <div class="page-header" style="background-image:url(../../assets/images/bg/curvedbg.png)">
                <div class="container">
                    <h2 class="title">Konfirmasi Pemesanan Depo Air Minum</h2>
                    <p>Informasi Pelanggan</p>
                </div>
            </div>

            <!-- ===  Step wrapper === -->
            <div class="step-wrapper">
                <div class="container">
                    <div class="stepper">
                        <ul class="row">
                            <li class="col-md-4 active">
                                <span data-text="Lihat Keranjang"></span>
                            </li>
                            <li class="col-md-4 active">
                               <span data-text="Pemesanan"></span>
                            </li>
                            <li class="col-md-4">
                                <span data-text="Checkout"></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ===  Checkout === -->
            <div class="checkout">
                <div class="container">
                    <div class="clearfix">
                        <!-- ========================  Cart wrapper ======================== -->
                        <div class="cart-wrapper">
                            <!--cart header -->
                            <div class="cart-block cart-block-header clearfix">
                                <div>
                                    <span>Produk</span>
                                </div>
                                <div class="text-right">
                                    <span>Harga</span>
                                </div>
                            </div>

                            <!--cart items-->
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                    <div class="clearfix">
                                        <div class="cart-block cart-block-item clearfix">
                                            <div class="image">
                                                <img src="../../fotoproduk/<?php echo $row['gambar_produk']; ?>" alt="" />
                                            </div>
                                            <div class="title">
                                                <div class="h4"><?php echo $row['nama_produk']; ?></div>
                                                <div>
                                                    <strong>Jumlah  : </strong> <strong><?php echo $row['jumlah']; ?></strong>
                                                </div>
                                            </div>
                                            <div class="price">
                                                <span class="final h5">Rp <?php echo number_format($row['harga'] * $row['jumlah'], 0, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                    $total_belanja += $row['harga'] * $row['jumlah'];
                                }
                            } else {
                                echo "<div class='clearfix'>Keranjang Anda kosong.</div>";
                            }
                            ?>
                            <div class="clearfix">
                                <div class="cart-block cart-block-footer clearfix">
                                    <div>
                                        <strong>Sub Total</strong>
                                    </div>
                                    <div>
                                        <span class="h5 title"><strong>Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?></strong></span>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix">
                                <div class="cart-block cart-block-footer clearfix">
                                    <div>
                                        <strong>Ongkos Kirim</strong>
                                    </div>
                                    <div>
                                        <span class="h5 title"><strong>Rp 7.000</strong></span>
                                    </div>
                                </div>
                            </div>

                            <!--cart final price -->
                            <div class="clearfix">
                                <div class="cart-block cart-block-footer cart-block-footer-price clearfix">
                                    <div class="h2 title"><strong>Total</strong></div>
                                    <div>
                                        <div class="h2 title"><strong>Rp <?php echo number_format($total_belanja + 7000, 0, ',', '.'); ?></strong></div>
                                    </div>
                                </div>
                            </div>

                <!-- ========================  Cart navigation ======================== -->
                <div class="clearfix">
                    <div class="cart-block cart-block-footer cart-block-footer-price clearfix">
                        <div>
                            <a href="keranjang" class="btn btn-clean-dark">Kembali</a>
                        </div>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                        ?>
                            <div>
                                <button class="btn btn-main" onclick="confirmCheckout()">Checkout <span class="icon icon-chevron-right"></span></button>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                        </div>
                    </div>
                </div> <!--/container-->
            </div> <!--/checkout-->
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
            <?php include 'layouts/footer.php' ?>
        </footer>
    </div> <!--/wrapper-->

    <?php include 'layouts/script.php'; ?>

    <script>
            function confirmCheckout() {
            if (confirm('Apakah Anda yakin ingin melanjutkan ke proses checkout?')) {
                window.location.href = 'checkout-proses';
            } else {
                return false;
            }
        }

    </script>
</body>

</html>
