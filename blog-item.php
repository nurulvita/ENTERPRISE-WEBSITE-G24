<?php
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

        <!-- ========================  Blog ======================== -->

        <section class="page">

            <!-- ===  Page header === -->

            <div class="page-header" style="background-image:url(assets/images/bg/curvedbg.png)">
                <div class="container">
                    <h2 class="title">Blog</h2>
                    <p>Tips & Panduan</p>
                </div>
            </div>

            <!-- ===  Blog item === -->

            <div class="blog blog-item">
                <div class="container">

                    <div class="row">

                        <!-- === blog-content === -->

                        <div class="col">

                        <?php

                        $id_selected = $_GET['id']; 

                        $query = "SELECT * FROM blog_post WHERE id = $id_selected";
                        $result = mysqli_query($conn, $query);



                        if (mysqli_num_rows($result) > 0) {

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<article>';
                                echo '<div class="image">';
                                echo '<img src="blog_post/' . $row['gambar'] . '" alt="" />';
                                echo '</div>';
                                echo '<div class="content">';
                                echo '<div class="blog-info blog-info-top">';
                                echo '<div class="entry">';
                                echo '<i class="fa fa-calendar"></i>';
                                echo '<span>Tanggal Publikasi : ' . date('d.m.Y', strtotime($row['tanggal_publikasi'])) . '</span>';
                                echo '</div>';
                                echo '</div>'; 
                                echo '<div class="h1 title">' . $row['judul'] . '</div>';
                                echo '<p>' . $row['konten'] . '</p>';
                                echo '</div>';
                                echo '</article>';
                            }
                        } else {
                            echo "Tidak ada artikel yang ditemukan.";
                        }

                        mysqli_close($conn);
                        ?>

                        </div>
                    </div> <!--/row-->
                </div><!--/container-->
            </div> <!--/blog-item-->
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
            <?php include 'layouts/footer.php' ?>
        </footer>

    </div> <!--/wrapper-->

    <?php   
        include 'layouts/script.php';
    ?>
</body>

</html>