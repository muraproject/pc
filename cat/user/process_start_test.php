<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Test.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['package_id'])) {
    header("Location: /pc/cat/user/take_test.php");
    exit();
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
exit();
?>