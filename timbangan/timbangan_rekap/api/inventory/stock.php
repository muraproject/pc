 
<?php
header("Content-Type: application/json");
require_once "../../includes/db.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getStock();
        break;
    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        break;
}

function getStock() {
    global $conn;
    
    // Query untuk mendapatkan stock per kategori
    $sql = "SELECT 
                k.id as kategori_id,
                k.nama as kategori,
                SUM(COALESCE(bm.berat, 0)) as total_masuk,
                SUM(COALESCE(bk.berat, 0)) as total_keluar,
                (SUM(COALESCE(bm.berat, 0)) - SUM(COALESCE(bk.berat, 0))) as stock
            FROM tr_kategori k
            LEFT JOIN tr_produk p ON k.id = p.kategori_id
            LEFT JOIN tr_barang_masuk bm ON p.id = bm.produk_id
            LEFT JOIN tr_barang_keluar bk ON p.id = bk.produk_id
            GROUP BY k.id, k.nama";

    $result = $conn->query($sql);
    
    if ($result === false) {
        echo json_encode([
            "success" => false,
            "error" => $conn->error
        ]);
        return;
    }

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        // Get product details for each category
        $products_sql = "SELECT 
                p.id,
                p.nama,
                SUM(COALESCE(bm.berat, 0)) as total_masuk,
                SUM(COALESCE(bk.berat, 0)) as total_keluar,
                (SUM(COALESCE(bm.berat, 0)) - SUM(COALESCE(bk.berat, 0))) as stock
            FROM tr_produk p
            LEFT JOIN tr_barang_masuk bm ON p.id = bm.produk_id
            LEFT JOIN tr_barang_keluar bk ON p.id = bk.produk_id
            WHERE p.kategori_id = ?
            GROUP BY p.id, p.nama";
        
        $stmt = $conn->prepare($products_sql);
        $stmt->bind_param("i", $row['kategori_id']);
        $stmt->execute();
        $products_result = $stmt->get_result();
        
        $products = [];
        while ($product = $products_result->fetch_assoc()) {
            $products[] = $product;
        }
        
        $row['products'] = $products;
        $categories[] = $row;
    }

    echo json_encode([
        "success" => true,
        "data" => $categories
    ]);
}
?>