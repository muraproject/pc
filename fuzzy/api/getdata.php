<?php
include('../includes/db.php');

$query = "SELECT temperature, temp_class, humidity, hum_class, watering FROM sensors ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo json_encode($data);
} else {
    echo json_encode(array("error" => "No data found"));
}
?>
