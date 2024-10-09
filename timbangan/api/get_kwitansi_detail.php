<?php
header("Content-Type: application/json");
require_once "../includes/db_connect.php";

if (isset($_GET['id_kwitansi'])) {
    $id_kwitansi = $_GET['id_kwitansi'];
    
    // Ambil tanggal kwitansi
    $sql_tanggal = "SELECT waktu FROM timbangan WHERE id_kwitansi = ? ORDER BY waktu DESC LIMIT 1";
    $stmt_tanggal = $conn->prepare($sql_tanggal);
    $stmt_tanggal->bind_param("s", $id_kwitansi);
    $stmt_tanggal->execute();
    $result_tanggal = $stmt_tanggal->get_result();
    $tanggal_kwitansi = $result_tanggal->fetch_assoc()['waktu'];
    
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
        $details[] = $row;
    }
    
    if (count($details) > 0) {
        echo json_encode([
            "success" => true, 
            "tanggal" => $tanggal_kwitansi,
            "details" => $details
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Tidak ada detail untuk kwitansi ini"]);
    }
    
    $stmt->close();
    $stmt_tanggal->close();
} else {
    echo json_encode(["success" => false, "message" => "ID Kwitansi tidak diberikan"]);
}

$conn->close();
?>