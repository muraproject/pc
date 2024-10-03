<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}
require_once ROOT_PATH . '/includes/config.php';
require_once ROOT_PATH . '/includes/functions.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timbang - <?php echo APP_NAME; ?></title>
</head>
<body>
    <div class="container">
        <h1>Timbang</h1>
        <div id="scale-display">
            <h2>Nilai Timbangan: <span id="scale-value">0</span> kg</h2>
        </div>
        <form id="weighing-form">
            <input type="text" id="nama" placeholder="Nama" required>
            <select id="produk" required>
                <option value="">Pilih Produk</option>
                <?php
                $products = getAllProducts();
                foreach ($products as $product) {
                    echo "<option value='{$product['id']}'>{$product['nama']}</option>";
                }
                ?>
            </select>
            <button type="button" onclick="addWeighingData()">Timbang</button>
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
    </div>
</body>
</html>