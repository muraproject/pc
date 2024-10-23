<?php
require_once 'includes/db_connect.php';

$is_logged_in = false;
$login_error = '';
$success_message = '';
$error_message = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    
    $sql = "SELECT * FROM users WHERE username = ? AND password = ? AND user_type = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $is_logged_in = true;
    } else {
        $login_error = "Username atau password salah, atau Anda bukan admin!";
    }
    $stmt->close();
}

// Handle change password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    $admin_username = $conn->real_escape_string($_POST['admin_username']);
    $admin_password = $conn->real_escape_string($_POST['admin_password']);
    
    // Verifikasi admin lagi
    $sql = "SELECT * FROM users WHERE username = ? AND password = ? AND user_type = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $admin_username, $admin_password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $username = $conn->real_escape_string($_POST['user_to_change']);
        $new_password = $conn->real_escape_string($_POST['new_password']);
        
        $sql = "UPDATE users SET password = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $new_password, $username);
        
        if ($stmt->execute()) {
            $success_message = "Password untuk user '$username' berhasil diubah!";
            $is_logged_in = true;  // Tetap tampilkan form change password
        } else {
            $error_message = "Gagal mengubah password: " . $conn->error;
            $is_logged_in = false;
        }
    } else {
        $error_message = "Kredensial admin tidak valid!";
        $is_logged_in = false;
    }
    $stmt->close();
}

// Get users list if logged in
$users = [];
if ($is_logged_in) {
    $sql = "SELECT username FROM users";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $users[] = $row['username'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .password-mismatch {
            border-color: #dc3545;
            background-color: #fff8f8;
        }
        .password-match {
            border-color: #28a745;
            background-color: #f8fff8;
        }
        .validation-message {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .card {
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if (!$is_logged_in): ?>
                <!-- Login Form -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Login Admin</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($login_error): ?>
                            <div class="alert alert-danger"><?php echo $login_error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="login">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                </div>
                <?php else: ?>
                <!-- Change Password Form -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Ubah Password User</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($success_message): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="" id="changePasswordForm">
                            <input type="hidden" name="action" value="change_password">
                            <input type="hidden" name="admin_username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                            <input type="hidden" name="admin_password" value="<?php echo htmlspecialchars($_POST['password'] ?? ''); ?>">
                            
                            <div class="form-group">
                                <label for="user_to_change">Pilih Username</label>
                                <select class="form-control" id="user_to_change" name="user_to_change" required>
                                    <option value="">Pilih username...</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?php echo htmlspecialchars($user); ?>">
                                            <?php echo htmlspecialchars($user); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="new_password">Password Baru</label>
                                <input type="password" class="form-control" id="new_password" 
                                       name="new_password" required>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="confirm_password" 
                                       name="confirm_password" required>
                                <div class="validation-message text-danger" id="password-validation-message"></div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block" id="submit-btn" disabled>
                                Ubah Password
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Real-time password validation
            $('#new_password, #confirm_password').on('input', function() {
                const newPassword = $('#new_password').val();
                const confirmPassword = $('#confirm_password').val();
                const validationMessage = $('#password-validation-message');
                const submitBtn = $('#submit-btn');
                
                if (newPassword === '' || confirmPassword === '') {
                    validationMessage.text('');
                    $('#confirm_password').removeClass('password-match password-mismatch');
                    submitBtn.prop('disabled', true);
                    return;
                }
                
                if (newPassword === confirmPassword) {
                    validationMessage.text('Password cocok!').removeClass('text-danger').addClass('text-success');
                    $('#confirm_password').removeClass('password-mismatch').addClass('password-match');
                    submitBtn.prop('disabled', false);
                } else {
                    validationMessage.text('Password tidak cocok!').removeClass('text-success').addClass('text-danger');
                    $('#confirm_password').removeClass('password-match').addClass('password-mismatch');
                    submitBtn.prop('disabled', true);
                }
            });

            // Form validation before submit
            $('#changePasswordForm').on('submit', function(e) {
                const newPassword = $('#new_password').val();
                const confirmPassword = $('#confirm_password').val();

                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('Password baru dan konfirmasi password tidak cocok!');
                    return false;
                }

                if (newPassword.length < 6) {
                    e.preventDefault();
                    alert('Password harus minimal 6 karakter!');
                    return false;
                }
            });
        });
    </script>
</body>
</html>