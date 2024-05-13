<?php
// Mulai sesi
session_start();
include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    header('location: ../../login');
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

if(isset($_POST['hapus_produk'])) {
    $id_produk = $_POST['hapus_produk'];
    $query_hapus_produk = "DELETE FROM keranjang WHERE id_produk = $id_produk AND username = '$username'";
    mysqli_query($conn, $query_hapus_produk);
}

if(isset($_POST['update_jumlah'])) {
    $id_produk = isset($_POST['id_produk']) ? intval($_POST['id_produk']) : 0;
    $jumlah = isset($_POST['jumlah']) ? intval($_POST['jumlah']) : 0;

    if ($id_produk > 0 && $jumlah >= 0) {
        $query_update_jumlah = "UPDATE keranjang SET jumlah = ? WHERE id_produk = ? AND username = ?";
        $stmt = mysqli_prepare($conn, $query_update_jumlah);
        mysqli_stmt_bind_param($stmt, 'iis', $jumlah, $id_produk, $username);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(array('status' => 'success'));
            exit();
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Gagal memperbarui jumlah produk dalam keranjang.'));
            exit();
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Nilai id_produk atau jumlah tidak valid.'));
        exit();
    }
}

$query = "SELECT k.*, p.nama_produk, p.gambar_produk FROM keranjang k JOIN produk p ON k.id_produk = p.id_produk WHERE k.username = '$username'";
$result = mysqli_query($conn, $query);

$keranjang_kosong = mysqli_num_rows($result) == 0;
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <?php include 'layouts/head.php' ?>

    <style>
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

    <!-- <div class="page-loader"></div> -->

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
                    <h2 class="title">Order Galon Air Minum</h2>
                    <p>Lihat Keranjang</p>
                </div>
            </div>

            <!-- ===  Checkout steps === -->

            <div class="step-wrapper">
                <div class="container">
                    <div class="stepper">
                        <ul class="row">
                            <li class="col-md-4 active">
                                <span data-text="Lihat Keranjang"></span>
                            </li>
                            <li class="col-md-4">
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
                            
                            <div style="padding-bottom: 5vh;">
                                <a href="pesanan" class="btn btn-success">Cek Pesanan</a>
                            </div>

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
                            if (!$keranjang_kosong) {
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                    <div class="cart-block cart-block-item clearfix">
                                        <div class="image">
                                            <img src="../../fotoproduk/<?php echo $row['gambar_produk']; ?>" alt="" />
                                        </div>
                                        <div class="title">
                                            <div class="h4"><?php echo $row['nama_produk']; ?></div>
                                            <p>
                                                <strong>Jumlah</strong> <br />
                                                <div class="jumlah-box">
                                                    <button class="btn btn-primary" onclick="kurangiJumlah(<?php echo $row['id_produk']; ?>)">-</button>
                                                    <span class="jumlah btn btn-primary" id="jumlah<?php echo $row['id_produk']; ?>"><?php echo $row['jumlah']; ?></span>
                                                    <button class="btn btn-primary" onclick="tambahJumlah(<?php echo $row['id_produk']; ?>)">+</button>
                                                </div>
                                            </p>
                                        </div>
                                        <div class="price">
                                            <span class="final h5">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></span>
                                        </div>
                                        <!--delete-this-item-->
                                        <span class="icon icon-cross icon-delete" onclick="hapusProduk(<?php echo $row['id_produk']; ?>)"></span>
                                    </div>
                            <?php
                                }
                            } else {
                                // Jika tidak ada produk dalam keranjang
                                echo "<div class='cart-block cart-block-item clearfix'>";
                                echo "<img class='img-responsive' src='../../assets/images/cart_empty.png' alt='Keranjang Kosong' />";
                                echo "Keranjang Anda kosong.";
                                echo "</div>";
                            }
                            
                            ?>

                            <!-- ========================  Cart navigation ======================== -->

                            <div class="clearfix">
                                <?php if (!$keranjang_kosong) { ?>
                                    <div class="cart-block cart-block-footer cart-block-footer-price clearfix text-right">
                                        <a href="pemesanan" class="btn btn-main">Pesan Sekarang <span class="icon icon-chevron-right"></span></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </div> <!--/container-->
            </div> <!--/checkout-->

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
        function hapusProduk(id_produk) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                var form = document.createElement('form');
                form.method = 'post';
                form.action = '';
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'hapus_produk';
                input.value = id_produk;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function tambahJumlah(id_produk) {
            var jumlahSpan = document.getElementById('jumlah' + id_produk);
            var jumlah = parseInt(jumlahSpan.innerText);
            jumlah++;
            updateJumlah(id_produk, jumlah);
        }
        
        function kurangiJumlah(id_produk) {
            var jumlahSpan = document.getElementById('jumlah' + id_produk);
            var jumlah = parseInt(jumlahSpan.innerText);
            if (jumlah > 1) {
                jumlah--;
                updateJumlah(id_produk, jumlah);
            }
        }

        function updateJumlah(id_produk, jumlah) {
            $.ajax({
                url: 'keranjang',
                type: 'POST',
                dataType: 'json',
                data: {
                    update_jumlah: true,
                    id_produk: id_produk,
                    jumlah: jumlah
                },
                success: function(response) {
                    if (response.status === 'success') {
                        window.location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
</body>

</html>
