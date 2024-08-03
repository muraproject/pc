<?php
require_once 'includes/db_connect.php';
require('fpdf/fpdf.php');

$id = $_GET['id'];

// Ambil data dari database
$stmt = $conn->prepare("SELECT * FROM hasil_diagnosa WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Buat PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Hasil Diagnosa Penyakit Ikan Nila',0,1,'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Nama: '.$data['nama'],0,1);
$pdf->Cell(0,10,'Alamat: '.$data['alamat'],0,1);
$pdf->Cell(0,10,'Waktu Diagnosa: '.$data['waktu'],0,1);
$pdf->Cell(0,10,'Penyakit: '.$data['penyakit'],0,1);
$pdf->Cell(0,10,'Tingkat Keyakinan: '.$data['persentase'].'%',0,1);
$pdf->Ln(5);

$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Penyebab:',0,1);
$pdf->SetFont('Arial','',12);
$pdf->MultiCell(0,10,$data['penyebab']);
$pdf->Ln(5);

$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Pengendalian:',0,1);
$pdf->SetFont('Arial','',12);
$pdf->MultiCell(0,10,$data['pengendalian']);

$pdf->Output('Hasil_Diagnosa_'.$id.'.pdf', 'I');