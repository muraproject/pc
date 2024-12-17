<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User tidak terautentikasi'
    ]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['buyer_id']) || !isset($input['items']) || empty($input['items'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Data tidak lengkap'
    ]);
    exit;
}

$receipt_id = 'OUT' . date('YmdHis') . rand(100, 999);
$conn->begin_transaction();

try {
    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("
        INSERT INTO weighing_out (
            receipt_id,
            buyer_id,
            product_id,
            weight,
            price,
            user_id,
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    foreach ($input['items'] as $item) {
        $stmt->bind_param(
            'siiddi',
            $receipt_id,
            $input['buyer_id'],
            $item['product_id'],
            $item['weight'],
            $item['price'],
            $user_id
        );
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to insert item: ' . $stmt->error);
        }
    }

    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'receipt_id' => $receipt_id,
        'message' => 'Data berhasil disimpan'
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan data: ' . $e->getMessage()
    ]);
}
?>