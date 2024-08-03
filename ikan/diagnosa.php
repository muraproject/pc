<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

function hitungCF($cfPakar, $cfUser) {
    return $cfPakar * $cfUser;
}

function kombinasiCF($cf1, $cf2) {
    return $cf1 + $cf2 * (1 - $cf1);
}

// Ambil data CF pakar dari database
$cfPakar = [];
$result = $conn->query("SELECT gejala_id, penyakit_id, nilai FROM cf_pakar");
while ($row = $result->fetch_assoc()) {
    $cfPakar[$row['penyakit_id']][$row['gejala_id']] = $row['nilai'];
}

// Ambil data penyakit dari database
$penyakit = [];
$result = $conn->query("SELECT id, nama, penyebab, pengendalian FROM penyakit");
while ($row = $result->fetch_assoc()) {
    $penyakit[$row['id']] = $row;
}

$hasilCF = [];
$langkahPerhitungan = [];

foreach ($penyakit as $kodePenyakit => $dataPenyakit) {
    $cfKombinasi = 0;
    $langkahPenyakit = [];
    foreach ($cfPakar[$kodePenyakit] as $kodeGejala => $nilaiCF) {
        if (isset($_POST[$kodeGejala]) && $_POST[$kodeGejala] == '1') {
            $cf = hitungCF($nilaiCF, 1);
            $langkahPenyakit[] = "CF($kodeGejala) = $nilaiCF * 1 = $cf";
            $cfKombinasi = kombinasiCF($cfKombinasi, $cf);
            $langkahPenyakit[] = "CF kombinasi = " . number_format($cfKombinasi, 4);
        }
    }
    $hasilCF[$kodePenyakit] = $cfKombinasi;
    $langkahPerhitungan[$kodePenyakit] = $langkahPenyakit;
}

arsort($hasilCF);
$penyakitTerdiagnosa = key($hasilCF);
$persentaseKeyakinan = current($hasilCF) * 100;

if (is_array($penyakit) && isset($penyakit[$penyakitTerdiagnosa])) {
    $nama_penyakit = $penyakit[$penyakitTerdiagnosa]['nama'];
    $penyebab = $penyakit[$penyakitTerdiagnosa]['penyebab'];
    $pengendalian = $penyakit[$penyakitTerdiagnosa]['pengendalian'];
} else {
    $nama_penyakit = "Tidak dapat menentukan penyakit";
    $penyebab = "Tidak ada informasi penyebab";
    $pengendalian = "Tidak ada informasi pengendalian";
}

// Simpan hasil diagnosa ke database
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$waktu = date('Y-m-d H:i:s');
$persentase = number_format($persentaseKeyakinan, 2);

$stmt = $conn->prepare("INSERT INTO hasil_diagnosa (nama, alamat, waktu, penyakit, persentase, penyebab, pengendalian) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $nama, $alamat, $waktu, $nama_penyakit, $persentase, $penyebab, $pengendalian);
$stmt->execute();
$id_diagnosa = $stmt->insert_id;

$response = [
    'id' => $id_diagnosa,
    'penyakit' => $nama_penyakit,
    'persentase' => $persentase,
    'penyebab' => $penyebab,
    'pengendalian' => $pengendalian,
    'langkah_perhitungan' => $langkahPerhitungan,
    'rumus' => [
        'CF(H,E) = CF(E) * CF(rule) = CF(user) * CF(pakar)',
        'CF kombinasi(CF1,CF2) = CF1 + CF2 * (1 - CF1)',
        'Persentase keyakinan = CF * 100%'
    ]
];

echo json_encode($response);