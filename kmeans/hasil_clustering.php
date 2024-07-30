<?php
require_once 'config/database.php';
require_once 'functions/clustering_functions.php';
include 'includes/header.php';

// Jika tombol "Mulai Clustering" ditekan
if (isset($_POST['start_clustering'])) {
    performClustering($pdo);
}

// Ambil hasil clustering
$hasil_clustering = getClusteringResults($pdo);
$cluster_summary = getClusterSummary($pdo);
$last_clustering_date = getLastClusteringDate($pdo);
?>

<div class="container mt-4">
    <h2>Hasil Clustering</h2>
    
    <div class="mb-4">
        <p>Clustering terakhir dilakukan pada: <?php echo $last_clustering_date; ?></p>
        <form method="POST">
            <button type="submit" name="start_clustering" class="btn btn-primary">Mulai Clustering</button>
        </form>
    </div>

    <h3>Ringkasan Cluster</h3>
    <table class="table table-bordered mb-4">
        <thead>
            <tr>
                <th>Cluster</th>
                <th>Keterangan</th>
                <th>Jumlah Mahasiswa</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cluster_summary as $summary): ?>
            <tr>
                <td><?php echo htmlspecialchars($summary['nama']); ?></td>
                <td><?php echo htmlspecialchars($summary['keterangan']); ?></td>
                <td><?php echo $summary['count']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Detail Hasil Clustering</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>IPK</th>
                <th>Penghasilan Ayah</th>
                <th>Penghasilan Ibu</th>
                <th>Angkatan</th>
                <th>Cluster</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hasil_clustering as $hasil): ?>
            <tr>
                <td><?php echo htmlspecialchars($hasil['nama']); ?></td>
                <td><?php echo number_format($hasil['ipk'], 2); ?></td>
                <td><?php echo htmlspecialchars($hasil['penghasilan_ayah']); ?></td>
                <td><?php echo htmlspecialchars($hasil['penghasilan_ibu']); ?></td>
                <td><?php echo htmlspecialchars($hasil['angkatan']); ?></td>
                <td><?php echo htmlspecialchars($hasil['cluster_nama']); ?></td>
                <td><?php echo htmlspecialchars($hasil['keterangan']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>