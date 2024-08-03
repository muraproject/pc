<?php
include('../includes/db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $temperature = $_GET['temperature'];
    $temp_class = $_GET['temp_class'];
    $humidity = $_GET['humidity'];
    $hum_class = $_GET['hum_class'];
    $watering = $_GET['watering'];

    $stmt = $conn->prepare("INSERT INTO sensors (temperature, temp_class, humidity, hum_class, watering) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("dsssi", $temperature, $temp_class, $humidity, $hum_class, $watering);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>
