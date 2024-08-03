<?php
include('../includes/db.php');

header('Content-Type: application/json');

$query = "SELECT status FROM motor_status ORDER BY id DESC LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo json_encode(['status' => $data['status']]);
} else {
    echo json_encode(['error' => 'Status tidak ditemukan']);
}
?>