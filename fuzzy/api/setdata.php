<?php
include('../includes/db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Ambil parameter dari query string
    $temperature = isset($_GET['temperature']) ? $_GET['temperature'] : null;
    $temp_class = isset($_GET['temp_class']) ? $_GET['temp_class'] : null;
    $humidity = isset($_GET['humidity']) ? $_GET['humidity'] : null;
    $hum_class = isset($_GET['hum_class']) ? $_GET['hum_class'] : null;
    $watering = isset($_GET['watering']) ? $_GET['watering'] : null;
    $setmotor = isset($_GET['setmotor']) ? $_GET['setmotor'] : 'cek';

    // Jika ada data sensor, simpan ke database
    if ($temperature !== null && $humidity !== null) {
        $stmt = $conn->prepare("INSERT INTO sensors (temperature, temp_class, humidity, hum_class, watering) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("dsssi", $temperature, $temp_class, $humidity, $hum_class, $watering);
        $stmt->execute();
    }

    // Proses parameter setmotor
    if ($setmotor == 'on' || $setmotor == 'off') {
        $updateStmt = $conn->prepare("INSERT INTO motor_status (status) VALUES (?)");
        $updateStmt->bind_param("s", $setmotor);
        $updateStmt->execute();
    }

    // Ambil status motor terbaru
    $query = "SELECT status FROM motor_status ORDER BY id DESC LIMIT 1";
    $result = $conn->query($query);
    $motor_status = 'off'; // Default ke off jika tidak ada status
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $motor_status = $data['status'];
    }

    echo json_encode(['status' => 'success', 'motor_status' => $motor_status]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan']);
}
?>