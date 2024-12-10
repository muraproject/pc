<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'detail':
        getReceiptDetail();
        break;
    case 'delete':
        deleteReceipt();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getReceiptDetail() {
    global $conn;
    $receipt_id = $_GET['receipt_id'] ?? '';
    
    if (empty($receipt_id)) {
        echo json_encode(['success' => false, 'message' => 'Receipt ID is required']);
        return;
    }

    // Get receipt header info
    $stmt = $conn->prepare("
        SELECT 
            MIN(wo.created_at) as date,
            u.name as user_name
        FROM weighing_out wo
        LEFT JOIN users u ON wo.user_id = u.id
        WHERE wo.receipt_id = ?
        GROUP BY wo.receipt_id, u.name
    ");
    
    $stmt->bind_param("s", $receipt_id);
    $stmt->execute();
    $header = $stmt->get_result()->fetch_assoc();

    if (!$header) {
        echo json_encode(['success' => false, 'message' => 'Receipt not found']);
        return;
    }

    // Get receipt items
    $stmt = $conn->prepare("
        SELECT 
            wo.id,
            wo.weight,
            wo.price,
            c.name as category_name,
            p.name as product_name
        FROM weighing_out wo
        LEFT JOIN categories c ON wo.category_id = c.id
        LEFT JOIN products p ON wo.product_id = p.id
        WHERE wo.receipt_id = ?
        ORDER BY c.name, p.name
    ");
    
    $stmt->bind_param("s", $receipt_id);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Calculate total weight and amount
    $total_weight = 0;
    $total_amount = 0;
    foreach ($items as $item) {
        $total_weight += $item['weight'];
        $total_amount += $item['weight'] * $item['price'];
    }

    echo json_encode([
        'success' => true,
        'receipt_id' => $receipt_id,
        'date' => date('d/m/Y H:i', strtotime($header['date'])),
        'user_name' => $header['user_name'],
        'items' => $items,
        'total_weight' => $total_weight,
        'total_amount' => $total_amount
    ]);
}

function deleteReceipt() {
    global $conn;
    $receipt_id = $_POST['receipt_id'] ?? '';
    
    if (empty($receipt_id)) {
        echo json_encode(['success' => false, 'message' => 'Receipt ID is required']);
        return;
    }

    // Check if user has permission
    if ($_SESSION['role'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Permission denied']);
        return;
    }

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("DELETE FROM weighing_out WHERE receipt_id = ?");
        $stmt->bind_param("s", $receipt_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $conn->commit();
            echo json_encode(['success' => true]);
        } else {
            throw new Exception('No records deleted');
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>