<?php

include_once('../../config/koneksi.php');

$username = isset($_POST['username']) ? $_POST['username'] : '';

$response = array('exists' => false);

$query = "SELECT COUNT(*) as count FROM pelanggan WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

$row = $result->fetch_assoc();
$count = $row['count'];

if ($count > 0) {
    $response['exists'] = true;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
