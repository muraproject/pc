<?php
session_start();

$db_host = 'localhost';
$db_name = 'smarthome';
$db_user = 'root';  // Sesuaikan dengan username MySQL Anda
$db_pass = '';      // Sesuaikan dengan password MySQL Anda

try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');