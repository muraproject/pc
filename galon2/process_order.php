<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/rsa.php';

header('Content-Type: application/json');

// Fungsi dekripsi RSA
function rsaDecrypt($encryptedData, $privateKey, $n) {
    $decrypted = '';
    foreach ($encryptedData as $char) {
        $m = bcpowmod($char, $privateKey, $n);
        $decrypted .= chr($m);
    }
    return $decrypted;
}

// Terima data terenkripsi
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$encryptedData = $data['encryptedData'];

// Dekripsi data
$rsa = new RSA(RSA_P, RSA_Q, RSA_E);
$decryptedData = $rsa->decrypt($encryptedData);
$orderData = json_decode($decryptedData, true);

// Koneksi ke database
$db = new Database();
$conn = $db->getConnection();

// Masukkan data ke database
$sql = "INSERT INTO orders (name, whatsapp, address, longitude, latitude, refill_quantity, original_quantity, total)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssddiid", $orderData['name'], $orderData['whatsapp'], $orderData['address'], $orderData['longitude'], $orderData['latitude'], $orderData['refillQuantity'], $orderData['originalQuantity'], $orderData['total']);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
    // echo $orderData['longitude'];
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();