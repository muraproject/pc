<?php
header("Content-Type: application/json");
require_once "../includes/db_connect.php";

$action = $_POST['action'] ?? '';
$type = $_POST['type'] ?? '';

switch ($action) {
    case 'create':
        createItem($type);
        break;
    case 'update':
        updateItem($type);
        break;
    case 'delete':
        deleteItem($type);
        break;
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
}

function createItem($type) {
    global $conn;
    $name = $_POST['name'] ?? '';
    
    if (empty($name)) {
        echo json_encode(["success" => false, "message" => "Nama tidak boleh kosong"]);
        return;
    }
    
    $table = ($type === 'orang') ? 'orang' : 'produk';
    $stmt = $conn->prepare("INSERT INTO $table (nama) VALUES (?)");
    $stmt->bind_param("s", $name);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => ucfirst($table) . " berhasil ditambahkan"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menambahkan " . $table . ": " . $conn->error]);
    }
    $stmt->close();
}

function updateItem($type) {
    global $conn;
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    
    if (empty($id) || empty($name)) {
        echo json_encode(["success" => false, "message" => "ID dan nama harus diisi"]);
        return;
    }
    
    $table = ($type === 'orang') ? 'orang' : 'produk';
    $stmt = $conn->prepare("UPDATE $table SET nama = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => ucfirst($table) . " berhasil diupdate"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal mengupdate " . $table . ": " . $conn->error]);
    }
    $stmt->close();
}

function deleteItem($type) {
    global $conn;
    $id = $_POST['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(["success" => false, "message" => "ID harus diisi"]);
        return;
    }
    
    $table = ($type === 'orang') ? 'orang' : 'produk';
    $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => ucfirst($table) . " berhasil dihapus"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menghapus " . $table . ": " . $conn->error]);
    }
    $stmt->close();
}

$conn->close();
?>