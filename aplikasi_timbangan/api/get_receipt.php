<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

$receipt_id = $_GET['id'] ?? '';

if (empty($receipt_id)) {
    echo json_encode(['success' => false, 'message' => 'Receipt ID is required']);
    exit;
}

// Get receipt details
$query = "
    SELECT 
        wi.created_at as date,
        wi.weight,
        s.name as supplier_name,
        p.name as product_name
    FROM weighing_in wi
    LEFT JOIN suppliers s ON wi.supplier_id = s.id
    LEFT JOIN products p ON wi.product_id = p.id
    WHERE wi.receipt_id = ?
    ORDER BY wi.created_at
";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $receipt_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Receipt not found']);
    exit;
}

$items = [];
$first_row = true;
$receipt_date = '';
$supplier_name = '';

while ($row = $result->fetch_assoc()) {
    if ($first_row) {
        $receipt_date = $row['date'];
        $supplier_name = $row['supplier_name'];
        $first_row = false;
    }
    
    $items[] = [
        'product_name' => $row['product_name'],
        'weight' => $row['weight']
    ];
}

echo json_encode([
    'success' => true,
    'receipt_id' => $receipt_id,
    'date' => date('d/m/Y H:i', strtotime($receipt_date)),
    'supplier_name' => $supplier_name,
    'items' => $items
]);
?>