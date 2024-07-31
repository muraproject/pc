<?php
require_once 'config/database.php';
include 'includes/header.php';

$message = '';

function validatePenghasilan($penghasilan) {
    $valid_options = ['0 - 500.000', '500.000 - 999.999', '1.000.000 - 1.999.999', '2.000.000 - 4.999.999', '> 5.000.000'];
    return in_array($penghasilan, $valid_options) ? $penghasilan : '0 - 500.000';
}

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
                    $ipk = floatval($data[1]);
                    $penghasilan_ayah = validatePenghasilan($data[2]);
                    $penghasilan_ibu = validatePenghasilan($data[3]);
                    $angkatan = $data[4];
                    $jumlah_tanggungan = intval($data[5]);

                    $sql = "INSERT INTO mahasiswa (nama, ipk, penghasilan_ayah, penghasilan_ibu, angkatan, jumlah_tanggungan) 
                            VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$nama, $ipk, $penghasilan_ayah, $penghasilan_ibu, $angkatan, $jumlah_tanggungan]);
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
        <p>Nama, IPK, Penghasilan Ayah, Penghasilan Ibu, Angkatan, Jumlah Tanggungan</p>
        <p>Contoh:</p>
        <pre>John Doe, 3.75, 1.000.000 - 1.999.999, 500.000 - 999.999, 2022/2023, 2</pre>
        <p>Opsi penghasilan yang valid:</p>
        <ul>
            <li>0 - 500.000</li>
            <li>500.000 - 999.999</li>
            <li>1.000.000 - 1.999.999</li>
            <li>2.000.000 - 4.999.999</li>
            <li>> 5.000.000</li>
        </ul>
    </div>
</div>

<?php include 'includes/footer.php'; ?>