<?php
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

    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="src/assets/css/light/components/modal.css" rel="stylesheet" type="text/css">
    <link href="src/assets/css/light/apps/notes.css" rel="stylesheet" type="text/css" />
    <link href="src/assets/css/dark/components/modal.css" rel="stylesheet" type="text/css">
    <link href="src/assets/css/dark/apps/notes.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
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
        
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">
                    
                    <div class="row app-notes layout-top-spacing" id="cancel-row">
                        <div class="col-lg-12">
                            <div class="app-hamburger-container">
                                <div class="hamburger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu chat-menu d-xl-none"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></div>
                            </div>
    
                            <div class="app-container">
                                
                                <div class="app-note-container">
    
                                    <div class="app-note-overlay"></div>
    
                                    <div class="tab-title">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-12 mb-5">
                                                <ul class="nav nav-pills d-block" id="pills-tab3" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link list-actions active" id="all-notes"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Semua Catatan</a>
                                                    </li>
                                                    <br>
                                                    <br>
                                                </ul>
    
                                                <hr/>
    
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-12 text-center">
                                                <a id="btn-add-notes" class="btn btn-secondary w-100" href="javascript:void(0);">Add Note</a>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div id="ct" class="note-container note-grid">

                                        <?php
                                        $sql = "SELECT * FROM catatan";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                        ?>
                                                <div class="note-item all-notes note-important">
                                                    <div class="note-inner-content">
                                                        <div class="note-content">
                                                            <p class="note-title" data-noteTitle="<?php echo $row["judul"]; ?>">
                                                                <?php echo $row["judul"]; ?>
                                                            </p>
                                                            <p class="meta-time">
                                                                <?php echo date('d M Y', strtotime($row['tanggal'])); ?>
                                                            </p>
                                                            <div class="note-description-content">
                                                                <p class="note-description" data-noteDescription="<?php echo $row["deskripsi"]; ?>">
                                                                    <?php echo $row["deskripsi"]; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        
                                                            <div class="badge badge-light-danger text-start action-delete"  data-id-note="<?php echo $row["id"]; ?>">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                                </svg>
                                                            </div>
                                                        
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        } else {
                                            echo "tidak ada hasil";
                                        }
                                        $conn->close();
                                        ?>
                                    </div>
    
                                </div>
                                
                            </div>
    
                            <!-- Modal -->
                            <div class="modal fade" id="notesMailModal" tabindex="-1" role="dialog" aria-labelledby="notesMailModalTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title add-title" id="notesMailModalTitleeLabel">Tambah Catatan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                              <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        </div>
                                        
                                        <div class="modal-body">
                                            <div class="notes-box">
                                                <div class="notes-content">  

                                                <form action="add_note" method="POST">
                                                        <div class="modal-body">
                                                            <div class="notes-box">
                                                                <div class="notes-content">  
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="d-flex note-title">
                                                                                <input type="text" name="title" class="form-control" maxlength="25" placeholder="Title">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="d-flex note-description">
                                                                                <textarea name="description" class="form-control" maxlength="60" placeholder="Description" rows="3"></textarea>
                                                                            </div>
                                                                            <span class="d-inline-block mt-1 text-danger">Maximum Limit 60 characters</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn" data-bs-dismiss="modal">Discard</button>
                                                            <button type="submit" class="btn btn-primary">Add</button>
                                                        </div>
                                                    </form>
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
    <script src="src/plugins/src/global/vendors.min.js"></script>
    <script src="src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="src/plugins/src/waves/waves.min.js"></script>
    <script src="layouts/vertical-light-menu/app.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="src/assets/js/apps/notes.js"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

   <script>
    $(document).ready(function() {
    $('.action-delete').click(function() {
        var noteId = $(this).data('id-note');

        var confirmation = confirm("Apakah Anda yakin ingin menghapus catatan ini?");

        if (confirmation) {
            deleteNote(noteId);
        }
    });
});

function deleteNote(noteId) {
    $.ajax({
        url: 'hapus-notes', 
        type: 'POST',
        data: { noteId: noteId }, 
        success: function(response) {
            console.log('Response:', response);
            var data = JSON.parse(response);
            if (data.status === "success") {
                alert(data.message);
                location.reload(); 
            } else {
                alert(data.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Terjadi kesalahan saat menghapus catatan:', error);
        }
    });
}
</script> 
</body>
</html>
