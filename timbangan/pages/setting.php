<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setting - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Setting</h1>
        <h2>Tambah Produk Baru</h2>
        <form id="add-product-form">
            <input type="text" id="new-product-name" placeholder="Nama Produk" required>
            <button type="button" onclick="addProduct()">Tambah Produk</button>
        </form>
        <h2>Daftar Produk</h2>
        <table id="product-table">
            <thead>
                <tr>
                    <th>Nama Produk</th>
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
            loadProducts();
        });

        function addProduct() {
            const productName = document.getElementById('new-product-name').value;
            fetch('../api/setting.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=addProduct&nama_produk=${encodeURIComponent(productName)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Produk berhasil ditambahkan');
                    document.getElementById('new-product-name').value = '';
                    loadProducts();
                } else {
                    alert('Gagal menambahkan produk: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function loadProducts() {
            fetch('../api/setting.php?action=getProducts')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#product-table tbody');
                tableBody.innerHTML = '';
                data.forEach(product => {
                    const row = `
                        <tr>
                            <td>${product.nama}</td>
                            <td>
                                <button onclick="editProduct(${product.id}, '${product.nama}')">Edit</button>
                                <button onclick="deleteProduct(${product.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            })
            .catch(error => console.error('Error:', error));
        }

        function editProduct(id, name) {
            const newName = prompt("Masukkan nama baru untuk produk:", name);
            if (newName) {
                fetch('../api/setting.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=editProduct&id=${id}&nama=${encodeURIComponent(newName)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Produk berhasil diupdate');
                        loadProducts();
                    } else {
                        alert('Gagal mengupdate produk: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function deleteProduct(id) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                fetch('../api/setting.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=removeProduct&id_produk=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Produk berhasil dihapus');
                        loadProducts();
                    } else {
                        alert('Gagal menghapus produk: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>