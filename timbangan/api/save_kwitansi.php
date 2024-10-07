<?php
header("Content-Type: application/json");
require_once "../includes/db_connect.php";

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['id_kwitansi']) && isset($input['data']) && is_array($input['data'])) {
    $id_kwitansi = $input['id_kwitansi'];
    $data = $input['data'];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO timbangan (id_kwitansi, waktu, nama, id_produk, nilai_timbang, harga) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($data as $item) {
            $waktu = date('Y-m-d H:i:s', strtotime($item['waktu']));
            $stmt->bind_param("sssidd", $id_kwitansi, $waktu, $item['nama'], $item['produkId'], $item['nilaiTimbang'], $item['harga']);
            $stmt->execute();
        }

        $conn->commit();
        echo json_encode(["success" => true, "message" => "Kwitansi berhasil disimpan"]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Gagal menyimpan kwitansi: " . $e->getMessage()]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Data tidak valid"]);
}

$conn->close();
?>