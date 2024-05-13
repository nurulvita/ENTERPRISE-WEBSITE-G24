<?php
include_once('../../config/koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // Prevent SQL injection
    $title = mysqli_real_escape_string($conn, $title);
    $description = mysqli_real_escape_string($conn, $description);
    
    $query = "INSERT INTO catatan (judul, deskripsi) VALUES ('$title', '$description')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Catatan berhasil ditambahkan.'); window.location='pendukung-notes';</script>"; 
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
    
    mysqli_close($conn);
}
?>
