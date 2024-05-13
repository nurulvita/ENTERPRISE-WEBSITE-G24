<?php
include_once '../../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['noteId'])) { 
        $noteId = $_POST['noteId']; 

        $query = "DELETE FROM notes WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $noteId);

        // Eksekusi query
        if ($stmt->execute()) {
            echo json_encode(array("status" => "success", "message" => "Catatan berhasil dihapus.")); // Mengubah pesan berhasil
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal menghapus catatan.")); // Mengubah pesan error
        }

        $stmt->close();
    } else {
        echo json_encode(array("status" => "error", "message" => "Parameter noteId tidak ditemukan.")); // Mengubah pesan error
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Metode permintaan tidak valid.")); // Mengubah pesan error
}

$conn->close();
?>
