<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Dashboard Admin</h1>

<p>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

<div class="list-group">
    <a href="/pc/cat/admin/manage_users.php" class="list-group-item list-group-item-action">Kelola Pengguna</a>
    <a href="/pc/cat/admin/manage_questions.php" class="list-group-item list-group-item-action">Kelola Pertanyaan</a>
    <a href="/pc/cat/admin/manage_packages.php" class="list-group-item list-group-item-action">Kelola Paket Soal</a>
    <a href="/pc/cat/admin/view_results.php" class="list-group-item list-group-item-action">Lihat Hasil Tes</a>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>>

<h1>Dashboard Admin</h1>

<p>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

<ul>
    <li><a href="/pc/cat/admin/manage_users.php">Kelola Pengguna</a></li>
    <li><a href="/pc/cat/admin/manage_questions.php">Kelola Pertanyaan</a></li>
    <li><a href="/pc/cat/admin/view_results.php">Lihat Hasil Tes</a></li>
</ul>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>