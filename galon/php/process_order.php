<?php
include 'config.php';
include 'RSA.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $whatsapp = $_POST['whatsapp'];
    $quantity1 = $_POST['quantity1'];
    $quantity2 = $_POST['quantity2'];
    $total = $_POST['total'];
    
    $rsa = new RSA();
    $decryptedName = $rsa->decrypt(explode(' ', $name));
    $decryptedWhatsapp = $rsa->decrypt(explode(' ', $whatsapp));

    if ($quantity1 > 0) {
        $product = "Galon Isi Ulang";
        $quantity = $quantity1;
        $stmt = $conn->prepare("INSERT INTO orders (name, whatsapp, product, quantity, total) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssid", $decryptedName, $decryptedWhatsapp, $product, $quantity, $total);
        $stmt->execute();
        $stmt->close();
    }

    if ($quantity2 > 0) {
        $product = "Galon Asli";
        $quantity = $quantity2;
        $stmt = $conn->prepare("INSERT INTO orders (name, whatsapp, product, quantity, total) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssid", $decryptedName, $decryptedWhatsapp, $product, $quantity, $total);
        $stmt->execute();
        $stmt->close();
    }

    echo "Order received.";
}

$conn->close();
?>
