<?php
// Default filter values
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$category_id = $_GET['category_id'] ?? '';
$search = $_GET['search'] ?? '';

// Prepare the base query
$query = "
    SELECT 
        wo.id,
        wo.receipt_id,
        wo.weight,
        wo.price,
        wo.created_at,
        b.name as buyer_name,
        c.name as category_name,
        p.name as product_name,
        u.name as user_name
    FROM weighing_out wo
    LEFT JOIN buyers b ON wo.buyer_id = b.id
    LEFT JOIN products p ON wo.product_id = p.id
    LEFT JOIN categories c ON p.category_id = c.id
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

if ($category_id) {
    $query .= " AND p.category_id = ?";
    $params[] = $category_id;
    $types .= "i";
}

if ($search) {
    $search = "%$search%";
    $query .= " AND (wo.receipt_id LIKE ? OR b.name LIKE ? OR p.name LIKE ?)";
    $params = array_merge($params, [$search, $search, $search]);
    $types .= "sss";
}

$query .= " ORDER BY wo.created_at DESC";

// Prepare and execute query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get categories for filter
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
?>

<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700">Cari</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                           placeholder="Cari no kwitansi, pembeli, atau produk..."
                           class="block w-full rounded-l-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-r-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cari
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Kwitansi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembeli</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Berat (kg)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga/kg</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['receipt_id']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['buyer_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['category_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo number_format($row['weight'], 2); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?php echo number_format($row['price']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?php echo number_format($row['weight'] * $row['price']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['user_name']); ?></td>
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

                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga per kg</label>
                    <input type="number" id="edit_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
    fetch(`api/history_out.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_category_id').value = data.category_id;
            loadProducts(data.category_id, data.product_id);
            document.getElementById('edit_weight').value = data.weight;
            document.getElementById('edit_price').value = data.price;
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
    formData.append('category_id', document.getElementById('edit_category_id').value);
    formData.append('product_id', document.getElementById('edit_product_id').value);
    formData.append('weight', document.getElementById('edit_weight').value);
    formData.append('price', document.getElementById('edit_price').value);

    fetch('api/history_out.php', {
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
        fetch('api/history_out.php', {
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
    const urlParams = new URLSearchParams(window.location.search);
    const params = {
        start_date: urlParams.get('start_date'),
        end_date: urlParams.get('end_date'),
        category_id: urlParams.get('category_id'),
        search: urlParams.get('search')
    };

    const queryString = Object.keys(params)
        .filter(key => params[key])
        .map(key => `${key}=${params[key]}`)
        .join('&');

    window.location.href = `api/export_history_out.php?${queryString}`;
}

function printData() {
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
        <h2>History Barang Keluar</h2>
        <p>Periode: ${document.querySelector('[name="start_date"]').value} s/d ${document.querySelector('[name="end_date"]').value}</p>
        ${document.querySelector('.min-w-full').outerHTML}
    `;

    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print History Barang Keluar</title></head><body>');
    printWindow.document.write(printContent.innerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}

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