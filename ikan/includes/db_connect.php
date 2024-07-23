<?php
// Konfigurasi database
$host = 'localhost';  // Biasanya 'localhost' untuk pengembangan lokal
$username = 'root';
$password = '';
$database = 'ikan_nila_diagnosis';

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set karakter encoding ke UTF-8
$conn->set_charset("utf8mb4");