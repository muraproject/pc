<?php
header("Content-Type: application/json");
require_once "../includes/db_connect.php";

if (isset($_GET['id_kwitansi'])) {
    $id_kwitansi = $_GET['id_kwitansi'];
    
    $sql = "SELECT t.id, t.nilai_timbang, t.harga, p.nama AS nama_produk 
            FROM timbangan t
            JOIN produk p ON t.id_produk = p.id
            WHERE t.id_kwitansi = ?
            ORDER BY t.waktu";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_kwitansi);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $details = [];
    while ($row = $result->fetch_assoc()) {
        $details[] = [
            'id' => $row['id'],
            'nama_produk' => $row['nama_produk'],
            'nilai_timbang' => $row['nilai_timbang'],
            'harga' => $row['harga']
        ];
    }
    
    if (count($details) > 0) {
        echo json_encode(["success" => true, "details" => $details]);
    } else {
        echo json_encode(["success" => false, "message" => "Tidak ada detail untuk kwitansi ini"]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "ID Kwitansi tidak diberikan"]);
}

$conn->close();
?>