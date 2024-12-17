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
            MIN(wo.created_at) as date,
            b.name as buyer_name
        FROM weighing_out wo
        LEFT JOIN buyers b ON wo.buyer_id = b.id
        WHERE wo.receipt_id = ?
        GROUP BY wo.receipt_id, b.name
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
            wo.id,
            wo.weight,
            wo.price,
            c.name as category_name,
            p.name as product_name
        FROM weighing_out wo
        LEFT JOIN products p ON wo.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE wo.receipt_id = ?
        ORDER BY c.name, p.name
    ";
    
    $stmt = $conn->prepare($items_query);
    $stmt->bind_param("s", $receipt_id);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Calculate total weight and total amount
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
        'buyer_name' => $header['buyer_name'],
        'items' => $items,
        'total_weight' => $total_weight,
        'total_amount' => $total_amount
    ]);
}
?>