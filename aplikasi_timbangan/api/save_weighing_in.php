<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Start session to get user_id
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User tidak terautentikasi'
    ]);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['supplier_id']) || !isset($input['items']) || empty($input['items'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Data tidak lengkap'
    ]);
    exit;
}

// Generate receipt ID
$receipt_id = 'IN' . date('YmdHis') . rand(100, 999);

// Start transaction
$conn->begin_transaction();

try {
    // Get user_id from session
    $user_id = $_SESSION['user_id'];

    // Prepare statement for inserting items
    $stmt = $conn->prepare("
        INSERT INTO weighing_in (
            receipt_id,
            supplier_id,
            product_id,
            weight,
            user_id,
            created_at
        ) VALUES (?, ?, ?, ?, ?, NOW())
    ");

    // Insert each item
    foreach ($input['items'] as $item) {
        $stmt->bind_param(
            'siidi',
            $receipt_id,
            $input['supplier_id'],
            $item['product_id'],
            $item['weight'],
            $user_id
        );
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to insert item: ' . $stmt->error);
        }
    }

    // Commit transaction
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'receipt_id' => $receipt_id,
        'message' => 'Data berhasil disimpan'
    ]);

} catch (Exception $e) {
    // Rollback transaction
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan data: ' . $e->getMessage()
    ]);
}
?>