<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/QuestionPackage.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$package = new QuestionPackage($db);

$package_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$package_info = $package->getPackageById($package_id);

if (!$package_info) {
    die("Paket soal tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_package'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        if ($package->updatePackage($package_id, $name, $description)) {
            $success_message = "Paket soal berhasil diperbarui.";
            $package_info = $package->getPackageById($package_id); // Refresh data
        } else {
            $error_message = "Gagal memperbarui paket soal.";
        }
    }
}

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Edit Paket Soal</h1>

<?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Nama Paket</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($package_info['name']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($package_info['description']); ?></textarea>
    </div>
    <button type="submit" name="update_package" class="btn btn-primary">Perbarui Paket</button>
</form>

<a href="manage_packages.php" class="btn btn-secondary mt-3">Kembali ke Daftar Paket</a>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>