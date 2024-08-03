<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/rsa.php';

header('Content-Type: application/json');

$db = new Database();
$conn = $db->getConnection();

$rsa = new RSA(RSA_P, RSA_Q, RSA_E);

try {
    // Ambil pesanan yang sudah dikonfirmasi
    $sql = "SELECT o.id, o.name, o.whatsapp, o.address, o.latitude, o.longitude, o.total, o.refill_quantity, o.original_quantity
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
        $decryptedData = [];
        foreach ($row as $key => $value) {
            $decryptedData[$key] = $rsa->decrypt(explode(',', $value));
        }
        
        $locations[] = [
            "name" => $letters[$letterIndex],
            "lat" => (float)$decryptedData['latitude'],
            "lng" => (float)$decryptedData['longitude'],
            "type" => "destination",
            "details" => "{$decryptedData['name']}, {$decryptedData['whatsapp']}, " . 
                         ($decryptedData['address'] ? "Alamat: {$decryptedData['address']}, " : "") . 
                         "Rp " . number_format((float)$decryptedData['total'], 0, ',', '.')
        ];
        $letterIndex++;
    }
    
    $response = ["locations" => $locations];
    
    echo json_encode($response, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

$conn->close();