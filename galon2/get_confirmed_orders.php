<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

header('Content-Type: application/json');

$db = new Database();
$conn = $db->getConnection();

try {
    // Ambil pesanan yang sudah dikonfirmasi
    $sql = "SELECT o.id, o.name, o.whatsapp, o.latitude, o.longitude, o.total 
            FROM orders o 
            JOIN confirmed_orders co ON o.id = co.order_id 
            ORDER BY co.confirmed_at";
    
    $result = $conn->query($sql);
    
    $locations = [];
    
    // Tambahkan Toko Utama sebagai lokasi pertama
    $locations[] = [
        "name" => "Toko Utama",
        "lat" => -7.8700,
        "lng" => 111.4600,
        "type" => "toko"
    ];
    
    // Huruf untuk penamaan lokasi
    $letters = range('B', 'Z');
    $letterIndex = 0;
    
    while ($row = $result->fetch_assoc()) {
        $locations[] = [
            "name" => $letters[$letterIndex],
            "lat" => (float)$row['latitude'],
            "lng" => (float)$row['longitude'],
            "type" => "destination",
            "details" => "{$row['name']}, {$row['whatsapp']}, Rp " . number_format($row['total'], 0, ',', '.')
        ];
        $letterIndex++;
    }
    
    $response = ["locations" => $locations];
    
    echo json_encode($response, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

$conn->close();