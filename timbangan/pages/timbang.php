<?php
// $conn dan $base_url sudah tersedia dari index.php

// Ambil data produk dari database
$sql = "SELECT id, nama FROM produk ORDER BY nama";
$sql1 = "SELECT id, nama FROM orang ORDER BY nama";
$result = $conn->query($sql);
$result1 = $conn->query($sql1);
?>
<div class="container">
    <!-- <h2>Timbang</h2> -->
    <div id="scale-display">
        <h3>Nilai Timbangan: <span id="scale-value">0</span> kg</h3>
    </div>
    <form id="weighing-form">
        <!-- <input type="text" id="nama" placeholder="Nama" required> -->
        <select id="nama" required>
            <option value="">Pilih Nama</option>
            <?php
            // $result1 = $result;
            if ($result1->num_rows > 0) {
                while($row1 = $result1->fetch_assoc()) {
                    echo "<option value='" . $row1["id"] . "'>" . $row1["nama"] . "</option>";
                }
            }
            ?>
        </select>
        <select id="produk" required>
            <option value="">Pilih Produk</option>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'>" . $row["nama"] . "</option>";
                }
            }
            ?>
        </select>
        <button class="add" type="button" onclick="addWeighingData()">Timbang</button>
    </form>
    <table id="weighing-table">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Nama</th>
                <th>Produk</th>
                <th>Nilai Timbang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan diisi oleh JavaScript -->
        </tbody>
    </table>
    <button id="save-kwitansi" class ="saved" onclick="saveKwitansi()">Simpan Kwitansi</button>
</div>
<script src="<?php echo $base_url; ?>/assets/js/timbang.js"></script>