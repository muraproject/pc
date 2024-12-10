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
            MIN(wi.created_at) as date,
            s.name as supplier_name
        FROM weighing_in wi
        LEFT JOIN suppliers s ON wi.supplier_id = s.id
        WHERE wi.receipt_id = ?
        GROUP BY wi.receipt_id, s.name
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
            wi.id,
            wi.weight,
            c.name as category_name,
            p.name as product_name
        FROM weighing_in wi
        LEFT JOIN categories c ON wi.category_id = c.id
        LEFT JOIN products p ON wi.product_id = p.id
        WHERE wi.receipt_id = ?
        ORDER BY c.name, p.name
    ");
    
    $stmt->bind_param("s", $receipt_id);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Calculate total weight
    $total_weight = array_sum(array_column($items, 'weight'));

    echo json_encode([
        'success' => true,
        'receipt_id' => $receipt_id,
        'date' => date('d/m/Y H:i', strtotime($header['date'])),
        'supplier_name' => $header['supplier_name'],
        'items' => $items,
        'total_weight' => $total_weight
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
        $stmt = $conn->prepare("DELETE FROM weighing_in WHERE receipt_id = ?");
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