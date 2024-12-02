<?php
header("Content-Type: application/json");
require_once "../includes/db_connect.php";

// Inisialisasi response
$response = [
    "success" => false,
    "message" => "",
    "updated_rows" => 0,
    "debug_info" => []
];

try {
    // Ambil dan decode input JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Validasi input
    if (!isset($input['id_kwitansi']) || !isset($input['data']) || !is_array($input['data'])) {
        throw new Exception("Data input tidak valid");
    }

    $id_kwitansi = $input['id_kwitansi'];
    $data = $input['data'];

    // Mulai transaksi
    $conn->begin_transaction();

    $updateCount = 0;
    $stmt = $conn->prepare("UPDATE timbangan SET harga = ?, nilai_timbang = ? WHERE id = ? AND id_kwitansi = ?");

    foreach ($data as $item) {
        if (!isset($item['id']) || !isset($item['harga']) || !isset($item['nilai_timbang'])) {
            throw new Exception("Data item tidak lengkap");
        }

        $stmt->bind_param("ddis", $item['harga'], $item['nilai_timbang'], $item['id'], $id_kwitansi);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $updateCount += $affectedRows;

        // Get the current values in the database
        $checkStmt = $conn->prepare("SELECT harga, nilai_timbang FROM timbangan WHERE id = ? AND id_kwitansi = ?");
        $checkStmt->bind_param("is", $item['id'], $id_kwitansi);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $currentValue = $checkResult->fetch_assoc();

        $debugInfo = [
            "id" => $item['id'],
            "harga" => $item['harga'],
            "nilai_timbang" => $item['nilai_timbang'],
            "id_kwitansi" => $id_kwitansi,
            "affected_rows" => $affectedRows,
            "current_value" => $currentValue
        ];
        $response["debug_info"][] = $debugInfo;
    }

    // Commit transaksi jika semua berhasil
    $conn->commit();

    $response["success"] = true;
    $response["message"] = $updateCount > 0 ? "Data berhasil diupdate" : "Tidak ada perubahan data";
    $response["updated_rows"] = $updateCount;

} catch (Exception $e) {
    // Rollback transaksi jika terjadi error
    if (isset($conn) && !$conn->connect_errno) {
        $conn->rollback();
    }

    $response["message"] = "Error: " . $e->getMessage();
} finally {
    // Tutup statement dan koneksi
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($checkStmt)) {
        $checkStmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}

// Kirim response
echo json_encode($response);