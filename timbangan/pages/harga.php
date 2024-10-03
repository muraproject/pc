<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harga - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Pengaturan Harga</h1>
        <form id="price-form">
            <select id="product-select" required>
                <option value="">Pilih Produk</option>
                <?php
                $products = getAllProducts();
                foreach ($products as $product) {
                    echo "<option value='{$product['id']}'>{$product['nama']}</option>";
                }
                ?>
            </select>
            <input type="number" id="price-input" placeholder="Harga" required>
            <button type="button" onclick="setPrice()">Set Harga</button>
        </form>
        <h2>Daftar Harga</h2>
        <table id="price-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Tanggal Update</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data akan diisi oleh JavaScript -->
            </tbody>
        </table>
    </div>
    <?php include 'navbar.php'; ?>
    <script src="../assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadPrices();
        });

        function setPrice() {
            const productId = document.getElementById('product-select').value;
            const price = document.getElementById('price-input').value;
            fetch('../api/harga.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=setPrice&id_produk=${productId}&harga=${price}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Harga berhasil diatur');
                    document.getElementById('price-input').value = '';
                    loadPrices();
                } else {
                    alert('Gagal mengatur harga: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function loadPrices() {
            fetch('../api/harga.php?action=getPrices')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#price-table tbody');
                tableBody.innerHTML = '';
                data.forEach(item => {
                    const row = `
                        <tr>
                            <td>${item.produk}</td>
                            <td>Rp ${item.harga}</td>
                            <td>${formatDate(item.tanggal)}</td>
                            <td>
                                <button onclick="editPrice(${item.id}, ${item.harga})">Edit</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            })
            .catch(error => console.error('Error:', error));
        }

        function editPrice(id, currentPrice) {
            const newPrice = prompt("Masukkan harga baru:", currentPrice);
            if (newPrice) {
                fetch('../api/harga.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=updatePrice&id_harga=${id}&harga_baru=${newPrice}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Harga berhasil diupdate');
                        loadPrices();
                    } else {
                        alert('Gagal mengupdate harga: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }
    </script>
</body>
</html>