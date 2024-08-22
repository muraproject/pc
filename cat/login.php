<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// Fungsi untuk mengalihkan berdasarkan peran
function redirectBasedOnRole($role) {
    if ($role === 'admin') {
        header("Location: /pc/cat/admin/dashboard.php");
    } else {
        header("Location: /pc/cat/user/index.php");
    }
    exit();
}

// Debug: Tampilkan isi session sebelum login
echo "<pre>Session before login: "; print_r($_SESSION); echo "</pre>";

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $logged_in_user = $user->login($username, $password);

    // Debug: Tampilkan hasil login
    echo "<pre>Login result: "; print_r($logged_in_user); echo "</pre>";

    if ($logged_in_user) {
        $_SESSION['user_id'] = $logged_in_user['id'];
        $_SESSION['username'] = $logged_in_user['username'];
        $_SESSION['role'] = $logged_in_user['role'];

        // Debug: Tampilkan isi session setelah login
        echo "<pre>Session after login: "; print_r($_SESSION); echo "</pre>";

        redirectBasedOnRole($logged_in_user['role']);
    } else {
        $error_message = "Username atau password salah.";
    }
}

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
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
    <button type="submit" class="btn btn-primary">Login</button>
</form>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>