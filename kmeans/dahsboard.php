<?php
include '/includes/header.php';
include '/config/database.php';
include '/functions/mahasiswa_functions.php';
include '/functions/clustering_functions.php';

// Mengambil data untuk dashboard
$totalMahasiswa = getTotalMahasiswa($pdo);
$avgIPK = getAverageIPK($pdo);
$clusterSummary = getClusterSummary($pdo);
$lastClusteringDate = getLastClusteringDate($pdo);

// Mengambil data untuk grafik distribusi IPK
$ipkDistribution = getIPKDistribution($pdo);

// Mengambil data untuk grafik distribusi penghasilan
$penghasilanDistribution = getPenghasilanDistribution($pdo);
?>

<div class="container mt-4">
    <h1 class="mb-4">Dashboard Sistem Klasifikasi Beasiswa</h1>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Mahasiswa</h5>
                    <p class="card-text display-4"><?php echo $totalMahasiswa; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Rata-rata IPK</h5>
                    <p class="card-text display-4"><?php echo number_format($avgIPK, 2); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Terakhir Clustering</h5>
                    <p class="card-text"><?php echo $lastClusteringDate; ?></p>
                    <a href="hasil_clustering.php" class="btn btn-primary">Lihat Hasil</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Distribusi Cluster</h5>
                    <canvas id="clusterChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ringkasan Cluster</h5>
                    <ul class="list-group">
                        <?php foreach ($clusterSummary as $cluster): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo $cluster['nama']; ?> - <?php echo $cluster['keterangan']; ?>
                            <span class="badge bg-primary rounded-pill"><?php echo $cluster['count']; ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Distribusi IPK</h5>
                    <canvas id="ipkChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Distribusi Penghasilan Orang Tua</h5>
                    <canvas id="penghasilanChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Cluster
    var ctxCluster = document.getElementById('clusterChart').getContext('2d');
    var clusterChart = new Chart(ctxCluster, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode(array_column($clusterSummary, 'nama')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($clusterSummary, 'count')); ?>,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Distribusi Cluster'
            }
        }
    });

    // Grafik IPK
    var ctxIPK = document.getElementById('ipkChart').getContext('2d');
    var ipkChart = new Chart(ctxIPK, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_keys($ipkDistribution)); ?>,
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: <?php echo json_encode(array_values($ipkDistribution)); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            responsive: true,
            title: {
                display: true,
                text: 'Distribusi IPK'
            }
        }
    });

    // Grafik Penghasilan
    var ctxPenghasilan = document.getElementById('penghasilanChart').getContext('2d');
    var penghasilanChart = new Chart(ctxPenghasilan, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_keys($penghasilanDistribution)); ?>,
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: <?php echo json_encode(array_values($penghasilanDistribution)); ?>,
                backgroundColor: 'rgba(255, 206, 86, 0.5)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            responsive: true,
            title: {
                display: true,
                text: 'Distribusi Penghasilan Orang Tua'
            }
        }
    });
</script>

<?php include 'includes/footer.php'; ?>