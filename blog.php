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

            <!-- ===  Blog === -->
            <div class="blog blog-category">

                <div class="container">

                    <div class="row">

                        <!-- === blog-content === -->

                        <div class="col">

                            <div class="row">

                                <!-- === article item === -->
                                <?php
                                include 'config/koneksi.php';

                                $query = "SELECT * FROM blog_post WHERE showPublicly = 1 ORDER BY id DESC"; 

                                $result = mysqli_query($conn, $query);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $id = $row['id'];
                                        $judul = $row['judul'];
                                        $gambar = $row['gambar'];
                                        $waktu = strtotime($row['tanggal_publikasi']); 

                                        echo '<div class="col-sm-6 col-md-6">';
                                        echo '<a href="blog-item?id=' . $id . '">';
                                        echo '<article>';
                                        echo '<div class="image">';
                                        echo '<img src="blog_post/' . $gambar . '" alt="" />';
                                        echo '</div>';
                                        echo '<div class="text">';
                                        echo '<h2 class="h4 title">' . $judul . '</h2>';
                                        echo '<div class="time">';

                                        echo '<span>' . date('d', $waktu) . '</span>'; 
                                        echo '<span>' . date('m', $waktu) . '</span>';
                                        echo '<span>' . date('Y', $waktu) . '</span>';

                                        echo '</div>';
                                        echo '</div>';
                                        echo '</article>';
                                        echo '</a>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo '<p>Tidak ada artikel yang ditemukan.</p>';
                                }

                                // Tutup koneksi database
                                mysqli_close($conn);
                                ?>

                            <!-- === pagination === -->

                            <!-- <div class="pagination-wrapper">
                                <ul class="pagination">
                                    <li>
                                        <a href="#" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li class="active"><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">5</a></li>
                                    <li>
                                        <a href="#" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>  -->
                            <!--/pagination-->

                        </div>


                    </div> <!--/row-->


                </div><!--/container-->
            </div> <!--/blog-category-->
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