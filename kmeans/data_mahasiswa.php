<?php
require_once 'config/database.php';
require_once 'functions/mahasiswa_functions.php';
include 'includes/header.php';

// Proses penghapusan data jika ada
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (deleteMahasiswa($pdo, $id)) {
        echo "<div class='alert alert-success'>Data mahasiswa berhasil dihapus.</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menghapus data mahasiswa. Silakan coba lagi.</div>";
    }
}

// Mengambil semua data mahasiswa
$mahasiswa = getAllMahasiswa($pdo);
?>

<div class="container mt-4">
    <h2>Data Mahasiswa</h2>
    <a href="tambah_mahasiswa.php" class="btn btn-primary mb-3">Tambah Mahasiswa</a>
    
    <table class="table table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>IPK</th>
            <th>Penghasilan Ayah</th>
            <th>Penghasilan Ibu</th>
            <th>Angkatan</th>
            <th>Jumlah Tanggungan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1; // Inisialisasi nomor urut
        foreach ($mahasiswa as $mhs): 
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($mhs['nama']); ?></td>
            <td><?php echo number_format($mhs['ipk'], 2); ?></td>
            <td><?php echo htmlspecialchars($mhs['penghasilan_ayah']); ?></td>
            <td><?php echo htmlspecialchars($mhs['penghasilan_ibu']); ?></td>
            <td><?php echo htmlspecialchars($mhs['angkatan']); ?></td>
            <td><?php echo $mhs['jumlah_tanggungan']; ?></td>
            <td>
                <a href="edit_mahasiswa.php?id=<?php echo $mhs['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="?delete=<?php echo $mhs['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<?php include 'includes/footer.php'; ?>