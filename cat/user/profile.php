<?php
session_start();
require_once '../config/database.php';
require_once '../classes/User.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$user_data = $user->getUserById($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    if ($user->updateUser($_SESSION['user_id'], $username, $email, $user_data['role'])) {
        $success_message = "Profil berhasil diperbarui.";
        $user_data = $user->getUserById($_SESSION['user_id']); // Refresh user data
    } else {
        $error_message = "Gagal memperbarui profil. Silakan coba lagi.";
    }
}

include '../includes/header.php';
?>

<h1>Edit Profil</h1>

<?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Perbarui Profil</button>
</form>

<?php include '../includes/footer.php'; ?>
