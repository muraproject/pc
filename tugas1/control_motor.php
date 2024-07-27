<?php
if (isset($_GET['direction'])) {
    $direction = $_GET['direction'];
    
    // Simpan arah putaran ke database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "iot_db";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "INSERT INTO motor_control (direction) VALUES ('$direction')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Motor direction set to " . strtoupper($direction);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
} else {
    echo "No direction specified!";
}
?>
