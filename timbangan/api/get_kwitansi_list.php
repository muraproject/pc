<?php
header("Content-Type: application/json");
require_once "../includes/db_connect.php";

try {
    $sql = "SELECT DISTINCT id_kwitansi, MAX(waktu) as waktu, nama FROM timbangan GROUP BY id_kwitansi ORDER BY MAX(waktu) DESC";
    $result = $conn->query($sql);

    $kwitansiList = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $kwitansiList[] = [
                'id_kwitansi' => $row['id_kwitansi'],
                'waktu' => $row['waktu'],
                'nama' => $row['nama']
            ];
        }
    }

    echo json_encode([
        "success" => true,
        "kwitansiList" => $kwitansiList
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}

$conn->close();
?>