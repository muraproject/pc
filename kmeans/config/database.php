<?php
$host = 'localhost';
$db   = 'beasiswa_db';
$user = 'root';
$pass = '';
$charset = '';
          // Password database (biasanya kosong untuk setup XAMPP default)

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    
    // Set mode error PDO ke exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode ke associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // echo "Koneksi database berhasil";
} catch(PDOException $e) {
    // Jika terjadi error, tampilkan pesan error
    die("Koneksi database gagal: " . $e->getMessage());
}