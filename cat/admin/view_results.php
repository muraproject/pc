<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Test.php';

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$test = new Test($db);

// Ambil semua hasil tes
$results = $test->getAllTestResults();

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Hasil Tes Semua Pengguna</h1>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID Tes</th>
            <th>Username</th>
            <th>Tanggal Tes</th>
            <th>Skor</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $result): ?>
            <tr>
                <td><?php echo $result['id']; ?></td>
                <td><?php echo htmlspecialchars($result['username']); ?></td>
                <td><?php echo date('d-m-Y H:i', strtotime($result['start_time'])); ?></td>
                <td><?php echo $result['score']; ?></td>
                <td>
                    <a href="/pc/cat/admin/view_detail_result.php?id=<?php echo $result['id']; ?>" class="btn btn-sm btn-info">Lihat Detail</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>