<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Test.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Question.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$test = new Test($db);
$question = new Question($db);

$test_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$test_data = $test->getTestById($test_id);

if (!$test_data || $test_data['user_id'] != $_SESSION['user_id']) {
    header("Location: /pc/cat/user/history.php");
    exit();
}

$user_data = $user->getUserById($_SESSION['user_id']);
$test_questions = $question->getQuestionsForTest($test_id);
$user_answers = $test->getTestAnswers($test_id);

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Hasil Tes CAT CPNS</h1>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Informasi Tes</h5>
        <p><strong>Nama:</strong> <?php echo htmlspecialchars($user_data['username']); ?></p>
        <p><strong>Tanggal Tes:</strong> <?php echo date('d-m-Y H:i', strtotime($test_data['start_time'])); ?></p>
        <p><strong>Durasi:</strong> <?php 
            $start = new DateTime($test_data['start_time']);
            $end = new DateTime($test_data['end_time']);
            $duration = $start->diff($end);
            echo $duration->format('%H jam %i menit %s detik');
        ?></p>
        <p><strong>Skor:</strong> <?php echo $test_data['score']; ?> / 100</p>
    </div>
</div>

<h2>Detail Jawaban</h2>

<?php foreach ($test_questions as $q): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Pertanyaan <?php echo $q['id']; ?></h5>
            <p><?php echo htmlspecialchars($q['question']); ?></p>
            
            <?php
            $options = ['A', 'B', 'C', 'D'];
            foreach ($options as $option):
                $option_value = 'option_' . strtolower($option);
                $is_user_answer = ($user_answers[$q['id']] == $option);
                $is_correct_answer = ($q['correct_answer'] == $option);
            ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" disabled
                           <?php echo $is_user_answer ? 'checked' : ''; ?>
                           <?php echo $is_correct_answer ? 'style="accent-color: green;"' : ''; ?>>
                    <label class="form-check-label <?php echo $is_correct_answer ? 'text-success' : ''; ?>">
                        <?php echo htmlspecialchars($q[$option_value]); ?>
                        <?php if ($is_user_answer && !$is_correct_answer): ?>
                            <span class="text-danger">(Jawaban Anda)</span>
                        <?php elseif ($is_correct_answer): ?>
                            <span class="text-success">(Jawaban Benar)</span>
                        <?php endif; ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>

<a href="/pc/cat/user/history.php" class="btn btn-primary">Kembali ke Riwayat Tes</a>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>