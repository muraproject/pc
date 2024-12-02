<?php
// $conn dan $base_url sudah tersedia dari index.php

// Query untuk mengambil semua produk
$sql_produk = "SELECT id, nama FROM produk ORDER BY nama";
$result_produk = $conn->query($sql_produk);

// Query untuk mengambil semua orang
$sql_orang = "SELECT id, nama FROM orang ORDER BY nama";
$result_orang = $conn->query($sql_orang);
?>

<div class="container">
    <h2 class="mt-4">Pengaturan Produk</h2>
    
    <!-- Form for products -->
    <form id="product-form">
        <input type="hidden" id="product-id">
        <div class="form-group">
            <label for="product-name">Nama Produk:</label>
            <input type="text" class="form-control" id="product-name" required>
        </div>
        <button type="submit" class="btn btn-primary" id="product-submit-btn">Tambah Produk</button>
    </form>

    <!-- Products Table -->
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="product-list">
            <?php
            if ($result_produk->num_rows > 0) {
                while($row = $result_produk->fetch_assoc()) {
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

    <h2 class="mt-5">Pengaturan Orang</h2>
    
    <!-- Form for people -->
    <form id="person-form">
        <input type="hidden" id="person-id">
        <div class="form-group">
            <label for="person-name">Nama Orang:</label>
            <input type="text" class="form-control" id="person-name" required>
        </div>
        <button type="submit" class="btn btn-primary" id="person-submit-btn">Tambah Orang</button>
    </form>

    <!-- People Table -->
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Nama Orang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="person-list">
            <?php
            if ($result_orang->num_rows > 0) {
                while($row = $result_orang->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["nama"] . "</td>";
                    echo "<td>";
                    echo "<button class='btn btn-sm btn-info mr-2' onclick='editPerson(" . $row["id"] . ", \"" . $row["nama"] . "\")'>Edit</button>";
                    echo "<button class='btn btn-sm btn-danger' onclick='deletePerson(" . $row["id"] . ")'>Hapus</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>Tidak ada orang</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="<?php echo $base_url; ?>/assets/js/setting.js"></script>