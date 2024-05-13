<?php

require_once "config/koneksi.php";

session_start();

$errors = [];

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

if (cekUsername($conn, $username)) {
  $errors['username'] = "Username sudah digunakan.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $no_hp = isset($_POST['no_hp']) ? $_POST['no_hp'] : '';

    if (empty($nama)) {
        $errors['nama'] = "Nama harus diisi.";
    }

    if (empty($username)) {
        $errors['username'] = "Username harus diisi.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors['username'] = "Username hanya boleh mengandung huruf, angka, atau underscore.";
    }

    if (empty($password)) {
        $errors['password'] = "Password harus diisi.";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password harus terdiri dari minimal 8 karakter.";
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^a-zA-Z0-9]/', $password)) {
        $errors['password'] = "Password harus mengandung setidaknya satu huruf besar, satu huruf kecil, satu angka, dan satu karakter khusus.";
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
            echo '<script>alert("Registrasi Berhasil! Anda telah terdaftar sebagai member!");</script>';
            echo '<script>setTimeout(function(){ window.location.href = "login"; }, 1000);</script>';
        } else {
            echo '<div class="error-message">Error: ' . $query . "<br>" . $conn->error . '</div>';
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="favicon.ico">
  <link rel="icon" type="image/png" href="favicon.ico">
  <title>
    G -24
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,500&amp;subset=latin-ext" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700&amp;subset=latin-ext" rel="stylesheet">

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />

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

<body class="g-sidenav-show  bg-gray-100">

  <section class="min-vh-200 mb-8">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('assets/images/bg/curvedbg.png');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 text-center mx-auto">
            <h1 class="text-white mb-2 mt-5">Selamat Datang di G -24!</h1>
            <p class="text-lead text-white">Daftarkan diri anda dan kumpulkan kupon untuk mendapatkan keuntungan!</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
          <div class="card z-index-0">
            <div class="card-header text-center pt-4">
                <h3 class="font-weight-bolder text-dark">Daftar Akun</h3>
            </div>

            <div class="card-body">
            <form method="post" class="form text-left" enctype="multipart/form-data">

            <div class="mb-3">
                      <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" aria-label="Nama" aria-describedby="nama-addon" required>
                      <div id="nama-error" class="error-message"></div>
                  </div>


                  <div class="mb-3">
                      <input type="text" class="form-control" id="username" name="username" placeholder="Username" aria-label="Username" aria-describedby="username-addon" required>
                      <div id="username-error" class="error-message"></div>
                  </div>


                  <div class="mb-3">
                      <input type="password" class="form-control" id="password" name="password" placeholder="Password" aria-label="Password" aria-describedby="password-addon" required>
                      <div id="password-error" class="error-message"></div>
                  </div>


                  <div class="mb-3">
                      <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" aria-label="Alamat" required>
                      <div id="alamat-error" class="error-message"></div>
                  </div>


                  <div class="mb-3">
                      <input type="tel" class="form-control" id="no_hp" name="no_hp" placeholder="Nomor Telepon" aria-label="Phone Number" required>
                      <div id="no_hp-error" class="error-message"></div>
                  </div>


                  <div class="mb-3">
                    <label for="foto_profil" class="form-label">(Opsional)</label>
                    <input class="form-control" type="file" id="foto_profil" name="foto_profil" value="profil.png" onchange="validateImage()">
                    <div id="foto_profil-error" class="error-message"></div>
                </div>


                    <div class="form-check form-check-info text-left">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
                    <label class="form-check-label" for="flexCheckDefault">
                        Saya Setuju dengan <a href="javascript:;" class="text-dark font-weight-bolder">Syarat dan Ketentuan</a>
                    </label>
                    </div>
                    <div class="text-center">
                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Daftar</button>
                    </div>
                    <p class="text-sm mt-3 mb-0">Sudah Punya Akun? <a href="login" class="text-dark font-weight-bolder">Login</a></p>
                </form>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mb-4 mx-auto text-center">
          <a href="beranda" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
            Beranda
          </a>
          <a href="tentang" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
            Tentang Kami
          </a>
          <a href="layanan" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
            Layanan
          </a>
          <a href="produk" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
            Produk
          </a>
          <a href="kontak" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
            Kontak
          </a>
        </div>
      </div>

      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p class="mb-0 text-secondary">
            Copyright © <script>
              document.write(new Date().getFullYear())
            </script> Depo Air Minum G -24.
          </p>
        </div>
      </div>
    </div>
  </footer>


  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>

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

    // Validasi password
    var passwordInput = document.getElementById('password');
    var password = passwordInput.value;
    if (password === '') {
        showError('password', 'Password harus diisi.');
    } else if (password.length < 8) {
        showError('password', 'Password harus terdiri dari minimal 8 karakter.');
    } else if (!/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password) || !/[^a-zA-Z0-9]/.test(password)) {
        showError('password', 'Password harus mengandung setidaknya satu huruf besar, satu huruf kecil, satu angka, dan satu karakter khusus.');
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

    function validateInput() {
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

        var passwordInput = document.getElementById('password');
        var password = passwordInput.value;
        var passwordError = '';
        if (password === '') {
            // passwordError = 'Password harus diisi.';
        } else if (password.length < 8) {
            passwordError = 'Password harus terdiri dari minimal 8 karakter.';
        } else if (!/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password) || !/[^a-zA-Z0-9]/.test(password)) {
            passwordError = 'Password harus mengandung setidaknya satu huruf besar, satu huruf kecil, satu angka, dan satu karakter khusus.';
        }
        showErrorMessage('password', passwordError);
    }

    document.getElementById('no_hp').addEventListener('input', validateInput);
    validateInput();

    document.getElementById('password').addEventListener('input', validateInput);
    validateInput();
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
