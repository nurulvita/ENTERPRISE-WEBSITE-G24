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

$no_pemesanan = isset($_GET['no_pemesanan']) ? $_GET['no_pemesanan'] : '';

$query_pemesanan = "SELECT * FROM pemesanan WHERE no_pemesanan = '$no_pemesanan' AND username = '$username'";
$result_pemesanan = mysqli_query($conn, $query_pemesanan);
$row_pemesanan = mysqli_fetch_assoc($result_pemesanan);


if (!$row_pemesanan) {
    header('location: pesanan');
    exit();
}

$query_detail_produk = "SELECT * FROM  detail_pemesanan WHERE no_pemesanan = '$no_pemesanan'";
$result_detail_produk = mysqli_query($conn, $query_detail_produk);

$total_harga_produk = 0;
while ($row_produk = mysqli_fetch_assoc($result_detail_produk)) {
    $total_harga_produk += $row_produk['harga'] * $row_produk['jumlah'];
}

$total_ongkos_kirim = 7000; 

$total_harga = $total_harga_produk + $total_ongkos_kirim;
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
            .logo,
            .page-header,
            title {
                display: none !important;
            }
        }
    </style>
            <link href="src/assets/css/light/apps/invoice-preview.css" rel="stylesheet" type="text/css" />
    <link href="src/assets/css/dark/apps/invoice-preview.css" rel="stylesheet" type="text/css" />
</head>

