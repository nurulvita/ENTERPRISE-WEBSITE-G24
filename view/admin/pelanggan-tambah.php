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

$errors = [];

$default_password = 'pelanggan123';

function generateUniqueKodeMember($conn) {
  $query = "SELECT kode_member FROM pelanggan ORDER BY kode_member DESC LIMIT 1";
  $result = $conn->query($query);
  if ($result->num_rows > 0) {
      $last_kode_member = $result->fetch_assoc()['kode_member'];
      $next_kode = (int)substr($last_kode_member, 3) + 1;
      $next_kode_member = 'G24' . sprintf('%05d', $next_kode);
      while (cekKodeMember($conn, $next_kode_member)) {
          $next_kode++;
          $next_kode_member = 'G24' . sprintf('%05d', $next_kode);
      }
      return $next_kode_member;
  } else {
      return 'G2400001';
  }
}

function cekKodeMember($conn, $kode_member) {
  $query = "SELECT * FROM pelanggan WHERE kode_member = '$kode_member'";
  $result = $conn->query($query);
  return $result->num_rows > 0;
}


function cekUsername($conn, $username) {
    $query = "SELECT * FROM pelanggan WHERE username = '$username'";
    $result = $conn->query($query);
    return $result->num_rows > 0;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : $default_password;
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $no_hp = isset($_POST['no_hp']) ? $_POST['no_hp'] : '';

    if (cekUsername($conn, $username)) {
        $errors['username'] = "Username sudah digunakan.";
    }

    if (empty($nama)) {
        $errors['nama'] = "Nama harus diisi.";
    }

    if (empty($username)) {
        $errors['username'] = "Username harus diisi.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors['username'] = "Username hanya boleh mengandung huruf, angka, atau underscore.";
    }

    if (empty($alamat)) {
        $errors['alamat'] = "Alamat harus diisi.";
    }

    if (empty($no_hp)) {
        $errors['no_hp'] = "Nomor Telepon harus diisi.";
    } elseif (!preg_match('/^\d{10,14}$/', $no_hp)) {
        $errors['no_hp'] = "Nomor Telepon tidak valid.";
    }

    $allowed_extensions = array('jpg', 'jpeg', 'png');

    if (!isset($_FILES['foto_profil']) || $_FILES['foto_profil']['error'] === UPLOAD_ERR_NO_FILE) {
        $foto_profil_name = 'profil.png';
    } else {
        $foto_profil_tmp = $_FILES['foto_profil']['tmp_name'];
        $foto_profil_name = $_FILES['foto_profil']['name'];
        $upload_directory = 'uploads/';
        $foto_profil_path = $upload_directory . $foto_profil_name;

        $file_info = pathinfo($foto_profil_name);
        $extension = strtolower($file_info['extension']);

        if (!in_array($extension, $allowed_extensions)) {
            $errors['foto_profil'] = "Ekstensi file gambar tidak valid. Harus berupa JPG, JPEG, atau PNG.";
        } elseif (!move_uploaded_file($foto_profil_tmp, $foto_profil_path)) {
            $errors['foto_profil'] = "Gagal mengunggah file.";
        }
    }


    if (empty($errors)) {
        $kode_member = generateUniqueKodeMember($conn);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO pelanggan (kode_member, nama, username, password, alamat, no_hp, foto_profil) 
                  VALUES ('$kode_member', '$nama', '$username', '$hashed_password', '$alamat', '$no_hp', '$foto_profil_name')";

        if ($conn->query($query) === TRUE) {
            echo '<script>alert("Pendaftaran member berhasil!");</script>';
            echo '<script>setTimeout(function(){ window.location.href = "pelanggan-list"; }, 1000);</script>';
        } else {
            echo '<div class="error-message">Error: ' . $query . "<br>" . $conn->error . '</div>';
        }
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
    <link href="src/assets/css/light/scrollspyNav.css" rel="stylesheet" type="text/css" />
    <link href="src/assets/css/dark/scrollspyNav.css" rel="stylesheet" type="text/css" />
    <style>

    /* CSS untuk pesan alert */
    .success-message {
      background-color: #d4edda; 
      color: #155724;
      padding: 10px;
      border: 1px solid #c3e6cb; 
      margin-bottom: 10px;
      border-radius: 5px;
  }

    .error-message {
        color: red;
    }

    .input-error {
        border-color: red !important;
    }


  </style>

</head>
<body class="layout-boxed" data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100">
    
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
            <div class="container">
                <div class="container">
                    <br>
                    <form method="post" class="form text-left" enctype="multipart/form-data">
                    <div class="row">
                        <div id="flLoginForm" class="col-lg-12 layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">                                
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Form Registrasi Pelanggan</h4>
                                        </div>                                                                        
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
                                    <form class="row g-3">
                                        <div class="col-12">
                                            <label for="inputEmail4" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" required>
                                            <div id="username-error" class="error-message"></div>
                                        </div>
                                        <br>
                                        <div class="col-12">
                                            <label for="inputAddress" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" required>
                                            <div id="nama-error" class="error-message"></div>
                                        </div>
                                        <br>
                                        <div class="col-12">
                                            <label for="inputnohp" class="form-label">No HP</label>
                                            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Masukkan Nomor HP" required>
                                            <div id="no_hp-error" class="error-message"></div>
                                        </div>
                                        <br>
                                        <div class="col-12">
                                            <label for="inputAddress2" class="form-label">Alamat</label>
                                            <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat" required>
                                            <div id="alamat-error" class="error-message"></div>
                                        </div>
                                        <br>
                                        <div class="col-12">
                                            <label for="inpufoto" class="form-label">Foto Profil (opsional)</label>
                                            <input type="file" class="form-control" id="foto_profil" name="foto_profil" onchange="validateImage()">
                                            <div id="foto_profil-error" class="error-message"></div>
                                        </div>
                                        <br>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
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
    <script src="src/plugins/src/highlight/highlight.pack.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="src/assets/js/scrollspyNav.js"></script>

    <script async defer src="https://buttons.github.io/buttons.js"></script>

  <script>
    // Validasi nama
    var namaInput = document.getElementById('nama');
    var nama = namaInput.value.trim();
    if (nama === '') {
        showError('nama', 'Nama harus diisi.');
    }

    // Validasi username
    var usernameInput = document.getElementById('username');
    var username = usernameInput.value.trim();
    if (username === '') {
        showError('username', 'Username harus diisi.');
    } else if (!/^[a-zA-Z0-9_]+$/.test(username)) {
        showError('username', 'Username hanya boleh mengandung huruf, angka, atau underscore.');
    }

    // Validasi alamat
    var alamatInput = document.getElementById('alamat');
    var alamat = alamatInput.value.trim();
    if (alamat === '') {
        showError('alamat', 'Alamat harus diisi.');
    }

    // Validasi nomor telepon
    var noHpInput = document.getElementById('no_hp');
    var noHp = noHpInput.value.trim();
    if (noHp === '') {
        showError('no_hp', 'Nomor Telepon harus diisi.');
    } else if (!/^\d{10,14}$/.test(noHp)) {
        showError('no_hp', 'Nomor Telepon tidak valid.');
    }
</script>
<script>
    function showError(inputId, errorMessage) {
        var errorDiv = document.getElementById(inputId + '-error');
        errorDiv.innerHTML = errorMessage;
        
        var inputElement = document.getElementById(inputId);
        inputElement.classList.add('input-error');
    }
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    function showErrorMessage(inputId, errorMessage) {
        var errorDiv = document.getElementById(inputId + '-error');
        if (errorMessage) {
            errorDiv.innerHTML = errorMessage;
            errorDiv.style.display = 'block';
        } else {
            errorDiv.innerHTML = '';
            errorDiv.style.display = 'none'; 
        }
    }

    function validateInput1() {
        // Validasi nomor telepon
        var noHpInput = document.getElementById('no_hp');
        var noHp = noHpInput.value.trim();
        var noHpError = '';
        if (noHp === '') {
            // noHpError = 'Nomor Telepon harus diisi.';
        } else if (!/^(?:\+62|08)\d{9,13}$/.test(noHp) || noHp.length > 13) {
            noHpError = 'Nomor Telepon harus diisi dengan +62 atau 08 dan terdiri dari 10-13 digit angka.';
        }
        showErrorMessage('no_hp', noHpError); 
    }


    function validateInput() {
    var UsnInput = document.getElementById('username');
    var Usn = UsnInput.value.trim();
    var UsnError = '';

    // Validasi regex
    if (!/^[a-zA-Z0-9_]+$/.test(Usn)) {
        UsnError = 'Username hanya boleh mengandung huruf, angka, atau garis bawah (_).';
    }

    // Validasi ketersediaan username
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'checkusername.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.exists) {
                UsnError = 'Username sudah digunakan.';
            }
            showErrorMessage('username', UsnError);
        } else {
            console.error('Permintaan gagal. Status: ' + xhr.status);
        }
    };
    xhr.send('username=' + encodeURIComponent(Usn));
    showErrorMessage('username', UsnError);
}

document.getElementById('username').addEventListener('input', validateInput);
validateInput();

document.getElementById('no_hp').addEventListener('input', validateInput1);
validateInput1();


});
</script>

<script>
    function validateImage() {
        var fileInput = document.getElementById('foto_profil');
        var filePath = fileInput.value;
        var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
        if (!allowedExtensions.exec(filePath)) {
            showError('foto_profil', 'Ekstensi file gambar tidak valid. Harus berupa JPG, JPEG, atau PNG.');
            fileInput.value = '';
            return false;
        } else {
            showError('foto_profil', '');
        }
    }
</script>


</body>
</html>