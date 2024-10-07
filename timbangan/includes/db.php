<?php
require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// Function to get database connection
function getDbConnection() {
    global $conn;
    return $conn;
}

// Function to close database connection
function closeDbConnection() {
    global $conn;
    $conn->close();
}
?>