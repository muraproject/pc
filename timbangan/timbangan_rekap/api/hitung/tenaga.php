 
<?php
header("Content-Type: application/json");
require_once "../../includes/db.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getWorkerCalculations();
        break;
    case 'POST':
        calculateWorkerPayment();
        break;
    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        break;
}

function getWorkerCalculations() {
    global $conn;
    
    $worker_id = isset($_GET['worker_id']) ? (int)$_GET['worker_id'] : 0;
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

    $sql = "SELECT 
                bt.id,
                k.nama as karyawan,
                p.nama as produk,
                kat.nama as kategori,
                bt.berat,
                bt.biaya_per_kg,
                bt.keterangan,
                bt.tanggal
            FROM tr_biaya_tenaga bt
            JOIN tr_karyawan k ON bt.karyawan_id = k.id
            JOIN tr_produk p ON bt.produk_id = p.id
            JOIN tr_kategori kat ON p.kategori_id = kat.id
            WHERE bt.karyawan_id = ? 
            AND bt.tanggal BETWEEN ? AND ?
            ORDER BY bt.tanggal DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $worker_id, $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $total_biaya = 0;
    $total_berat = 0;
    
    while ($row = $result->fetch_assoc()) {
        $biaya_item = $row['berat'] * $row['biaya_per_kg'];
        $row['total_biaya'] = $biaya_item;
        $total_biaya += $biaya_item;
        $total_berat += $row['berat'];
        $data[] = $row;
    }

    echo json_encode([
        "success" => true,
        "data" => $data,
        "summary" => [
            "total_biaya" => $total_biaya,
            "total_berat" => $total_berat,
            "total_item" => count($data)
        ]
    ]);
}

function calculateWorkerPayment() {
    global $conn;
    
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['worker_id']) || !isset($data['items'])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required data"]);
        return;
    }

    $worker_id = $data['worker_id'];
    $items = $data['items'];
    $total = 0;

    $conn->begin_transaction();

    try {
        $sql = "UPDATE tr_biaya_tenaga 
                SET biaya_per_kg = ?,
                    total_biaya = berat * ?,
                    status_bayar = 1
                WHERE id = ? AND karyawan_id = ?";
                
        $stmt = $conn->prepare($sql);

        foreach ($items as $item) {
            $biaya = $item['biaya_per_kg'];
            $id = $item['id'];
            
            $stmt->bind_param("ddii", $biaya, $biaya, $id, $worker_id);
            $stmt->execute();
            
            $total += ($item['berat'] * $biaya);
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