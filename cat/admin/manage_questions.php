<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Debug: Tampilkan isi session
echo "<pre>Session in manage_questions.php: "; print_r($_SESSION); echo "</pre>";

require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Question.php';

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Redirecting to login from manage_questions.php...";
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$question = new Question($db);

// Ambil semua pertanyaan
$questions = $question->getAllQuestions();

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<!-- Tampilkan daftar pertanyaan dan form untuk menambah/edit pertanyaan di sini -->

<!-- <?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?> -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pertanyaan - Simulasi CAT CPNS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <!-- <?php include '../includes/header.php'; ?> -->

    <div class="container mt-4">
        <h1>Kelola Pertanyaan</h1>
        
        <!-- Form untuk menambah pertanyaan baru -->
        <form method="POST" class="mb-4">
            <h2>Tambah Pertanyaan Baru</h2>
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
            <div class="mb-3">
                <label for="category" class="form-label">Kategori</label>
                <input type="text" class="form-control" id="category" name="category" required>
            </div>
            <button type="submit" name="add_question" class="btn btn-primary">Tambah Pertanyaan</button>
        </form>

        <!-- Tabel untuk menampilkan dan mengedit pertanyaan -->
        <h2>Daftar Pertanyaan</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pertanyaan</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $question): ?>
                <tr>
                    <td><?php echo $question['id']; ?></td>
                    <td><?php echo $question['question']; ?></td>
                    <td><?php echo $question['category']; ?></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editQuestion(<?php echo $question['id']; ?>)">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteQuestion(<?php echo $question['id']; ?>)">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/custom.js"></script>
    <script>
        function editQuestion(id) {
            // Implementasi fungsi edit
        }

        function deleteQuestion(id) {
            // Implementasi fungsi delete
        }
    </script>
</body>
</html>