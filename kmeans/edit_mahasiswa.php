<?php
include 'includes/header.php';
include 'config/database.php';
include 'functions/mahasiswa_functions.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: data_mahasiswa.php");
    exit;
}

$mahasiswa = getMahasiswaById($pdo, $id);

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

    if (updateMahasiswa($pdo, $id, $data)) {
        header("Location: data_mahasiswa.php");
        exit;
    } else {
        $error = "Gagal mengupdate data mahasiswa";
    }
}
?>

<h2>Edit Mahasiswa</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form action="" method="POST">
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $mahasiswa['nama']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="ipk" class="form-label">IPK</label>
        <input type="number" step="0.01" class="form-control" id="ipk" name="ipk" value="<?php echo $mahasiswa['ipk']; ?>" required>
    </div>
    <div class="mb-3">
    <label for="penghasilan_ayah" class="form-label">Penghasilan Ayah</label>
    <select class="form-select" id="penghasilan_ayah" name="penghasilan_ayah" required>
    <option value="0 - 500.000" <?php echo ($mahasiswa['penghasilan_ayah'] == '0 - 500.000') ? 'selected' : ''; ?>>0 - 500.000</option>
    <option value="500.000 - 999.999" <?php echo ($mahasiswa['penghasilan_ayah'] == '500.000 - 999.999') ? 'selected' : ''; ?>>500.000 - 999.999</option>
    <option value="1.000.000 - 1.999.999" <?php echo ($mahasiswa['penghasilan_ayah'] == '1.000.000 - 1.999.999') ? 'selected' : ''; ?>>1.000.000 - 1.999.999</option>
    <option value="2.000.000 - 4.999.999" <?php echo ($mahasiswa['penghasilan_ayah'] == '2.000.000 - 4.999.999') ? 'selected' : ''; ?>>2.000.000 - 4.999.999</option>
    <option value="> 5.000.000" <?php echo ($mahasiswa['penghasilan_ayah'] == '> 5.000.000') ? 'selected' : ''; ?>>> 5.000.000</option>
</select>
</div>

<div class="mb-3">
    <label for="penghasilan_ibu" class="form-label">Penghasilan Ibu</label>
    <select class="form-select" id="penghasilan_ibu" name="penghasilan_ibu" required>
    <option value="0 - 500.000" <?php echo ($mahasiswa['penghasilan_ibu'] == '0 - 500.000') ? 'selected' : ''; ?>>0 - 500.000</option>
    <option value="500.000 - 999.999" <?php echo ($mahasiswa['penghasilan_ibu'] == '500.000 - 999.999') ? 'selected' : ''; ?>>500.000 - 999.999</option>
    <option value="1.000.000 - 1.999.999" <?php echo ($mahasiswa['penghasilan_ibu'] == '1.000.000 - 1.999.999') ? 'selected' : ''; ?>>1.000.000 - 1.999.999</option>
    <option value="2.000.000 - 4.999.999" <?php echo ($mahasiswa['penghasilan_ibu'] == '2.000.000 - 4.999.999') ? 'selected' : ''; ?>>2.000.000 - 4.999.999</option>
    <option value="> 5.000.000" <?php echo ($mahasiswa['penghasilan_ibu'] == '> 5.000.000') ? 'selected' : ''; ?>>> 5.000.000</option>
</select>
</div>
    <div class="mb-3">
        <label for="angkatan" class="form-label">Angkatan</label>
        <input type="text" class="form-control" id="angkatan" name="angkatan" value="<?php echo $mahasiswa['angkatan']; ?>" required>
    </div>
    <div class="mb-3">
    <label for="jumlah_tanggungan" class="form-label">Jumlah Tanggungan</label>
    <input type="number" class="form-control" id="jumlah_tanggungan" name="jumlah_tanggungan" value="<?php echo $mahasiswa['jumlah_tanggungan']; ?>" required min="0" max="10">
</div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

<?php include 'includes/footer.php'; ?>