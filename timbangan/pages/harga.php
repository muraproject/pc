<?php
// $conn dan $base_url sudah tersedia dari index.php

// Query untuk mengambil data kwitansi
$sql = "SELECT DISTINCT id_kwitansi, waktu, nama FROM timbangan ORDER BY waktu DESC";
$result = $conn->query($sql);
?>

<div class="container">
    <!-- <h2>Daftar Kwitansi</h2> -->
    <table id="kwitansi-table" class="table table-striped">
        <thead>
            <tr>
                <th>ID Kwitansi</th>
                <th>Waktu</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id_kwitansi"] . "</td>";
                    echo "<td>" . $row["waktu"] . "</td>";
                    echo "<td>" . $row["nama"] . "</td>";
                    echo "<td><button class='btn btn-primary btn-sm' onclick='showDetail(\"" . $row["id_kwitansi"] . "\", \"" . $row["nama"] . "\")'>Detail</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Tidak ada data kwitansi</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Kwitansi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Detail kwitansi akan diisi oleh JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="saveChanges()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $base_url; ?>/assets/js/harga.js"></script>