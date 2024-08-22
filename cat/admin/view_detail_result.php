<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Test.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Question.php';

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$test = new Test($db);
$question = new Question($db);

$test_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$test_details = $test->getTestDetails($test_id);
$test_answers = $test->getTestAnswers($test_id);

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Detail Hasil Tes</h1>

<h2>Informasi Tes</h2>
<p><strong>Pengguna:</strong> <?php echo htmlspecialchars($test_details['username']); ?></p>
<p><strong>Tanggal Tes:</strong> <?php echo date('d-m-Y H:i', strtotime($test_details['start_time'])); ?></p>
<p><strong>Skor:</strong> <?php echo $test_details['score']; ?></p>

<h2>Detail Jawaban</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Pertanyaan</th>
            <th>Jawaban Pengguna</th>
            <th>Jawaban Benar</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($test_answers as $index => $answer): ?>
            <?php $q = $question->getQuestionById($answer['question_id']); ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo htmlspecialchars($q['question']); ?></td>
                <td><?php echo $answer['user_answer']; ?></td>
                <td><?php echo $q['correct_answer']; ?></td>
                <td><?php echo $answer['is_correct'] ? 'Benar' : 'Salah'; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="/pc/cat/admin/view_results.php" class="btn btn-primary">Kembali ke Daftar Hasil</a>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>