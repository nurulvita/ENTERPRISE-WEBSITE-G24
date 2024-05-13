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

        <!-- ========================  Rooms ======================== -->


        <section class="page" style="padding-bottom: 10vh;">
            <!-- ========================  Page header ======================== -->
            <div class="page-header" style="background-image:url(assets/images/bg/curvedbg.png)">
                <div class="container">
                    <h2 class="title">Produk</h2>
                    <p>Depo Air Minum G-24 </p>
                </div>
            </div>

            <!-- === rooms content === -->
            <div class="rooms rooms-category">
                <div class="container">
                    <div class="row">
                        <?php
                        include 'config/koneksi.php';

                        $query = "SELECT * FROM produk";
                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                                <div class="col-sm-6 col-md-4">
                                    <div class="item">
                                        <article>
                                            <div class="image">
                                                <img src="fotoproduk/<?php echo $row['gambar_produk']; ?>" alt="" />
                                            </div>
                                            <div class="details">
                                                <div class="text">
                                                    <h2 class="title"><a href="login"><?php echo $row['nama_produk']; ?></a></h2>
                                                    <p>Stok : <?php echo $row['stok']; ?></p>
                                                </div>
                                                <div class="book">
                                                    <div>
                                                        <a href="login" class="btn btn-main"> Beli
                                                            <i class="icon icon-tag"></i> <!-- Ikon keranjang belanja -->
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <span class="price h2"><?php echo 'Rp ' . number_format($row['harga'], 0, ',', '.'); ?></span>
                                                        <span><?php echo $row['unit']; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "Tidak ada produk yang ditemukan.";
                        }
                        ?>
                    </div>
                </div> <!--/container-->
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