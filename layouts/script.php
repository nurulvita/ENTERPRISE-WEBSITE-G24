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
        
    </script>
    <!--JS files-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery-ui.js"></script>
    <script src="assets/js/jquery.bootstrap.js"></script>
    <script src="assets/js/jquery.magnific-popup.js"></script>
    <script src="assets/js/jquery.owl.carousel.js"></script>
    <script src="assets/js/main.js"></script>