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
    <a href="tambah_mahasiswa.php" class="btn btn-primary mb-3">Tambah Mahasiswa</a>

    
    <div class="card">
    <div class="card-body">
        <h3 class="card-title mb-4">Data Mahasiswa</h3>
        <div class="table-responsive">
            <table class="table table-hover" id="mahasiswaTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>IPK</th>
                        <th>Penghasilan Ayah</th>
                        <th>Penghasilan Ibu</th>
                        <th>Jumlah Tanggungan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mahasiswa as $index => $mhs): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($mhs['nama']); ?></td>
                        <td><?php echo number_format($mhs['ipk'], 2); ?></td>
                        <td><?php echo htmlspecialchars($mhs['penghasilan_ayah']); ?></td>
                        <td><?php echo htmlspecialchars($mhs['penghasilan_ibu']); ?></td>
                        <td><?php echo $mhs['jumlah_tanggungan']; ?></td>
                        <td>
                            <a href="edit_mahasiswa.php?id=<?php echo $mhs['id']; ?>" class="btn btn-sm btn-link text-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?delete=<?php echo $mhs['id']; ?>" class="btn btn-sm btn-link text-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center" id="pagination">
            </ul>
        </nav>
    </div>
</div>
</div>
</div>
        
    
</div>

<?php include 'includes/footer.php'; ?>