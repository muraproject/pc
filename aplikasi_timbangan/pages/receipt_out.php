<?php
// Default filter values
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$search = $_GET['search'] ?? '';

// Query to get receipt list
$query = "
    SELECT DISTINCT 
        wo.receipt_id,
        MIN(wo.created_at) as date,
        COUNT(wo.id) as total_items,
        SUM(wo.weight) as total_weight,
        SUM(wo.weight * wo.price) as total_amount,
        u.name as user_name
    FROM weighing_out wo
    LEFT JOIN users u ON wo.user_id = u.id
    WHERE 1=1
";

$params = [];
$types = "";

if ($start_date) {
    $query .= " AND DATE(wo.created_at) >= ?";
    $params[] = $start_date;
    $types .= "s";
}

if ($end_date) {
    $query .= " AND DATE(wo.created_at) <= ?";
    $params[] = $end_date;
    $types .= "s";
}

if ($search) {
    $search = "%$search%";
    $query .= " AND (wo.receipt_id LIKE ? OR u.name LIKE ?)";
    $params = array_merge($params, [$search, $search]);
    $types .= "ss";
}

$query .= " GROUP BY wo.receipt_id ORDER BY MIN(wo.created_at) DESC";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" name="start_date" value="<?php echo $start_date; ?>" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                <input type="date" name="end_date" value="<?php echo $end_date; ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Cari</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                           placeholder="Cari no kwitansi..."
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Kwitansi List -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Kwitansi Barang Keluar</h2>
                <div class="flex">
                    <button onclick="exportToExcel()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export Excel
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Kwitansi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operator</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Berat (kg)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($row['receipt_id']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo date('d/m/Y H:i', strtotime($row['date'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($row['user_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo $row['total_items']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo number_format($row['total_weight'], 2); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp <?php echo number_format($row['total_amount'], 0); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="viewDetail('<?php echo $row['receipt_id']; ?>')" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button onclick="printReceipt('<?php echo $row['receipt_id']; ?>')" class="text-gray-600 hover:text-gray-900 mr-3">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                    </button>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <button onclick="deleteReceipt('<?php echo $row['receipt_id']; ?>')" class="text-red-600 hover:text-red-900">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detail Kwitansi</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="detailContent" class="mt-4">
                <!-- Detail content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function viewDetail(receiptId) {
    fetch(`api/receipt_out.php?action=detail&receipt_id=${receiptId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let content = `
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">No Kwitansi: ${data.receipt_id}</p>
                        <p class="text-sm text-gray-600">Tanggal: ${data.date}</p>
                        <p class="text-sm text-gray-600">Operator: ${data.user_name}</p>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Berat (kg)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga/kg</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                `;

                let totalAmount = 0;
                data.items.forEach(item => {
                    const itemTotal = item.weight * item.price;
                    totalAmount += itemTotal;
                    content += `
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">${item.category_name}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">${item.product_name}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">${Number(item.weight).toFixed(2)}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Rp ${Number(item.price).toLocaleString()}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Rp ${Number(itemTotal).toLocaleString()}</td>
                        </tr>
                    `;
                });

                content += `
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-sm font-medium text-gray-900">Total</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp ${Number(totalAmount).toLocaleString()}</td>
                            </tr>
                        </tfoot>
                    </table>
                `;

                document.getElementById('detailContent').innerHTML = content;
                document.getElementById('detailModal').classList.remove('hidden');
            } else {
                alert('Gagal memuat detail kwitansi: ' + data.message);
            }
        });
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function printReceipt(receiptId) {
    fetch(`api/receipt_out.php?action=detail&receipt_id=${receiptId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const printContent = document.createElement('div');
                printContent.innerHTML = `
                    <style>
                        @media print {
                            @page { size: 80mm auto; margin: 0; }
                            body { font-family: Arial, sans-serif; padding: 10mm; }
                            table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                            th, td { padding: 5px; text-align: left; }
                            .text-center { text-align: center; }
                            .text-right { text-align: right; }
                            .heading { font-size: 16px; font-weight: bold; margin: 10px 0; }
                            .info { font-size: 12px; margin: 5px 0; }
                            .total { font-weight: bold; border-top: 1px solid #000; }
                            .amount { text-align: right; }
                        }
                    </style>
                    <div class="text-center heading">KWITANSI BARANG KELUAR</div>
                    <div class="info">No: ${data.receipt_id}</div>
                    <div class="info">Tanggal: ${data.date}</div>
                    <div class="info">Operator: ${data.user_name}</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-right">Berat</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                let totalAmount = 0;
                data.items.forEach(item => {
                    const itemTotal = item.weight * item.price;
                    totalAmount += itemTotal;
                    printContent.innerHTML += `
                        <tr>
                            <td>${item.product_name}</td>
                            <td class="text-right">${Number(item.weight).toFixed(2)}</td>
                            <td class="text-right">${Number(item.price).toLocaleString()}</td>
                            <td class="text-right">${Number(itemTotal).toLocaleString()}</td>
                        </tr>
                    `;
                });

                printContent.innerHTML += `
                            <tr class="total">
                                <td colspan="3">Total</td>
                                <td class="text-right">${Number(totalAmount).toLocaleString()}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center info">Terima kasih</div>
                `;

                const printWindow = window.open('', 'PRINT', 'height=600,width=800');
                printWindow.document.write(printContent.innerHTML);
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            } else {
                alert('Gagal memuat data kwitansi untuk dicetak: ' + data.message);
            }
        });
}

function deleteReceipt(receiptId) {
    if (confirm('Apakah Anda yakin ingin menghapus kwitansi ini?')) {
        fetch('api/receipt_out.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete&receipt_id=${receiptId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus kwitansi: ' + data.message);
            }
        });
    }
}

function exportToExcel() {
    const urlParams = new URLSearchParams(window.location.search);
    const params = {
        start_date: urlParams.get('start_date'),
        end_date: urlParams.get('end_date'),
        search: urlParams.get('search')
    };

    const queryString = Object.keys(params)
        .filter(key => params[key])
        .map(key => `${key}=${params[key]}`)
        .join('&');

    window.location.href = `api/export_receipt_out.php?${queryString}`;
}

// Handle filter form submission
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    window.location.href = `?${params.toString()}`;
});
</script>