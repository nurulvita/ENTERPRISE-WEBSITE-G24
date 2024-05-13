<?php
include '../../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['productId'])) {
        $productId = $_POST['productId'];


        $query = "DELETE FROM produk WHERE id_produk = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $productId);

        // Eksekusi query
        if ($stmt->execute()) {
            echo json_encode(array("status" => "success", "message" => "Produk berhasil dihapus."));
            header("produk-list");
            exit();
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal menghapus produk."));
        }

        $stmt->close();
    } else {
        echo json_encode(array("status" => "error", "message" => "Parameter productId tidak ditemukan."));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Metode permintaan tidak valid."));
}

$conn->close();
?>
