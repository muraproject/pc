 
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
            getProdukById($_GET['id']);
        } elseif(isset($_GET['kategori_id'])) {
            getProdukByKategori($_GET['kategori_id']);
        } else {
            getAllProduk();
        }
        break;
    case 'POST':
        addProduk();
        break;
    case 'PUT':
        updateProduk();
        break;
    case 'DELETE':
        deleteProduk();
        break;
}

function getAllProduk() {
    global $conn;
    $sql = "SELECT p.*, k.nama as kategori_nama 
            FROM tr_produk p 
            JOIN tr_kategori k ON p.kategori_id = k.id 
            ORDER BY p.nama";
    $result = $conn->query($sql);
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $data]);
}

function getProdukById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM tr_produk WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode(['success' => true, 'data' => $result->fetch_assoc()]);
}

function getProdukByKategori($kategoriId) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM tr_produk WHERE kategori_id = ?");
    $stmt->bind_param("i", $kategoriId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $data]);
}

function addProduk() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO tr_produk (kategori_id, nama, keterangan) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $data['kategori_id'], $data['nama'], $data['keterangan']);
    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
}

function updateProduk() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("UPDATE tr_produk SET kategori_id = ?, nama = ?, keterangan = ? WHERE id = ?");
    $stmt->bind_param("issi", $data['kategori_id'], $data['nama'], $data['keterangan'], $data['id']);
    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
}

function deleteProduk() {
    global $conn;
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM tr_produk WHERE id = ?");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
}

$conn->close();