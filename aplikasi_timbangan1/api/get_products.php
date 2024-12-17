<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

try {
    // Validate input
    $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
    
    if (!$category_id) {
        echo json_encode([]);
        exit;
    }

    // Prepare and execute query
    $stmt = $conn->prepare("
        SELECT id, name 
        FROM products 
        WHERE category_id = ? 
        ORDER BY name
    ");
    
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all products
    $products = $result->fetch_all(MYSQLI_ASSOC);

    // Return array directly without wrapper
    echo json_encode($products);

} catch (Exception $e) {
    error_log("Error in get_products.php: " . $e->getMessage());
    echo json_encode([]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
}
?>