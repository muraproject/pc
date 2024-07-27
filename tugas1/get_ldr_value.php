<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iot_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT  temperature, humidity FROM sensor_data ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(["temperature" => 0, "humidity" => 0, "motor_status" => "unknown"]);
}

$conn->close();
?>
