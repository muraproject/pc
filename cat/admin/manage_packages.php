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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_package'])) {
        $package->addPackage($_POST['name'], $_POST['description']);
    } elseif (isset($_POST['delete_package'])) {
        $delete_id = intval($_POST['delete_package']);
        if ($package->deletePackage($delete_id)) {
            $success_message = "Paket soal berhasil dihapus.";
        } else {
            $error_message = "Gagal menghapus paket soal.";
        }
    }
}

$packages = $package->getAllPackages();

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Kelola Paket Soal</h1>

<?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<form method="POST" class="mb-4">
    <h2>Tambah Paket Soal Baru</h2>
    <div class="mb-3">
        <label for="name" class="form-label">Nama Paket</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="description" name="description"></textarea>
    </div>
    <a href="import_questions.php" class="btn btn-success">Impor Soal dari CSV</a>
    <button type="submit" name="add_package" class="btn btn-primary">Tambah Paket</button>
</form>

<h2>Daftar Paket Soal</h2>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($packages as $p): ?>
        <tr>
            <td><?php echo $p['id']; ?></td>
            <td><?php echo htmlspecialchars($p['name']); ?></td>
            <td><?php echo htmlspecialchars($p['description']); ?></td>
            <td>
                <a href="edit_package.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="manage_package_questions.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-info">Kelola Soal</a>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="delete_package" value="<?php echo $p['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus paket soal ini?');">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>