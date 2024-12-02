 
<?php
header("Content-Type: application/json");
require_once "../../includes/db.php";

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getSupplierCalculations();
        break;
    case 'POST':
        calculateSupplierPayment();
        break;
    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        break;
}

function getSupplierCalculations() {
    global $conn;
    
    $supplier_id = isset($_GET['supplier_id']) ? (int)$_GET['supplier_id'] : 0;
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

    $sql = "SELECT 
                bm.id,
                s.nama as supplier,
                p.nama as produk,
                k.nama as kategori,
                bm.berat,
                bm.keterangan,
                bm.tanggal
            FROM tr_barang_masuk bm
            JOIN tr_supplier s ON bm.supplier_id = s.id
            JOIN tr_produk p ON bm.produk_id = p.id
            JOIN tr_kategori k ON p.kategori_id = k.id
            WHERE bm.supplier_id = ? 
            AND bm.tanggal BETWEEN ? AND ?
            ORDER BY bm.tanggal DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $supplier_id, $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $total_berat = 0;
    while ($row = $result->fetch_assoc()) {
        $total_berat += $row['berat'];
        $data[] = $row;
    }

    echo json_encode([
        "success" => true,
        "data" => $data,
        "summary" => [
            "total_berat" => $total_berat,
            "total_item" => count($data)
        ]
    ]);
}

function calculateSupplierPayment() {
    global $conn;
    
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['supplier_id']) || !isset($data['items'])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required data"]);
        return;
    }

    $supplier_id = $data['supplier_id'];
    $items = $data['items'];
    $total = 0;

    $conn->begin_transaction();

    try {
        $sql = "UPDATE tr_barang_masuk 
                SET harga_per_kg = ?, 
                    total_harga = berat * ?,
                    status_bayar = 1
                WHERE id = ? AND supplier_id = ?";
                
        $stmt = $conn->prepare($sql);

        foreach ($items as $item) {
            $harga = $item['harga_per_kg'];
            $id = $item['id'];
            
            $stmt->bind_param("ddii", $harga, $harga, $id, $supplier_id);
            $stmt->execute();
            
            $total += ($item['berat'] * $harga);
        }

        $conn->commit();
        
        echo json_encode([
            "success" => true,
            "message" => "Perhitungan berhasil disimpan",
            "total" => $total
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode([
            "error" => "Gagal menyimpan perhitungan: " . $e->getMessage()
        ]);
    }
}
?>