<body>

    <div class="page-loader"></div>

    <div class="wrapper">

        <header>

            <!-- ======================== Navigation ======================== -->
            <?php include 'layouts/header.php' ?>

        </header>

        <!-- ========================  Lihat Faktur ======================== -->

        <section class="page">

            <!-- ===  Page header === -->

            <div class="page-header" style="background-image:url(../../assets/images/bg/curvedbg.png)">

                <div class="container">
                    <h2 class="title">Faktur </h2>
                    <p>Depo Air Minum G - 24</p>
                </div>

            </div>

            <!-- ===  Faktur === -->

            <div class="checkout">

                <div class="container">

                    <div class="clearfix">

                        <!-- ========================  Note block ======================== -->

                        <div class="cart-wrapper">

                            <div class="note-block">
                            <div class="row invoice layout-top-spacing layout-spacing">
    
                                <div class="row">
    
                                <!-- <div class="col-xl-9"> -->
                                    <div class="invoice-container">
                                        <div class="invoice-inbox">
                                            <div id="ct" class="">
                                                <!-- <div class="invoice-00001"> -->
                                                    <div class="content-section">

                                                        <div class="inv--head-section inv--detail-section">

                                                            <div class="row">

                                                                <div class="col-sm-6 col-12 mr-auto">
                                                                    <div class="d-flex">
                                                                        <img class="company-logo" src="../../assets/images/logo/logo.png" alt="company" style="width: fit-content; height: 50px;">
                                                                        <h3 class="align-self-center">Depo Air Minum G-24</h3>
                                                                        <p class="inv-street-addr mt-3">Jalan Rotan Semambu Belimbing Raya No.24</p>
                                                                        <p class="inv-street-addr mt-3">081220009575</p>
                                                                        <p class="inv-street-addr mt-3">08.00 WITA - 21.00 WITA</p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-6 text-sm-end">
                                                                <?php
                                                                    $no_pemesanan = $_GET['no_pemesanan'];

                                                                    $sql_order = "SELECT * FROM pemesanan WHERE no_pemesanan = '$no_pemesanan'";
                                                                    $result_order = $conn->query($sql_order);

                                                                    if ($result_order->num_rows > 0) {
                                                                        $order_data = $result_order->fetch_assoc();

                                                                        $username = $order_data['username'];
                                                                        $sql_pelanggan = "SELECT * FROM pelanggan WHERE username = '$username'";
                                                                        $result_pelanggan = $conn->query($sql_pelanggan);

                                                                        if ($result_pelanggan->num_rows > 0) {
                                                                            $pelanggan_data = $result_pelanggan->fetch_assoc();
                                                                        } else {
                                                                            echo "Data pelanggan tidak ditemukan";
                                                                        }

                                                                        $sql_detail = "SELECT * FROM detail_pemesanan WHERE no_pemesanan = '$no_pemesanan'";
                                                                        $result_detail = $conn->query($sql_detail);

                                                                        if ($result_detail->num_rows > 0) {
                                                                            $detail_data = $result_detail->fetch_all(MYSQLI_ASSOC);
                                                                        } else {
                                                                            echo "Detail pemesanan tidak ditemukan";
                                                                        }
                                                                    } else {
                                                                        echo "Data pemesanan tidak ditemukan";
                                                                    }
                                                                    ?>

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="inv--detail-section inv--customer-detail-section">

                                                            <div class="row">

                                                                <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4 align-self-center">
                                                                    <p class="inv-to">Pemesanan</p>
                                                                </div>

                                                                <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8 align-self-center order-sm-0 order-1 text-sm-end mt-sm-0 mt-5">
                                                                    <!-- <h6 class=" inv-title">Invoice From</h6> -->
                                                                </div>

                                                                <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4">
                                                                    <p class="inv-email-address">Nomor Faktur : <?php echo $order_data['no_pemesanan']; ?></p>
                                              
                                                                    <p class="inv-customer-name"><?php echo $order_data['nama']; ?></p>
                                                                    <p class="inv-street-addr"><?php echo $order_data['alamat']; ?></p>
                                                                    <p class="inv-street-addr"><?php echo $pelanggan_data['no_hp']; ?></p>
                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="inv--product-table-section">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <thead class="">
                                                                        <tr>
                                                                            <th scope="col">No.</th>
                                                                            <th scope="col">Barang</th>
                                                                            <th class="text-end" scope="col">Jumlah</th>
                                                                            <th class="text-end" scope="col">Harga</th>
                                                                            <th class="text-end" scope="col">Total Harga</th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $sql_detail = "SELECT * FROM detail_pemesanan WHERE no_pemesanan = '$no_pemesanan'";
                                                                        $result_detail = $conn->query($sql_detail);

                                                                        if ($result_detail->num_rows > 0) {
                                                                            $index = 1;
                                                                            $subtotal = 0; 
                                                
                                                                            while ($detail_data = $result_detail->fetch_assoc()) {
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $index++; ?></td>
                                                                                    <td><?php echo $detail_data['nama_produk']; ?></td>
                                                                                    <td class="text-end"><?php echo $detail_data['jumlah']; ?></td>
                                                                                    <td class="text-end"><?php echo 'Rp ' . number_format($detail_data['harga'], 0, ',', '.'); ?></td>
                                                                                    <td class="text-end"><?php echo 'Rp ' . number_format($detail_data['jumlah'] * $detail_data['harga'], 0, ',', '.'); ?></td>
                                                                                </tr>
                                                                                <?php 
                                                                                $subtotal += $detail_data['jumlah'] * $detail_data['harga'];
                                                                            }
                                                                        } else {
                                                                            echo "<tr><td colspan='5'>Detail pemesanan tidak ditemukan</td></tr>";
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="inv--total-amounts">
                                                            <div class="row mt-4">
                                                                <div class="col-sm-5 col-12 order-sm-0 order-1">
                                                                </div>
                                                                <div class="col-sm-7 col-12 order-sm-1 order-0">
                                                                    <div class="text-sm-end">
                                                                        <div class="row">
                                                                            <div class="col-sm-8 col-7">
                                                                                <p class="">Sub Total :</p>
                                                                            </div>
                                                                            <div class="col-sm-4 col-5">
                                                                                <p class=""><?php echo 'Rp ' . number_format($subtotal, 0, ',', '.'); ?></p>
                                                                            </div>
                                                                            <div class="col-sm-8 col-7">
                                                                                <p class=" discount-rate">Ongkos Kirim :</p>
                                                                            </div>
                                                                            <div class="col-sm-4 col-5">
                                                                                <?php
                                                                                $shipping = 7000;
                                                                                ?>
                                                                                <p class=""><?php echo 'Rp ' . number_format($shipping, 0, ',', '.'); ?></p>
                                                                            </div>
                                                                            <div class="col-sm-8 col-7">
                                                                                <p><strong>Total Pembayaran : </strong></p>
                                                                            </div>
                                                                            <div class="col-sm-4 col-5">
                                                                                <?php
                                                                                $grand_total = $subtotal + $shipping;
                                                                                ?>
                                                                                <p class=""><strong><?php echo 'Rp ' . number_format($grand_total, 0, ',', '.'); ?></strong></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="inv--note">

                                                            <div class="row mt-4">
                                                                <div class="col-sm-12 col-12 order-sm-0 order-1">
                                                                    <p>Catatan: Terimakasih telah membeli produk kami. Dapatkan kupon untuk setiap pembelian produk.</p>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                <!-- </div>  -->

                                            </div>


                                            <div class="clearfix">
                                                <div class="cart-block cart-block-footer cart-block-footer-price clearfix">
                                                    <div>
                                                        <a href="pesanan" class="btn btn-clean-dark">Kembali</a>
                                                    </div>
                                                    <div>
                                                        <a onclick="window.print()" class="btn btn-main">Cetak <span class="icon icon-printer"></span></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                <!-- </div> -->
    
                                </div>
                                
                            <!-- </div> -->
    
                        <!-- </div> -->
                    </div>
                            </div>
                        </div>
                    </div>
                </div> <!--/container-->
            </div> <!--/checkout-->

        </section>

        <!-- ================== Footer  ================== -->

        <footer>
            <?php include 'layouts/footer.php' ?>
        </footer>

    </div> <!--/wrapper-->

    <?php include 'layouts/script.php' ?>
    <script src="src/assets/js/apps/invoice-preview.js"></script>


</body>

</html>
