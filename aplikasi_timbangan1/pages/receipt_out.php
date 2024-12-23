<?php
// Default filter values
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
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
                    <!-- <button onclick="exportToExcel()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export Excel
                    </button> -->
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
    fetch(`api/receipt_out.php?action=detail&id=${receiptId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Group items by product
                const groupedItems = {};
                data.items.forEach(item => {
                    if (!groupedItems[item.product_name]) {
                        groupedItems[item.product_name] = {
                            items: [],
                            subtotal: 0,
                            subtotal_weight: 0
                        };
                    }
                    groupedItems[item.product_name].items.push(item);
                    groupedItems[item.product_name].subtotal += (item.weight * item.price);
                    groupedItems[item.product_name].subtotal_weight += parseFloat(item.weight);
                });

                let modalContent = `
                    <div class="mb-4">
                        <p class="font-medium">Nama: ${data.buyer_name}</p>
                        <p class="text-sm text-gray-600">Tanggal: ${data.date}</p>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Produk</th>
                                <th class="px-4 py-2">Berat (kg)</th>
                                <th class="px-4 py-2">Harga</th>
                                <th class="px-4 py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                let no = 1;
                let totalAll = 0;
                
                Object.entries(groupedItems).forEach(([productName, group]) => {
                    group.items.forEach(item => {
                        const itemTotal = item.weight * item.price;
                        modalContent += `
                            <tr>
                                <td class="px-4 py-2">${no++}</td>
                                <td class="px-4 py-2">${productName}</td>
                                <td>
        <input type="number" step="0.01" value="${item.weight}" 
               class="border rounded px-2 py-1 w-24" 
               onchange="updateItemTotal(this, this.parentElement.nextElementSibling.querySelector('input'))">
    </td>
    <td>
        <input type="number" value="${item.price}" 
               class="border rounded px-2 py-1 w-24"
               onchange="updateItemTotal(this.parentElement.previousElementSibling.querySelector('input'), this)">
    </td>
    <td class="item-total">${itemTotal.toFixed(2)}</td>
                            </tr>
                        `;
                    });

                    // Subtotal per product
                    modalContent += `
                        <tr class="bg-gray-100">
                            <td colspan="2" class="px-4 py-2">Subtotal ${productName}</td>
                            <td class="px-4 py-2">${group.subtotal_weight.toFixed(2)} kg</td>
                            <td></td>
                            <td class="px-4 py-2 subtotal">${group.subtotal.toFixed(2)}</td>
                        </tr>
                    `;
                    totalAll += group.subtotal;
                });

                modalContent += `
                        </tbody>
                        <tfoot>
                            <tr class="font-bold">
                                <td colspan="4" class="px-4 py-2">Total Harga:</td>
                                <td class="px-4 py-2" id="totalAll">${totalAll.toFixed(2)}</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-500 text-white rounded">Tutup</button>
                        <button onclick="saveChanges('${receiptId}')" class="px-4 py-2 bg-blue-500 text-white rounded">Simpan Perubahan</button>
                        <button onclick="printDetail('${receiptId}')" class="px-4 py-2 bg-green-500 text-white rounded">Print PDF</button>
                    </div>
                    </br>
                    <div class="mt-4 flex justify-end space-x-2">
                       
                    </div>
                `;

                document.getElementById('detailContent').innerHTML = modalContent;
                document.getElementById('detailModal').classList.remove('hidden');
            }
        });
}

let currentItems = [];

function updateItemTotal(weightInput, priceInput) {
    const row = weightInput.parentElement.parentElement; // Dapatkan parent tr
    const weight = parseFloat(weightInput.value || 0);
    const price = parseFloat(priceInput.value || 0);
    const total = weight * price;
    
    const totalCell = row.querySelector('.item-total');
    if (totalCell) {
        totalCell.textContent = total.toLocaleString();
    }
    
    // Update subtotal
    const productName = row.querySelector('td:first-child').textContent;
    updateSubtotalByProduct(productName);
    updateGrandTotal();
}

