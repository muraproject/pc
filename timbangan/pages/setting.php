<?php
// $conn dan $base_url sudah tersedia dari index.php

// Query untuk mengambil semua produk
$sql = "SELECT id, nama FROM produk ORDER BY nama";
$result = $conn->query($sql);

$sql1 = "SELECT id, nama FROM orang ORDER BY nama";
$result1 = $conn->query($sql1);
?>
<div class="container">
    <!-- <h2>Pengaturan Produk</h2> -->
    
    <form id="product-form">
        <input type="hidden" id="product-id">
        <div class="form-group">
            <label for="product-name">Nama Produk:</label>
            <input type="text" class="form-control" id="product-name" required>
        </div>
        <button type="submit" class="btn btn-primary" id="submit-btn">Tambah Produk</button>
    </form>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="product-list">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["nama"] . "</td>";
                    echo "<td>";
                    echo "<button class='btn btn-sm btn-info mr-2' onclick='editProduct(" . $row["id"] . ", \"" . $row["nama"] . "\")'>Edit</button>";
                    echo "<button class='btn btn-sm btn-danger' onclick='deleteProduct(" . $row["id"] . ")'>Hapus</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>Tidak ada produk</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="container">
    <!-- <h2>Pengaturan Produk</h2> -->
    
    <form id="product-form">
        <input type="hidden" id="orang-id">
        <div class="form-group">
            <label for="product-name">Nama Orang</label>
            <input type="text" class="form-control" id="product-name" required>
        </div>
        <button type="submit" class="btn btn-primary" id="submit-btn">Tambah Orang</button>
    </form>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="orang-list">
            <?php
            if ($result1->num_rows > 0) {
                while($row1 = $result1->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row1["nama"] . "</td>";
                    echo "<td>";
                    echo "<button class='btn btn-sm btn-info mr-2' onclick='editProduct(" . $row1["id"] . ", \"" . $row1["nama"] . "\")'>Edit</button>";
                    echo "<button class='btn btn-sm btn-danger' onclick='deleteProduct(" . $row1["id"] . ")'>Hapus</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>Tidak ada produk</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="<?php echo $base_url; ?>/assets/js/setting.js"></script>