<?php
header("Content-Type: application/json");
require_once "../includes/db.php";
require_once "../includes/functions.php";

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'getHistory':
        $result = getHistory();
        echo json_encode($result);
        break;
        
    case 'editHistory':
        $id = $_POST['id'] ?? 0;
        $nama = $_POST['nama'] ?? '';
        $produk = $_POST['produk'] ?? '';
        $nilai_timbang = $_POST['nilai_timbang'] ?? 0;
        $result = editHistory($id, $nama, $produk, $nilai_timbang);
        echo json_encode($result);
        break;
        
    case 'deleteHistory':
        $id = $_POST['id'] ?? 0;
        $result = deleteHistory($id);
        echo json_encode($result);
        break;
        
    default:
        echo json_encode(["error" => "Invalid action"]);
        break;
}

function getHistory() {
    global $conn;
    $query = "SELECT t.id, t.nama, p.nama as produk, t.nilai_timbang, t.waktu, h.harga 
              FROM timbangan t 
              JOIN produk p ON t.id_produk = p.id 
              LEFT JOIN harga h ON p.id = h.id_produk 
              ORDER BY t.waktu DESC";
    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $row['total'] = $row['nilai_timbang'] * $row['harga'];
        $data[] = $row;
    }
    return $data;
}

function editHistory($id, $nama, $produk, $nilai_timbang) {
    global $conn;
    $stmt = $conn->prepare("UPDATE timbangan SET nama = ?, id_produk = (SELECT id FROM produk WHERE nama = ?), nilai_timbang = ? WHERE id = ?");
    $stmt->bind_param("ssdi", $nama, $produk, $nilai_timbang, $id);
    
    if ($stmt->execute()) {
        return ["success" => true, "message" => "Data histori berhasil diupdate"];
    } else {
        return ["success" => false, "message" => "Gagal mengupdate data histori: " . $stmt->error];
    }
}

function deleteHistory($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM timbangan WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        return ["success" => true, "message" => "Data histori berhasil dihapus"];
    } else {
        return ["success" => false, "message" => "Gagal menghapus data histori: " . $stmt->error];
    }
}
?>