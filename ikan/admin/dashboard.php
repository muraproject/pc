<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Ambil jumlah data dari setiap tabel
$gejala_count = $conn->query("SELECT COUNT(*) FROM gejala")->fetch_row()[0];
$penyakit_count = $conn->query("SELECT COUNT(*) FROM penyakit")->fetch_row()[0];
$cf_pakar_count = $conn->query("SELECT COUNT(*) FROM cf_pakar")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
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
}</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column h-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    
        <div class="container">
        <a class="navbar-brand" href="#">
        <img src="https://i.pinimg.com/originals/92/61/b3/9261b3445438ca96b8ecec445171704b.gif" alt="..." height="75">
        </a>
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="gejala.php">Gejala</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="penyakit.php">Penyakit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cf_pakar.php">CF Pakar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="keterangan.php">Keterangan</a>
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
        <h1>Dashboard</h1>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gejala</h5>
                        <p class="card-text">Jumlah: <?php echo $gejala_count; ?></p>
                        <a href="gejala.php" class="btn btn-primary">Kelola Gejala</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Penyakit</h5>
                        <p class="card-text">Jumlah: <?php echo $penyakit_count; ?></p>
                        <a href="penyakit.php" class="btn btn-primary">Kelola Penyakit</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">CF Pakar</h5>
                        <p class="card-text">Jumlah: <?php echo $cf_pakar_count; ?></p>
                        <a href="cf_pakar.php" class="btn btn-primary">Kelola CF Pakar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <h5>Tentang Kami</h5>
                <p class="text-muted">Sistem Pakar Diagnosa Penyakit Ikan Nila menggunakan metode Certainty Factor.</p>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <h5>Tautan Cepat</h5>
                <ul class="list-unstyled">
                    <li><a href="index.html" class="text-muted">Beranda</a></li>
                    <li><a href="#" class="text-muted">Diagnosa</a></li>
                    <li><a href="#" class="text-muted">Informasi Penyakit</a></li>
                    <li><a href="#" class="text-muted">Kontak</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Hubungi Kami</h5>
                <ul class="list-unstyled text-muted">
                    <li>Email: info@diagnosaikan.com</li>
                    <li>Telepon: (021) 1234-5678</li>
                    <li>Alamat: Jl. Ikan Sehat No. 123, Jakarta</li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12 text-center">
                <p class="text-muted">&copy; 2024 Sistem Pakar Diagnosa Ikan Nila. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>