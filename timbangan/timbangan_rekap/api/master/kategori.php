<?php
header('Content-Type: application/json');
require_once '../../includes/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => $conn->connect_error]));
}

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if(isset($_GET['id'])) {
            getKategoriById($_GET['id']);
        } else {
            getAllKategori();
        }
        break;
    case 'POST':
        addKategori();
        break;
    case 'PUT':
        updateKategori();
        break;
    case 'DELETE':
        deleteKategori();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}

function getAllKategori() {
    global $conn;
    $sql = "SELECT * FROM tr_kategori ORDER BY nama";
    $result = $conn->query($sql);
    
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $data]);
}

function getKategoriById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM tr_kategori WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    echo json_encode(['success' => true, 'data' => $data]);
}

function addKategori() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['nama'])) {
        echo json_encode(['success' => false, 'message' => 'Nama kategori harus diisi']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO tr_kategori (nama, keterangan) VALUES (?, ?)");
    $stmt->bind_param("ss", $data['nama'], $data['keterangan']);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'id' => $conn->insert_id
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menambahkan kategori: ' . $stmt->error
        ]);
    }
}

function updateKategori() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id']) || !isset($data['nama'])) {
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        return;
    }

    $stmt = $conn->prepare("UPDATE tr_kategori SET nama = ?, keterangan = ? WHERE id = ?");
    $stmt->bind_param("ssi", $data['nama'], $data['keterangan'], $data['id']);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Kategori berhasil diupdate'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal mengupdate kategori: ' . $stmt->error
        ]);
    }
}

function deleteKategori() {
    global $conn;
    $id = $_GET['id'];

    // Check if kategori is being used
    $check = $conn->prepare("SELECT COUNT(*) as count FROM tr_produk WHERE kategori_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $result = $check->get_result();
    $count = $result->fetch_assoc()['count'];

    if ($count > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Kategori tidak dapat dihapus karena masih digunakan'
        ]);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM tr_kategori WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menghapus kategori: ' . $stmt->error
        ]);
    }
}

$conn->close();
?>