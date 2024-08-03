<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin Panel'; ?> - Sistem Pakar Diagnosa Penyakit Ikan Nila</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        /* Tambahkan custom CSS di sini jika diperlukan */
        html, body {
    height: 100%;
}

body {
    display: flex;
    flex-direction: column;
}

main {
    flex: 1 0 auto;
}

.footer {
    flex-shrink: 0;
    background-color: #f8f9fa;
    padding-top: 20px;
    padding-bottom: 20px;
    border-top: 1px solid #e7e7e7;
}

.footer h5 {
    color: #333;
    font-weight: bold;
    margin-bottom: 15px;
}

.footer ul {
    padding-left: 0;
}

.footer ul li {
    margin-bottom: 8px;
}

.footer a {
    color: #6c757d;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer a:hover {
    color: #007bff;
    text-decoration: underline;
}

.footer hr {
    margin: 20px 0;
    border-top-color: #e7e7e7;
}

.footer .text-muted {
    font-size: 0.9rem;
}

.floating-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
        }
        .navbar-brand img {
            margin-right: 10px;
            height: 80px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
           <a class="navbar-brand" href="dashboard.php">
                <img src="https://i.pinimg.com/originals/92/61/b3/9261b3445438ca96b8ecec445171704b.gif" alt="Ikan Nila">
                Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activePage == 'dashboard' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activePage == 'gejala' ? 'active' : ''; ?>" href="gejala.php">Gejala</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activePage == 'penyakit' ? 'active' : ''; ?>" href="penyakit.php">Penyakit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activePage == 'cf_pakar' ? 'active' : ''; ?>" href="cf_pakar.php">CF Pakar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activePage == 'histori' ? 'active' : ''; ?>" href="histori.php">Histori Diagnosa</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 flex-shrink-0">