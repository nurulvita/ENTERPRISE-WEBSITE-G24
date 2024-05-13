
<?php
// Mulai sesi
session_start();
include_once('../../config/koneksi.php');

if (!isset($_COOKIE['username_admin']) && !isset($_SESSION['username_admin'])) {
    header('location: ../../login');
    exit();
}
if (isset($_SESSION['username_admin'])) {
    $username_session = $_SESSION['username_admin'];
} else {
    $username_session = "Sesi belum terbuat";
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
    <link rel="stylesheet" type="text/css" href="src/plugins/src/table/datatable/datatables.css">
    
    <link rel="stylesheet" type="text/css" href="src/plugins/css/light/table/datatable/dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/css/dark/table/datatable/dt-global_style.css">
    <!--  END CUSTOM STYLE FILE  -->

    <style>
        #blog-list img {
            border-radius: 18px;
        }
    </style>
    
</head>
<body class="layout-boxed" data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="140">
    
    <!-- BEGIN LOADER -->
    <?php include 'layouts/load.php'?>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <?php include 'layouts/navbar.php'?>
    <!--  END NAVBAR  -->


    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

            <!--  BEGIN SIDEBAR  -->
            <?php include 'layouts/sidebar.php'?>
         <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">

            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">
    
                    <?php
                        // Buat koneksi ke database
                        include_once('../../config/koneksi.php');

                        // Periksa koneksi
                        if (!$conn) {
                            die("Koneksi gagal: " . mysqli_connect_error());
                        }

                        // Query untuk mengambil data dari database
                        $query = "SELECT * FROM blog_post";
                        $result = mysqli_query($conn, $query);

                        ?>

                        <div class="row layout-top-spacing">
                            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                                <div class="widget-content widget-content-area br-8">
                                    <table id="blog-list" class="table dt-table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="checkbox-column"></th>
                                                <th>Postingan</th>
                                                <th>Tanggal Publikasi</th>
                                                <th>Status</th>
                                                <th class="no-content text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row_blog = mysqli_fetch_assoc($result)) {
                                                    echo '<tr>';
                                                    echo '<td>' . $row_blog['id'] . '</td>';
                                                    echo '<td>';
                                                    echo '<div class="d-flex justify-content-left align-items-center">';
                                                    echo '<div class="avatar  me-3">';
                                                    echo '<img src="../../blog_post/' . $row_blog['gambar'] . '" alt="Avatar" width="64" height="64">';
                                                    echo '</div>';
                                                    echo '<div class="d-flex flex-column">';
                                                    echo '<span class="text-truncate fw-bold">' . $row_blog['judul'] . '</span>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                    echo '</td>';
                                                    echo '<td>' . $row_blog['tanggal_publikasi'] . '</td>';
                                                    $statusText = ($row_blog['showPublicly'] == 1) ? 'Published' : 'Draft';
                                                    $statusColor = ($row_blog['showPublicly'] == 1) ? 'success' : 'danger';
                                                    echo '<td><span class="badge badge-' . $statusColor . '">' . $statusText . '</span></td>';
                                                    echo '<td class="text-center">';
                                                    echo '<div class="dropdown">';
                                                    echo '<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink' . $row_blog['id'] . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">';
                                                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>';
                                                    echo '</a>';
                                                    echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink' . $row_blog['id'] . '">';
                                                    echo '<a class="dropdown-item" href="blog-edit?id=' . $row_blog['id'] . '">Edit</a>';
                                                    echo '<a class="dropdown-item delete" href="javascript:void(0);" data-blog-id="' . $row_blog['id'] . '">Delete</a>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                    echo '</td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>Tidak ada data yang ditemukan</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <?php
                        mysqli_close($conn);
                        ?>



                </div>

            </div>
            
            <!--  BEGIN FOOTER  -->
            <?php include 'layouts/footer.php'?>
            <!--  END FOOTER  -->

        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <script src="src/plugins/src/global/vendors.min.js"></script>
    <script src="src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="src/plugins/src/waves/waves.min.js"></script>
    <script src="layouts/vertical-light-menu/app.js"></script>
    <script src="src/assets/js/custom.js"></script>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="src/plugins/src/table/datatable/datatables.js"></script>
    <script>
        blogList = $('#blog-list').DataTable({
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
        multiCheck(blogList);
    </script>
<script>
    $(document).ready(function() {
        $('.delete').click(function() {
            var blogId = $(this).data('blog-id');

            var confirmation = confirm("Apakah Anda yakin ingin menghapus blog ini?");
            
            if (confirmation) {
                deleteBlog(blogId);
            }
        });
    });

    function deleteBlog(blogId) {
        $.ajax({
            url: 'blog-delete',
            type: 'POST',
            data: { blogId: blogId }, 
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
                console.error('Terjadi kesalahan saat menghapus blog:', error);
            }
        });
    }
</script>


    <!-- END PAGE LEVEL SCRIPTS -->    
</body>
</html>