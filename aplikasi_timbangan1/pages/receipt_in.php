<?php
// Default filter values
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$supplier_id = $_GET['supplier_id'] ?? '';
$search = $_GET['search'] ?? '';

// Query untuk mendapatkan daftar kwitansi
$query = "
    SELECT DISTINCT 
        wi.receipt_id,
        MIN(wi.created_at) as date,
        s.name as supplier_name,
        COUNT(wi.id) as total_items,
        SUM(wi.weight) as total_weight
    FROM weighing_in wi
    LEFT JOIN suppliers s ON wi.supplier_id = s.id
    WHERE 1=1
";

$params = [];
$types = "";

if ($start_date) {
    $query .= " AND DATE(wi.created_at) >= ?";
    $params[] = $start_date;
    $types .= "s";
}

if ($end_date) {
    $query .= " AND DATE(wi.created_at) <= ?";
    $params[] = $end_date;
    $types .= "s";
}

if ($supplier_id) {
    $query .= " AND wi.supplier_id = ?";
    $params[] = $supplier_id;
    $types .= "i";
}

if ($search) {
    $search = "%$search%";
    $query .= " AND (wi.receipt_id LIKE ? OR s.name LIKE ?)";
    $params = array_merge($params, [$search, $search]);
    $types .= "ss";
}

$query .= " GROUP BY wi.receipt_id ORDER BY MIN(wi.created_at) DESC";

// Prepare and execute query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get suppliers for filter
$suppliers = $conn->query("SELECT id, name FROM suppliers ORDER BY name");
?>

<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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
                <label class="block text-sm font-medium text-gray-700">Supplier</label>
                <select name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Supplier</option>
                    <?php while ($supplier = $suppliers->fetch_assoc()): ?>
                        <option value="<?php echo $supplier['id']; ?>" <?php echo $supplier_id == $supplier['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($supplier['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Cari</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                           placeholder="Cari no kwitansi atau supplier..."
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Daftar Kwitansi -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900">Daftar Kwitansi Masuk</h2>
                <div class="flex space-x-2">
                    <button onclick="exportToExcel()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none">
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Kwitansi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Berat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
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
                                    <?php echo htmlspecialchars($row['supplier_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo number_format($row['total_items']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo number_format($row['total_weight'], 2); ?> kg
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="viewDetail('<?php echo $row['receipt_id']; ?>')" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button onclick="printReceipt('<?php echo $row['receipt_id']; ?>')" class="text-gray-600 hover:text-gray-900">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                    </button>
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

<!-- Script --> 
<script>
// Handle filter form
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams();
    
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    window.location.href = `?page=receipt_in&${params.toString()}`;
});

// Close modal
function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// View detail 
function viewDetail(receiptId) {
    fetch(`api/receipt_in.php?action=detail&receipt_id=${receiptId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Group items by product
                const groupedItems = {};
                data.items.forEach(item => {
                    if (!groupedItems[item.product_name]) {
                        groupedItems[item.product_name] = {
                            items: [],
                            subtotal_weight: 0
                        };
                    }
                    groupedItems[item.product_name].items.push(item);
                    groupedItems[item.product_name].subtotal_weight += parseFloat(item.weight);
                });

                let modalContent = `
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">No Kwitansi: ${data.receipt_id}</p>
                        <p class="text-sm text-gray-600">Tanggal: ${data.date}</p>
                        <p class="text-sm text-gray-600">Supplier: ${data.supplier_name}</p>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Produk</th>
                                <th class="px-4 py-2">Berat (kg)</th>
                                <th class="px-4 py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                let no = 1;
                let totalWeight = 0;

                Object.entries(groupedItems).forEach(([product, group]) => {
                    group.items.forEach(item => {
                        modalContent += `
                            <tr>
                                <td class="px-4 py-2">${no++}</td>
                                <td class="px-4 py-2">${product}</td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.01" value="${item.weight}" 
                                           class="border rounded px-2 py-1 w-24" 
                                           onchange="updateItemWeight(this)">
                                </td>
                                <td class="px-4 py-2">${item.weight} kg</td>
                            </tr>
                        `;
                    });
                    
                    modalContent += `
                        <tr class="bg-gray-100">
                            <td colspan="2" class="px-4 py-2">Subtotal ${product}</td>
                            <td class="px-4 py-2">${group.subtotal_weight.toFixed(2)} kg</td>
                            <td class="px-4 py-2">${group.subtotal_weight.toFixed(2)} kg</td>
                        </tr>
                    `;
                    totalWeight += group.subtotal_weight;
                });

                modalContent += `
                        </tbody>
                        <tfoot>
                            <tr class="font-bold">
                                <td colspan="3" class="px-4 py-2 text-right">Total Berat:</td>
                                <td class="px-4 py-2">${totalWeight.toFixed(2)} kg</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-500 text-white rounded">Tutup</button>
                        <button onclick="saveChanges('${data.receipt_id}')" class="px-4 py-2 bg-blue-500 text-white rounded">
                            Simpan Perubahan
                        </button>
                        <button onclick="printDetail('${data.receipt_id}')" class="px-4 py-2 bg-green-500 text-white rounded">
                            Print
                        </button>
                    </div>
                `;

                document.getElementById('detailContent').innerHTML = modalContent;
                document.getElementById('detailModal').classList.remove('hidden');
            }
        });
}

