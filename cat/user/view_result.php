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
    die("Tes tidak ditemukan atau Anda tidak memiliki akses.");
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
        <p><strong>Skor:</strong> <?php echo $test_data['score']; ?> / 100</p>
    </div>
</div>

<h2>Detail Jawaban</h2>

<?php foreach ($test_questions as $index => $q): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Pertanyaan <?php echo $index + 1; ?></h5>
            <p><?php echo htmlspecialchars($q['question']); ?></p>
            
            <?php
            $user_answer = isset($user_answers[$q['id']]) ? $user_answers[$q['id']] : 'Tidak dijawab';
            $is_correct = ($user_answer == $q['correct_answer']);
            ?>
            
            <p>Jawaban Anda: 
                <span class="<?php echo $is_correct ? 'text-success' : 'text-danger'; ?>">
                    <?php echo htmlspecialchars($user_answer); ?>
                </span>
            </p>
            <p>Jawaban Benar: <?php echo htmlspecialchars($q['correct_answer']); ?></p>
            
            <?php
            $options = ['A', 'B', 'C', 'D', 'E'];
            foreach ($options as $option):
                $option_key = 'option_' . strtolower($option);
                if (isset($q[$option_key]) && !empty($q[$option_key])):
            ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" disabled
                        <?php echo $user_answer === $option ? 'checked' : ''; ?>
                        <?php echo $q['correct_answer'] === $option ? 'style="accent-color: green;"' : ''; ?>>
                    <label class="form-check-label <?php echo $q['correct_answer'] === $option ? 'text-success' : ''; ?>">
                        <?php echo htmlspecialchars($q[$option_key]); ?>
                        <?php if ($user_answer === $option && $option !== $q['correct_answer']): ?>
                            <span class="text-danger">(Jawaban Anda)</span>
                        <?php elseif ($option === $q['correct_answer']): ?>
                            <span class="text-success">(Jawaban Benar)</span>
                        <?php endif; ?>
                    </label>
                </div>
            <?php
                endif;
            endforeach;
            ?>
        </div>
    </div>
<?php endforeach; ?>

<a href="/pc/cat/user/history.php" class="btn btn-primary">Kembali ke Riwayat Tes</a>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>