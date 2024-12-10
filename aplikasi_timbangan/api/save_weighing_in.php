<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['supplier_id']) || !isset($input['items']) || empty($input['items'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Data tidak lengkap'
    ]);
    exit;
}

// Validate supplier exists
$stmt = $conn->prepare("SELECT id FROM suppliers WHERE id = ?");
$stmt->bind_param("i", $input['supplier_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Supplier tidak ditemukan'
    ]);
    exit;
}

// Generate receipt ID
$receipt_id = 'IN' . date('YmdHis') . rand(100, 999);

// Start transaction
$conn->begin_transaction();

try {
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
        // Validate product exists
        $checkProduct = $conn->prepare("SELECT id FROM products WHERE id = ?");
        $checkProduct->bind_param("i", $item['product_id']);
        $checkProduct->execute();
        if ($checkProduct->get_result()->num_rows === 0) {
            throw new Exception('Product dengan ID ' . $item['product_id'] . ' tidak ditemukan');
        }

        $stmt->bind_param(
            'siidi',
            $receipt_id,
            $input['supplier_id'],
            $item['product_id'],
            $item['weight'],
            $_SESSION['user_id']
        );
        
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
    }

    // Log activity
    logActivity($_SESSION['user_id'], 'CREATE_WEIGHING_IN', [
        'receipt_id' => $receipt_id,
        'supplier_id' => $input['supplier_id'],
        'total_items' => count($input['items'])
    ]);

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

// Close all statements
if (isset($stmt)) $stmt->close();
if (isset($checkProduct)) $checkProduct->close();
$conn->close();
?>