<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$users = $user->getAllUsers();

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Kelola Pengguna</h1>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?php echo $u['id']; ?></td>
            <td><?php echo htmlspecialchars($u['username']); ?></td>
            <td><?php echo htmlspecialchars($u['email']); ?></td>
            <td><?php echo $u['role']; ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="delete_user.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="add_user.php" class="btn btn-success">Tambah Pengguna Baru</a>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>