// Update weight
function updateItemWeight(input) {
    const row = input.closest('tr');
    const weight = parseFloat(input.value);
    row.querySelector('td:last-child').textContent = `${weight.toFixed(2)} kg`;
    updateTotals();
}

// Update totals
function updateTotals() {
    let totalWeight = 0;
    document.querySelectorAll('tr:not(.bg-gray-100) input[type="number"]').forEach(input => {
        totalWeight += parseFloat(input.value) || 0;
    });
    document.querySelector('tfoot td:last-child').textContent = `${totalWeight.toFixed(2)} kg`;
}

// Print receipt
function printReceipt(receiptId) {
    fetch(`api/receipt_in.php?action=detail&receipt_id=${receiptId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const printContent = document.createElement('div');
                printContent.innerHTML = `
                    <style>
                        @media print {
                            @page { 
                                size: A4;
                                margin: 10mm; 
                            }
                            .no-print { display: none; }
                            table { 
                                width: 100%; 
                                border-collapse: collapse;
                                margin: 10px 0;
                            }
                            th, td { 
                                border: 1px solid black;
                                padding: 5px;
                                text-align: left;
                            }
                            .subtotal { background: #f0f0f0; }
                        }
                    </style>
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h2>KWITANSI BARANG MASUK</h2>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <p>No: ${data.receipt_id}</p>
                        <p>Tanggal: ${data.date}</p>
                        <p>Supplier: ${data.supplier_name}</p>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Berat (kg)</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                // Group items
                const groupedItems = {};
                data.items.forEach(item => {
                    if (!groupedItems[item.product_name]) {
                        groupedItems[item.product_name] = {
                            items: [],
                            subtotal_weight: 0
                        };
                    }
                    groupedItems[item.product_name].items.push(item);
                    groupedItems[item.product_name].subtotal_weight += parseFloat(item.weight);
                });

                let no = 1;
                let totalWeight = 0;

                Object.entries(groupedItems).forEach(([product, group]) => {
                    group.items.forEach(item => {
                        printContent.innerHTML += `
                            <tr>
                                <td>${no++}</td>
                                <td>${product}</td>
                                <td style="text-align: right">${item.weight.toFixed(2)}</td>
                                <td style="text-align: right">${item.weight.toFixed(2)} kg</td>
                            </tr>
                        `;
                    });

                    printContent.innerHTML += `
                        <tr class="subtotal">
                            <td colspan="2">Subtotal ${product}</td>
                            <td style="text-align: right">${group.subtotal_weight.toFixed(2)}</td>
                            <td style="text-align: right">${group.subtotal_weight.toFixed(2)} kg</td>
                        </tr>
                    `;
                    totalWeight += group.subtotal_weight;
                });

                printContent.innerHTML += `
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right"><strong>Total Berat:</strong></td>
                                <td style="text-align: right"><strong>${totalWeight.toFixed(2)} kg</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                `;

                const printWindow = window.open('', '', 'height=600,width=800');
                printWindow.document.write(printContent.innerHTML);
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }
        });
}

// Export to excel
function exportToExcel() {
    const urlParams = new URLSearchParams(window.location.search);
    const params = {
        start_date: urlParams.get('start_date'),
        end_date: urlParams.get('end_date'),
        supplier_id: urlParams.get('supplier_id'),
        search: urlParams.get('search')
    };

    const queryString = Object.keys(params)
        .filter(key => params[key])
        .map(key => `${key}=${params[key]}`)
        .join('&');

    window.location.href = `api/export_receipt_in.php?${queryString}`;
}

// Save changes
function saveChanges(receiptId) {
    const updates = [];
    document.querySelectorAll('tr:not(.bg-gray-100) input[type="number"]').forEach((input, index) => {
        updates.push({
            weight: parseFloat(input.value) || 0
        });
    });

    fetch('api/update_receipt_in.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            receipt_id: receiptId,
            updates: updates
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Perubahan berhasil disimpan');
            location.reload();
        } else {
            alert('Gagal menyimpan perubahan: ' + data.message);
        }
    });
}
</script>