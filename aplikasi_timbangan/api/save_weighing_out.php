<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['items']) || empty($input['items'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

// Generate receipt ID
$receipt_id = 'OUT' . date('YmdHis') . rand(100, 999);

// Start transaction
$conn->begin_transaction();

try {
    // Prepare statement for inserting items
    $stmt = $conn->prepare("
        INSERT INTO weighing_out (
            receipt_id,
            product_id,
            weight,
            price,
            user_id,
            created_at
        ) VALUES (?, ?, ?, ?, ?, NOW())
    ");

    // Insert each item
    foreach ($input['items'] as $item) {
        $stmt->bind_param(
            'siddi',
            $receipt_id,
            $item['product_id'],
            $item['weight'],
            $item['price'],
            $_SESSION['user_id']
        );
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to insert item: ' . $stmt->error);
        }

        // Update latest price
        $update_price = $conn->prepare("
            INSERT INTO product_prices (product_id, price, created_at)
            VALUES (?, ?, NOW())
        ");
        $update_price->bind_param('id', $item['product_id'], $item['price']);
        $update_price->execute();
    }

    // Commit transaction
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'receipt_id' => $receipt_id,
        'message' => 'Data saved successfully'
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>