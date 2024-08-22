<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Question.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Test.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/QuestionPackage.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$question = new Question($db);
$test = new Test($db);
$package = new QuestionPackage($db);

// Get available packages
$available_packages = $package->getAllPackages();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['start_test'])) {
    $package_id = $_POST['package_id'];
    $test_id = $test->startTest($_SESSION['user_id']);
    $test->assignPackageToTest($test_id, $package_id);
    $questions = $question->getQuestionsByPackage($package_id);
    // Redirect to actual test page
    header("Location: test_page.php?test_id=" . $test_id);
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Pilih Paket Soal</h1>

<form method="POST">
    <div class="mb-3">
        <label for="package_id" class="form-label">Pilih Paket Soal</label>
        <select class="form-select" id="package_id" name="package_id" required>
            <?php foreach ($available_packages as $pkg): ?>
                <option value="<?php echo $pkg['id']; ?>"><?php echo htmlspecialchars($pkg['name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" name="start_test" class="btn btn-primary">Mulai Tes</button>
</form>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>