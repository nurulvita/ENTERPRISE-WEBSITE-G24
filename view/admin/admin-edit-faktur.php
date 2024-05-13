<?php
// Mulai sesi
session_start();
include_once('../../config/koneksi.php');

$no_pemesanan = $_GET['no_pemesanan'];

if (!isset($_COOKIE['username_admin']) && !isset($_SESSION['username_admin'])) {
    header('location: ../../login');
    exit();
}
if (isset($_SESSION['username_admin'])) {
    $username_session = $_SESSION['username_admin'];
} else {
    $username_session = "Sesi belum terbuat";
}

function updatePemesananTotals($conn, $no_pemesanan) {
    $sql_update_totals = "UPDATE pemesanan 
                         SET total_barang = (SELECT SUM(jumlah) FROM detail_pemesanan WHERE no_pemesanan = '$no_pemesanan'), 
                             total_harga = (SELECT SUM(harga * jumlah) FROM detail_pemesanan WHERE no_pemesanan = '$no_pemesanan') 
                         WHERE no_pemesanan = '$no_pemesanan'";
    
    if(mysqli_query($conn, $sql_update_totals)) {
        return true; 
    } else {
        return false;
    }
}

if (isset($_POST['kurangi_produk'])) {
    $id_produk = $_POST['produk_id'];

    $query_kurangi_jumlah = "UPDATE detail_pemesanan SET jumlah = jumlah - 1 WHERE no_pemesanan = '$no_pemesanan' AND produk_id = '$id_produk'";

    if (mysqli_query($conn, $query_kurangi_jumlah)) {
        if (updatePemesananTotals($conn, $no_pemesanan)) {
            header('location: admin-edit-faktur?no_pemesanan=' . $no_pemesanan);
            exit;
        } else {
            echo '<script>alert("Gagal memperbarui total barang dan total harga");</script>';
        }
    } else {
        echo '<script>alert("Gagal mengurangi jumlah produk dalam keranjang.");</script>';
    }
}




if(isset($_POST['hapus_produk'])) {
    $produk_id = $_POST['hapus_produk'];
    $query_hapus_produk = "DELETE FROM detail_pemesanan WHERE no_pemesanan = '$no_pemesanan' AND produk_id = '$produk_id'";
    
    if(mysqli_query($conn, $query_hapus_produk)) {
        if(updatePemesananTotals($conn, $no_pemesanan)) {
            echo '<script>alert("Produk berhasil dihapus");</script>';
            header('location: admin-edit-faktur?no_pemesanan=' . $no_pemesanan);
            exit; 
        } else {
            echo '<script>alert("Gagal memperbarui total barang dan total harga");</script>';
        }
    } else {
        echo '<script>alert("Gagal menghapus produk");</script>';
    }
}



