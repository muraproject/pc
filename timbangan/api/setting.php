<?php
header("Content-Type: application/json");
require_once "../includes/db.php";
require_once "../includes/functions.php";

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'addProduct':
        $nama_produk = $_POST['nama_produk'] ?? '';
        $result = addProduct($nama_produk);
        echo json_encode($result);
        break;
        
    case 'removeProduct':
        $id_produk = $_POST['id_produk'] ?? 0;
        $result = removeProduct($id_produk);
        echo json_encode($result);
        break;
        
    case 'getProducts':
        $result = getProducts();
        echo json_encode($result);
        break;
        
    default:
        echo json_encode(["error" => "Invalid action"]);
        break;
}

function addProduct($nama_produk) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO produk (nama) VALUES (?)");
    $stmt->bind_param("s", $nama_produk);
    
    if ($stmt->execute()) {
        return ["success" => true, "message" => "Produk berhasil ditambahkan"];
    } else {
        return ["success" => false, "message" => "Gagal menambahkan produk: " . $stmt->error];
    }
}

function removeProduct($id_produk) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM produk WHERE id = ?");
    $stmt->bind_param("i", $id_produk);
    
    if ($stmt->execute()) {
        return ["success" => true, "message" => "Produk berhasil dihapus"];
    } else {
        return ["success" => false, "message" => "Gagal menghapus produk: " . $stmt->error];
    }
}

function getProducts() {
    global $conn;
    $result = $conn->query("SELECT * FROM produk ORDER BY nama");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}
?>