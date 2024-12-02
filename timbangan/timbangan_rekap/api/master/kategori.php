 
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
    $stmt = $conn->prepare("INSERT INTO tr_kategori (nama, keterangan) VALUES (?, ?)");
    $stmt->bind_param("ss", $data['nama'], $data['keterangan']);
    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
}

function updateKategori() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("UPDATE tr_kategori SET nama = ?, keterangan = ? WHERE id = ?");
    $stmt->bind_param("ssi", $data['nama'], $data['keterangan'], $data['id']);
    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
}

function deleteKategori() {
    global $conn;
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM tr_kategori WHERE id = ?");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
}

$conn->close();