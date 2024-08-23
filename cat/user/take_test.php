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

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<div class="container mt-5">
    <h1 class="mb-4">Pilih Paket Soal</h1>
    <div class="row">
        <?php foreach ($available_packages as $pkg): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="https://fahum.umsu.ac.id/blog/wp-content/uploads/2024/08/cpns-2024-hal-yang-harus-dipersiapkan-dan-diperhatikan-1.webp" class="card-img-top" alt="<?php echo htmlspecialchars($pkg['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($pkg['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($pkg['description']); ?></p>
                    </div>
                    <div class="card-footer">
                        <form method="POST" action="start_test.php">
                            <input type="hidden" name="package_id" value="<?php echo $pkg['id']; ?>">
                            <button type="submit" name="start_test" class="btn btn-primary w-100">Mulai Tes</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>