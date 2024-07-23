<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];
    
    $db = new Database();
    $conn = $db->getConnection();
    
    $conn->begin_transaction();
    
    try {
        // Update status pesanan
        $updateSql = "UPDATE orders SET status = 'confirmed' WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("i", $orderId);
        $updateStmt->execute();
        
        // Masukkan ke tabel confirmed_orders
        $insertSql = "INSERT INTO confirmed_orders (order_id) VALUES (?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("i", $orderId);
        $insertStmt->execute();
        
        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}