<?php
header("Content-Type: application/json");
require_once "../includes/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_kwitansi'])) {
    $id_kwitansi = $_POST['id_kwitansi'];
    
    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Hapus detail kwitansi
        $stmt = $conn->prepare("DELETE FROM timbangan WHERE id_kwitansi = ?");
        $stmt->bind_param("s", $id_kwitansi);
        $stmt->execute();

        // Jika ada tabel khusus untuk kwitansi, hapus juga dari sana
        // $stmt = $conn->prepare("DELETE FROM kwitansi WHERE id_kwitansi = ?");
        // $stmt->bind_param("s", $id_kwitansi);
        // $stmt->execute();

        $conn->commit();
        echo json_encode(["success" => true, "message" => "Kwitansi berhasil dihapus"]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Gagal menghapus kwitansi: " . $e->getMessage()]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}

$conn->close();
?>