function updateSubtotalByProduct(productName) {
    // Get all rows for this product
    const rows = Array.from(document.querySelectorAll('tr')).filter(row => 
        row.querySelector('td:nth-child(2)')?.textContent === productName
    );
    
    let subtotalWeight = 0;
    let subtotalAmount = 0;
    
    // Calculate subtotals
    rows.forEach(row => {
        if (!row.classList.contains('bg-gray-100')) { // Skip subtotal row
            const weight = parseFloat(row.querySelector('input[type="number"][step="0.01"]').value || 0);
            const price = parseFloat(row.querySelector('input[type="number"]:not([step])').value || 0);
            subtotalWeight += weight;
            subtotalAmount += (weight * price);
        }
    });

    // Update subtotal row
    const subtotalRow = Array.from(document.querySelectorAll('tr.bg-gray-100')).find(row => 
        row.textContent.includes('Subtotal ' + productName)
    );
    
    if (subtotalRow) {
        subtotalRow.querySelector('td:nth-child(3)').textContent = subtotalWeight.toFixed(2) + ' kg';
        subtotalRow.querySelector('.subtotal').textContent = subtotalAmount.toLocaleString();
    }

    updateGrandTotal();
}

function updateGrandTotal() {
    const subtotals = document.querySelectorAll('.subtotal');
    let grandTotal = 0;
    
    subtotals.forEach(subtotal => {
        grandTotal += parseFloat(subtotal.textContent.replace(/,/g, '') || 0);
    });
    
    document.getElementById('totalAll').textContent = grandTotal.toLocaleString();
}

function updateSubtotals() {
    const subtotalRows = document.querySelectorAll('.subtotal');
    let totalAll = 0;
    
    subtotalRows.forEach(row => {
        const productRows = row.closest('tbody').querySelectorAll('.item-total');
        let subtotal = 0;
        productRows.forEach(itemRow => {
            subtotal += parseFloat(itemRow.textContent);
        });
        row.textContent = subtotal;
        totalAll += subtotal;
    });
    
    document.getElementById('totalAll').textContent = totalAll;
}

function saveChanges(receiptId) {
   const rows = document.querySelectorAll('tbody tr:not(.bg-gray-100)');
   const updates = [];
   
   rows.forEach(row => {
       const weightInput = row.querySelector('input[type="number"][step]');
       const priceInput = row.querySelector('input[type="number"]:not([step])');
       
       // Skip header rows and subtotal rows
       if (!row.closest('thead') && !row.closest('tfoot') && weightInput && priceInput) {
           const weight = parseFloat(weightInput.value);
           const price = parseFloat(priceInput.value);
           
           if (!isNaN(weight) && !isNaN(price)) {
               updates.push({
                   weight: weight,
                   price: price
               });
           }
       }
   });

   if (updates.length === 0) {
       alert('Tidak ada data yang dapat disimpan');
       return;
   }

   fetch('api/update_receipt_out.php', {
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
   })
   .catch(error => {
       console.error('Error:', error);
       alert('Terjadi kesalahan saat menyimpan data');
   });
}

// function printDetail(receiptId) {
//     window.open(`api/print_receipt_out.php?id=${receiptId}`, '_blank');
// }

function printDetail(receiptId) {
    fetch(`api/generate_pdf_out.php?id=${receiptId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Download URL:', data.download_url);
                // Trigger download
                // window.location.href = data.download_url;
            } else {
                console.error('Error generating PDF:', data.message);
                alert('Gagal membuat PDF: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem');
        });
}
function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
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
// document.querySelector('form').addEventListener('submit', function(e) {
//     e.preventDefault();
//     const formData = new FormData(this);
//     const params = new URLSearchParams();
//     for (const [key, value] of formData.entries()) {
//         if (value) params.append(key, value);
//     }
//     window.location.href = `?${params.toString()}`;
// });

document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams();
    
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    window.location.href = `?page=receipt_out&${params.toString()}`;
});
</script>