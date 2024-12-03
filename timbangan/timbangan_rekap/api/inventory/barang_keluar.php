 
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
            getBarangKeluarById($_GET['id']);
        } else {
            getBarangKeluar();
        }
        break;
    case 'POST':
        addBarangKeluar();
        break;
    case 'PUT':
        updateBarangKeluar();
        break;
    case 'DELETE':
        deleteBarangKeluar();
        break;
}

function getBarangKeluar() {
    global $conn;
    
    $sql = "SELECT bk.*, 
            c.nama as customer_nama,
            p.nama as produk_nama,
            k.nama as kategori_nama
            FROM tr_barang_keluar bk
            LEFT JOIN tr_customer c ON bk.customer_id = c.id
            LEFT JOIN tr_produk p ON bk.produk_id = p.id
            LEFT JOIN tr_kategori k ON p.kategori_id = k.id 
            ORDER BY bk.tanggal DESC";

    if(isset($_GET['customer_id'])) {
        $sql = $sql . " WHERE bk.customer_id = " . $_GET['customer_id'];
    }
    if(isset($_GET['start_date']) && isset($_GET['end_date'])) {
        $sql = $sql . " AND bk.tanggal BETWEEN '" . $_GET['start_date'] . "' AND '" . $_GET['end_date'] . "'";
    }

    $result = $conn->query($sql);
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $data]);
}

function getBarangKeluarById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM tr_barang_keluar WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode(['success' => true, 'data' => $result->fetch_assoc()]);
}

function addBarangKeluar() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    // Check required fields
    if (!isset($data['customer_id']) || !isset($data['produk_id']) || !isset($data['berat'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak lengkap'
        ]);
        return;
    }

    // Check stock availability
    $stock = checkStock($data['produk_id']);
    if($stock < $data['berat']) {
        echo json_encode([
            'success' => false,
            'message' => "Stock tidak mencukupi. Stock tersedia: $stock kg"
        ]);
        return;
    }

    // Set default values if not provided
    $harga_per_kg = isset($data['harga_per_kg']) ? $data['harga_per_kg'] : 0;
    $total_harga = $data['berat'] * $harga_per_kg;
    $keterangan = isset($data['keterangan']) ? $data['keterangan'] : '';

    $stmt = $conn->prepare("INSERT INTO tr_barang_keluar 
        (customer_id, produk_id, berat, harga_per_kg, total_harga, keterangan, tanggal) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())");
    
    $stmt->bind_param("iiddds", 
        $data['customer_id'], 
        $data['produk_id'], 
        $data['berat'],
        $harga_per_kg,
        $total_harga,
        $keterangan
    );

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
}

function updateBarangKeluar() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    // Get current berat
    $stmt = $conn->prepare("SELECT berat FROM tr_barang_keluar WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $current = $result->fetch_assoc();
    
    // Check stock availability for additional weight
    $additionalWeight = $data['berat'] - $current['berat'];
    if($additionalWeight > 0) {
        $stock = checkStock($data['produk_id']);
        if($stock < $additionalWeight) {
            echo json_encode([
                'success' => false,
                'message' => "Stock tidak mencukupi. Stock tersedia: $stock kg"
            ]);
            return;
        }
    }

    $stmt = $conn->prepare("UPDATE tr_barang_keluar SET customer_id = ?, produk_id = ?, berat = ?, harga_per_kg = ?, total_harga = ?, keterangan = ? WHERE id = ?");
    
    $total_harga = $data['berat'] * $data['harga_per_kg'];
    $stmt->bind_param("iidddsi", 
        $data['customer_id'], 
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

function deleteBarangKeluar() {
    global $conn;
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM tr_barang_keluar WHERE id = ?");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
}

function checkStock($produk_id) {
    global $conn;
    
    $sql = "SELECT 
            COALESCE(SUM(bm.berat), 0) as total_masuk,
            COALESCE(SUM(bk.berat), 0) as total_keluar
            FROM tr_produk p
            LEFT JOIN tr_barang_masuk bm ON p.id = bm.produk_id
            LEFT JOIN tr_barang_keluar bk ON p.id = bk.produk_id
            WHERE p.id = ?
            GROUP BY p.id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $produk_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    return $data ? ($data['total_masuk'] - $data['total_keluar']) : 0;
}

$conn->close();