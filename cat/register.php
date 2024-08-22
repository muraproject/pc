<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';

if (isset($_SESSION['user_id'])) {
    header("Location: /pc/cat/user/index.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error_message = "Password tidak cocok.";
    } else {
        if ($user->register($username, $email, $password)) {
            $success_message = "Registrasi berhasil. Silakan login.";
        } else {
            $error_message = "Registrasi gagal. Silakan coba lagi.";
        }
    }
}

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Registrasi</h1>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
    </div>
    <button type="submit" class="btn btn-primary">Daftar</button>
</form>

<p class="mt-3">Sudah punya akun? <a href="login.php">Login di sini</a></p>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>
