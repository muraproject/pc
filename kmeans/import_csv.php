<?php
require_once 'config/database.php';
include 'includes/header.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["csvFile"]) && $_FILES["csvFile"]["error"] == 0) {
        $fileName = $_FILES["csvFile"]["tmp_name"];
        if (($handle = fopen($fileName, "r")) !== FALSE) {
            try {
                $pdo->beginTransaction();
                
                // Skip header row if exists
                fgetcsv($handle, 1000, ",");
                
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $nama = $data[0];
                    $ipk = $data[1];
                    $penghasilan_ayah = $data[2];
                    $penghasilan_ibu = $data[3] ?? '0 - 500.000'; // Default value if not provided
                    $angkatan = $data[4];

                    $sql = "INSERT INTO mahasiswa (nama, ipk, penghasilan_ayah, penghasilan_ibu, angkatan) 
                            VALUES (?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$nama, $ipk, $penghasilan_ayah, $penghasilan_ibu, $angkatan]);
                }
                
                $pdo->commit();
                $message = "Data berhasil diimpor.";
            } catch (Exception $e) {
                $pdo->rollBack();
                $message = "Error: " . $e->getMessage();
            }
            fclose($handle);
        } else {
            $message = "Tidak dapat membuka file.";
        }
    } else {
        $message = "Error: " . $_FILES["csvFile"]["error"];
    }
}
?>

<div class="container mt-4">
    <h2>Import Data Mahasiswa dari CSV</h2>
    
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="csvFile" class="form-label">Pilih file CSV:</label>
            <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required>
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
    </form>

    <div class="mt-4">
        <h4>Format CSV yang diharapkan:</h4>
        <p>Nama, IPK, Penghasilan Ayah, Penghasilan Ibu, Angkatan</p>
        <p>Contoh:</p>
        <pre>John Doe, 3.75, 500.000 - 999.999, 0 - 500.000, 2022/2023</pre>
    </div>
</div>

<?php include 'includes/footer.php'; ?>