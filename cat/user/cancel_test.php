<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Test.php';

// Aktifkan error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    error_log("User tidak terautentikasi, redirect ke login");
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$test = new Test($db);

$test_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
error_log("Mencoba membatalkan tes dengan ID: " . $test_id);

$test_data = $test->getTestById($test_id);

if (!$test_data || $test_data['user_id'] != $_SESSION['user_id']) {
    error_log("Tes tidak ditemukan atau user tidak memiliki akses. User ID: " . $_SESSION['user_id']);
    $_SESSION['error_message'] = "Tes tidak ditemukan atau Anda tidak memiliki akses.";
    header("Location: /pc/cat/user/history.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_cancel'])) {
    error_log("Konfirmasi pembatalan diterima untuk tes ID: " . $test_id);
    $cancel_result = $test->cancelTest($test_id);
    if ($cancel_result) {
        error_log("Pembatalan berhasil untuk tes ID: " . $test_id);
        $_SESSION['success_message'] = "Tes berhasil dibatalkan.";
    } else {
        error_log("Pembatalan gagal untuk tes ID: " . $test_id);
        $_SESSION['error_message'] = "Gagal membatalkan tes. Silakan coba lagi atau hubungi administrator.";
    }
    header("Location: /pc/cat/user/history.php");
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Batalkan Tes</h1>

<p>Apakah Anda yakin ingin membatalkan tes ini? Tindakan ini tidak dapat dibatalkan.</p>

<form method="POST">
    <button type="submit" name="confirm_cancel" class="btn btn-danger">Ya, Batalkan Tes</button>
    <a href="/pc/cat/user/history.php" class="btn btn-secondary">Tidak, Kembali ke Riwayat Tes</a>
</form>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>