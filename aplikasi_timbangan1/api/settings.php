<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if user is admin
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Permission denied']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    // Category actions
    case 'create':
        createCategory();
        break;
    case 'update':
        updateCategory();
        break;
    case 'delete_category':
        deleteCategory();
        break;
        
    // Product actions
    case 'create_product':
        createProduct();
        break;
    case 'update_product':
        updateProduct();
        break;
    case 'delete_product':
        deleteProduct();
        break;
        
    // Supplier actions
    case 'create_supplier':
        createSupplier();
        break;
    case 'update_supplier':
        updateSupplier();
        break;
    case 'delete_supplier':
        deleteSupplier();
        break;

    case 'create_buyer':
        createBuyer();
        break;
    case 'update_buyer':
        updateBuyer(); 
        break;
    case 'delete_buyer':
        deleteBuyer();
        break;
        
    // User actions
    case 'create_user':
        createUser();
        break;
    case 'update_user':
        updateUser();
        break;
    case 'delete_user':
        deleteUser();
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

// Category functions
function createCategory() {
    global $conn;
    $name = $_POST['name'] ?? '';
    
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Name is required']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function updateCategory() {
    global $conn;
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    
    if (empty($id) || empty($name)) {
        echo json_encode(['success' => false, 'message' => 'ID and name are required']);
        return;
    }

    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function deleteCategory() {
    global $conn;
    $id = $_POST['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID is required']);
        return;
    }

    // Check if category is being used
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    if ($result['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Category is in use']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

// Product functions
function createProduct() {
    global $conn;
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    
    if (empty($name) || empty($category_id)) {
        echo json_encode(['success' => false, 'message' => 'Name and category are required']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO products (name, category_id) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $category_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function updateProduct() {
    global $conn;
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    
    if (empty($id) || empty($name) || empty($category_id)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }

    $stmt = $conn->prepare("UPDATE products SET name = ?, category_id = ? WHERE id = ?");
    $stmt->bind_param("sii", $name, $category_id, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function deleteProduct() {
    global $conn;
    $id = $_POST['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID is required']);
        return;
    }

    // Check if product is being used
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM weighing_in WHERE product_id = ? UNION ALL SELECT COUNT(*) FROM weighing_out WHERE product_id = ?");
    $stmt->bind_param("ii", $id, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        $count += $row['count'];
    }
    
    if ($count > 0) {
        echo json_encode(['success' => false, 'message' => 'Product is in use']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

// Supplier functions
function createSupplier() {
    global $conn;
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Name is required']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO suppliers (name, address, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $address, $phone);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function updateSupplier() {
    global $conn;
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    
    if (empty($id) || empty($name)) {
        echo json_encode(['success' => false, 'message' => 'ID and name are required']);
        return;
    }

    $stmt = $conn->prepare("UPDATE suppliers SET name = ?, address = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $address, $phone, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function deleteSupplier() {
    global $conn;
    $id = $_POST['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID is required']);
        return;
    }

    // Check if supplier is being used
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM weighing_in WHERE supplier_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    if ($result['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Supplier is in use']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM suppliers WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

// User functions
function createUser() {
    global $conn;
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $name = $_POST['name'] ?? '';
    $role = $_POST['role'] ?? 'user';
    $wage_per_kg = $_POST['wage_per_kg'] ?? 0;
    
    if (empty($username) || empty($password) || empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Username, password and name are required']);
        return;
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        return;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, name, role, wage_per_kg) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $username, $hashed_password, $name, $role, $wage_per_kg);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function updateUser() {
    global $conn;
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $role = $_POST['role'] ?? 'user';
    $wage_per_kg = $_POST['wage_per_kg'] ?? 0;
    $password = $_POST['password'] ?? '';
    
    if (empty($id) || empty($name)) {
        echo json_encode(['success' => false, 'message' => 'ID and name are required']);
        return;
    }

    if (!empty($password)) {
        // Update with new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name = ?, role = ?, wage_per_kg = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssdsi", $name, $role, $wage_per_kg, $hashed_password, $id);
    } else {
        // Update without changing password
        $stmt = $conn->prepare("UPDATE users SET name = ?, role = ?, wage_per_kg = ? WHERE id = ?");
        $stmt->bind_param("ssdi", $name, $role, $wage_per_kg, $id);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function deleteUser() {
    global $conn;
    $id = $_POST['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID is required']);
        return;
    }

    // Check if user is being used
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM weighing_in WHERE user_id = ? UNION ALL SELECT COUNT(*) FROM weighing_out WHERE user_id = ?");
    $stmt->bind_param("ii", $id, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        $count += $row['count'];
    }
    
    if ($count > 0) {
        echo json_encode(['success' => false, 'message' => 'User has transaction records']);
        return;
    }

    // Prevent deleting the last admin
    if ($_POST['role'] === 'admin') {
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'admin' AND id != ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result['count'] === 0) {
            echo json_encode(['success' => false, 'message' => 'Cannot delete the last admin']);
            return;
        }
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function createBuyer() {
    global $conn;
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Nama wajib diisi']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO buyers (name, address, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $address, $phone);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function updateBuyer() {
    global $conn;
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    
    if (empty($id) || empty($name)) {
        echo json_encode(['success' => false, 'message' => 'ID dan nama wajib diisi']);
        return;
    }

    $stmt = $conn->prepare("UPDATE buyers SET name = ?, address = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $address, $phone, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function deleteBuyer() {
    global $conn;
    $id = $_POST['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID wajib diisi']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM buyers WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}
?>