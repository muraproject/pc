<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        getWeighingIn();
        break;
    case 'update':
        updateWeighingIn();
        break;
    case 'delete':
        deleteWeighingIn();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getWeighingIn() {
    global $conn;
    $id = $_GET['id'] ?? 0;
    
    $stmt = $conn->prepare("
        SELECT 
            wi.id,
            wi.supplier_id,
            wi.category_id,
            wi.product_id,
            wi.weight,
            s.name as supplier_name,
            c.name as category_name,
            p.name as product_name
        FROM weighing_in wi
        LEFT JOIN suppliers s ON wi.supplier_id = s.id
        LEFT JOIN categories c ON wi.category_id = c.id
        LEFT JOIN products p ON wi.product_id = p.id
        WHERE wi.id = ?
    ");
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(['success' => false, 'message' => 'Data not found']);
    }
}

function updateWeighingIn() {
    global $conn;
    
    $id = $_POST['id'] ?? 0;
    $supplier_id = $_POST['supplier_id'] ?? 0;
    $category_id = $_POST['category_id'] ?? 0;
    $product_id = $_POST['product_id'] ?? 0;
    $weight = $_POST['weight'] ?? 0;
    
    $stmt = $conn->prepare("
        UPDATE weighing_in 
        SET supplier_id = ?, 
            category_id = ?, 
            product_id = ?, 
            weight = ?
        WHERE id = ?
    ");
    
    $stmt->bind_param("iiidi", $supplier_id, $category_id, $product_id, $weight, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
}

function deleteWeighingIn() {
    global $conn;
    
    $id = $_POST['id'] ?? 0;
    
    $stmt = $conn->prepare("DELETE FROM weighing_in WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
}
?>