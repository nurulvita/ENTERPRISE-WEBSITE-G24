<?php
include '../../config/koneksi.php';

$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

$query = "SELECT * FROM produk WHERE stok = 'tersedia'";
if ($kategori && $kategori != 'Semua') {
    $query .= " AND kategori = '$kategori'";
}

$result = mysqli_query($conn, $query);

$html = '';

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= '<div class="col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card style-6">
                        <img src="../../fotoproduk/'.$row['gambar_produk'].'" class="card-img-top" alt="...">
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <b>'.$row['nama_produk'].'</b>
                                </div>
                                <div class="col-12 mb-2">
                                    Stok: '.$row['stok'].'
                                </div>
                                <div class="col-12 text-end">
                                    <p class="text-success mb-0">Rp '.number_format($row['harga'], 0, ',', '.').'</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
    }
} else {
    $html .= '<p>Tidak ada produk tersedia.</p>';
}

echo $html;

mysqli_close($conn);
?>
