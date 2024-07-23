<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/database.php';

header('Content-Type: application/json');

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['order_type'])) {
    $orderId = $_POST['order_id'];
    $orderType = $_POST['order_type'];
    
    $db = new Database();
    $conn = $db->getConnection();
    
    $conn->begin_transaction();
    
    try {
        // Jika pesanan sudah dikonfirmasi, hapus dulu dari tabel confirmed_orders
        if ($orderType === 'confirmed') {
            $deleteConfirmedSql = "DELETE FROM confirmed_orders WHERE order_id = ?";
            $deleteConfirmedStmt = $conn->prepare($deleteConfirmedSql);
            $deleteConfirmedStmt->bind_param("i", $orderId);
            $deleteConfirmedStmt->execute();
        }
        
        // Kemudian hapus dari tabel orders
        $deleteSql = "DELETE FROM orders WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("i", $orderId);
        $deleteStmt->execute();
        
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