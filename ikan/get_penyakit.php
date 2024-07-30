<?php
require_once 'includes/db_connect.php';

$query = "SELECT * FROM penyakit";
$result = $conn->query($query);

$penyakit_data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $penyakit_data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($penyakit_data);
?>