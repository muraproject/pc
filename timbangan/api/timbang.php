<?php
header("Content-Type: application/json");
require_once "../includes/db.php";
require_once "../includes/functions.php";

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'save':
        $nama = $_POST['nama'] ?? '';
        $id_produk = $_POST['id_produk'] ?? 0;
        $nilai_timbang = $_POST['nilai_timbang'] ?? 0;
        
        $result = saveTimbangan($nama, $id_produk, $nilai_timbang);
        echo json_encode($result);
        break;
        
    case 'get':
        $result = getTimbangan();
        echo json_encode($result);
        break;
        
    case 'update':
        $id = $_POST['id'] ?? 0;
        $nama = $_POST['nama'] ?? '';
        $id_produk = $_POST['id_produk'] ?? 0;
        $nilai_timbang = $_POST['nilai_timbang'] ?? 0;
        
        $result = updateTimbangan($id, $nama, $id_produk, $nilai_timbang);
        echo json_encode($result);
        break;
        
    case 'delete':
        $id = $_POST['id'] ?? 0;
        
        $result = deleteTimbangan($id);
        echo json_encode($result);
        break;
        
    default:
        echo json_encode(["error" => "Invalid action"]);
        break;
}

function saveTimbangan($nama, $id_produk, $nilai_timbang) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO timbangan (nama, id_produk, nilai_timbang, waktu) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sid", $nama, $id_produk, $nilai_timbang);
    
    if ($stmt->execute()) {
        return ["success" => true, "message" => "Data berhasil disimpan"];
    } else {
        return ["success" => false, "message" => "Gagal menyimpan data: " . $stmt->error];
    }
}

function getTimbangan() {
    global $conn;
    $result = $conn->query("SELECT t.*, p.nama as nama_produk FROM timbangan t JOIN produk p ON t.id_produk = p.id ORDER BY t.waktu DESC");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function updateTimbangan($id, $nama, $id_produk, $nilai_timbang) {
    global $conn;
    $stmt = $conn->prepare("UPDATE timbangan SET nama = ?, id_produk = ?, nilai_timbang = ? WHERE id = ?");
    $stmt->bind_param("sidi", $nama, $id_produk, $nilai_timbang, $id);
    
    if ($stmt->execute()) {
        return ["success" => true, "message" => "Data berhasil diupdate"];
    } else {
        return ["success" => false, "message" => "Gagal mengupdate data: " . $stmt->error];
    }
}

function deleteTimbangan($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM timbangan WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        return ["success" => true, "message" => "Data berhasil dihapus"];
    } else {
        return ["success" => false, "message" => "Gagal menghapus data: " . $stmt->error];
    }
}
?>