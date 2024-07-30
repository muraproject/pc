\<?php
include 'includes/header.php';
include 'config/database.php';
include 'functions/mahasiswa_functions.php';

$mahasiswa = getAllMahasiswa($pdo);
?>

<h1>Data Mahasiswa</h1>
<a href="tambah_mahasiswa.php" class="btn btn-primary mb-3">Tambah Mahasiswa</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>IPK</th>
            <th>Penghasilan Ayah</th>
            <th>Penghasilan Ibu</th>
            <th>Angkatan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mahasiswa as $mhs): ?>
        <tr>
            <td><?php echo $mhs['id']; ?></td>
            <td><?php echo $mhs['nama']; ?></td>
            <td><?php echo $mhs['ipk']; ?></td>
            <td><?php echo $mhs['penghasilan_ayah']; ?></td>
            <td><?php echo $mhs['penghasilan_ibu']; ?></td>
            <td><?php echo $mhs['angkatan']; ?></td>
            <td>
                <a href="edit_mahasiswa.php?id=<?php echo $mhs['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="hapus_mahasiswa.php?id=<?php echo $mhs['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>