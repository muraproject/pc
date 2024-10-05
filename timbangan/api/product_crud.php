<?php
header("Content-Type: application/json");
require_once "../includes/db_connect.php";

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'create':
        createProduct();
        break;
    case 'update':
        updateProduct();
        break;
    case 'delete':
        deleteProduct();
        break;
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
}

function createProduct() {
    global $conn;
    $name = $_POST['name'] ?? '';
    
    if (empty($name)) {
        echo json_encode(["success" => false, "message" => "Nama produk tidak boleh kosong"]);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO produk (nama) VALUES (?)");
    $stmt->bind_param("s", $name);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Produk berhasil ditambahkan"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menambahkan produk: " . $conn->error]);
    }
    $stmt->close();
}

function updateProduct() {
    global $conn;
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    
    if (empty($id) || empty($name)) {
        echo json_encode(["success" => false, "message" => "ID dan nama produk harus diisi"]);
        return;
    }

    $stmt = $conn->prepare("UPDATE produk SET nama = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Produk berhasil diupdate"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal mengupdate produk: " . $conn->error]);
    }
    $stmt->close();
}

function deleteProduct() {
    global $conn;
    $id = $_POST['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(["success" => false, "message" => "ID produk harus diisi"]);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM produk WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Produk berhasil dihapus"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menghapus produk: " . $conn->error]);
    }
    $stmt->close();
}

$conn->close();
?>