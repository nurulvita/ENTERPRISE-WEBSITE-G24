<?php
// Mulai sesi
session_start();
include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username_admin']) && !isset($_SESSION['username_admin'])) {
    header('location: ../../login.php');
    exit();
}

$username = $_SESSION['username_admin'];
$query = "SELECT * FROM admin WHERE username_admin = '$username'";
$result = mysqli_query($conn, $query);

$row = mysqli_fetch_assoc($result);
$nama = $row['nama_admin'];
$no_hp = $row['no_hp'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>G-24 | Dashboard Admin </title>
    <link rel="icon" type="image/x-icon" href="../../src/assets/img/G-24.ico"/>
    <link href="layouts/vertical-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="layouts/vertical-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="layouts/vertical-light-menu/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="src/assets/css/light/apps/invoice-preview.css" rel="stylesheet" type="text/css" />
    <link href="src/assets/css/dark/apps/invoice-preview.css" rel="stylesheet" type="text/css" />
    <!--  END CUSTOM STYLE FILE  -->
    
</head>
<body class="layout-boxed">
    
    <!-- BEGIN LOADER -->
    <?php include 'layouts/load.php'?>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <?php include 'layouts/navbar.php'?>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="cs-overlay"></div>
        <div class="search-overlay"></div>

            <!--  BEGIN SIDEBAR  -->
            <?php include 'layouts/sidebar.php'?>
         <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">
                    
                    <div class="row invoice layout-top-spacing layout-spacing">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            
                        <div class="doc-container">

                            <div class="row">

                                <div class="col-xl-9">
                                    <div class="invoice-container">
                                        <div class="invoice-inbox">
                                            <div id="ct" class="">
                                                <div class="invoice-00001">
                                                    <div class="content-section">

                                                        <div class="inv--head-section inv--detail-section">

                                                            <div class="row">

                                                                <div class="col-sm-6 col-12 mr-auto">
                                                                    <div class="d-flex">
                                                                        <img class="company-logo" src="../../assets/images/logo/G-24.png" alt="company">
                                                                        <h3 class="in-heading align-self-center">Depo Air Minum G-24</h3>
                                                                    </div>
                                                                    <p class="inv-street-addr mt-3">Jalan Rotan Semambu Belimbing Raya No.24</p>
                                                                    <p class="inv-email-address">081220009575</p>
                                                                    <p class="inv-email-address">08.00 WITA - 21.00 WITA</p>
                                                                </div>

                                                                <div class="col-sm-6 text-sm-end">
                                                                    <?php
                                                                    $no_pemesanan = $_GET['no_pemesanan'];

                                                                    $sql_order = "SELECT * FROM pemesanan WHERE no_pemesanan = '$no_pemesanan'";
                                                                    $result_order = $conn->query($sql_order);

                                                                    if ($result_order->num_rows > 0) {
                                                                        $order_data = $result_order->fetch_assoc();

                                                                        $username = $order_data['username'];
                                                                        if ($username != '') {
                                                                            // Jika kode member tidak kosong, ambil data pelanggan
                                                                            $sql_pelanggan = "SELECT * FROM pelanggan WHERE username = '$username'";
                                                                            $result_pelanggan = $conn->query($sql_pelanggan);

                                                                            if ($result_pelanggan->num_rows > 0) {
                                                                                $pelanggan_data = $result_pelanggan->fetch_assoc();

                                                                            }
                                                                        } 
                                                                          

                                                                        // Ambil detail pemesanan
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
                                                                <div class="">

                                                                    <p class="inv-street-addr">Tanggal Pemesanan: <?php echo isset($order_data['tanggal']) ? $order_data['tanggal'] : ''; ?></p>
                                                                    <p class="inv-street-addr">Nomor Pemesanan: <?php echo isset($order_data['no_pemesanan']) ? $order_data['no_pemesanan'] : ''; ?></p>
                                                                </div>

                                                                <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4">
                                                                    <p class="inv-to"></p>
                                                                    <?php if ($order_data['jenis_pemesanan'] == 'Beli Langsung') { ?>
                                                                        <p class="inv-email-address"><?php echo isset($pelanggan_data['username']) ? $pelanggan_data['username'] : ''; ?></p>
                                                                        <p class="inv-customer-name"><?php echo isset($pelanggan_data['nama']) ? $pelanggan_data['nama'] : ''; ?></p>
                                                                        <p class="inv-street-addr"><?php echo isset($pelanggan_data['alamat']) ? $pelanggan_data['alamat'] : ''; ?></p>
                                                                        <p class="inv-email-address"><?php echo isset($pelanggan_data['no_hp']) ? $pelanggan_data['no_hp'] : ''; ?></p>
                                                                    <?php } else { ?>
                                                                        <p class="inv-email-address"><?php echo isset($order_data['username']) ? $order_data['username'] : ''; ?></p>
                                                                        <p class="inv-customer-name"><?php echo isset($order_data['nama']) ? $order_data['nama'] : ''; ?></p>
                                                                        <p class="inv-street-addr"><?php echo isset($order_data['alamat']) ? $order_data['alamat'] : ''; ?></p>
                                                                        <p class="inv-email-address"><?php echo isset($pelanggan_data['no_hp']) ? $pelanggan_data['no_hp'] : ''; ?></p>
                                                                    <?php } ?>
                                                                    <!-- Tambahkan informasi kontak pelanggan jika tersedia -->
                                                                </div>




                                                                <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8 col-12 order-sm-0 order-1 text-sm-end">
                                                                    <!-- Informasi pihak yang meminta pembayaran -->
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
                                                                        // Query untuk mengambil detail pemesanan berdasarkan no_pemesanan
                                                                        $sql_detail = "SELECT * FROM detail_pemesanan WHERE no_pemesanan = '$no_pemesanan'";
                                                                        $result_detail = $conn->query($sql_detail);

                                                                        // Cek apakah detail pemesanan ditemukan
                                                                        if ($result_detail->num_rows > 0) {
                                                                            $index = 1;
                                                                            $subtotal = 0; // Inisialisasi subtotal di luar loop
                                                                            // Loop untuk menampilkan detail pemesanan
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
                                                                                // Tambahkan harga item ke subtotal di setiap iterasi loop
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
                                                                            <?php if ($order_data['jenis_pemesanan'] == 'Antar') { ?>
                                                                                <div class="col-sm-8 col-7">
                                                                                    <p class=" discount-rate">Ongkos Kirim :</p>
                                                                                </div>
                                                                                <div class="col-sm-4 col-5">
                                                                                    <?php
                                                                                    // Biaya ongkos kirim (misalnya Rp 7,000)
                                                                                    $shipping = 7000;
                                                                                    ?>
                                                                                    <p class=""><?php echo 'Rp ' . number_format($shipping, 0, ',', '.'); ?></p>
                                                                                </div>
                                                                            <?php } ?>
                                                                            <div class="col-sm-8 col-7">
                                                                                <h5 class="">Total Pembayaran : </h5>
                                                                            </div>
                                                                            <div class="col-sm-4 col-5">
                                                                                <?php
                                                                                // Hitung total pembayaran
                                                                                $grand_total = $subtotal;
                                                                                if ($order_data['jenis_pemesanan'] == 'Antar') {
                                                                                    $grand_total += $shipping;
                                                                                }
                                                                                ?>
                                                                                <h5 class=""><?php echo 'Rp ' . number_format($grand_total, 0, ',', '.'); ?></h5>
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
                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                </div>


                                <div class="col-xl-3">
                                    <div class="invoice-actions-btn">
                                        <div class="invoice-action-btn">
                                            <div class="row">
                                                <div class="col-xl-12 col-md-3 col-sm-6">
                                                    <a href="javascript:void(0);" class="btn btn-secondary btn-print action-print">Print</a>
                                                </div>
                                                <?php 
                                                // Periksa apakah status pemesanan adalah "Selesai"
                                                if ($order_data['status'] != 'Selesai' && $order_data['status'] != 'Diantar') { 
                                                ?>
                                                <div class="col-xl-12 col-md-3 col-sm-6">
                                                    <a href="admin-edit-faktur<?php echo '?no_pemesanan=' . $order_data['no_pemesanan']; ?>" class="btn btn-dark btn-edit">Edit</a>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            </div>

    
                        </div>
                    </div>

                </div>

            </div>

            <!--  BEGIN FOOTER  -->
            <?php include 'layouts/footer.php'?>
            <!--  END FOOTER  -->
        </div>
        <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="src/plugins/src/waves/waves.min.js"></script>
    <script src="layouts/vertical-light-menu/app.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="src/assets/js/apps/invoice-preview.js"></script>
</body>
</html>