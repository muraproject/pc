<?php
require_once 'db.php';

// Function to sanitize input
function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Function to validate number
function validateNumber($number) {
    return is_numeric($number) && $number >= 0;
}

// Function to get all products
function getAllProducts() {
    global $conn;
    $sql = "SELECT * FROM produk ORDER BY nama";
    $result = $conn->query($sql);
    $products = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

// Function to get latest price for a product
function getLatestPrice($productId) {
    global $conn;
    $sql = "SELECT harga FROM harga WHERE id_produk = ? ORDER BY tanggal DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['harga'];
    }
    return 0;
}

// Function to format date
function formatDate($date) {
    return date("d-m-Y H:i:s", strtotime($date));
}

// Function to calculate total price
function calculateTotalPrice($weight, $price) {
    return $weight * $price;
}

// Function to log errors
function logError($message) {
    $logFile = __DIR__ . '/../logs/error.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Function to generate pagination
function generatePagination($totalItems, $itemsPerPage, $currentPage, $urlPattern) {
    $totalPages = ceil($totalItems / $itemsPerPage);
    $pagination = '';
    
    if ($totalPages > 1) {
        $pagination .= '<ul class="pagination">';
        for ($i = 1; $i <= $totalPages; $i++) {
            $class = ($i == $currentPage) ? 'active' : '';
            $url = str_replace('{page}', $i, $urlPattern);
            $pagination .= "<li class='$class'><a href='$url'>$i</a></li>";
        }
        $pagination .= '</ul>';
    }
    
    return $pagination;
}
?>