if (isset($_POST['produk_id'])) {
    $id_produk = $_POST['produk_id'];
    $username = $username_session;

    $query_get_produk = "SELECT nama_produk, harga FROM produk WHERE id_produk = '$id_produk'";
    $result_produk = mysqli_query($conn, $query_get_produk);

    if ($result_produk && mysqli_num_rows($result_produk) > 0) {
        $row_produk = mysqli_fetch_assoc($result_produk);
        $nama_produk = $row_produk['nama_produk'];
        $harga_produk = $row_produk['harga'];

        // Cek apakah produk sudah ada di detail pemesanan
        $query_check_produk = "SELECT COUNT(*) AS count FROM detail_pemesanan WHERE no_pemesanan = '$no_pemesanan' AND produk_id = '$id_produk'";
        $result_check_produk = mysqli_query($conn, $query_check_produk);
        $row_check_produk = mysqli_fetch_assoc($result_check_produk);
        $count_produk = $row_check_produk['count'];

        if ($count_produk > 0) {
            $query_update_jumlah = "UPDATE detail_pemesanan SET jumlah = jumlah + 1 WHERE no_pemesanan = '$no_pemesanan' AND produk_id = '$id_produk'";
            if (mysqli_query($conn, $query_update_jumlah)) {
                if (updatePemesananTotals($conn, $no_pemesanan)) {
                    header('location: admin-edit-faktur?no_pemesanan=' . $no_pemesanan);
                    exit;
                } else {
                    echo '<script>alert("Gagal memperbarui total barang dan total harga");</script>';
                }
            } else {
                echo '<script>alert("Gagal memperbarui jumlah produk dalam keranjang.");</script>';
            }
        } else {
            $query_insert_detail = "INSERT INTO detail_pemesanan (no_pemesanan, produk_id, username, nama_produk, jumlah, harga) 
                                    VALUES ('$no_pemesanan', '$id_produk', '$username', '$nama_produk', 1, '$harga_produk')";
            if (mysqli_query($conn, $query_insert_detail)) {
                if (updatePemesananTotals($conn, $no_pemesanan)) {
                    header('location: admin-edit-faktur?no_pemesanan=' . $no_pemesanan);
                    exit;
                } else {
                    echo '<script>alert("Gagal memperbarui total barang dan total harga");</script>';
                }
            } else {
                echo '<script>alert("Gagal menambahkan produk ke detail pemesanan");</script>';
            }
        }
    } else {
        echo '<script>alert("Produk tidak ditemukan");</script>';
    }
}



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
    <link href="src/plugins/src/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="src/plugins/src/filepond/filepond.min.css">
    <link rel="stylesheet" href="src/plugins/src/filepond/FilePondPluginImagePreview.min.css">

    <link href="src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
    <link href="src/plugins/css/light/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <link href="src/assets/css/light/apps/invoice-edit.css" rel="stylesheet" type="text/css" />


    <link href="src/plugins/css/dark/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
    <link href="src/plugins/css/dark/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    
    <link href="src/assets/css/dark/apps/invoice-edit.css" rel="stylesheet" type="text/css" />
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

                <div class="middle-content container-xxl p-0 pt-4">

                    <!-- Form dan Tabel Produk -->
                    <div class="row form-tabel-produk">
                        <div class="col-xl-12">
                            <div class="table-responsive">
                                <!-- Tabel Produk -->
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Harga</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $query_produk = "SELECT p.id_produk, p.nama_produk, p.harga, COALESCE(dp.jumlah, 0) AS jumlah, ps.username
                                                        FROM produk p
                                                        LEFT JOIN detail_pemesanan dp ON p.id_produk = dp.produk_id AND dp.no_pemesanan = '$no_pemesanan'
                                                        LEFT JOIN pemesanan ps ON dp.no_pemesanan = ps.no_pemesanan";
                                        $result_produk = mysqli_query($conn, $query_produk);

                                        if (mysqli_num_rows($result_produk) > 0) {
                                            while ($row = mysqli_fetch_assoc($result_produk)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row['nama_produk']; ?></td>
                                                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                                    <td>
                                                    <form method="post">
                                                        <input type="hidden" name="produk_id" value="<?php echo $row['id_produk']; ?>">
                                                        <?php if ($row['username'] === 'NON-MEMBER'): ?>
                                                            <?php if ($row['jumlah'] > 1): ?>
                                                                <button type="submit" class="btn btn-secondary btn-minus" name="kurangi_produk">-</button>
                                                            <?php else: ?>
                                                                <button type="button" class="btn btn-secondary btn-minus">-</button>
                                                            <?php endif; ?>
                                                            <span class="btn btn-light" id="jumlah<?php echo $row['id_produk']; ?>"><?php echo $row['jumlah']; ?></span>
                                                            <button type="submit" class="btn btn-primary btn-plus">+</button>
                                                        <?php else: ?>
                                                            <?php if ($row['jumlah'] > 1): ?>
                                                                <button type="submit" class="btn btn-secondary btn-minus" name="kurangi_produk">-</button>
                                                                <?php else: ?>
                                                                    <button type="button" class="btn btn-secondary btn-minus" disabled>-</button>
                                                                <?php endif; ?>
                                                                <span class="btn btn-light" id="jumlah<?php echo $row['id_produk']; ?>"><?php echo $row['jumlah']; ?></span>
                                                                <button type="submit" class="btn btn-primary btn-plus" disabled>+</button>
                                                            <!-- <button type="submit" class="btn btn-primary btn-plus" disabled>+</button> -->
                                                        <?php endif; ?>
                                                    </form>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='3'>Tidak ada produk tersedia.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>


                    <form method="POST">
                    <div class="row invoice layout-top-spacing layout-spacing">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            
                            <div class="doc-container">
    
                                <div class="row">
                                    <div class="col-xl-12">
    
                                        <div class="invoice-content">
    
                                            <div class="invoice-detail-body">

                                            <div class="invoice-detail-header">
                                                <div class="row justify-content-between">
                                                <div class="col-xl-5 invoice-address-client">
                                                    <h4>Faktur Untuk:-</h4>
                                                    <div class="invoice-address-client-fields">
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
                                                                echo '<div class="form-group row">
                                                                        <label for="kode-member" class="col-sm-3 col-form-label col-form-label-sm">Member</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control form-control-sm" id="kode-member" placeholder="Member" value="' . $order_data['username'] . '" readonly>
                                                                        </div>
                                                                    </div>';
                                                                if ($pelanggan_data['username'] === 'NON-MEMBER') {
                                                                    echo '<input type="hidden" class="form-control form-control-sm" id="client-phone" placeholder="Phone" value="' . $pelanggan_data['no_hp'] . '" readonly>';
                                                                } else {
                                                                    echo '<div class="form-group row">
                                                                            <label for="client-phone" class="col-sm-3 col-form-label col-form-label-sm">No. HP</label>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" class="form-control form-control-sm" id="client-phone" placeholder="Phone" value="' . $pelanggan_data['no_hp'] . '" readonly>
                                                                            </div>
                                                                        </div>';
                                                                }
                                                            } else {
                                                                echo "NON-MEMBER";
                                                            }

                                                            $nama = !empty($order_data['nama']) ? $order_data['nama'] : '';
                                                            $alamat = !empty($order_data['alamat']) ? $order_data['alamat'] : '';

                                                            echo '<div class="form-group row">
                                                                    <label for="client-name" class="col-sm-3 col-form-label col-form-label-sm">Nama</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="client-name" placeholder="-" value="' . $nama . '" readonly>
                                                                    </div>
                                                                </div>';

                                                            echo '<div class="form-group row">
                                                                    <label for="client-address" class="col-sm-3 col-form-label col-form-label-sm">Alamat</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="client-address" placeholder="-" value="' . $alamat . '" readonly>
                                                                    </div>
                                                                </div>';
                                                        } else {
                                                            echo "Data pemesanan tidak ditemukan";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>



                                                    <div class="col-xl-5 invoice-actions">
                                                        <div class="invoice-action-currency">
                                                            <div class="form-group mb-0">
                                                                <label>Status Pemesanan</label>
                                                                <div class="dropdown selectable-dropdown invoice-select">
                                                                    <a id="statusDropdown" href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <span class="selectable-text">
                                                                            <?php 
                                                                            $no_pemesanan = $_GET['no_pemesanan'];

                                                                            $sql_order = "SELECT * FROM pemesanan WHERE no_pemesanan = '$no_pemesanan'";
                                                                            $result_order = $conn->query($sql_order);

                                                                            if ($result_order->num_rows > 0) {
                                                                                $order_data = $result_order->fetch_assoc();
                                                                                echo $order_data['status'];
                                                                            } else {
                                                                                echo 'Pilih Status';
                                                                            }
                                                                            ?>
                                                                        </span> 
                                                                        <span class="selectable-arrow">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                                                                                <polyline points="6 9 12 15 18 9"></polyline>
                                                                            </svg>
                                                                        </span>
                                                                    </a>
                                                                    <div class="dropdown-menu" aria-labelledby="statusDropdown">
                                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="updateStatus('Menunggu')">Menunggu</a>
                                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="updateStatus('Dikonfirmasi')">Dikonfirmasi</a>
                                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="updateStatus('Diantar')">Diantar</a>
                                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="updateStatus('Selesai')">Selesai</a>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="invoice-action-tax">
                                                            <h5>Pemesanan</h5>
                                                            <div class="invoice-action-tax-fields">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-group mb-0">
                                                                            <label>Jenis Pemesanan</label>
                                                                            <div class="dropdown selectable-dropdown invoice-tax-select">
                                                                                <a id="taxTypeDropdown" href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    <span class="selectable-text">
                                                                                        <?php
                                                                                        echo $order_data['jenis_pemesanan'];
                                                                                        ?>
                                                                                    </span>
                                                                                    <span class="selectable-arrow">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                                                                                            <polyline points="6 9 12 15 18 9"></polyline>
                                                                                        </svg>
                                                                                    </span>
                                                                                </a>
                                                                                <div class="dropdown-menu" aria-labelledby="taxTypeDropdown">
                                                                                    <a class="dropdown-item" data-value="Antar" href="javascript:void(0);">Antar</a>
                                                                                    <a class="dropdown-item" data-value="Beli Langsung" href="javascript:void(0);">Beli Langsung</a>
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

    
                                                <div class="invoice-detail-terms">
    
                                                    <div class="row justify-content-between">
    
                                                        <div class="col-md-3">
    
                                                            <div class="form-group mb-4">
                                                                <label for="number">Nomor Pemesanan :</label>
                                                                <p class="inv-street-addr"><?php echo isset($order_data['no_pemesanan']) ? $order_data['no_pemesanan'] : ''; ?></p>
                                                            </div>
    
                                                        </div>
    
                                                        <div class="col-md-3">
    
                                                        <div class="form-group mb-4">
                                                            <label for="date">Tgl. Pemesanan :</label>
                                                            <p class="inv-street-addr"><?php echo isset($order_data['tanggal']) ? $order_data['tanggal'] : ''; ?></p>

                                                        </span>

                                                        </div>
    
                                                    </div>
                                                    
                                                </div>
    
    
                                                <div class="invoice-detail-items">
                                                    <div class="table-responsive">
                                                        <table class="table item-table">
                                                            <!-- Tabel Header -->
                                                            <thead>
                                                                <tr>
                                                                    <th class=""></th>
                                                                    <th>Barang</th>
                                                                    <th class="">Harga</th>
                                                                    <th class="">Kuantitas</th>
                                                                    <th class="text-right">Total Harga</th>
                                                                </tr>
                                                                <tr aria-hidden="true" class="mt-3 d-block table-row-hidden"></tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $sql_detail = "SELECT * FROM detail_pemesanan WHERE no_pemesanan = '$no_pemesanan'";
                                                                $result_detail = $conn->query($sql_detail);

                                                                $status = $order_data['status'];

                                                                if ($result_detail->num_rows > 0) {
                                                                    $index = 1;
                                                                    $subtotal = 0;
                                                                    while ($detail_data = $result_detail->fetch_assoc()) {
                                                                        ?>
                                                                        <tr>
                                                                            <td class='delete-item-row' <?php echo ($status == 'Diantar' || $status == 'Selesai') ? 'style="display:none;"' : ''; ?> onclick="hapusProduk('<?php echo $detail_data['produk_id']; ?>')">
                                                                                <ul class='table-controls'>
                                                                                    <li>
                                                                                        <a href='javascript:void(0);' class='delete-item' data-toggle='tooltip' data-placement='top' title='Delete'>
                                                                                            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-x-circle'>
                                                                                                <circle cx='12' cy='12' r='10'></circle>
                                                                                                <line x1='15' y1='9' x2='9' y2='15'></line>
                                                                                                <line x1='9' y1='9' x2='15' y2='15'></line>
                                                                                            </svg>
                                                                                        </a>
                                                                                    </li>
                                                                                </ul>
                                                                            </td>

                                                                            <td class="description">
                                                                                <?php echo '<span>' . $detail_data['nama_produk'] . '</span>'; ?>
                                                                            </td>
                                                                            <td class="rate">
                                                                                <?php echo 'Rp ' . number_format($detail_data['harga'], 0, ',', '.'); ?>
                                                                            </td>
                                                                            <td class="text-right qty">
                                                                                <?php echo $detail_data['jumlah']; ?>
                                                                            </td>
                                                                            <td class="text-right amount">
                                                                                <?php echo 'Rp ' . number_format($detail_data['jumlah'] * $detail_data['harga'], 0, ',', '.'); ?>
                                                                            </td>
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
                                                            <div>
                                                                <div class="row">
                                                                    <div class="col-sm-8 col-7">
                                                                        <p class="">Sub Total :</p>
                                                                    </div>
                                                                    <div class="col-sm-4 col-5">
                                                                        <p class=""><?php echo 'Rp ' . number_format($subtotal, 0, ',', '.'); ?></p>
                                                                    </div>
                                                                    <?php if($order_data['jenis_pemesanan'] !== 'Beli Langsung'): ?>
                                                                    <div class="col-sm-8 col-7">
                                                                        <p class=" discount-rate">Ongkos Kirim :</p>
                                                                    </div>
                                                                    <div class="col-sm-4 col-5">
                                                                        <?php
                                                                        $shipping = 7000;
                                                                        ?>
                                                                        <p class=""><?php echo 'Rp ' . number_format($shipping, 0, ',', '.'); ?></p>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                    <div class="col-sm-8 col-7">
                                                                        <h5 class="">Total Pembayaran : </h5>
                                                                    </div>
                                                                    <div class="col-sm-4 col-5">
                                                                        <?php
                                                                        $grand_total = $subtotal;
                                                                        if($order_data['jenis_pemesanan'] !== 'Beli Langsung') {
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


                                                <div class="invoice-detail-note">
    
                                                    <div class="row">
                                                    
                                                        <div class="col-md-12 align-self-center">
    
                                                            <div class="form-group row invoice-note">
                                                                <label for="invoice-detail-notes" class="col-sm-12 col-form-label col-form-label-sm">Notes:</label>
                                                                <div class="col-sm-12">
                                                                    <p id="invoice-detail-notes" style="height: 88px;">Catatan: Terimakasih telah membeli produk kami. Dapatkan kupon untuk setiap pembelian produk.</p>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
    
                                                    </div>
    
                                                </div>      
                                                
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
    
                                    <div class="col-xl-12">
    
                                        <div class="invoice-actions-btn">
    
                                            <div class="invoice-action-btn">
    
                                                <div class="row">
                                                    <div class="col-xl-12 col-md-4">
                                                        <a href="admin-lihat-faktur.php?no_pemesanan=<?php echo $order_data['no_pemesanan']; ?>" class="btn btn-secondary btn-preview">Preview</a>
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
                </form>
                
            </div>

        </div>
        <!--  END CONTENT AREA  -->
        
        <!--  BEGIN FOOTER  -->
        <?php include 'layouts/footer.php'?>
        <!--  END FOOTER  -->

    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="src/plugins/src/global/vendors.min.js"></script>
    <script src="src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="src/plugins/src/waves/waves.min.js"></script>
    <script src="layouts/vertical-light-menu/app.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <script src="src/plugins/src/filepond/filepond.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginFileValidateType.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImagePreview.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImageCrop.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImageResize.min.js"></script>
    <script src="src/plugins/src/filepond/FilePondPluginImageTransform.min.js"></script>
    <script src="src/plugins/src/filepond/filepondPluginFileValidateSize.min.js"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="src/plugins/src/flatpickr/flatpickr.js"></script>
    <script src="src/assets/js/apps/invoice-edit.js"></script>
    
    
    <script src="src/assets/js/apps/invoice-add.js"></script>
    <script>
        document.getElementById("no-pemesanan").value = "<?php echo $nomor_pemesanan; ?>";
    </script>
    


    <script>
        function hapusProduk(id) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                var form = document.createElement('form');
                form.method = 'post';
                form.action = '';
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'hapus_produk';
                input.value = id;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }

    document.addEventListener("DOMContentLoaded", function() {
               const currentDate = new Date();

                const options = {
                    timeZone: "Asia/Makassar",
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit",
                    hour: "2-digit",
                    minute: "2-digit",
                    second: "2-digit"
                };

                const formattedDateTime = currentDate.toLocaleString("en-US", options);

                document.getElementById("kalender").value = formattedDateTime;
        const formattedDate = currentDate.toISOString().slice(0, 10).replace(/-/g, "");
        const randomNumber = Math.floor(Math.random() * 90 + 10); 
        const invoiceNumber = `P${formattedDate}${randomNumber}`;
        
        document.getElementById("number").value = invoiceNumber;
    });
    
    function formatRupiah(angka) {
        var number_string = angka.toString(),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/g);
        
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return 'Rp ' + rupiah;
    }
    $('.product-select').change(function() {
        var price = $(this).children('option:selected').data('price');
        
        $('#price').val(formatRupiah(price));
    });

    // Fungsi untuk menghitung total amount
    function calculateTotal(row) {
        var price = parseFloat(row.find('.price').val().replace(/\D/g, ''));
        var quantity = parseInt(row.find('.quantity').val());
        var total = price * quantity;
        row.find('.total-amount').text(formatRupiah(total));
    }

        $(document).ready(function() {
            // Fungsi untuk mengonversi angka menjadi format mata uang Rupiah
            function formatRupiah(angka) {
                var reverse = angka.toString().split('').reverse().join(''),
                    ribuan = reverse.match(/\d{1,3}/g);
                ribuan = ribuan.join('.').split('').reverse().join('');
                return 'Rp ' + ribuan;
            }


            // Fungsi untuk menghitung total amount
            function calculateTotal(row) {
                var price = parseFloat(row.find('.price').val().replace(/\D/g, '')); 
                var quantity = parseInt(row.find('.quantity').val());
                var total = price * quantity;
                row.find('.total-amount').text(formatRupiah(total));
            }


            $('.quantity').on('input', function() {
                var row = $(this).closest('tr');
                calculateTotal(row); 
            });
        });
        
    $('.delete-item').click(function() {
        var row = $(this).closest('tr');
        row.remove();
    });

</script>

<script>

    function addProductRow(id, name, price) {
        var html = `
            <tr>
                <td class="delete-item-row">
                    <ul class="table-controls">
                        <li>
                            <a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </td>
                <td class="description">${name}</td>
                <td class="rate">
                    <input type="text" class="form-control form-control-sm price" value="${price}" readonly>
                </td>
                <td class="text-right qty">
                    <input type="number" class="form-control form-control-sm quantity" placeholder="Quantity">
                </td>
                <td class="text-right amount">
                    <span class="editable-amount"></span> 
                    <span class="total-amount"></span>
                </td>
            </tr>`;
        $('#produk-container').append(html);
    }

    $('.product-select').change(function() {
        var productId = $(this).val();
        var productName = $(this).find('option:selected').text();
        var productPrice = $(this).find('option:selected').data('price');
        addProductRow(productId, productName, productPrice);
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var dropdownToggle = document.getElementById("taxTypeDropdown");
        dropdownToggle.removeAttribute("href");
        dropdownToggle.removeAttribute("data-bs-toggle");
        dropdownToggle.removeAttribute("aria-haspopup");
        dropdownToggle.removeAttribute("aria-expanded");
        dropdownToggle.addEventListener("click", function(event) {
            event.preventDefault(); 
        });
    });
    
</script>
<script>
    function updateStatus(status) {
    var no_pemesanan = '<?php echo $no_pemesanan; ?>';

    $.ajax({
        url: 'admin-edit-status',
        type: 'POST',
        dataType: 'json',
        data: {
            update_status: true,
            no_pemesanan: no_pemesanan,
            status: status
        },
        success: function(response) {
            if (response.status === 'success') {
                alert('Status pemesanan berhasil diperbarui');
                window.location.reload();
            } else {
                alert('Gagal memperbarui status pemesanan: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert('Terjadi kesalahan saat memperbarui status pemesanan');
        }
    });
}

</script>



</body>
</html>