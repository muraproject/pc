<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="wages_export.xls"');
header('Cache-Control: max-age=0');

// Get filter parameters
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$user_id = $_GET['user_id'] ?? '';
$category_id = $_GET['category_id'] ?? '';
$product_id = $_GET['product_id'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$query = "
    SELECT 
        w.created_at,
        u.name as user_name,
        c.name as category_name,
        p.name as product_name,
        w.weight,
        u.wage_per_kg,
        (w.weight * u.wage_per_kg) as total_wage
    FROM wages_data w
    LEFT JOIN users u ON w.user_id = u.id
    LEFT JOIN categories c ON w.category_id = c.id
    LEFT JOIN products p ON w.product_id = p.id
    WHERE 1=1
";

$params = [];
$types = "";

if ($start_date) {
    $query .= " AND DATE(w.created_at) >= ?";
    $params[] = $start_date;
    $types .= "s";
}

if ($end_date) {
    $query .= " AND DATE(w.created_at) <= ?";
    $params[] = $end_date;
    $types .= "s";
}

if ($user_id) {
    $query .= " AND w.user_id = ?";
    $params[] = $user_id;
    $types .= "i";
}

if ($category_id) {
    $query .= " AND w.category_id = ?";
    $params[] = $category_id;
    $types .= "i";
}

if ($product_id) {
    $query .= " AND w.product_id = ?";
    $params[] = $product_id;
    $types .= "i";
}

if ($search) {
    $search = "%$search%";
    $query .= " AND (u.name LIKE ?)";
    $params[] = $search;
    $types .= "s";
}

$query .= " ORDER BY w.created_at DESC";

// Execute query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Create Excel content
echo "
<table border='1'>
    <tr>
        <th>Tanggal</th>
        <th>Penimbang</th>
        <th>Kategori</th>
        <th>Produk</th>
        <th>Berat (kg)</th>
        <th>Upah/kg</th>
        <th>Total Upah</th>
    </tr>
";

$total_weight = 0;
$total_wages = 0;

while ($row = $result->fetch_assoc()) {
    $total_weight += $row['weight'];
    $total_wages += $row['total_wage'];
    
    echo "
    <tr>
        <td>" . date('d/m/Y H:i', strtotime($row['created_at'])) . "</td>
        <td>" . htmlspecialchars($row['user_name']) . "</td>
        <td>" . htmlspecialchars($row['category_name']) . "</td>
        <td>" . htmlspecialchars($row['product_name']) . "</td>
        <td>" . number_format($row['weight'], 2) . "</td>
        <td>Rp " . number_format($row['wage_per_kg']) . "</td>
        <td>Rp " . number_format($row['total_wage']) . "</td>
    </tr>
    ";
}

echo "
    <tr>
        <td colspan='4'><strong>Total</strong></td>
        <td><strong>" . number_format($total_weight, 2) . "</strong></td>
        <td></td>
        <td><strong>Rp " . number_format($total_wages) . "</strong></td>
    </tr>
</table>";
?>