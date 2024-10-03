<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Histori Timbang</h1>
        <table id="history-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Nama</th>
                    <th>Produk</th>
                    <th>Nilai Timbang</th>
                    <th>Harga</th>
                    <th>Total</th>
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
            loadHistoryData();
        });

        function loadHistoryData() {
            fetch('../api/histori.php?action=getHistory')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#history-table tbody');
                tableBody.innerHTML = '';
                data.forEach(item => {
                    const row = `
                        <tr>
                            <td>${formatDate(item.waktu)}</td>
                            <td>${item.nama}</td>
                            <td>${item.produk}</td>
                            <td>${item.nilai_timbang} kg</td>
                            <td>Rp ${item.harga}</td>
                            <td>Rp ${item.total}</td>
                            <td>
                                <button onclick="editHistoryData(${item.id})">Edit</button>
                                <button onclick="deleteHistoryData(${item.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            })
            .catch(error => console.error('Error:', error));
        }

        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }
    </script>
</body>
</html>