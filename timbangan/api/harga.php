<?php
header("Content-Type: application/json");
require_once "../includes/db.php";
require_once "../includes/functions.php";

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'setPrice':
        $id_produk = $_POST['id_produk'] ?? 0;
        $harga = $_POST['harga'] ?? 0;
        $result = setPrice($id_produk, $harga);
        echo json_encode($result);
        break;
        
    case 'getPrices':
        $result = getPrices();
        echo json_encode($result);
        break;
        
    case 'updatePrice':
        $id_harga = $_POST['id_harga'] ?? 0;
        $harga_baru = $_POST['harga_baru'] ?? 0;
        $result = updatePrice($id_harga, $harga_baru);
        echo json_encode($result);
        break;
        
    default:
        echo json_encode(["error" => "Invalid action"]);
        break;
}

function setPrice($id_produk, $harga) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO harga (id_produk, harga, tanggal) VALUES (?, ?, NOW())");
    $stmt->bind_param("id", $id_produk, $harga);
    
    if ($stmt->execute()) {
        return ["success" => true, "message" => "Harga berhasil diatur"];
    } else {
        return ["success" => false, "message" => "Gagal mengatur harga: " . $stmt->error];
    }
}

function getPrices() {
    global $conn;
    $result = $conn->query("SELECT h.id, p.nama as produk, h.harga, h.tanggal FROM harga h JOIN produk p ON h.id_produk = p.id ORDER BY h.tanggal DESC");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function updatePrice($id_harga, $harga_baru) {
    global $conn;
    $stmt = $conn->prepare("UPDATE harga SET harga = ?, tanggal = NOW() WHERE id = ?");
    $stmt->bind_param("di", $harga_baru, $id_harga);
    
    if ($stmt->execute()) {
        return ["success" => true, "message" => "Harga berhasil diupdate"];
    } else {
        return ["success" => false, "message" => "Gagal mengupdate harga: " . $stmt->error];
    }
}
?>