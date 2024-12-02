 
<?php
header("Content-Type: application/json");
require_once "../../includes/db.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getBiayaTenaga();
        break;
    case 'POST':
        saveBiayaTenaga();
        break;
    case 'PUT':
        updateBiayaTenaga();
        break;
    case 'DELETE':
        deleteBiayaTenaga();
        break;
    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        break;
}

function getBiayaTenaga() {
    global $conn;
    
    $sql = "SELECT 
                bt.id,
                k.nama as karyawan,
                kat.nama as kategori,
                p.nama as produk,
                bt.berat,
                bt.biaya_per_kg,
                bt.keterangan,
                bt.tanggal,
                (bt.berat * bt.biaya_per_kg) as total_biaya
            FROM tr_biaya_tenaga bt
            JOIN tr_karyawan k ON bt.karyawan_id = k.id
            JOIN tr_produk p ON bt.produk_id = p.id
            JOIN tr_kategori kat ON p.kategori_id = kat.id
            ORDER BY bt.tanggal DESC";

    $result = $conn->query($sql);
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode([
        "success" => true,
        "data" => $data
    ]);
}

function saveBiayaTenaga() {
    global $conn;
    
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['karyawan_id']) || !isset($data['produk_id']) || 
        !isset($data['berat']) || !isset($data['biaya_per_kg'])) {
        http_response_code(400);
        echo json_encode(["error" => "Data tidak lengkap"]);
        return;
    }

    $sql = "INSERT INTO tr_biaya_tenaga (karyawan_id, produk_id, berat, biaya_per_kg, keterangan) 
            VALUES (?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iidds", 
        $data['karyawan_id'], 
        $data['produk_id'], 
        $data['berat'],
        $data['biaya_per_kg'],
        $data['keterangan']
    );

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Data berhasil disimpan",
            "id" => $conn->insert_id
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "error" => "Gagal menyimpan data: " . $conn->error
        ]);
    }
}

function updateBiayaTenaga() {
    global $conn;
    
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID tidak ditemukan"]);
        return;
    }

    $sql = "UPDATE tr_biaya_tenaga 
            SET karyawan_id = ?, 
                produk_id = ?, 
                berat = ?, 
                biaya_per_kg = ?,
                keterangan = ?
            WHERE id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiddsi", 
        $data['karyawan_id'], 
        $data['produk_id'], 
        $data['berat'],
        $data['biaya_per_kg'],
        $data['keterangan'],
        $data['id']
    );

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Data berhasil diupdate"
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "error" => "Gagal mengupdate data: " . $conn->error
        ]);
    }
}

function deleteBiayaTenaga() {
    global $conn;
    
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        http_response_code(400);
        echo json_encode(["error" => "ID tidak ditemukan"]);
        return;
    }

    $sql = "DELETE FROM tr_biaya_tenaga WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Data berhasil dihapus"
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "error" => "Gagal menghapus data: " . $conn->error
        ]);
    }
}
?>