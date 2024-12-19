<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

$input = json_decode(file_get_contents('php://input'), true);
$receipt_id = $input['receipt_id'] ?? '';
$updates = $input['updates'] ?? [];

if (empty($receipt_id) || empty($updates)) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

$conn->begin_transaction();

try {
    // Get all weighing_in IDs for this receipt in order
    $stmt = $conn->prepare("
        SELECT id 
        FROM weighing_in 
        WHERE receipt_id = ? 
        ORDER BY id ASC
    ");
    $stmt->bind_param('s', $receipt_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row['id'];
    }

    // Update each row
    $updateStmt = $conn->prepare("
        UPDATE weighing_in 
        SET weight = ?
        WHERE id = ?
    ");

    foreach ($updates as $i => $update) {
        if (isset($ids[$i])) {
            $updateStmt->bind_param('di', $update['weight'], $ids[$i]);
            $updateStmt->execute();
        }
    }

    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>