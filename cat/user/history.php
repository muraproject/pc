<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Test.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$test = new Test($db);

$user_data = $user->getUserById($_SESSION['user_id']);
$test_history = $test->getUserTestHistory($_SESSION['user_id']);

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Riwayat Tes</h1>

<table class="table">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Skor</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($test_history as $test): ?>
        <tr>
            <td><?php echo date('d-m-Y H:i', strtotime($test['start_time'])); ?></td>
            <td><?php echo isset($test['score']) ? $test['score'] : 'Belum selesai'; ?></td>
            <td><?php echo isset($test['end_time']) ? 'Selesai' : 'Belum selesai'; ?></td>
            <td>
                <?php if (!isset($test['end_time'])): ?>
                    <a href="/pc/cat/user/continue_test.php?id=<?php echo $test['id']; ?>" class="btn btn-sm btn-warning">Lanjutkan</a>
                    <a href="/pc/cat/user/cancel_test.php?id=<?php echo $test['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin membatalkan tes ini?');">Batalkan</a>
                <?php else: ?>
                    <a href="/pc/cat/user/view_result.php?id=<?php echo $test['id']; ?>" class="btn btn-sm btn-info">Lihat Hasil</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>