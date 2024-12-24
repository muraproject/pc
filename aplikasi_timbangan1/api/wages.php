<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Permission denied']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        getWageData();
        break;
    case 'update':
        updateWageData();
        break;
    case 'delete':
        deleteWageData();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getWageData() {
    global $conn;
    $id = $_GET['id'] ?? 0;
    
    $stmt = $conn->prepare("
        SELECT 
            w.id,
            w.user_id,
            w.category_id,
            w.product_id,
            w.shift,
            w.weight,
            w.notes,
            u.name as user_name,
            c.name as category_name,
            p.name as product_name
        FROM wages_data w
        LEFT JOIN users u ON w.user_id = u.id
        LEFT JOIN categories c ON w.category_id = c.id
        LEFT JOIN products p ON w.product_id = p.id
        WHERE w.id = ?
    ");
    
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Database error']);
        return;
    }
    
    $stmt->bind_param("i", $id);
    
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Database error']);
        return;
    }
    
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['success' => true] + $row);
    } else {
        echo json_encode(['success' => false, 'message' => 'Data not found']);
    }
}

function updateWageData() {
    global $conn;
    
    // Get POST data
    $id = $_POST['id'] ?? 0;
    $user_id = $_POST['user_id'] ?? 0;
    $category_id = $_POST['category_id'] ?? 0;
    $product_id = $_POST['product_id'] ?? 0;
    $shift = $_POST['shift'] ?? '';
    $weight = $_POST['weight'] ?? 0;
    $notes = $_POST['notes'] ?? '';
    
    // Validate required fields
    if (!$id || !$user_id || !$category_id || !$product_id || !$shift || !$weight) {
        echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi kecuali keterangan']);
        return;
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update wages_data record
        $stmt = $conn->prepare("
            UPDATE wages_data 
            SET user_id = ?,
                category_id = ?,
                product_id = ?,
                shift = ?,
                weight = ?,
                notes = ?
            WHERE id = ?
        ");

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("iiisdsi", 
            $user_id, 
            $category_id, 
            $product_id, 
            $shift, 
            $weight, 
            $notes,
            $id
        );

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        if ($stmt->affected_rows === 0) {
            throw new Exception("No data was updated");
        }

        $conn->commit();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error updating wage data: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Gagal mengupdate data: ' . $e->getMessage()
        ]);
    }
}

function deleteWageData() {
    global $conn;
    
    $id = $_POST['id'] ?? 0;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
        return;
    }
    
    $conn->begin_transaction();

    try {
        // First check if record exists
        $check = $conn->prepare("SELECT id FROM wages_data WHERE id = ?");
        $check->bind_param("i", $id);
        $check->execute();
        
        if ($check->get_result()->num_rows === 0) {
            throw new Exception("Data tidak ditemukan");
        }

        // Delete record
        $stmt = $conn->prepare("DELETE FROM wages_data WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $id);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $conn->commit();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error deleting wage data: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menghapus data: ' . $e->getMessage()
        ]);
    }
}

// Helper function to validate numeric input
function validateNumeric($value, $field_name) {
    if (!is_numeric($value) || $value <= 0) {
        throw new Exception("$field_name harus berupa angka positif");
    }
    return true;
}

// Helper function to validate string input
function validateString($value, $field_name, $required = true) {
    if ($required && empty(trim($value))) {
        throw new Exception("$field_name tidak boleh kosong");
    }
    return true;
}
?>