<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (isset($_GET['action']) && $_GET['action'] === 'detail') {
    getReceiptDetail();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getReceiptDetail() {
    global $conn;
    
    $receipt_id = $_GET['id'] ?? '';
    
    if (empty($receipt_id)) {
        echo json_encode(['success' => false, 'message' => 'Receipt ID is required']);
        return;
    }

    // Get receipt header info
    $header_query = "
        SELECT 
            MIN(wi.created_at) as date,
            s.name as supplier_name
        FROM weighing_in wi
        LEFT JOIN suppliers s ON wi.supplier_id = s.id
        WHERE wi.receipt_id = ?
        GROUP BY wi.receipt_id, s.name
    ";
    
    $stmt = $conn->prepare($header_query);
    $stmt->bind_param("s", $receipt_id);
    $stmt->execute();
    $header = $stmt->get_result()->fetch_assoc();

    if (!$header) {
        echo json_encode(['success' => false, 'message' => 'Receipt not found']);
        return;
    }

    // Get receipt items
    $items_query = "
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
    
    $stmt = $conn->prepare($items_query);
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
?>