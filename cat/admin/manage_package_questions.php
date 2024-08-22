<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/QuestionPackage.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Question.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$package = new QuestionPackage($db);
$question = new Question($db);

$package_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$package_info = $package->getPackageById($package_id);

if (!$package_info) {
    die("Paket soal tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_question'])) {
        $question->addQuestion(
            $package_id,
            $_POST['question_type'],
            $_POST['question'],
            $_POST['option_a'],
            $_POST['option_b'],
            $_POST['option_c'],
            $_POST['option_d'],
            $_POST['correct_answer']
        );
    }
}

$questions = $question->getQuestionsByPackage($package_id);

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Kelola Soal - <?php echo htmlspecialchars($package_info['name']); ?></h1>

<form method="POST" class="mb-4">
    <h2>Tambah Soal Baru</h2>
    <div class="mb-3">
        <label for="question_type" class="form-label">Tipe Soal</label>
        <select class="form-select" id="question_type" name="question_type" required>
            <option value="TWK">TWK</option>
            <option value="TIU">TIU</option>
            <option value="TKP">TKP</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="question" class="form-label">Pertanyaan</label>
        <textarea class="form-control" id="question" name="question" required></textarea>
    </div>
    <div class="mb-3">
        <label for="option_a" class="form-label">Opsi A</label>
        <input type="text" class="form-control" id="option_a" name="option_a" required>
    </div>
    <div class="mb-3">
        <label for="option_b" class="form-label">Opsi B</label>
        <input type="text" class="form-control" id="option_b" name="option_b" required>
    </div>
    <div class="mb-3">
        <label for="option_c" class="form-label">Opsi C</label>
        <input type="text" class="form-control" id="option_c" name="option_c" required>
    </div>
    <div class="mb-3">
        <label for="option_d" class="form-label">Opsi D</label>
        <input type="text" class="form-control" id="option_d" name="option_d" required>
    </div>
    <div class="mb-3">
        <label for="correct_answer" class="form-label">Jawaban Benar</label>
        <select class="form-select" id="correct_answer" name="correct_answer" required>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
        </select>
    </div>
    <button type="submit" name="add_question" class="btn btn-primary">Tambah Soal</button>
</form>

<h2>Daftar Soal</h2>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tipe</th>
            <th>Pertanyaan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($questions as $q): ?>
        <tr>
            <td><?php echo $q['id']; ?></td>
            <td><?php echo $q['question_type']; ?></td>
            <td><?php echo htmlspecialchars(substr($q['question'], 0, 50)) . '...'; ?></td>
            <td>
                <a href="edit_question.php?id=<?php echo $q['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="delete_question.php?id=<?php echo $q['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus soal ini?');">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="manage_packages.php" class="btn btn-secondary">Kembali ke Daftar Paket</a>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>