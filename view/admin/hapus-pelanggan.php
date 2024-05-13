<?php
include '../../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['noteId'])) {
        $noteId = $_POST['noteId'];


        $query = "DELETE FROM pelanggan WHERE id_pelanggan = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $noteId);

        if ($stmt->execute()) {
            echo json_encode(array("status" => "success", "message" => "Pelanggan berhasil dihapus."));
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal menghapus pelanggan."));
        }

        $stmt->close();
    } else {
        echo json_encode(array("status" => "error", "message" => "Parameter pelanggan id tidak ditemukan."));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Metode permintaan tidak valid."));
}

$conn->close();
?>
