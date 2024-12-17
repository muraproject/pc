<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        getWeighingOut();
        break;
    case 'update':
        updateWeighingOut();
        break;
    case 'delete':
        deleteWeighingOut();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getWeighingOut() {
    global $conn;
    $id = $_GET['id'] ?? 0;
    
    $stmt = $conn->prepare("
        SELECT 
            wo.id,
            wo.category_id,
            wo.product_id,
            wo.weight,
            wo.price,
            c.name as category_name,
            p.name as product_name
        FROM weighing_out wo
        LEFT JOIN categories c ON wo.category_id = c.id
        LEFT JOIN products p ON wo.product_id = p.id
        WHERE wo.id = ?
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

function updateWeighingOut() {
    global $conn;
    
    $id = $_POST['id'] ?? 0;
    $category_id = $_POST['category_id'] ?? 0;
    $product_id = $_POST['product_id'] ?? 0;
    $weight = $_POST['weight'] ?? 0;
    $price = $_POST['price'] ?? 0;
    
    $stmt = $conn->prepare("
        UPDATE weighing_out 
        SET category_id = ?, 
            product_id = ?, 
            weight = ?,
            price = ?
        WHERE id = ?
    ");
    
    $stmt->bind_param("iiddi", $category_id, $product_id, $weight, $price, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
}

function deleteWeighingOut() {
    global $conn;
    
    $id = $_POST['id'] ?? 0;
    
    $stmt = $conn->prepare("DELETE FROM weighing_out WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
}
?>