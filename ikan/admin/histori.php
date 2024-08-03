<?php


require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Ambil data histori dari database
$query = "SELECT * FROM hasil_diagnosa ORDER BY waktu DESC";
$result = $conn->query($query);
include 'header.php';

?>


        <h1 class="mb-4">Histori Diagnosa</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Waktu</th>
                    <th>Penyakit</th>
                    <th>Persentase</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter =0; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php $counter= $counter+1; echo $counter; ?></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                    <td><?php echo $row['waktu']; ?></td>
                    <td><?php echo htmlspecialchars($row['penyakit']); ?></td>
                    <td><?php echo $row['persentase']; ?>%</td>
                    <td>
                       
                        <a href="cetak_pdf.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm" target="_blank">Cetak PDF</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>