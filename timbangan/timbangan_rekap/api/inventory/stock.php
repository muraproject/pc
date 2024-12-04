<?php
header('Content-Type: application/json');
require_once '../../includes/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => $conn->connect_error]));
}

// Handle produk_id request untuk single stock
if (isset($_GET['produk_id'])) {
    $produk_id = $_GET['produk_id'];
    
    $sql = "SELECT 
            (SELECT COALESCE(SUM(berat), 0) FROM tr_barang_masuk WHERE produk_id = p.id) as total_masuk,
            (SELECT COALESCE(SUM(berat), 0) FROM tr_barang_keluar WHERE produk_id = p.id) as total_keluar,
            (SELECT COALESCE(SUM(berat), 0) FROM tr_barang_masuk WHERE produk_id = p.id) - 
            (SELECT COALESCE(SUM(berat), 0) FROM tr_barang_keluar WHERE produk_id = p.id) as stock
            FROM tr_produk p
            WHERE p.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $produk_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    echo json_encode([
        "success" => true,
        "stock" => $data ? floatval($data['stock']) : 0
    ]);
    exit;
}

// Get all stock information
try {
    // Get categories with their stocks
    $sql = "SELECT 
            k.id,
            k.nama,
            k.keterangan,
            (SELECT COALESCE(SUM(bm.berat), 0) 
             FROM tr_barang_masuk bm 
             JOIN tr_produk p ON bm.produk_id = p.id 
             WHERE p.kategori_id = k.id) as total_masuk,
            (SELECT COALESCE(SUM(bk.berat), 0) 
             FROM tr_barang_keluar bk 
             JOIN tr_produk p ON bk.produk_id = p.id 
             WHERE p.kategori_id = k.id) as total_keluar,
            (SELECT COALESCE(SUM(bm.berat), 0) 
             FROM tr_barang_masuk bm 
             JOIN tr_produk p ON bm.produk_id = p.id 
             WHERE p.kategori_id = k.id) - 
            (SELECT COALESCE(SUM(bk.berat), 0) 
             FROM tr_barang_keluar bk 
             JOIN tr_produk p ON bk.produk_id = p.id 
             WHERE p.kategori_id = k.id) as total
            FROM tr_kategori k";
    
    $result = $conn->query($sql);
    $categories = [];
    
    while ($category = $result->fetch_assoc()) {
        // Get products for each category
        $produkSql = "SELECT 
                      p.id,
                      p.nama,
                      p.keterangan,
                      (SELECT COALESCE(SUM(berat), 0) FROM tr_barang_masuk WHERE produk_id = p.id) as total_masuk,
                      (SELECT COALESCE(SUM(berat), 0) FROM tr_barang_keluar WHERE produk_id = p.id) as total_keluar,
                      (SELECT COALESCE(SUM(berat), 0) FROM tr_barang_masuk WHERE produk_id = p.id) - 
                      (SELECT COALESCE(SUM(berat), 0) FROM tr_barang_keluar WHERE produk_id = p.id) as stock
                      FROM tr_produk p
                      WHERE p.kategori_id = ?";
        
        $stmt = $conn->prepare($produkSql);
        $stmt->bind_param("i", $category['id']);
        $stmt->execute();
        $produktResult = $stmt->get_result();
        
        $products = [];
        while ($product = $produktResult->fetch_assoc()) {
            // Convert numeric strings to float
            $product['total_masuk'] = floatval($product['total_masuk']);
            $product['total_keluar'] = floatval($product['total_keluar']);
            $product['stock'] = floatval($product['stock']);
            $products[] = $product;
        }
        
        // Convert numeric strings to float for category
        $category['total_masuk'] = floatval($category['total_masuk']);
        $category['total_keluar'] = floatval($category['total_keluar']);
        $category['total'] = floatval($category['total']);
        
        $category['products'] = $products;
        $categories[] = $category;
    }

    echo json_encode([
        "success" => true,
        "data" => [
            "categories" => $categories
        ]
    ]);

} catch(Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}

$conn->close();
?>