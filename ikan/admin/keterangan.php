<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Fungsi untuk mengupdate keterangan
function updateKeterangan($conn, $konten) {
    $stmt = $conn->prepare("UPDATE keterangan SET konten = ? WHERE id = 1");
    $stmt->bind_param("s", $konten);
    return $stmt->execute();
}

// Logika untuk menangani form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        updateKeterangan($conn, $_POST['konten']);
    }
}

// Ambil data keterangan
$result = $conn->query("SELECT konten FROM keterangan WHERE id = 1");
$keterangan = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Keterangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
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
                        <a class="nav-link active" href="keterangan.php">Keterangan</a>
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

    <div class="container mt-5">
        <h1>Edit Keterangan</h1>
        
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="konten" class="form-label">Keterangan</label>
                <textarea class="form-control" id="konten" name="konten" rows="10" required><?php echo $keterangan['konten']; ?></textarea>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Keterangan</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>