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

<h1>Selamat datang, <?php echo htmlspecialchars($user_data['username']); ?>!</h1>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Profil Anda</h5>
                <p>Username: <?php echo htmlspecialchars($user_data['username']); ?></p>
                <p>Email: <?php echo htmlspecialchars($user_data['email']); ?></p>
                <a href="profile.php" class="btn btn-primary">Edit Profil</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Mulai Tes Baru</h5>
                <p>Siap untuk mengambil tes CAT CPNS?</p>
                <a href="take_test.php" class="btn btn-success">Mulai Tes</a>
            </div>
        </div>
    </div>
</div>

<h2 class="mt-4">Riwayat Tes</h2>
<table class="table">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Skor</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($test_history as $test): ?>
        <tr>
            <td><?php echo htmlspecialchars($test['start_time']); ?></td>
            <td><?php echo isset($test['score']) ? htmlspecialchars($test['score']) : 'Belum selesai'; ?></td>
            <td>
                <?php if (isset($test['score'])): ?>
                    <a href="view_result.php?id=<?php echo $test['id']; ?>" class="btn btn-sm btn-info">Lihat Hasil</a>
                <?php else: ?>
                    <a href="continue_test.php?id=<?php echo $test['id']; ?>" class="btn btn-sm btn-warning">Lanjutkan</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>