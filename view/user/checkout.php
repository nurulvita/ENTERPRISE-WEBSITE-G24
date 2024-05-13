<?php
include_once('../../config/koneksi.php');
session_start();

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (!isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    header('location: ../../login');
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
// $kode_member = isset($_SESSION['kode_member']) ? $_SESSION['kode_member'] : '';
$nomor_pemesanan = isset($_SESSION['nomor_pemesanan']) ? $_SESSION['nomor_pemesanan'] : '';
$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : '';
$alamat = isset($_SESSION['alamat']) ? $_SESSION['alamat'] : '';
$no_hp = isset($_SESSION['no_hp']) ? $_SESSION['no_hp'] : '';


$query_cek_pemesanan = "SELECT * FROM pemesanan WHERE no_pemesanan = '$nomor_pemesanan'";
$result_cek_pemesanan = mysqli_query($conn, $query_cek_pemesanan);


if (mysqli_num_rows($result_cek_pemesanan) == 0) {
    $query_keranjang = "SELECT k.*, p.nama_produk, p.harga FROM keranjang k JOIN produk p ON k.id_produk = p.id_produk WHERE k.username = '$username'";
    $result_keranjang = mysqli_query($conn, $query_keranjang);

    // $detail_pemesanan_data = [];

    

    //     $detail_pemesanan_data[] = [
    //         'produk_id' => $row['id_produk'],
    //         'kode_member' => $kode_member,
    //         'nama_produk' => $row['nama_produk'],
    //         'jumlah' => $row['jumlah'],
    //         'harga' => $row['harga']
    //     ];
    // }

    $query_select_nomor_pemesanan = "SELECT no_pemesanan FROM pemesanan ORDER BY id DESC LIMIT 1";
    $result_select_nomor_pemesanan = mysqli_query($conn, $query_select_nomor_pemesanan);
    $row_nomor_pemesanan = mysqli_fetch_assoc($result_select_nomor_pemesanan);
    $nomor_pemesanan = $row_nomor_pemesanan['no_pemesanan'];

}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'layouts/head.php' ?>
    <style>
        @media print {
            .cart-block-footer,
            .toggle-menu,
            .text-center,
            .cart-block-footer,
            .step-wrapper,
            title {
                display: none !important;
            }
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
                    <h2 class="title">Faktur Pemesanan</h2>
                    <p>Thank you!</p>
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
                            <li class="col-md-4 active">
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

                        <!-- ========================  Note block ======================== -->

                        <div class="cart-wrapper">

                            <div class="note-block">

                                <div class="row">
                                    <!-- === left content === -->

                                    <div class="text-center" style="background-color: #4caf50; padding: 10px;">
                                        <strong>
                                            <p style="color: white;">Pembayaran dapat dilakukan langsung saat pengantaran barang. Terima kasih atas pemesanan Anda!</p>
                                        </strong>
                                    </div>

                                    <div class="col-md-6">

                                        <div class="white-block">

                                            <div class="h4">Informasi Pelanggan</div>

                                            <hr />

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <strong>Nama</strong> <br />
                                                        <span><?php echo $nama; ?></span>
                                                    </div>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <strong>Alamat</strong><br />
                                                        <span><?php echo $alamat; ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <strong>No HP</strong><br />
                                                        <span><?php echo $no_hp; ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <strong>Jam Pengantaran</strong><br />
                                                        <span>09:00 - 17:00 WITA</span>
                                                    </div>
                                                </div>

                                            </div>

                                        </div> <!--/col-md-6-->

                                    </div>

                                    <!-- === right content === -->

                                    <div class="col-md-6">
                                        <div class="white-block">

                                            <div class="h4">Detail Pembelian</div>

                                            <hr />

                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama Produk</th>
                                                            <th>Jumlah</th>
                                                            <th>Harga</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $query_produk_pemesanan = "SELECT nama_produk, jumlah, harga FROM detail_pemesanan WHERE no_pemesanan = '$nomor_pemesanan'";
                                                        $result_produk_pemesanan = mysqli_query($conn, $query_produk_pemesanan);
                                                        while ($row = mysqli_fetch_assoc($result_produk_pemesanan)) {
                                                            echo "<tr>";
                                                            echo "<td>" . $row['nama_produk'] . "</td>";
                                                            echo "<td>" . $row['jumlah'] . "</td>";
                                                            echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                                            echo "</tr>";
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="h4">Detail Pembayaran</div>

                                            <hr />

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <?php
                                                    $query_cek_pemesanan = "SELECT * FROM pemesanan WHERE no_pemesanan = '$nomor_pemesanan'";
                                                    $result_cek_pemesanan = mysqli_query($conn, $query_cek_pemesanan);
                                                    $row = mysqli_fetch_assoc($result_cek_pemesanan);
                                                    $total_harga = $row['total_harga'];
                                                    ?>
                                                    <div class="form-group">
                                                        <strong>Total Barang</strong><br />
                                                        <span><?php echo $row['total_barang']; ?></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <strong>Sub Total</strong><br />
                                                        <span>Rp <?php echo number_format($total_harga, 0, ',', '.'); ?></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <strong>Ongkos Kirim</strong><br />
                                                        <span>Rp 7,000</span>
                                                    </div>
                                                    <div class="form-group">
                                                        <strong>Total</strong><br />
                                                        <?php
                                                        // Tambahkan ongkos kirim ke total harga
                                                        $total_harga_ongkir = $total_harga + 7000;
                                                        ?>
                                                        <span>Rp <?php echo number_format($total_harga_ongkir, 0, ',', '.'); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix">
                                    <div class="cart-block cart-block-footer cart-block-footer-price clearfix">
                                        <div>
                                            <!-- <a href="cekpesanan" class="btn btn-clean-dark">Cek Pesanan</a> -->
                                        </div>
                                        <div>
                                            <a href="pesanan" class="btn btn-clean-dark">Cek Pesanan</a>

                                            <!-- <a onclick="window.print()" class="btn btn-main">Cetak <span class="icon icon-printer"></span></a> -->
                                        </div>
                                    </div>
                                </div>
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

    <?php include 'layouts/script.php' ?>


</body>

</html>
