<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Question.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Test.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$question = new Question($db);
$test = new Test($db);

$test_id = isset($_GET['test_id']) ? intval($_GET['test_id']) : 0;
$test_data = $test->getTestById($test_id);

if (!$test_data || $test_data['user_id'] != $_SESSION['user_id']) {
    die("Tes tidak ditemukan atau Anda tidak memiliki akses.");
}

$questions = $question->getQuestionsForTest($test_id);
$user_answers = $test->getTestAnswers($test_id);

$total_questions = count($questions);
$correct_answers = 0;

foreach ($questions as $q) {
    if (isset($user_answers[$q['id']]) && $user_answers[$q['id']] == $q['correct_answer']) {
        $correct_answers++;
    }
}

$score = ($correct_answers / $total_questions) * 100;

// Update test score if not already set
if ($test_data['score'] === null) {
    $test->updateTestScore($test_id, $score);
}

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Hasil Tes</h1>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Ringkasan</h5>
        <p>Total Pertanyaan: <?php echo $total_questions; ?></p>
        <p>Jawaban Benar: <?php echo $correct_answers; ?></p>
        <p>Skor: <?php echo number_format($score, 2); ?>%</p>
    </div>
</div>

<h2>Detail Jawaban</h2>

<?php foreach ($questions as $q): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Pertanyaan <?php echo $q['id']; ?></h5>
            <p><?php echo htmlspecialchars($q['question']); ?></p>
            
            <?php
            $user_answer = isset($user_answers[$q['id']]) ? $user_answers[$q['id']] : 'Tidak dijawab';
            $is_correct = ($user_answer == $q['correct_answer']);
            ?>
            
            <p>Jawaban Anda: <span class="<?php echo $is_correct ? 'text-success' : 'text-danger'; ?>"><?php echo $user_answer; ?></span></p>
            <p>Jawaban Benar: <?php echo $q['correct_answer']; ?></p>
        </div>
    </div>
<?php endforeach; ?>

<a href="/pc/cat/user/history.php" class="btn btn-primary">Kembali ke Riwayat Tes</a>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mengubah perilaku tombol kembali
    history.pushState(null, '', location.href);
    window.onpopstate = function () {
        history.go(1);
        window.location.href = '/pc/cat/user/take_test.php';
    };
});
</script>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>