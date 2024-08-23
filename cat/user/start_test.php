<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Test.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/QuestionPackage.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['package_id'])) {
    header("Location: /pc/cat/user/take_test.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$test = new Test($db);
$package = new QuestionPackage($db);

$package_id = intval($_POST['package_id']);
$package_info = $package->getPackageById($package_id);

if (!$package_info) {
    die("Paket soal tidak ditemukan.");
}

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<div class="container mt-5">
    <h1 class="mb-4">Konfirmasi Memulai Tes</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($package_info['name']); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($package_info['description']); ?></p>
            <p><strong>Peringatan:</strong> Setelah Anda memulai tes, waktu akan berjalan dan Anda tidak dapat menghentikan atau mengulang tes.</p>
            <form id="startTestForm" method="POST" action="process_start_test.php">
                <input type="hidden" name="package_id" value="<?php echo $package_id; ?>">
                <button type="submit" class="btn btn-primary" id="startTestBtn">Mulai Tes</button>
                <a href="/pc/cat/user/take_test.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('startTestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    if (confirm('Apakah Anda yakin ingin memulai tes? Tes akan dimulai segera dan waktu akan berjalan.')) {
        this.submit();
    }
});
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>