<?php
// Mulai sesi
session_start();
include_once('config/koneksi.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include 'layouts/head.php' ?>

</head>

<body>

    <div class="page-loader"></div>

    <div class="wrapper">

        <header>

            <!-- ======================== Navigation ======================== -->
            <?php include 'layouts/header.php' ?>
    
        </header>

        <!-- ========================  Header content ======================== -->

        <section class="frontpage-slider">
            <div class="owl-slider owl-slider-header owl-slider-fullscreen">
        
                <!-- === slide item === -->
        
                <div class="item" style="background-image:url(assets/images/water/water7.jpg)">
                    <div class="box text-center">
                        <div class="container">
                            <div class="rating animated" data-animation="fadeInDown">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <h2 class="title animated h1" data-animation="fadeInDown">
                                Depo Air Minum <br /> <span>Air Isi Ulang Berkualitas</span>
                            </h2>
                            <div class="desc animated" data-animation="fadeInUp">
                                Menyediakan air berkualitas untuk kebutuhan harian Anda.
                            </div>
                        </div>
                    </div>
                </div>
        
                <!-- === slide item === -->
        
                <div class="item" style="background-image:url(assets/images/water/water3.jpg)">
                    <div class="box text-center">
                        <div class="container">
                            <h2 class="title animated h1" data-animation="fadeInDown">
                                Kumpulkan Poin dan <br />
                                Dapatkan Dispenser Gratis
                            </h2>
                            <div class="desc animated" data-animation="fadeInUp">
                                Kumpulkan poin setiap kali Anda membeli, dan dapatkan dispenser gratis
                                ketika poin Anda mencapai 370.
                            </div>
                        </div>
                    </div>
                </div>
                
        
                <!-- === slide item === -->
        
                <div class="item" style="background-image:url(assets/images/water/depot.jpg)">
                    <div class="box text-center">
                        <div class="container">
                            <div class="rating animated" data-animation="fadeInDown">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <h2 class="title animated h1" data-animation="fadeInDown">Kebersihan Terjamin!</h2>
                            <div class="desc animated" data-animation="fadeInUp">Kami berkomitmen untuk menjaga kebersihan air kami.</div>
                        </div>
                    </div>
                </div>
                
        
            </div> <!--/owl-slider-->
        </section>
        

        <!-- ======================== Pesan ======================== -->

        <section class="booking booking-inner">


            <div class="booking-wrapper">

                <div class="container">
                    <div class="row">

                        <div class="col-xs-4 col-sm-3" style="margin-left: 65vh; margin-right:70vh">
                            <a href="produk" class="btn btn-clean">
                                Lihat Produk
                                <small>Pesan Sekarang</small>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- ========================  Product ======================== -->

        <section class="rooms rooms-widget">

            <!-- === product header === -->

            <div class="section-header">
                <div class="container">
                    <h2 class="title">Produk Tersedia <a href="produk" class="btn btn-sm btn-clean-dark">Lihat Semua Produk</a></h2>
                    <p>Lihat berbagai macam produk yang tersedia, termasuk air galon dan barang lainnya.</p>
                </div>
            </div>            

            <!-- === produk content === -->

            <div class="container">

                <div class="owl-rooms owl-theme">

                    <!-- === produk item === -->

                    <?php

                        $query = "SELECT * FROM produk";
                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <div class="item" style="border-radius: 30px;">
                                <article>
                                    <div class="image">
                                        <img src="fotoproduk/<?php echo $row['gambar_produk']; ?>" alt="" />
                                    </div>
                                    <div class="details">
                                        <div class="text">
                                            <h3 class="title"><?php echo $row['nama_produk']; ?></h3>
                                            <p>Stok : <?php echo $row['stok']; ?></p>
                                        </div>
                                        <div class="book">
                                            <div>
                                                <a href="login" class="btn btn-main">Beli Sekarang</a>
                                            </div>
                                            <div>
                                                <span class="price h4"><?php echo 'Rp ' . number_format($row['harga'], 0, ',', '.'); ?></span>
                                                <span><?php echo $row['unit']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php
                            }
                        } else {
                            echo "Tidak ada produk yang ditemukan.";
                        }
                        ?>


                </div><!--/owl-rooms-->

            </div> <!--/container-->

        </section>

        <!-- ========================  Stretcher widget ======================== -->

        <section class="stretcher-wrapper">

            <!-- === stretcher header === -->

            <div class="section-header">
                <div class="container">
                    <h2 class="title">Layanan <a href="layanan" class="btn btn-sm btn-clean-dark">Lihat Lebih</a></h2>
                    <p>
                        Kami memberikan berbagai layanan untuk kenyamanan Anda, termasuk:
                        <ul>
                            <li>Layanan pengantaran galon ke rumah Anda</li>
                            <li>Penjualan aksesoris galon</li>
                            <li>Penyediaan alat filtrasi air</li>
                            <li>Pemasangan dispenser air</li>
                        </ul>
                    </p>
                </div>
            </div>
            

            <!-- === stretcher === -->

            <ul class="stretcher">

                <!-- === stretcher item === -->

                <li class="stretcher-item" style="background-image:url(assets/images/layanan/isiulang.png);">
                    <!--logo-item-->
                    <div class="stretcher-logo">
                        <div class="text">
                            <span class="text-intro h4">Isi Ulang Air Galon</span>
                        </div>
                    </div>
                    <!--main text-->
                    <figure>
                        <h4>Isi Ulang Air Galon</h4>
                        <figcaption>Unparalleled devotion to luxury</figcaption>
                    </figure>
                    <!--anchor-->
                    <a href="layanan">Anchor link</a>
                </li>

                <!-- === stretcher item === -->

                <li class="stretcher-item" style="background-image:url(assets/images/layanan/antar.png);">
                    <!--logo-item-->
                    <div class="stretcher-logo">
                        <div class="text">
                            <span class="text-intro h4">Pengantaran ke Rumah</span>
                        </div>
                    </div>
                    <!--main text-->
                    <figure>
                        <h4>Antar ke Rumah</h4>
                        <figcaption>Antar Cepat</figcaption>
                    </figure>
                    <!--anchor-->
                    <a href="layanan">Anchor link</a>
                </li>

                <!-- === stretcher item === -->

                <li class="stretcher-item" style="background-image:url(assets/images/products/galonaqua.jpg);">
                    <!--logo-item-->
                    <div class="stretcher-logo">
                        <div class="text">
                            <span class="text-intro h4">Galon Aqua dan DC Asli Pabrik</span>
                        </div>
                    </div>
                    <figure>
                        <h4>Galon Aqua dan DC</h4>
                        <figcaption>Air Asli Pabrik</figcaption>
                    </figure>
                    <!--anchor-->
                    <a href="layanan">Anchor link</a>
                </li>

                <!-- === stretcher item === -->

                <li class="stretcher-item" style="background-image:url(assets/images/layanan/filtrasiair.png);">


                    <!--logo-item-->
                    <div class="stretcher-logo">
                        <div class="text">
                            <span class="text-intro h4">Filtrasi Air</span>
                        </div>
                    </div>
                    <!--main text-->
                    <figure>
                        <h4>Filtrasi Air</h4>
                        <figcaption>Kami menyediakan filter air yang terbaik</figcaption>
                    </figure>
                    <!--anchor-->
                    <a href="layanan">Anchor link</a>
                </li>

                <!-- === stretcher item more === -->

                <li class="stretcher-item more">
                    <div class="more-icon">
                        <span data-title-show="Lihat Lebih" data-title-hide="+"></span>
                    </div>
                    <a href="layanan">Anchor link</a>
                </li>

            </ul>
        </section>

        <!-- ========================  Blog ======================== -->

        <section class="blog blog-widget">
            <div class="section-header">
                <div class="container">
                    <h2 class="title">Tips &amp; Panduan <a href="blog" class="btn btn-sm btn-clean-dark">Telusuri Lebih Lanjut</a></h2>
                    <p>Temukan tips praktis, panduan, dan informasi berguna seputar air minum.</p>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <?php
                    $query = "SELECT * FROM blog_post ORDER BY id DESC"; 
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id = $row['id'];
                            $judul = $row['judul'];
                            $gambar = $row['gambar'];
                    ?>
                    <div class="col-sm-4">
                        <a href="blog-item?id=<?php echo $id; ?>">
                            <article>
                                <div class="image">
                                    <img src="blog_post/<?php echo $gambar; ?>" alt="" />
                                </div>
                                <div class="text">
                                    <h2 class="h4 title"><?php echo $judul; ?></h2>
                                </div>
                            </article>
                        </a>
                    </div>
                    <?php
                        }
                    } else {
                        echo "Tidak ada data yang ditemukan.";
                    }
                    ?>
                </div> <!--/row-->
            </div> <!--/container-->
        </section>      


        <!-- ======================== Quotes ======================== -->

        <section class="quotes quotes-slider" style="background-image:url(assets/images/bg/curvedbg.png)">
            <div class="container">
                <div class="section-header">
                    <h2 class="title">Testimoni</h2>
                    <p>Ini pendapat mereka tentang layanan kami</p>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="quote-carousel">
                            <?php
                            $query = "SELECT * FROM ulasan";
                            $result = mysqli_query($conn, $query);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $username = $row['username'];
                                    $deskripsi = $row['deskripsi'];
                                    $rating = $row['rating'];
                                    ?>
                                    <div class="quote">
                                        <div class="text">
                                            <h4><?php echo $username; ?></h4>
                                            <p><?php echo $deskripsi; ?></p>
                                        </div>
                                        <div class="more">
                                            <div class="rating">
                                                <?php
                                                for ($i = 0; $i < $rating; $i++) {
                                                    echo '<span class="fa fa-star"></span>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "<p>Tidak ada testimoni yang tersedia saat ini.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- ========================  Berlangganan ======================== -->

        <section class="subscribe">
            <div class="container">
                <div class="box">
                    <h2 class="title">Berlangganan</h2>
                    <div class="text">
                        <p>Berlangganan untuk diskon dan penawaran khusus.</p>
                    </div>
                    <div class="form-group">
                        <input type="email" value="" placeholder="Masukan Email Anda" class="form-control" id="emailInput" required />
                        <button class="btn btn-sm btn-main" onclick="subscribe()">Kirim</button>
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

    <?php
        include 'layouts/script.php';
    ?>

</body>

</html>