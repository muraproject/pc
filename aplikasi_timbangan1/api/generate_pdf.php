<?php
// api/generate_pdf.php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../vendor/fpdf/fpdf.php';

header('Content-Type: application/json');

$receipt_id = $_GET['id'] ?? '';

if (empty($receipt_id)) {
    echo json_encode(['success' => false, 'message' => 'Receipt ID is required']);
    exit;
}

// Query untuk mendapatkan data kwitansi
$query = "
   SELECT 
       wi.receipt_id,
       wi.created_at,
       s.name as supplier_name,
       wi.weight,
       p.name as product_name
   FROM weighing_in wi
   LEFT JOIN suppliers s ON wi.supplier_id = s.id
   LEFT JOIN products p ON wi.product_id = p.id
   WHERE wi.receipt_id = ?
   ORDER BY p.name";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $receipt_id);
$stmt->execute();
$result = $stmt->get_result();

// Persiapkan data
$first_row = $result->fetch_assoc();
if(!$first_row) {
    echo json_encode(['success' => false, 'message' => 'Data not found']);
    exit;
}

$created_at = $first_row['created_at'] ?? date('Y-m-d H:i:s');
$supplier_name = $first_row['supplier_name'] ?? '-';

$result->data_seek(0);

// Kelompokkan data
$grouped = [];
while($row = $result->fetch_assoc()) {
   $product = $row['product_name'];
   if(!isset($grouped[$product])) {
       $grouped[$product] = [
           'items' => [],
           'subtotal_weight' => 0
       ];
   }
   $grouped[$product]['items'][] = $row;
   $grouped[$product]['subtotal_weight'] += $row['weight'];
}

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'KWITANSI MASUK', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Buat PDF
$pdf = new PDF();
$pdf->AddPage();

// Info Kwitansi
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 7, 'ID Kwitansi:', 0);
$pdf->Cell(0, 7, $receipt_id, 0, 1);
$pdf->Cell(30, 7, 'Tanggal:', 0);
$pdf->Cell(0, 7, date('d/m/Y H:i', strtotime($created_at)), 0, 1);
$pdf->Cell(30, 7, 'Supplier:', 0);
$pdf->Cell(0, 7, $supplier_name, 0, 1);
$pdf->Ln(10);

// Header Tabel
$pdf->SetFillColor(200, 200, 200);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 7, 'No', 1, 0, 'C', true);
$pdf->Cell(80, 7, 'Produk', 1, 0, 'C', true);
$pdf->Cell(50, 7, 'Berat (kg)', 1, 0, 'C', true);
$pdf->Cell(50, 7, 'Total', 1, 1, 'C', true);

// Isi Tabel
$pdf->SetFont('Arial', '', 10);
$no = 1;
$grand_total_weight = 0;

foreach($grouped as $product => $group) {
    foreach($group['items'] as $item) {
        $pdf->Cell(10, 7, $no++, 1, 0, 'C');
        $pdf->Cell(80, 7, $product, 1, 0, 'L');
        $pdf->Cell(50, 7, number_format($item['weight'], 2), 1, 0, 'R');
        $pdf->Cell(50, 7, number_format($item['weight'], 2) . ' kg', 1, 1, 'R');
    }
    
    $grand_total_weight += $group['subtotal_weight'];
    
    // Subtotal per produk
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell(90, 7, 'Subtotal ' . $product, 1, 0, 'L', true);
    $pdf->Cell(50, 7, number_format($group['subtotal_weight'], 2), 1, 0, 'R', true);
    $pdf->Cell(50, 7, number_format($group['subtotal_weight'], 2) . ' kg', 1, 1, 'R', true);
}

// Grand Total
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(140, 7, 'Total Berat:', 1, 0, 'R');
$pdf->Cell(50, 7, number_format($grand_total_weight, 2) . ' kg', 1, 1, 'R');

// Generate filename dan simpan
$filename = 'kwitansi_' . $receipt_id . '_' . date('YmdHis') . '.pdf';
$filepath = __DIR__ . '/../temp/' . $filename;

$pdf->Output('F', $filepath);

// Return download URL
echo json_encode([
    'success' => true,
    'download_url' => 'api/download.php?filename=' . $filename
]);