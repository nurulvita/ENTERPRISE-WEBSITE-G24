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
    <link rel="stylesheet" type="text/css" href="src/plugins/src/table/datatable/datatables.css">
    
    <link rel="stylesheet" type="text/css" href="src/plugins/css/light/table/datatable/dt-global_style.css">
    <link href="src/assets/css/light/apps/invoice-list.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" type="text/css" href="src/plugins/css/dark/table/datatable/dt-global_style.css">
    <link href="src/assets/css/dark/apps/invoice-list.css" rel="stylesheet" type="text/css" />
    <!--  END CUSTOM STYLE FILE  -->

    
</head>
<body>

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

        <!-- ========================  Tabel Pemesanan ======================== -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="middle-content container-xxl p-0">
                    <div class="row" id="cancel-row">
                        <div class="col-xl-12 col-lg-12 col-sm-12 layout-top-spacing layout-spacing">
                            <div class="widget-content widget-content-area br-8">
                                <table id="ecommerce-list" class="table dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="checkbox-column"> Record no. </th>
                                            <th>No. Pemesanan</th>
                                            <th>Tanggal/Waktu</th>
                                            <!-- <th>Kode Member</th> -->
                                            <th>Status</th>
                                            <th>Nama</th>
                                            <th>Alamat</th>
                                            <th>Total Barang</th>
                                            <th>Total Harga</th>
                                            <th>Jenis Pemesanan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
     
                                        $query_pemesanan = "SELECT `id`, `no_pemesanan`, `tanggal`, `status`,`nama`, `alamat`, `total_barang`, `total_harga`, `jenis_pemesanan` FROM `pemesanan` WHERE 1 ORDER BY `tanggal` DESC";

                                        $result_pemesanan = mysqli_query($conn, $query_pemesanan);


                                        if (mysqli_num_rows($result_pemesanan) > 0) {
                               
                                            while ($row_pemesanan = mysqli_fetch_assoc($result_pemesanan)) {
                                        ?>
                                            <tr>
                                                <td class="checkbox-column"><?php echo $row_pemesanan['id']; ?></td>
                                                <td><a href="admin-lihat-faktur?no_pemesanan=<?php echo $row_pemesanan['no_pemesanan']; ?>"><span class="inv-number">#<?php echo $row_pemesanan['no_pemesanan']; ?></span></a></td>
                                                <td><?php echo $row_pemesanan['tanggal']; ?></td>
                                              
                                                <td>
                                                    <?php 
                                                        // Add different status labels with different colors
                                                        $status = $row_pemesanan['status'];
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
                                                        echo '<span class="badge ' . $label_class . ' inv-status">' . $status . '</span>';
                                                    ?>
                                                </td>
                                                <td><?php echo $row_pemesanan['nama']; ?></td>
                                                <td><?php echo $row_pemesanan['alamat']; ?></td>
                                                <td><?php echo $row_pemesanan['total_barang']; ?></td>
                                                <td><?php echo $row_pemesanan['total_harga']; ?></td>
                                                <td><?php echo $row_pemesanan['jenis_pemesanan']; ?></td>

                                                <td>
                                                    <?php 
                                                    // Periksa apakah status pemesanan adalah "Selesai"
                                                    if ($row_pemesanan['status'] != 'Selesai') { 
                                                    ?>
                                                        <a class="badge badge-light-primary text-start me-2 action-edit" href="admin-edit-faktur?no_pemesanan=<?php echo $row_pemesanan['no_pemesanan']; ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3">
                                                                <path d="M12 20h9"></path>
                                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                                            </svg>
                                                        </a>
                                                    <?php } ?>
                                                    <a class="badge badge-light-danger text-start action-delete" href="" data-no-pemesanan="<?php echo $row_pemesanan['no_pemesanan']; ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        </svg>
                                                    </a>
                                                </td>

                                            </tr>

                                        <?php
                                            }
                                        } else {
                                            echo "Tidak ada data pemesanan yang tersedia.";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include 'layouts/footer.php'?>
        </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="src/plugins/src/global/vendors.min.js"></script>
    <script src="src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="src/plugins/src/waves/waves.min.js"></script>
    <script src="layouts/vertical-light-menu/app.js"></script>
    <script src="src/assets/js/custom.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="src/plugins/src/table/datatable/datatables.js"></script>
    <!-- <script src="src/plugins/src/table/datatable/button-ext/dataTables.buttons.min.js"></script> -->
    <script src="src/assets/js/apps/invoice-list.js"></script>
    <script>
        ecommerceList = $('#ecommerce-list').DataTable({
            headerCallback:function(e, a, t, n, s) {
                e.getElementsByTagName("th")[0].innerHTML=`
                <div class="form-check form-check-primary d-block new-control">
                    <input class="form-check-input chk-parent" type="checkbox" id="form-check-default">
                </div>`
            },
            columnDefs:[ {
                targets:0, width:"30px", className:"", orderable:!1, render:function(e, a, t, n) {
                    return `
                    <div class="form-check form-check-primary d-block new-control">
                        <input class="form-check-input child-chk" type="checkbox" id="form-check-default">
                    </div>`
                }
            }],
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 10 
        });
        multiCheck(ecommerceList);

 
        $(document).on('click', '.action-delete', function() {
            var no_pemesanan = $(this).data('no-pemesanan'); 
            if (confirm('Apakah Anda yakin ingin menghapus pesanan ini?')) {
                deletePemesanan(no_pemesanan);
            }
        });


        function deletePemesanan(no_pemesanan) {
            $.ajax({
                url: 'delete_pemesanan', 
                type: 'POST',
                data: { no_pemesanan: no_pemesanan }, 
                success: function(response) {
                    console.log('Response:', response);
                    var data = JSON.parse(response);
                    if (data.status === "success") {
                        alert(data.message);
                        exit;
                    } else {
                        alert(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan saat menghapus pesanan:', error);
                }
            });
        }



    </script>


</body>
</html>