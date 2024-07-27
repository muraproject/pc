<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    $stmt = $conn->prepare("INSERT INTO confirmed_orders (order_id, name, whatsapp, product, quantity, total) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssid", $order['id'], $order['name'], $order['whatsapp'], $order['product'], $order['quantity'], $order['total']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();

    echo "Order confirmed.";
}

$orders = $conn->query("SELECT * FROM orders WHERE status = 'pending'");

echo "<h1>Pending Orders</h1>";
while ($order = $orders->fetch_assoc()) {
    echo "<div>";
    echo "<p>Order ID: " . $order['id'] . "</p>";
    echo "<p>Name: " . $order['name'] . "</p>";
    echo "<p>WhatsApp: " . $order['whatsapp'] . "</p>";
    echo "<p>Product: " . $order['product'] . "</p>";
    echo "<p>Quantity: " . $order['quantity'] . "</p>";
    echo "<p>Total: " . $order['total'] . "</p>";
    echo "<form method='POST' action='admin.php'>";
    echo "<input type='hidden' name='order_id' value='" . $order['id'] . "'>";
    echo "<button type='submit'>Confirm Order</button>";
    echo "</form>";
    echo "</div>";
}

$conn->close();
?>
