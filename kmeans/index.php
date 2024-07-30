<?php
include 'includes/header.php';
include 'config/database.php';
include 'functions/mahasiswa_functions.php';

// Logika untuk mengambil data ringkasan untuk dashboard
$totalMahasiswa = getTotalMahasiswa($pdo);
$avgIPK = getAverageIPK($pdo);
// ... tambahkan logika lain sesuai kebutuhan

?>

<h1>Dashboard</h1>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Mahasiswa</h5>
                <p class="card-text"><?php echo $totalMahasiswa; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Rata-rata IPK</h5>
                <p class="card-text"><?php echo number_format($avgIPK, 2); ?></p>
            </div>
        </div>
    </div>
    <!-- Tambahkan card lain sesuai kebutuhan -->
</div>

<?php include 'includes/footer.php'; ?>