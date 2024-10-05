<?php
$conn = new mysqli("localhost", "root", "", "aplikasi_timbangan");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>