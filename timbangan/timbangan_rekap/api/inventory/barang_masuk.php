 
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
            getBarangMasukById($_GET['id']);
        } else {
            getBarangMasuk();
        }
        break;
    case 'POST':
        addBarangMasuk();
        break;
    case 'PUT':
        updateBarangMasuk();
        break;
    case 'DELETE':
        deleteBarangMasuk();
        break;
}

function getBarangMasuk() {
    global $conn;
    
    $sql = "SELECT bm.*, 
            s.nama as supplier_nama,
            p.nama as produk_nama,
            k.nama as kategori_nama
            FROM tr_barang_masuk bm
            LEFT JOIN tr_supplier s ON bm.supplier_id = s.id
            LEFT JOIN tr_produk p ON bm.produk_id = p.id
            LEFT JOIN tr_kategori k ON p.kategori_id = k.id 
            ORDER BY bm.tanggal DESC";

    if(isset($_GET['supplier_id'])) {
        $sql = $sql . " WHERE bm.supplier_id = " . $_GET['supplier_id'];
    }
    if(isset($_GET['start_date']) && isset($_GET['end_date'])) {
        $sql = $sql . " AND bm.tanggal BETWEEN '" . $_GET['start_date'] . "' AND '" . $_GET['end_date'] . "'";
    }

    $result = $conn->query($sql);
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $data]);
}

function getBarangMasukById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM tr_barang_masuk WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode(['success' => true, 'data' => $result->fetch_assoc()]);
}

function addBarangMasuk() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    // Default values jika tidak ada
    $harga_per_kg = isset($data['harga_per_kg']) ? $data['harga_per_kg'] : 0;
    $berat = isset($data['berat']) ? $data['berat'] : 0;
    $keterangan = isset($data['keterangan']) ? $data['keterangan'] : '';

    // Hitung total harga
    $total_harga = $berat * $harga_per_kg;

    $stmt = $conn->prepare("INSERT INTO tr_barang_masuk (
        supplier_id, 
        produk_id, 
        berat, 
        harga_per_kg, 
        total_harga, 
        keterangan
    ) VALUES (?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("iiddds", 
        $data['supplier_id'], 
        $data['produk_id'], 
        $berat,
        $harga_per_kg,
        $total_harga,
        $keterangan
    );

    try {
        if($stmt->execute()) {
            echo json_encode([
                'success' => true, 
                'id' => $conn->insert_id,
                'message' => 'Data berhasil disimpan'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $stmt->error
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

function updateBarangMasuk() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    $stmt = $conn->prepare("UPDATE tr_barang_masuk SET supplier_id = ?, produk_id = ?, berat = ?, harga_per_kg = ?, total_harga = ?, keterangan = ? WHERE id = ?");
    
    $total_harga = $data['berat'] * $data['harga_per_kg'];
    $stmt->bind_param("iidddsi", 
        $data['supplier_id'], 
        $data['produk_id'], 
        $data['berat'],
        $data['harga_per_kg'],
        $total_harga,
        $data['keterangan'],
        $data['id']
    );

    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
}

function deleteBarangMasuk() {
    global $conn;
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM tr_barang_masuk WHERE id = ?");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
}

$conn->close();