<?php
header("Content-Type: application/json");
require_once "../includes/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_produk = $_POST['id_produk'] ?? '';
    $harga = $_POST['harga'] ?? '';
    
    if (empty($id_produk) || empty($harga)) {
        echo json_encode(["success" => false, "message" => "ID produk dan harga harus diisi"]);
        exit;
    }

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Insert harga baru
        $stmt = $conn->prepare("INSERT INTO harga (id_produk, harga, tanggal) VALUES (?, ?, NOW())");
        $stmt->bind_param("id", $id_produk, $harga);
        $stmt->execute();

        // Commit transaksi
        $conn->commit();

        echo json_encode(["success" => true, "message" => "Harga berhasil diupdate"]);
    } catch (Exception $e) {
        // Rollback jika terjadi error
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Gagal mengupdate harga: " . $e->getMessage()]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>