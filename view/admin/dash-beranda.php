<?php

session_start();
include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username_admin']) && !isset($_SESSION['username_admin'])) {
    header('location: ../../login.php');
    exit();
}
if (isset($_SESSION['username_admin'])) {
    $username_session = $_SESSION['username_admin'];
} else {
    $username_session = "Sesi belum terbuat";
}

$sql_pelanggan = "SELECT COUNT(*) AS total_pelanggan FROM pelanggan";
$result_pelanggan = $conn->query($sql_pelanggan);

$row_pelanggan = $result_pelanggan->fetch_assoc();
$total_pelanggan = $row_pelanggan['total_pelanggan'];

$sql_orders = "SELECT SUM(total_harga) AS total_penjualan FROM pemesanan";
$result_orders = $conn->query($sql_orders);

$row_orders = $result_orders->fetch_assoc();
$total_penjualan = $row_orders['total_penjualan'];

$sql_produk = "SELECT SUM(total_barang) AS total FROM pemesanan";
$result_produk = $conn->query($sql_produk);

$row_produk = $result_produk->fetch_assoc();
$total_produk = $row_produk['total'];

$sql_rating = "SELECT AVG(rating) AS total_rating FROM ulasan";
$result_rating = $conn->query($sql_rating);

$row_rating = $result_rating->fetch_assoc();
$total_rating = $row_rating['total_rating'];

$total_rating = number_format($total_rating, 1);

$sql_recent_orders = "SELECT * FROM pemesanan ORDER BY tanggal DESC LIMIT 7";
$result_recent_orders = $conn->query($sql_recent_orders);

$sql_menunggu = "SELECT * FROM pemesanan WHERE status = 'menunggu' ORDER BY tanggal DESC LIMIT 7";
$result_menunggu = $conn->query($sql_menunggu);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>G-24 | Dashboard Admin </title>
    <link rel="icon" type="image/x-icon" href="src/assets/img/G-24.ico"/>
    <link href="layouts/vertical-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="layouts/vertical-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="layouts/vertical-light-menu/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="src/plugins/src/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="src/assets/css/light/components/list-group.css" rel="stylesheet" type="text/css">
    <link href="src/assets/css/light/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->  

