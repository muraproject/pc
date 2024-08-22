<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Question.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/QuestionPackage.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$question = new Question($db);
$package = new QuestionPackage($db);

$question_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$question_data = $question->getQuestionById($question_id);

if (!$question_data) {
    die("Pertanyaan tidak ditemukan.");
}

$package_info = $package->getPackageById($question_data['package_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_delete'])) {
    $delete_result = $question->deleteQuestion($question_id);
    if ($delete_result) {
        header("Location: manage_package_questions.php?id=" . $question_data['package_id'] . "&deleted=1");
        exit();
    } else {
        $error_message = "Gagal menghapus pertanyaan. Silakan coba lagi.";
    }
}

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Hapus Soal - <?php echo htmlspecialchars($package_info['name']); ?></h1>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Apakah Anda yakin ingin menghapus pertanyaan ini?</h5>
        <p class="card-text"><strong>Tipe Soal:</strong> <?php echo htmlspecialchars($question_data['question_type']); ?></p>
        <p class="card-text"><strong>Pertanyaan:</strong> <?php echo htmlspecialchars($question_data['question']); ?></p>
        <p class="card-text"><strong>Opsi A:</strong> <?php echo htmlspecialchars($question_data['option_a']); ?></p>
        <p class="card-text"><strong>Opsi B:</strong> <?php echo htmlspecialchars($question_data['option_b']); ?></p>
        <p class="card-text"><strong>Opsi C:</strong> <?php echo htmlspecialchars($question_data['option_c']); ?></p>
        <p class="card-text"><strong>Opsi D:</strong> <?php echo htmlspecialchars($question_data['option_d']); ?></p>
        <p class="card-text"><strong>Jawaban Benar:</strong> <?php echo htmlspecialchars($question_data['correct_answer']); ?></p>
        
        <form method="POST">
            <button type="submit" name="confirm_delete" class="btn btn-danger">Ya, Hapus Pertanyaan</button>
            <a href="manage_package_questions.php?id=<?php echo $question_data['package_id']; ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>