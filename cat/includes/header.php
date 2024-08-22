<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulasi CAT CPNS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/pc/cat/assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/pc/cat/">Simulasi CAT CPNS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/pc/cat/admin/dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/pc/cat/admin/manage_users.php">Kelola Pengguna</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/pc/cat/admin/manage_questions.php">Kelola Pertanyaan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/pc/cat/admin/view_results.php">Lihat Hasil</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/pc/cat/user/take_test.php">Ambil Tes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/pc/cat/user/history.php">Riwayat Tes</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/pc/cat/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/pc/cat/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/pc/cat/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">