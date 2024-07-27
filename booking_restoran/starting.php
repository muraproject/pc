<?php
include 'koneksi.php';

$username = 'admin'; // Ganti dengan username yang diinginkan
$password = 'password123'; // Ganti dengan password yang diinginkan

// Menghasilkan hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Menyiapkan query
$sql = "INSERT INTO admin (username, password) VALUES (?, ?)";

// Mempersiapkan statement
$stmt = $conn->prepare($sql);

// Bind parameter
$stmt->bind_param("ss", $username, $hashed_password);

// Eksekusi query
if ($stmt->execute()) {
    echo "Admin berhasil ditambahkan dengan username: $username";
} else {
    echo "Error: " . $stmt->error;
}

// Menutup statement dan koneksi
$stmt->close();
$conn->close();
?>