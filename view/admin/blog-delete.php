<?php
include '../../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['blogId'])) {
        $blogId = $_POST['blogId'];


        $query = "DELETE FROM blog_post WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $blogId);

        if ($stmt->execute()) {
            echo json_encode(array("status" => "success", "message" => "Blog berhasil dihapus."));
            header("Location: blog-list");
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
