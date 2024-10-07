<?php
// Pastikan tidak ada spasi atau baris kosong sebelum tag PHP pembuka
ob_start(); // Mulai output buffering di awal sekali
session_start();

// Fungsi untuk log error
function logError($message) {
    error_log(date('Y-m-d H:i:s') . " - " . $message . "\n", 3, $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/error.log');
}

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Test.php';

    if (!isset($_SESSION['user_id']) || !isset($_POST['package_id'])) {
        throw new Exception("Invalid session or missing package_id");
    }

    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);
    $test = new Test($db);

    $package_id = intval($_POST['package_id']);

    // Buat tes baru
    $test_id = $test->startTest($_SESSION['user_id']);

    // Assign paket soal ke tes
    $test->assignPackageToTest($test_id, $package_id);

    // Redirect ke halaman tes
    header("Location: /pc/cat/user/test_page.php?test_id=" . $test_id);
    ob_end_flush();
    exit();
} catch (Exception $e) {
    logError($e->getMessage());
    header("Location: /pc/cat/user/error.php?message=" . urlencode($e->getMessage()));
    ob_end_flush();
    exit();
}
?>