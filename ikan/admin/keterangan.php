<?php
include 'header.php';

require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Fungsi untuk mengupdate keterangan
function updateKeterangan($conn, $konten) {
    $stmt = $conn->prepare("UPDATE keterangan SET konten = ? WHERE id = 1");
    $stmt->bind_param("s", $konten);
    return $stmt->execute();
}

// Logika untuk menangani form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        updateKeterangan($conn, $_POST['konten']);
    }
}

// Ambil data keterangan
$result = $conn->query("SELECT konten FROM keterangan WHERE id = 1");
$keterangan = $result->fetch_assoc();
?>

        <h1>Edit Keterangan</h1>
        
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="konten" class="form-label">Keterangan</label>
                <textarea class="form-control" id="konten" name="konten" rows="10" required><?php echo $keterangan['konten']; ?></textarea>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Keterangan</button>
        </form>
        <?php include 'footer.php'; ?>