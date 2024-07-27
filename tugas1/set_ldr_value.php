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

$response = [];

if (isset($_GET['temperature']) && isset($_GET['humidity'])) {
    $temperature = $_GET['temperature'];
    $humidity = $_GET['humidity'];

    // Insert the new values into the database
    $sql_insert = "INSERT INTO sensor_data (temperature, humidity) VALUES ('$temperature', '$humidity')";
    if ($conn->query($sql_insert) === TRUE) {
        // Data successfully inserted
    } else {
        // Error inserting data
    }
}

// Fetch the latest sensor values from the database
$sql_select_sensor = "SELECT temperature, humidity FROM sensor_data ORDER BY id DESC LIMIT 1";
$result_sensor = $conn->query($sql_select_sensor);

if ($result_sensor->num_rows > 0) {
    $row_sensor = $result_sensor->fetch_assoc();
    $response["latest_temperature"] = $row_sensor["temperature"];
    $response["latest_humidity"] = $row_sensor["humidity"];
} else {
    $response["latest_temperature"] = 0;
    $response["latest_humidity"] = 0;
}

// Fetch the latest motor status from the database
$sql_motor = "SELECT direction,id FROM motor_control ORDER BY id DESC LIMIT 1";
$result_motor = $conn->query($sql_motor);

if ($result_motor->num_rows > 0) {
    $row_motor = $result_motor->fetch_assoc();
    $response["motor_status"] = $row_motor["direction"];
    $response["id_motor"] = $row_motor["id"];
} else {
    $response["motor_status"] = "unknown";
}

// Fetch the latest light status for each room from the database
$sql_light = "SELECT id,room status FROM light_status";
$result_light = $conn->query($sql_light);

if ($result_light->num_rows > 0) {
    // while ($row_light = $result_light->fetch_assoc()) {
    //     $response["light_status"][$row_light["room"]] = $row_light["status"];
    // }
    $row_light = $result_light->fetch_assoc();
    $response["id_lampu"] = $row_light["id"];
}

echo json_encode($response);

$conn->close();
?>
