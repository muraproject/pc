<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Booking Restoran</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .floating-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            text-align: center;
            line-height: 60px;
            font-size: 30px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <!-- Container wrapper -->  
        <div class="container-fluid">
            <!-- Navbar brand -->
            <a class="navbar-brand mt-2 mt-lg-0" href="/booking_restoran/">
                <h5 class="pt-1">Booking Restoran</h5>
            </a>
            <!-- Toggle button -->
            <button data-mdb-button-init class="navbar-toggler" type="button" data-mdb-collapse-init data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/booking_restoran">Project</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/booking_restoran/Profil.php">Profil</a>
                    </li>
                </ul>
                <!-- Left links -->
            </div>
            <div class="float-right me-3">
                    <!-- Icon -->
                
                    <a class="text-reset me-3 text-white float-right" href="logout.php">
                        <i class="fas fa-unlock text-white"></i>
                            Logout
                    </a>
                </div>
            <!-- Collapsible wrapper -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->

    <div class="container mt-5">
        <h2>Daftar Booking Restoran</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tanggal Booking</th>
                    <th>Nomor HP</th>
                    <th>Ayam Bakar</th>
                    <th>Ayam Goreng</th>
                    <th>Gurami Bakar</th>
                    <th>Gurami Goreng</th>
                    <th>Es Teh</th>
                    <th>Es Jeruk</th>
                    <th>Teh Hangat</th>
                    <th>Jeruk Hangat</th>
                    <th>Kopi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'koneksi.php';
                $result = mysqli_query($conn, "SELECT * FROM bookings");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['nama']}</td>
                        <td>{$row['tanggal_booking']}</td>
                        <td>{$row['nomor_hp']}</td>
                        <td>{$row['ayam_bakar']}</td>
                        <td>{$row['ayam_goreng']}</td>
                        <td>{$row['gurami_bakar']}</td>
                        <td>{$row['gurami_goreng']}</td>
                        <td>{$row['es_teh']}</td>
                        <td>{$row['es_jeruk']}</td>
                        <td>{$row['teh_hangat']}</td>
                        <td>{$row['jeruk_hangat']}</td>
                        <td>{$row['kopi']}</td>
                        <td>
                            <a href='edit_booking.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_booking.php?id={$row['id']}' class='btn btn-danger btn-sm'>Hapus</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <a href="create_booking.php" class="floating-btn">+</a>

    <!-- Footer -->
    <footer class="bg-primary text-center text-white">
        <!-- Grid container -->
        <div class="container p-4 pb-0">
            <!-- Section: Social media -->
            <section class="mb-4">
                <!-- Facebook -->
                <a data-mdb-ripple-init class="btn btn-outline-primary btn-floating m-1 border border-white" href="#!" role="button"><i class="fab fa-facebook-f text-white"></i></a>
                <!-- Twitter -->
                <a data-mdb-ripple-init class="btn btn-outline-primary btn-floating m-1 border border-white" href="#!" role="button"><i class="fab fa-twitter text-white"></i></a>
                <!-- Google -->
                <a data-mdb-ripple-init class="btn btn-outline-primary btn-floating m-1 border border-white" href="#!" role="button"><i class="fab fa-google text-white"></i></a>
                <!-- Instagram -->
                <a data-mdb-ripple-init class="btn btn-outline-primary btn-floating m-1 border border-white" href="#!" role="button"><i class="fab fa-instagram text-white"></i></a>
                <!-- Linkedin -->
                <a data-mdb-ripple-init class="btn btn-outline-primary btn-floating m-1 border border-white" href="#!" role="button"><i class="fab fa-linkedin-in text-white"></i></a>
                <!-- Github -->
                <a data-mdb-ripple-init class="btn btn-outline-primary btn-floating m-1 border border-white" href="#!" role="button"><i class="fab fa-github text-white"></i></a>
            </section>
            <!-- Section: Social media -->
        </div>
        <!-- Grid container -->

        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2024 Copyright:
            <a class="text-white" href="https://mdbootstrap.com/">A P R E L</a>
        </div>
        <!-- Copyright -->
    </footer>
    <!-- Footer -->

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
