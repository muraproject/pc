<?php
// log.php
require_once 'config.php';
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - Smart Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Log Aktivitas Smart Home</h1>
            <a href="index.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Perangkat</label>
                    <select id="type-filter" class="w-full rounded-lg border-gray-300 shadow-sm">
                        <option value="">Semua</option>
                        <option value="door">Pintu</option>
                        <option value="light">Lampu</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" id="date-start" class="w-full rounded-lg border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                    <input type="date" id="date-end" class="w-full rounded-lg border-gray-300 shadow-sm">
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <table id="logTable" class="w-full">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Perangkat</th>
                        <th>Tipe</th>
                        <th>Status Lama</th>
                        <th>Status Baru</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let table = $('#logTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: 'log_data.php',
                    type: 'POST',
                    data: function(d) {
                        d.type = $('#type-filter').val();
                        d.dateStart = $('#date-start').val();
                        d.dateEnd = $('#date-end').val();
                    }
                },
                columns: [
                    { 
                        data: 'timestamp',
                        render: function(data) {
                            return new Date(data).toLocaleString('id-ID');
                        }
                    },
                    { data: 'device_name' },
                    { 
                        data: 'type',
                        render: function(data) {
                            return data === 'door' ? 'Pintu' : 'Lampu';
                        }
                    },
                    { data: 'old_status' },
                    { data: 'new_status' }
                ],
                order: [[0, 'desc']],
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'csv'
                ],
                pageLength: 10,
                language: {
                    "emptyTable": "Tidak ada data yang tersedia",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "loadingRecords": "Memuat...",
                    "processing": "Memproses...",
                    "search": "Cari:",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            // Apply filters
            $('#type-filter, #date-start, #date-end').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
</body>
</html>