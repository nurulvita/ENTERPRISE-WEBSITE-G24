<!DOCTYPE html>
<html lang="en">

<head>

    <?php include 'layouts/head.php' ?>

</head>

<body>

    <div class="page-loader"></div>

    <div class="wrapper">

        <header>

            <?php include 'layouts/header.php' ?>
    
        </header>

        <!-- ======================== About ======================== -->

        <section class="page">
            <div class="page-header" style="background-image: url(assets/images/bg/curvedbg.png); background-color: rgba(0, 0, 0, 0.5);">
                <div class="container">
                    <h2 class="title">Tentang Kami</h2>
                    <p>Depo Air Minum G - 24</p>
                </div>
            </div>
            
        
            <div class="image-blocks image-blocks-category">

                <div class="container">
                    <div class="about">

                        <!--text-block-->

                        <div class="text-block">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">

                                    <div class="text">
                                        <h2>Tentang G - 24</h2>

                                        <p>
                                            <img src="assets/images/toko.png" alt="Toko G - 24" />
                                        </p>

                                        <p><strong class="text-center">Tentang</strong></p>
                                        <p>
                                            G-24 adalah usaha air minum galon yang berkomitmen untuk menyediakan air berkualitas tinggi dengan layanan pengiriman yang efisien dan pelayanan pelanggan yang berkualitas. 
                                            Mereka melakukan inovasi produk dan memperhatikan keberlanjutan lingkungan dalam operasinya, sambil menjalin kemitraan dengan bisnis lokal dan memantau kualitas air secara teratur. G-24, didirikan pada tahun 2015.
                                        </p>

                                        <p><strong class="text-center">Visi</strong></p>
                                        <p>
                                            visi untuk menjadi pemimpin dalam industri air minum galon dengan memberikan akses mudah kepada masyarakat akan air minum berkualitas tinggi yang menyehatkan.
                                        </p>
                                        <p><strong class="text-center">Misi</strong></p>
                                        <p>
                                        Untuk menyediakan produk air minum yang aman dan terjangkau serta memberikan layanan yang prima kepada pelanggan, sambil memperhatikan tanggung jawab lingkungan dan sosial dalam setiap aspek operasionalnya.
                                        </p>
                                    </div>

                                </div> <!--/col-->
                            </div> <!--/row-->
                        </div>

                    </div> <!--/container-->

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
                        <input type="email" value="" placeholder="Masukan Email Anda" class="form-control" id="emailInput" required />
                        <button class="btn btn-sm btn-main" onclick="subscribe()">Kirim</button>
                    </div>
                </div>
                <div id="notification"></div>
            </div>
        </section>
        
        <!-- ================== Footer  ================== -->

        <footer>

            <?php
                include 'layouts/footer.php';
            ?>

        </footer>

    </div> <!--/wrapper-->

    <?php
        include 'layouts/script.php';
    ?>
</body>

</html>