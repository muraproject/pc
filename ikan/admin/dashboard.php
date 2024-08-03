<?php
include 'header.php';

require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Ambil jumlah data dari setiap tabel
$gejala_count = $conn->query("SELECT COUNT(*) FROM gejala")->fetch_row()[0];
$penyakit_count = $conn->query("SELECT COUNT(*) FROM penyakit")->fetch_row()[0];
$cf_pakar_count = $conn->query("SELECT COUNT(*) FROM cf_pakar")->fetch_row()[0];
?>


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
        <?php include 'footer.php'; ?>