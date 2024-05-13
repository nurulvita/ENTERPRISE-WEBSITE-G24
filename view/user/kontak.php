<?php
// Mulai sesi
session_start();
include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    header('location: ../../login');
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

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'layouts/head.php' ?>
    <style>
        .rating {
            unicode-bidi: bidi-override;
            direction: rtl;
            text-align: center;
        }
        .star {
            display: inline-block;
            margin: 0 5px;
            font-size: 24px;
            cursor: pointer;
            color: #ccc; /* warna default untuk bintang tidak dipilih */
        }
        .star.active,
        .star:hover {
            color: gold; /* warna bintang saat dipilih atau dihover */
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


        <!-- ======================== Contact ======================== -->

        <section class="page">

            <!-- ===  Page header === -->

            <div class="page-header" style="background-image:url(../../assets/images/bg/curvedbg.png)">
                <div class="container">
                    <h2 class="title">Kontak</h2>
                    <p>Informasi Kontak dan Alamat</p>
                </div>
            </div>

            <!-- ===  Contact === -->

            <div class="contact">

                <div class="container">

                    <!-- === Google map === -->

                    <div id="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3974.0970976028816!2d117.13413849148539!3d-0.4739947748998852!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df67f14f1a82845%3A0xe895a71887be0d23!2sToko%20G24!5e1!3m2!1sid!2sid!4v1714354960923!5m2!1sid!2sid" 
                        width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>


                    <div class="row">

                        <div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1">

                            <!-- === konta block === -->

                            <div class="contact-block">

                                <!-- === kontak banner === -->

                                <div class="banner">
                                    <div class="row">
                                        <div class="col-md-offset-1 col-md-10 text-center">
                                            <h2 class="title">Berikan Ulasan</h2>
                                            <p>
                                                Silakan berikan ulasan Anda tentang produk dan layanan kami. Kami sangat menghargai setiap pendapat dan masukan Anda.
                                            </p>

                                            <div class="contact-form-wrapper">
                                                <a class="btn btn-clean open-form" data-text-open="Berikan Ulasan" data-text-close="Tutup Formulir">Berikan Ulasan</a>
                                                <div class="contact-form clearfix">
                                                <form action="ulasan" method="POST" onsubmit="return submitForm()">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <!-- Ganti input "Nama Anda" dengan nama pengguna dari sesi atau cookie -->
                                                                <input type="text" id="nama" class="form-control" placeholder="Nama Anda" required value="<?php echo $username_session; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <!-- Ubah input menjadi textarea -->
                                                                <textarea id="deskripsi" class="form-control" rows="5" placeholder="Ulasan Anda" required name="deskripsi"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="rating" class="form-label">Rating</label>
                                                                <div class="rating" id="rating">
                                                                    <!-- Hidden input field to store the selected rating value -->
                                                                    <input type="hidden" id="rating-value" name="rating">
                                                                    <span class="star" data-value="5">&#9733;</span>
                                                                    <span class="star" data-value="4">&#9733;</span>
                                                                    <span class="star" data-value="3">&#9733;</span>
                                                                    <span class="star" data-value="2">&#9733;</span>
                                                                    <span class="star" data-value="1">&#9733;</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 text-center">
                                                            <button type="submit" class="btn btn-clean" name="ulasan" id="ulasan">Kirim Ulasan</button>
                                                        </div>
                                                    </div>
                                                </form>

                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>


                                <!-- === Contact info === -->

                                <div class="contact-info">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <figure class="text-center">
                                                <span class="icon icon-map-marker"></span>
                                                <figcaption>
                                                    <strong>Alamat</strong>
                                                    <span>Jalan Rotan Semambu <br />Belimbing Raya No.24, Samarinda</span>
                                                </figcaption>
                                            </figure>
                                        </div>
                                        <div class="col-sm-3">
                                            <figure class="text-center">
                                                <span class="icon icon-phone"></span>
                                                <figcaption>
                                                    <strong>No.Telp/Whatsapp/SMS</strong>
                                                    <span>
                                                        <strong></strong> 081220009575 <br />
                                                    </span>
                                                </figcaption>
                                            </figure>
                                        </div>
                                        <div class="col-sm-3">
                                            <figure class="text-center">
                                                <span class="icon icon-clock"></span>
                                                <figcaption>
                                                    <strong>Jam Buka</strong>
                                                    <span>
                                                        <strong>Senin - Minggu </strong> <br>
                                                          08.00 WITA - 21.00 WITA <br />
                                                    </span>
                                                </figcaption>
                                            </figure>
                                        </div>
                                        <div class="col-sm-3">
                                            <figure class="text-center">
                                                <span class="icon icon-envelope"></span>
                                                <figcaption>
                                                    <strong>Pesan Antar</strong>
                                                    <span>
                                                        <strong>Senin - Minggu </strong> <br>
                                                          08.00 WITA - 17.00 WITA
                                                    </span>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    </div>
                                </div>

                            </div> <!--/contact-block-->
                        </div><!--col-sm-8-->
                    </div> <!--/row-->
                </div> <!--/container-->
            </div> <!--/contact-->
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

    <?php
        include 'layouts/script.php';
    ?>

<script>
    document.querySelectorAll('.star').forEach(item => {
        item.addEventListener('click', event => {
            const value = event.target.getAttribute('data-value');
            const stars = document.querySelectorAll('.star');
            document.getElementById('rating-value').value = value;
            stars.forEach(star => {
                const starValue = star.getAttribute('data-value');
                if (starValue <= value) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
            console.log('Rating:', value);
        });
    });
</script>


</body>

</html>
