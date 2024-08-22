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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_question'])) {
        $update_result = $question->updateQuestion(
            $question_id,
            $_POST['question_type'],
            $_POST['question'],
            $_POST['option_a'],
            $_POST['option_b'],
            $_POST['option_c'],
            $_POST['option_d'],
            $_POST['correct_answer']
        );
        
        if ($update_result) {
            $success_message = "Pertanyaan berhasil diperbarui.";
            $question_data = $question->getQuestionById($question_id); // Refresh data
        } else {
            $error_message = "Gagal memperbarui pertanyaan. Silakan coba lagi.";
        }
    }
}

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Edit Soal - <?php echo htmlspecialchars($package_info['name']); ?></h1>

<?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label for="question_type" class="form-label">Tipe Soal</label>
        <select class="form-select" id="question_type" name="question_type" required>
            <option value="TWK" <?php echo $question_data['question_type'] == 'TWK' ? 'selected' : ''; ?>>TWK</option>
            <option value="TIU" <?php echo $question_data['question_type'] == 'TIU' ? 'selected' : ''; ?>>TIU</option>
            <option value="TKP" <?php echo $question_data['question_type'] == 'TKP' ? 'selected' : ''; ?>>TKP</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="question" class="form-label">Pertanyaan</label>
        <textarea class="form-control" id="question" name="question" required><?php echo htmlspecialchars($question_data['question']); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="option_a" class="form-label">Opsi A</label>
        <input type="text" class="form-control" id="option_a" name="option_a" value="<?php echo htmlspecialchars($question_data['option_a']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="option_b" class="form-label">Opsi B</label>
        <input type="text" class="form-control" id="option_b" name="option_b" value="<?php echo htmlspecialchars($question_data['option_b']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="option_c" class="form-label">Opsi C</label>
        <input type="text" class="form-control" id="option_c" name="option_c" value="<?php echo htmlspecialchars($question_data['option_c']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="option_d" class="form-label">Opsi D</label>
        <input type="text" class="form-control" id="option_d" name="option_d" value="<?php echo htmlspecialchars($question_data['option_d']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="correct_answer" class="form-label">Jawaban Benar</label>
        <select class="form-select" id="correct_answer" name="correct_answer" required>
            <option value="A" <?php echo $question_data['correct_answer'] == 'A' ? 'selected' : ''; ?>>A</option>
            <option value="B" <?php echo $question_data['correct_answer'] == 'B' ? 'selected' : ''; ?>>B</option>
            <option value="C" <?php echo $question_data['correct_answer'] == 'C' ? 'selected' : ''; ?>>C</option>
            <option value="D" <?php echo $question_data['correct_answer'] == 'D' ? 'selected' : ''; ?>>D</option>
        </select>
    </div>
    <button type="submit" name="update_question" class="btn btn-primary">Perbarui Soal</button>
</form>

<a href="manage_package_questions.php?id=<?php echo $question_data['package_id']; ?>" class="btn btn-secondary mt-3">Kembali ke Daftar Soal</a>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>