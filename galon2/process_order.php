<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

header('Content-Type: application/json');

$json = file_get_contents('php://input');
$encryptedData = json_decode($json, true);

$db = new Database();
$conn = $db->getConnection();

// Masukkan data terenkripsi ke database
$sql = "INSERT INTO orders (name, whatsapp, address, longitude, latitude, refill_quantity, original_quantity, total)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", 
    $encryptedData['name'], 
    $encryptedData['whatsapp'], 
    $encryptedData['address'], 
    $encryptedData['longitude'], 
    $encryptedData['latitude'], 
    $encryptedData['refillQuantity'], 
    $encryptedData['originalQuantity'], 
    $encryptedData['total']
);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();