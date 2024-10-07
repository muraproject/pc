<?php
header("Content-Type: application/json");
require_once "../includes/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Prepared statement untuk menghindari SQL injection
    $stmt = $conn->prepare("DELETE FROM timbangan WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Data berhasil dihapus"]);
        } else {
            echo json_encode(["success" => false, "message" => "Tidak ada data yang dihapus"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menghapus data: " . $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}

$conn->close();
?>