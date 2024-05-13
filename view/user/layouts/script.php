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

        function displayNotification(message) {
            var notification = document.getElementById("notification");
            notification.innerHTML = message;
            notification.style.display = "block";
        }
    
        function isValidEmail(email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        function tambahJumlah(id) {
            var jumlahSpan = document.getElementById(id);
            var jumlah = parseInt(jumlahSpan.innerText);
            jumlah++;
            jumlahSpan.innerText = jumlah;
        }
        
        function kurangiJumlah(id) {
            var jumlahSpan = document.getElementById(id);
            var jumlah = parseInt(jumlahSpan.innerText);
            if (jumlah > 1) {
                jumlah--;
                jumlahSpan.innerText = jumlah;
            }
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

    function tambahkanKeKeranjang(idProduk) {
        // Kirim permintaan AJAX
        $.ajax({
            url: 'tambahkeranjang', 
            method: 'POST',
            data: { id_produk: idProduk },
            success: function(response) {
                // Ganti notifikasi dengan pesan yang diinginkan
                alert("Produk berhasil ditambahkan ke keranjang.");

                // Lakukan reload halaman setelah menambahkan produk ke keranjang
                location.reload();
            }
        });

    function confirmCheckout() {
            if (confirm('Apakah Anda yakin ingin melanjutkan ke proses checkout?')) {
                // Jika pengguna mengklik OK, redirect ke halaman checkout
                window.location.href = 'checkout';
            } else {
                // Jika pengguna mengklik Cancel, tidak melakukan apa-apa
                return false;
            }
        }

    }


        
    </script>
    <!--JS files-->
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/jquery-ui.js"></script>
    <script src="../../assets/js/jquery.bootstrap.js"></script>
    <script src="../../assets/js/jquery.magnific-popup.js"></script>
    <script src="../../assets/js/jquery.owl.carousel.js"></script>
    <script src="../../assets/js/main.js"></script>