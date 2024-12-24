<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

session_start();

// Log received data for debugging
error_log('Received wages data: ' . file_get_contents('php://input'));

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User tidak terautentikasi'
    ]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['items']) || empty($input['items'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Data tidak lengkap'
    ]);
    exit;
}

$conn->begin_transaction();

try {
    // First verify that the table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'wages_data'");
    if ($tableCheck->num_rows === 0) {
        throw new Exception('Table wages_data does not exist');
    }

    // Prepare insert statement
    $query = "INSERT INTO wages_data (user_id, category_id, product_id, weight, created_at) 
              VALUES (?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }

    foreach ($input['items'] as $item) {
        // Log item data
        error_log('Processing item: ' . json_encode($item));

        // Validate data
        if (!isset($item['user_id'], $item['category_id'], $item['product_id'], $item['weight'])) {
            throw new Exception('Missing required fields in item data');
        }

        // Bind parameters
        $result = $stmt->bind_param('iiid',
            $item['user_id'],
            $item['category_id'],
            $item['product_id'],
            $item['weight']
        );

        if ($result === false) {
            throw new Exception('Parameter binding failed: ' . $stmt->error);
        }

        // Execute statement
        if (!$stmt->execute()) {
            throw new Exception('Execute failed: ' . $stmt->error);
        }
    }

    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Data berhasil disimpan'
    ]);

} catch (Exception $e) {
    $conn->rollback();
    error_log('Error saving wages: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan data: ' . $e->getMessage()
    ]);
}