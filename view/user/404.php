<!DOCTYPE html>
<html lang="en">

<head>

    <?php include 'layouts/head.php' ?>
</head>

<body>

    <div class="page-loader"></div>

    <div class="wrapper">

        <header>

            <!-- ======================== Navigation ======================== -->

            <?php include 'layouts/header.php' ?>
    
        </header>

        <!-- ========================  404 ======================== -->

        <section class="not-found">
            <div class="container">
                <h1 class="title" data-title="404">404</h1>
                <div class="h4 subtitle">Sorry! Page not found.</div>
                <p>The requested URL was not found on this server. That’s all we know.</p>
                <p>Click <a href="#">here</a> to get to the front page? </p>
            </div>
        </section>



        <!-- ========================  Berlangganan ======================== -->

        <section class="subscribe" style="margin-top: 15vh;">
            <div class="container">
                <div class="box">
                    <h2 class="title">Berlangganan</h2>
                    <div class="text">
                        <p>Berlangganan untuk diskon dan penawaran khusus.</p>
                    </div>
                    <div class="form-group">
                        <input type="email" value="" placeholder="Masukan Email Anda" class="form-control" id="emailInput" required />
                        <button class="btn btn-sm btn-main" onclick="subscribe()">Kirim</button>
                    </div>
                </div>
                <div id="notification"></div>
            </div>
        </section>
        
        <!-- ================== Footer  ================== -->

        <footer>
            <div class="container">

                <!--footer links-->
                <div class="footer-links">
                    <div class="row">
                        <div class="col footer-right">
                            &nbsp; <a href="produk.php">Produk</a> | &nbsp; <a href="kontak.php">Kontak</a>
                        </div>
                    </div>
                </div>

                <!--footer social-->

                <div class="footer-social">
                    <div class="row">
                        <div class="col-sm-12 text-center hidden">
                            <a href="" class="footer-logo"><img src="images/logo/G-24.png" alt="Alternate Text" /></a>
                        </div>

                        <div class="col-sm-12 copyright">
                            <small>Copyright &copy; 2024 &nbsp; | &nbsp; Depo Air Minum G-24</a></small>
                        </div>
                        <div class="col-sm-12 text-center">
                            <img src="images/logo/G-24.png" alt="logo" style="height: 10vh;" />
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div> <!--/wrapper-->

    
    <script>
        function subscribe() {
            var emailInput = document.getElementById("emailInput");
            var email = emailInput.value.trim();
    
            if (email === "") {
                displayNotification("Email harus diisi!");
                return;
            } else if (!isValidEmail(email)) {
                displayNotification("Masukkan alamat email yang valid!");
                return;
            }
            displayNotification("Terima kasih! Email Anda telah berhasil berlangganan.");
        }
    
        function displayNotification(message) {
            var notification = document.getElementById("notification");
            notification.innerHTML = message;
            notification.style.display = "block";
        }
    
        function isValidEmail(email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function submitForm() {
            var nama = document.getElementById("nama").value.trim();
            var email = document.getElementById("email").value.trim();
            var subjek = document.getElementById("subjek").value.trim();
            var ulasan = document.getElementById("ulasan").value.trim();

            if (nama === "" || email === "" || subjek === "" || ulasan === "") {
                displayNotification("Harap lengkapi semua kolom!");
                return false;
            }

            // Kirim ulasan ke server (di sini Anda dapat menambahkan kode untuk mengirim ulasan ke server)
            displayNotification("Ulasan berhasil dikirim!");
            return true;
        }
    </script>

    <!--JS files-->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/jquery.bootstrap.js"></script>
    <script src="js/jquery.magnific-popup.js"></script>
    <script src="js/jquery.owl.carousel.js"></script>
    <script src="js/main.js"></script>

</body>

</html>