<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Question.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$question = new Question($db);

$total_users = $user->getTotalUsers();
$total_questions = $question->getTotalQuestions();

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<div class="jumbotron">
    <h1 class="display-4">Selamat Datang di Simulasi CAT CPNS</h1>
    <p class="lead">Latih kemampuan Anda dan persiapkan diri untuk tes CPNS dengan simulasi CAT kami.</p>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <a class="btn btn-primary btn-lg" href="/pc/cat/register.php" role="button">Daftar Sekarang</a>
    <?php else: ?>
        <a class="btn btn-primary btn-lg" href="/pc/cat/user/take_test.php" role="button">Mulai Tes</a>
    <?php endif; ?>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Pengguna</h5>
                <p class="card-text display-4"><?php echo $total_users; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Pertanyaan</h5>
                <p class="card-text display-4"><?php echo $total_questions; ?></p>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <h2>Tentang CAT CPNS</h2>
    <p>Computer Assisted Test (CAT) adalah metode ujian dengan menggunakan komputer dalam pelaksanaan seleksi CPNS. Sistem ini memungkinkan proses seleksi yang lebih efisien, transparan, dan akurat.</p>
    <p>Dengan menggunakan simulasi CAT kami, Anda dapat:</p>
    <ul>
        <li>Membiasakan diri dengan format ujian CAT</li>
        <li>Melatih kemampuan menjawab soal dengan batasan waktu</li>
        <li>Mengevaluasi kesiapan Anda untuk ujian CPNS yang sebenarnya</li>
    </ul>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>
