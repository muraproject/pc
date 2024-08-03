<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Klasifikasi Beasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .sidebar {
            background-color: #1e2a78; /* Warna biru tua untuk sidebar */
            color: #ffffff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            width: 250px;
            z-index: 1000;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 10px 20px;
            margin-bottom: 5px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: #ffffff;
        }

        .sidebar-logo {
        text-align: center;
        padding: 20px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .sidebar-logo img {
        max-width: 80%;
        height: auto;
    }
        .main-content {
            margin-left: 250px;
        }
        .content {
            padding: 20px;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            border-top: none;
            font-weight: 600;
        }
        .table td, .table th {
            padding: 1rem;
            vertical-align: middle;
        }
        .btn-link {
            padding: 0.25rem 0.5rem;
        }
        .btn-link:hover {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="sidebar">
    <div class="sidebar-logo">
            <img src="https://umpo.ac.id/web-con/app/app-upload/images/files/1686106386-UMPO-logo-resmi.png" alt="UMPO Logo">
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/index.php') ? 'active' : ''; ?>" href="index.php">
                    <i class="fas fa-home me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/data_mahasiswa.php') ? 'active' : ''; ?>" href="data_mahasiswa.php">
                    <i class="fas fa-user-graduate me-2"></i>
                    Data Mahasiswa
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/hasil_clustering.php') ? 'active' : ''; ?>" href="hasil_clustering.php">
                    <i class="fas fa-chart-pie me-2"></i>
                    Hasil Clustering
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Sistem Beasiswa</a>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout (<?php echo $_SESSION['username']; ?>)
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="content">