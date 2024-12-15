<?php
// Default filter values
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$category_id = $_GET['category_id'] ?? '';
$supplier_id = $_GET['supplier_id'] ?? '';
$search = $_GET['search'] ?? '';

// Prepare the base query
$query = "
    SELECT 
        wi.id,
        wi.receipt_id,
        wi.weight,
        wi.created_at,
        s.name as supplier_name,
        c.name as category_name,
        p.name as product_name,
        u.name as user_name
    FROM weighing_in wi
    LEFT JOIN suppliers s ON wi.supplier_id = s.id
    LEFT JOIN products p ON wi.product_id = p.id
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN users u ON wi.user_id = u.id
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

if ($category_id) {
    $query .= " AND p.category_id = ?";
    $params[] = $category_id;
    $types .= "i";
}

if ($supplier_id) {
    $query .= " AND wi.supplier_id = ?";
    $params[] = $supplier_id;
    $types .= "i";
}

if ($search) {
    $search = "%$search%";
    $query .= " AND (wi.receipt_id LIKE ? OR s.name LIKE ? OR p.name LIKE ?)";
    $params = array_merge($params, [$search, $search, $search]);
    $types .= "sss";
}

$query .= " ORDER BY wi.created_at DESC";

// Prepare and execute query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get categories for filter
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
// Get suppliers for filter
$suppliers = $conn->query("SELECT id, name FROM suppliers ORDER BY name");
?>

<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
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
                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    <?php while ($category = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endwhile; ?>
                    </select>
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
                           placeholder="Cari no kwitansi, supplier..."
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

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">History Barang Masuk</h2>
                <div class="flex">
                    <button onclick="exportToExcel()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export Excel
                    </button>
                    <button onclick="printData()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Kwitansi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat (kg)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th> -->
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['receipt_id']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['category_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo number_format($row['weight'], 2); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['user_name']); ?></td>
                                <!-- <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editData(<?php echo $row['id']; ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="deleteData(<?php echo $row['id']; ?>)" class="text-red-600 hover:text-red-900">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td> -->
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Edit Data</h3>
            <form id="editForm" class="space-y-4">
                <input type="hidden" id="edit_id">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Supplier</label>
                    <select id="edit_supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <?php 
                        $suppliers->data_seek(0);
                        while ($supplier = $suppliers->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $supplier['id']; ?>"><?php echo htmlspecialchars($supplier['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="edit_category_id" onchange="loadProducts(this.value)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <?php 
                        $categories->data_seek(0);
                        while ($category = $categories->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Produk</label>
                    <select id="edit_product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Berat (kg)</label>
                    <input type="number" step="0.01" id="edit_weight" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="flex justify-end mt-4">
                    <button type="button" onclick="closeEditModal()" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editData(id) {
    // Fetch data dan tampilkan modal
    fetch(`api/history_in.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_supplier_id').value = data.supplier_id;
            document.getElementById('edit_category_id').value = data.category_id;
            loadProducts(data.category_id, data.product_id);
            document.getElementById('edit_weight').value = data.weight;
            document.getElementById('editModal').classList.remove('hidden');
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function loadProducts(categoryId, selectedProductId = null) {
    fetch(`api/products.php?category_id=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('edit_product_id');
            select.innerHTML = data.map(product => 
                `<option value="${product.id}" ${selectedProductId == product.id ? 'selected' : ''}>
                    ${product.name}
                </option>`
            ).join('');
        });
}

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('id', document.getElementById('edit_id').value);
    formData.append('supplier_id', document.getElementById('edit_supplier_id').value);
    formData.append('category_id', document.getElementById('edit_category_id').value);
    formData.append('product_id', document.getElementById('edit_product_id').value);
    formData.append('weight', document.getElementById('edit_weight').value);

    fetch('api/history_in.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditModal();
            location.reload();
        } else {
            alert('Gagal mengupdate data: ' + data.message);
        }
    });
});

function deleteData(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        fetch('api/history_in.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus data: ' + data.message);
            }
        });
    }
}

function exportToExcel() {
    // Get current filter parameters
    const urlParams = new URLSearchParams(window.location.search);
    const params = {
        start_date: urlParams.get('start_date'),
        end_date: urlParams.get('end_date'),
        category_id: urlParams.get('category_id'),
        supplier_id: urlParams.get('supplier_id'),
        search: urlParams.get('search')
    };

    // Build query string for export
    const queryString = Object.keys(params)
        .filter(key => params[key])
        .map(key => `${key}=${params[key]}`)
        .join('&');

    // Redirect to export endpoint
    window.location.href = `api/export_history_in.php?${queryString}`;
}

function printData() {
    // Create a printable version
    const printContent = document.createElement('div');
    printContent.innerHTML = `
        <style>
            @media print {
                table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f8f9fa; }
                h2 { margin-bottom: 1rem; }
                .no-print { display: none; }
            }
        </style>
        <h2>History Barang Masuk</h2>
        <p>Periode: ${document.querySelector('[name="start_date"]').value} s/d ${document.querySelector('[name="end_date"]').value}</p>
        ${document.querySelector('.min-w-full').outerHTML}
    `;

    // Open print window
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print History Barang Masuk</title></head><body>');
    printWindow.document.write(printContent.innerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
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

// Initialize tooltips
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>

<!-- Styles for modals and tooltips -->
<style>
.modal-overlay {
    transition: opacity 0.3s ease-in-out;
}

.modal-content {
    transform: scale(0.95);
    transition: transform 0.3s ease-in-out;
}

.modal-overlay.show {
    opacity: 1;
}

.modal-overlay.show .modal-content {
    transform: scale(1);
}

/* Custom scrollbar */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>