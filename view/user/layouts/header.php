<?php

include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    header('location: ../../login.php');
    exit();
}

// Periksa apakah ada kode member dalam sesi
if(isset($_SESSION['username'])) {
    // Ambil kode member dari sesi
    $username = $_SESSION['username'];

    // Query untuk mengambil jumlah produk dari keranjang berdasarkan kode member
    $query_cart_count = "SELECT SUM(jumlah) AS total_produk FROM keranjang WHERE username = '$username'";
    $result_cart_count = mysqli_query($conn, $query_cart_count);

    // Periksa apakah berhasil mendapatkan jumlah produk dari keranjang
    if(mysqli_num_rows($result_cart_count) > 0) {
        $row_cart_count = mysqli_fetch_assoc($result_cart_count);
        $cart_count = $row_cart_count['total_produk'];
    } else {
        $cart_count = 0;
    }
} else {
    // Redirect ke halaman login jika tidak ada kode member dalam sesi
    header('location: ../../login.php');
    exit();
}
?>
        <header>

            <!-- ======================== Navigation ======================== -->

            <div class="container">

                <!-- === navigation-top === -->

                <nav class="navigation-top clearfix">

                    <!-- navigation-top-left -->

                    <div class="navigation-top-left">
                        <a class="box" href="#">
                            <i class="icon icon-phone-location"></i> 
                            Jalan Rotan Semambu Belimbing Raya No. 24 Samarinda
                        </a>
                    </div>

                    <!-- navigation-top-right -->

                    <div class="navigation-top-right">
                        <a class="box" href="https://api.whatsapp.com/send?phone=6281220009575" target="_blank">
                            <i class="icon icon-phone-handset"></i> 
                            Telp/sms/wa : 081220009575
                        </a>
                    </div>
                </nav>

                <!-- === navigation-main === -->

                <nav class="navigation-main clearfix">

                    <!-- logo -->

                    <div class="logo animated fadeIn">
                            <img class="logo-desktop" src="../../assets/images/logo/G-24.png" alt="logo" />
                            <img class="logo-mobile" src="../../assets/images/logo/G-24.png" alt="logo" style="height: 15vh;"/>
                    </div>

                    <!-- toggle-menu -->

                    <div class="toggle-menu"><i class="icon icon-menu"></i></div>

                    <!-- navigation-block -->

                    <div class="navigation-block clearfix">

                        <!-- navigation-left -->

                        <ul class="navigation-left">
                            <li><a href="beranda">Beranda <span class="open-dropdown"></span></a></li>
                            <li><a href="tentang">Tentang Kami</a></li>

                        </ul>

                        <!-- navigation-right -->

                        <ul class="navigation-right">
                            <li>
                                <a href="produk">Produk <span class="open-dropdown"></span></a>

                            </li>
                            <li>
                                <a href="layanan">Layanan</a>
                            </li>
                            <li>
                                <a href="blog">Blog</a>
                            </li>
                            <li>
                                <a href="kontak">Kontak</a>
                            </li>
                            <li>
                                <a href="keranjang">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span id="cart-count" class="cart-count"><?php echo $cart_count; ?></span>
                                </a>
                            </li>

                            <li>
                                <a href="profil"><i class="fa fa-user"></i>  <span class="open-dropdown"><i class="fa fa-angle-down"></i></span></a>
                                <ul>
                                    <li><a href="profil">profil</a></li>
                                    <li><a href="./../../logout">Log Out</a></li>
                                </ul>
                            </li>

                        </ul>

                    </div> <!--/navigation-block-->

                </nav>
            </div> <!--/container-->

        </header>

