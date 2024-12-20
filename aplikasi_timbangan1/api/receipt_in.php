<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'detail':
            getReceiptDetail();
            break;
        case 'delete':
            deleteReceipt();
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No action specified']);
}

function getReceiptDetail() {
    global $conn;
    
    $receipt_id = $_GET['receipt_id'] ?? '';
    
    if (empty($receipt_id)) {
        echo json_encode(['success' => false, 'message' => 'Receipt ID is required']);
        return;
    }

    // Get receipt header info
    $headerQuery = "
        SELECT 
            wi.created_at as date,
            s.name as supplier_name
        FROM weighing_in wi
        LEFT JOIN suppliers s ON wi.supplier_id = s.id
        WHERE wi.receipt_id = ?
        GROUP BY wi.receipt_id, s.name
    ";
    
    $stmt = $conn->prepare($headerQuery);
    $stmt->bind_param("s", $receipt_id);
    $stmt->execute();
    $header = $stmt->get_result()->fetch_assoc();

    if (!$header) {
        echo json_encode(['success' => false, 'message' => 'Receipt not found']);
        return;
    }

    // Get receipt items
    $itemsQuery = "
        SELECT 
            wi.id,
            wi.weight,
            c.name as category_name,
            p.name as product_name
        FROM weighing_in wi
        LEFT JOIN products p ON wi.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE wi.receipt_id = ?
        ORDER BY c.name, p.name
    ";
    
    $stmt = $conn->prepare($itemsQuery);
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

    $conn->begin_transaction();

    try {
        // Delete weighing_in records
        $stmt = $conn->prepare("DELETE FROM weighing_in WHERE receipt_id = ?");
        $stmt->bind_param("s", $receipt_id);
        $stmt->execute();

        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>