<?php
$filename = $_GET['filename'] ?? '';

if (empty($filename)) {
    die('Filename is required');
}

$filepath = __DIR__ . '/../temp/' . $filename;

if (!file_exists($filepath)) {
    die('File not found');
}

// Set headers untuk download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filepath));

// Output file
readfile($filepath);

// Hapus file setelah diunduh
unlink($filepath);