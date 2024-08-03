<?php
include('../includes/db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['status'])) {
    $status = $_GET['status'] == 'on' ? 'on' : 'off';

    $stmt = $conn->prepare("INSERT INTO motor_status (status) VALUES (?)");
    $stmt->bind_param("s", $status);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Gagal memperbarui status']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Permintaan tidak valid']);
}
?>