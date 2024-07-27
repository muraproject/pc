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

$gate1_status = isset($_POST['gate1_status']) ? $_POST['gate1_status'] : null;
$gate2_status = isset($_POST['gate2_status']) ? $_POST['gate2_status'] : null;

// Mengambil ID data terakhir
$sql = "SELECT id FROM gate_control ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($sql);
$last_id = $result->fetch_assoc()['id'];

// Memperbarui status pintu air terakhir
if ($gate1_status !== null && $gate2_status !== null) {
    $sql = "UPDATE gate_control SET gate1_status=$gate1_status, gate2_status=$gate2_status WHERE id=$last_id";
} elseif ($gate1_status !== null) {
    $sql = "UPDATE gate_control SET gate1_status=$gate1_status WHERE id=$last_id";
} elseif ($gate2_status !== null) {
    $sql = "UPDATE gate_control SET gate2_status=$gate2_status WHERE id=$last_id";
}

if ($conn->query($sql) === TRUE) {
    echo "Gate status updated successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
