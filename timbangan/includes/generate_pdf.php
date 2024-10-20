<?php
header("Content-Type: application/json");
require('fpdf/fpdf.php');
require_once "../includes/db_connect.php";

$id_kwitansi = $_GET['id_kwitansi'] ?? '';

if (empty($id_kwitansi)) {
    echo json_encode(["success" => false, "message" => "ID Kwitansi is required"]);
    exit;
}

// Ambil tanggal kwitansi
$sql_tanggal = "SELECT waktu FROM timbangan WHERE id_kwitansi = ? ORDER BY waktu DESC LIMIT 1";
$stmt_tanggal = $conn->prepare($sql_tanggal);
$stmt_tanggal->bind_param("s", $id_kwitansi);
$stmt_tanggal->execute();
$result_tanggal = $stmt_tanggal->get_result();
$tanggal_kwitansi = $result_tanggal->fetch_assoc()['waktu'];
$stmt_tanggal->close();

// Ambil detail kwitansi
$sql = "SELECT t.id, t.nilai_timbang, t.harga, p.nama AS nama_produk 
        FROM timbangan t
        JOIN produk p ON t.id_produk = p.id
        WHERE t.id_kwitansi = ?
        ORDER BY p.nama, t.waktu";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_kwitansi);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}
$stmt->close();

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Kwitansi', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'ID Kwitansi: ' . $id_kwitansi, 0, 1);
$pdf->Cell(0, 10, 'Tanggal: ' . $tanggal_kwitansi, 0, 1);
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(10, 10, 'No', 1);
$pdf->Cell(50, 10, 'Produk', 1);
$pdf->Cell(30, 10, 'Berat (kg)', 1);
$pdf->Cell(50, 10, 'Harga', 1);
$pdf->Cell(50, 10, 'Total', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$totalHarga = 0;
$currentProduct = '';
$subtotal = 0;
$subtotalNilaiTimbang = 0;
$rowCount = 0;

foreach ($items as $index => $item) {
    if ($currentProduct !== $item['nama_produk']) {
        if ($currentProduct !== '') {
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(60, 10, 'Subtotal ' . $currentProduct, 1, 0, 'L', true);
            $pdf->Cell(30, 10, number_format($subtotalNilaiTimbang, 2) . ' kg', 1, 0, 'R', true);
            $pdf->Cell(50, 10, 'Rp ' . number_format($lastHarga, 0), 1, 0, 'R', true);
            $pdf->Cell(50, 10, 'Rp ' . number_format($subtotal, 2), 1, 0, 'R', true);
            $pdf->Ln();
        }
        $currentProduct = $item['nama_produk'];
        $subtotal = 0;
        $subtotalNilaiTimbang = 0;
        $rowCount = 0;
    }

    $rowCount++;
    $itemTotal = $item['nilai_timbang'] * $item['harga'];
    $subtotal += $itemTotal;
    $subtotalNilaiTimbang += $item['nilai_timbang'];
    $totalHarga += $itemTotal;
    $lastHarga = $item['harga'];

    $pdf->Cell(10, 10, $rowCount, 1);
    $pdf->Cell(50, 10, $item['nama_produk'], 1);
    $pdf->Cell(30, 10, number_format($item['nilai_timbang'], 2), 1, 0, 'R');
    $pdf->Cell(50, 10, 'Rp ' . number_format($item['harga'], 0), 1, 0, 'R');
    $pdf->Cell(50, 10, 'Rp ' . number_format($itemTotal, 2), 1, 0, 'R');
    $pdf->Ln();

    if ($index === count($items) - 1) {
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(60, 10, 'Subtotal ' . $currentProduct, 1, 0, 'L', true);
        $pdf->Cell(30, 10, number_format($subtotalNilaiTimbang, 2) . ' kg', 1, 0, 'R', true);
        $pdf->Cell(50, 10, 'Rp ' . number_format($lastHarga, 0), 1, 0, 'R', true);
        $pdf->Cell(50, 10, 'Rp ' . number_format($subtotal, 2), 1, 0, 'R', true);
        $pdf->Ln();
    }
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Total Harga: Rp ' . number_format($totalHarga, 2), 0, 1, 'R');

// Simpan PDF ke file sementara
$temp_dir = __DIR__ . '/../temp/';
if (!file_exists($temp_dir)) {
    if (!mkdir($temp_dir, 0755, true)) {
        echo json_encode(["success" => false, "message" => "Gagal membuat direktori temp"]);
        exit;
    }
}

$filename = 'Kwitansi_' . $id_kwitansi . '_' . time() . '.pdf';
$filepath = $temp_dir . $filename;

try {
    $pdf->Output('F', $filepath);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Gagal membuat PDF: " . $e->getMessage()]);
    exit;
}

$conn->close();

// Ini adalah bagian di mana download_url dibuat
$base_url = "/pc/timbangan/includes"; // Ganti dengan domain aktual Anda
$download_url = $base_url . '/download_pdf.php?filename=' . $filename;

// Kembalikan download_url dalam respons JSON
echo json_encode(["success" => true, "download_url" => $download_url]);
