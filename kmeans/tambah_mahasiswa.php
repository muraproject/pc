<?php
include 'includes/header.php';
include 'config/database.php';
include 'functions/mahasiswa_functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nama' => $_POST['nama'],
        'ipk' => $_POST['ipk'],
        'penghasilan_ayah' => $_POST['penghasilan_ayah'],
        'penghasilan_ibu' => $_POST['penghasilan_ibu'],
        'angkatan' => $_POST['angkatan'],
        'jumlah_tanggungan' => $_POST['jumlah_tanggungan'],
        'prestasi' => $_POST['prestasi']
    ];

    if (addMahasiswa($pdo, $data)) {
        header("Location: data_mahasiswa.php");
        exit;
    } else {
        $error = "Gagal menambahkan data mahasiswa";
    }
}
?>

<h2>Tambah Mahasiswa</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form action="" method="POST">
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama" name="nama" required>
    </div>
    <div class="mb-3">
        <label for="ipk" class="form-label">IPK</label>
        <input type="number" step="0.01" class="form-control" id="ipk" name="ipk" required>
    </div>
    <div class="mb-3">
        <label for="penghasilan_ayah" class="form-label">Penghasilan Ayah</label>
        <select class="form-select" id="penghasilan_ayah" name="penghasilan_ayah" required>
            <option value="0 - 500.000">0 - 500.000</option>
            <option value="500.000 - 999.999">500.000 - 999.999</option>
            <option value="1.000.000 - 1.999.999">1.000.000 - 1.999.999</option>
            <option value="2.000.000+">2.000.000+</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="penghasilan_ibu" class="form-label">Penghasilan Ibu</label>
        <select class="form-select" id="penghasilan_ibu" name="penghasilan_ibu" required>
            <option value="0 - 500.000">0 - 500.000</option>
            <option value="500.000 - 999.999">500.000 - 999.999</option>
            <option value="1.000.000 - 1.999.999">1.000.000 - 1.999.999</option>
            <option value="2.000.000+">2.000.000+</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="angkatan" class="form-label">Angkatan</label>
        <input type="text" class="form-control" id="angkatan" name="angkatan" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<?php include 'includes/footer.php'; ?>