 
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
            getCustomerById($_GET['id']);
        } else {
            getAllCustomer();
        }
        break;
    case 'POST':
        addCustomer();
        break;
    case 'PUT':
        updateCustomer();
        break;
    case 'DELETE':
        deleteCustomer();
        break;
}

function getAllCustomer() {
    global $conn;
    $sql = "SELECT * FROM tr_customer ORDER BY nama";
    $result = $conn->query($sql);
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $data]);
}

function getCustomerById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM tr_customer WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode(['success' => true, 'data' => $result->fetch_assoc()]);
}

function addCustomer() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO tr_customer (nama, alamat, telepon, kontak_person) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $data['nama'], $data['alamat'], $data['telepon'], $data['kontak_person']);
    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
}

function updateCustomer() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("UPDATE tr_customer SET nama = ?, alamat = ?, telepon = ?, kontak_person = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $data['nama'], $data['alamat'], $data['telepon'], $data['kontak_person'], $data['id']);
    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
}

function deleteCustomer() {
    global $conn;
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM tr_customer WHERE id = ?");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
}

$conn->close();