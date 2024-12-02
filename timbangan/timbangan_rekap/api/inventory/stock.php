<?php
header('Content-Type: application/json');
require_once '../../includes/config.php';

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Connection failed: " . $conn->connect_error
    ]);
    exit;
}

try {
    // Get categories with stock info
    $sql = "SELECT k.*, 
            COALESCE(SUM(bm.berat), 0) as total_masuk,
            COALESCE(SUM(bk.berat), 0) as total_keluar,
            (COALESCE(SUM(bm.berat), 0) - COALESCE(SUM(bk.berat), 0)) as total
            FROM tr_kategori k
            LEFT JOIN tr_produk p ON k.id = p.kategori_id
            LEFT JOIN tr_barang_masuk bm ON p.id = bm.produk_id
            LEFT JOIN tr_barang_keluar bk ON p.id = bk.produk_id
            GROUP BY k.id";
    
    $result = $conn->query($sql);
    $categories = [];
    
    while($row = $result->fetch_assoc()) {
        // Get products for each category
        $produkSql = "SELECT p.*, 
                      COALESCE(SUM(bm.berat), 0) as total_masuk,
                      COALESCE(SUM(bk.berat), 0) as total_keluar,
                      (COALESCE(SUM(bm.berat), 0) - COALESCE(SUM(bk.berat), 0)) as stock
                      FROM tr_produk p
                      LEFT JOIN tr_barang_masuk bm ON p.id = bm.produk_id
                      LEFT JOIN tr_barang_keluar bk ON p.id = bk.produk_id
                      WHERE p.kategori_id = ?
                      GROUP BY p.id";
        
        $stmt = $conn->prepare($produkSql);
        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        $produktResult = $stmt->get_result();
        
        $products = [];
        while($product = $produktResult->fetch_assoc()) {
            $products[] = $product;
        }
        
        $row['products'] = $products;
        $categories[] = $row;
    }

    echo json_encode([
        "success" => true,
        "data" => ["categories" => $categories]
    ]);

} catch(Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}

$conn->close();
?>