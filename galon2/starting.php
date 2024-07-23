<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$db = new Database();
$conn = $db->getConnection();

$username = 'admin';
$password = 'password123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo "Admin berhasil ditambahkan";
} else {
    echo "Gagal menambahkan admin: " . $stmt->error;
}

$stmt->close();
$conn->close();