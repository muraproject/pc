<?php
session_start();
require_once 'config/database.php';
require_once 'classes/User.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: /pc/cat/admin/dashboard.php");
    } else {
        header("Location: /pc/cat/user/index.php");
    }
    exit();
}

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Cek cookie untuk "Remember Me"
if (isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    $userData = $user->getUserByRememberToken($token);
    if ($userData) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['role'] = $userData['role'];
        
        // Redirect sesuai role
        if ($_SESSION['role'] === 'admin') {
            header("Location: /pc/cat/admin/dashboard.php");
        } else {
            header("Location: /pc/cat/user/index.php");
        }
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? $_POST['remember'] : '';

    $logged_in_user = $user->login($username, $password);

    if ($logged_in_user) {
        $_SESSION['user_id'] = $logged_in_user['id'];
        $_SESSION['username'] = $logged_in_user['username'];
        $_SESSION['role'] = $logged_in_user['role'];

        if ($remember == 'on') {
            $token = bin2hex(random_bytes(16));
            $user->storeRememberToken($logged_in_user['id'], $token);
            setcookie('remember_me', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
        }

        // Redirect sesuai role
        if ($_SESSION['role'] === 'admin') {
            header("Location: /pc/cat/admin/dashboard.php");
        } else {
            header("Location: /pc/cat/user/index.php");
        }
        exit();
    } else {
        $error_message = "Username atau password salah.";
    }
}

// HTML form login di sini
include 'includes/header.php';
?>

<h2>Login</h2>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<form method="POST" action="">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">Remember Me</label>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>

<?php include 'includes/footer.php'; ?>