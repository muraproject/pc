<?php
session_start();
// Periksa apakah user sudah login sebagai admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/database.php';
require_once '../classes/User.php';
require_once '../classes/Question.php';

$userObj = new User($conn);
$questionObj = new Question($conn);

$totalUsers = $userObj->getTotalUsers();
$totalQuestions = $questionObj->getTotalQuestions();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Simulasi CAT CPNS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h1>Admin Dashboard</h1>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Pengguna</h5>
                        <p class="card-text display-4"><?php echo $totalUsers; ?></p>
                        <a href="manage_users.php" class="btn btn-primary">Kelola Pengguna</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Pertanyaan</h5>
                        <p class="card-text display-4"><?php echo $totalQuestions; ?></p>
                        <a href="manage_questions.php" class="btn btn-primary">Kelola Pertanyaan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/custom.js"></script>
</body>
</html>
