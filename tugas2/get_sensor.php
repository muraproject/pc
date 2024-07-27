<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iot_db2";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil 10 data sensor terbaru
$sql = "SELECT sensor_value, timestamp FROM ultrasonic_sensor ORDER BY timestamp DESC LIMIT 10";
$result = $conn->query($sql);
$sensor_data = [];
while ($row = $result->fetch_assoc()) {
    $sensor_data[] = $row;
}

// Mengambil status pintu air terbaru
$sql = "SELECT gate1_status, gate2_status FROM gate_control ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($sql);
$gate_status = $result->fetch_assoc();

$response = array(
    "sensor_data" => $sensor_data,
    "gate1_status" => $gate_status['gate1_status'],
    "gate2_status" => $gate_status['gate2_status']
);

echo json_encode($response);

$conn->close();
?>
