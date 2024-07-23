<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

$result = $conn->query("SELECT id, deskripsi FROM gejala");
$gejala = [];
while ($row = $result->fetch_assoc()) {
    $gejala[] = $row;
}

echo json_encode($gejala);