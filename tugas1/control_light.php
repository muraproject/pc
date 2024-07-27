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

if (isset($_GET['room']) && isset($_GET['action'])) {
    $room = $_GET['room'];
    $action = $_GET['action'];

    // Update the status of the light in the database
    $sql_update = "INSERT INTO light_status (room, status) VALUES ('$room', '$action')
                   ON DUPLICATE KEY UPDATE status='$action'";

    if ($conn->query($sql_update) === TRUE) {
        $response["status"] = $action;
        $response["message"] = "Light status updated successfully";
    } else {
        $response["status"] = "error";
        $response["message"] = "Error updating light status: " . $conn->error;
    }
} else {
    $response["status"] = "error";
    $response["message"] = "Invalid parameters";
}

echo json_encode($response);

$conn->close();
?>
