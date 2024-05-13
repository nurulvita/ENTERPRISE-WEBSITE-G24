<?php
require_once "../../config/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (
        isset($_POST["nama_produk"]) && isset($_POST["harga"]) &&
        isset($_POST["kategori"]) && isset($_POST["unit"]) && isset($_FILES["foto_produk"]) &&
        isset($_POST["stok"])
    ) {
        $namaProduk = $_POST["nama_produk"];
        $hargaProduk = $_POST["harga"];
        $kategoriProduk = $_POST["kategori"];
        $unitProduk = $_POST["unit"];
        $stokProduk = ($_POST["stok"] == "tersedia") ? 1 : 0;

        if (!isset($_FILES['foto_produk']) || $_FILES['foto_produk']['error'] === UPLOAD_ERR_NO_FILE) {
            echo "<script>alert('Mohon unggah foto produk.'); window.location='produk-tambah';</script>";
            exit();
        }

        if (!is_numeric($hargaProduk) || !is_numeric($stokProduk)) {
            echo "<script>alert('Harga dan stok harus berupa angka.'); window.location='produk-tambah';</script>";
            exit();
        }

        $file_name = $_FILES["foto_produk"]["name"];
        $file_tmp = $_FILES["foto_produk"]["tmp_name"];
        $file_info = pathinfo($file_name);
        $file_ext = strtolower($file_info["extension"]);

        $allowed_extensions = array('jpg', 'jpeg', 'png');
        if (!in_array($file_ext, $allowed_extensions)) {
            echo "<script>alert('Ekstensi file foto produk tidak valid. Hanya file JPG, JPEG, dan PNG yang diperbolehkan.'); window.location='produk-tambah';</script>";
            exit();
        }

        $foto_produk = "produk_" . uniqid() . "." . $file_ext;
        move_uploaded_file($file_tmp, "../../fotoproduk/" . $foto_produk);

        $sql = "INSERT INTO produk (nama_produk, harga, kategori, unit, stok, gambar_produk) 
                VALUES ('$namaProduk', $hargaProduk, '$kategoriProduk', '$unitProduk', $stokProduk, '$foto_produk')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Produk berhasil ditambahkan.'); window.location='produk-list';</script>";
        } else {
            echo "<script>alert('Produk gagal ditambahkan: " . $conn->error . "'); window.location='produk-tambah';</script>";
        }
    } else {
        echo "<script>alert('Data yang diperlukan tidak lengkap.'); window.location='produk-tambah';</script>";
    }
} else {
    echo "<script>alert('Metode permintaan tidak valid.'); window.location='produk-tambah';</script>";
}
?>