</head>
<body class=" layout-boxed">
    <?php include 'layouts/navbar.php'; ?>

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <?php include 'layouts/sidebar.php'; ?>
        
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="middle-content container-xxl p-0">
                    <div class="row layout-top-spacing">
                        <section class="col-xl-3 col-lg-6 col-md-6 col-sm-6 layout-spacing">
                            <div class="widget widget-t-sales-widget widget-m-sales">
                                <div class="media">
                                    <div class="icon ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="16"></line></svg>
                                    </div>
                                    <div class="media-body">
                                        <p class="widget-text">Rating</p>
                                        <p class="widget-numeric-value"><?php echo $total_rating; ?></p>
                                    </div>
                                </div>
                                <a href="pelanggan-ulasan">
                                    <p class="widget-total-stats">View all <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></p>
                                </a>
                            </div>
                        </section>

                        <section class="col-xl-3 col-lg-6 col-md-6 col-sm-6 layout-spacing">
                            <div class="widget widget-t-sales-widget widget-m-orders">
                                <div class="media">
                                    <div class="icon ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                    </div>
                                    <div class="media-body">
                                        <p class="widget-text">Pemesanan</p>
                                        <p class="widget-numeric-value"><?php echo $total_produk; ?></p>
                                    </div>
                                </div>
                                <a href="admin-pemesanan">
                                    <p class="widget-total-stats">View all <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></p>
                                </a>
                            </div>
                        </section>

                        <section class="col-xl-3 col-lg-6 col-md-6 col-sm-6 layout-spacing">
                            <div class="widget widget-t-sales-widget widget-m-customers">
                                <div class="media">
                                    <div class="icon ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    </div>
                                    <div class="media-body">
                                        <p class="widget-text">Pelanggan</p>
                                        <p class="widget-numeric-value"><?php echo $total_pelanggan; ?></p>
                                    </div>
                                </div>
                                <a href="pelanggan-list">
                                    <p class="widget-total-stats">View all <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></p>
                                </a>
                            </div>
                        </section>

                        <section class="col-xl-3 col-lg-6 col-md-6 col-sm-6 layout-spacing">
                            <div class="widget widget-t-sales-widget widget-m-income">
                                <div class="media">
                                    <div class="icon ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                    </div>
                                    <div class="media-body">
                                        <p class="widget-text">Pendapatan</p>
                                        <p class="widget-numeric-value">Rp. <?php echo $total_penjualan; ?></p>
                                    </div>
                                </div>
                                <a href="admin-pemesanan">
                                    <p class="widget-total-stats">View all <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></p>
                                </a>
                            </div>
                        </section>

                        <section class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-table-tree">

                                <div class="widget-heading">
                                    <h5 class="">Orderan Terbaru</h5>
                                </div>

                                <div class="widget-content">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th><div class="th-content th-heading">Waktu</div></th>
                                                    <th><div class="th-content th-heading">Nama</div></th>
                                                    <th><div class="th-content th-heading">Alamat</div></th>
                                                    <th><div class="th-content th-heading">Jumlah</div></th>
                                                    <th><div class="th-content th-heading">Harga</div></th>
                                                    <th><div class="th-content th-heading">Jenis</div></th>
                                                    <th><div class="th-content th-heading">Status</div></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                if ($result_recent_orders->num_rows > 0) {
                                                    while ($row = $result_recent_orders->fetch_assoc()) {
                                                        echo '<tr>';
                                                        echo '<td><div class="td-content product-brand text-danger">' . date('H:i', strtotime($row['tanggal'])) . '</div></td>';
                                                        echo '<td><div class="td-content product-invoice">' . $row['nama'] . '</div></td>';
                                                        echo '<td><div class="td-content product-invoice"><span class="">' . $row['alamat'] . '</span></div></td>';
                                                        echo '<td><div class="td-content product-invoice"><span class="">' . $row['total_barang'] . '</span></div></td>';
                                                        echo '<td><div class="td-content product-invoice"><span class="">' . $row['total_harga'] . '</span></div></td>';
                                                        echo '<td><div class="td-content product-invoice"><span class="">' . $row['jenis_pemesanan'] . '</span></div></td>';
                                                        echo '<td><div class="td-content"><span class="badge ' . getStatusClass($row['status']) . '">' . $row['status'] . '</span></div></td>';
                                                        echo '</tr>';
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5'>No recent orders found.</td></tr>";
                                                }

                                                function getStatusClass($status) {
                                                    $label_class = '';
                                                    switch ($status) {
                                                        case 'Menunggu':
                                                            $label_class = 'badge-light-warning';
                                                            break;
                                                        case 'Dikonfirmasi':
                                                            $label_class = 'badge-light-primary';
                                                            break;
                                                        case 'Diantar':
                                                            $label_class = 'badge-light-info';
                                                            break;
                                                        case 'Selesai':
                                                            $label_class = 'badge-light-success';
                                                            break;
                                                        default:
                                                            $label_class = 'badge-light-secondary';
                                                            break;
                                                    }
                                                    return $label_class;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </section>


                        <section class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-table-tree">

                                <div class="widget-heading">
                                    <h5 class="">Pesanan Menunggu</h5>
                                </div>

                                <div class="widget-content">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th><div class="th-content th-heading">Nama</div></th>
                                                    <th><div class="th-content th-heading">Alamat</div></th>
                                                    <th><div class="th-content th-heading">Jenis</div></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($result_menunggu->num_rows > 0) {
                                                    while ($row = $result_menunggu->fetch_assoc()) {
                                                        echo '<tr>';
                                                        echo '<td><div class="td-content product--invoice"><div class="align-self-center"><p class="prd-name">' . $row['nama'] . '</p><p class="prd-category text-primary">' . $row['no_pemesanan'] . '</p></div></div></td>';
                                                        echo '<td><div class="td-content product-invoice"><span class="">' . $row['alamat'] . '</span></div></td>';
                                                        echo '<td><div class="td-content product-invoice"><span class="">' . $row['jenis_pemesanan'] . '</span></div></td>';
                                                        echo '</tr>';
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5'>No recent orders found.</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            <!--  BEGIN FOOTER  -->
            <?php include 'layouts/footer.php'; ?>
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

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="src/plugins/src/apex/apexcharts.min.js"></script>
    <script src="src/assets/js/dashboard/dash_2.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

</body>
</html>