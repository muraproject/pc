<?php
if ($_SESSION['role'] !== 'admin') {
    echo '<div class="p-4 bg-red-100 text-red-700 rounded">Anda tidak memiliki akses ke halaman ini</div>';
    exit;
}

// Default filter values
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$user_id = $_GET['user_id'] ?? '';
$search = $_GET['search'] ?? '';

// Get users for filter
$users = $conn->query("SELECT id, name FROM users WHERE role = 'user' ORDER BY name");

// Prepare base query
$query = "
    SELECT 
        w.id,
        w.weight,
        w.created_at,
        u.name as user_name,
        u.wage_per_kg
    FROM wages_data w
    LEFT JOIN users u ON w.user_id = u.id
    WHERE 1=1
";

$params = [];
$types = "";

if ($start_date) {
    $query .= " AND DATE(w.created_at) >= ?";
    $params[] = $start_date;
    $types .= "s";
}

if ($end_date) {
    $query .= " AND DATE(w.created_at) <= ?";
    $params[] = $end_date;
    $types .= "s";
}

if ($user_id) {
    $query .= " AND w.user_id = ?";
    $params[] = $user_id;
    $types .= "i";
}

if ($search) {
    $search = "%$search%";
    $query .= " AND (u.name LIKE ?)";
    $params[] = $search;
    $types .= "s";
}

$query .= " ORDER BY w.created_at DESC";

// Prepare and execute query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="space-y-6">
    <!-- Scale Display -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-center">
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">Berat Timbangan</h2>
            <div class="text-5xl font-bold text-blue-600" id="scale-display">
                <span id="scale-value">0.00</span>
                <span class="text-3xl">kg</span>
            </div>
            <div class="mt-4 space-x-4">
                <button onclick="stabilizeScale()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Stabilkan
                </button>
                <button onclick="resetScale()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Input Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Data Timbangan</h3>
                <form id="weighing-form" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Penimbang</label>
                        <select id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Penimbang</option>
                            <?php 
                            $users->data_seek(0);
                            while ($user = $users->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select id="category_id" onchange="loadProducts(this.value)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Kategori</option>
                            <?php 
                            $categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
                            while ($category = $categories->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Produk</label>
                        <select id="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Produk</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Item Timbangan</h3>
                <div class="overflow-y-auto max-h-96">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penimbang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Berat (kg)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="items-table" class="bg-white divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bottom Buttons -->
        <div class="mt-6 flex justify-end space-x-4">
            <button onclick="cancelWeighing()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batal
            </button>
            <button onclick="saveData()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Simpan Data
            </button>
        </div>
    </div>

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
                <label class="block text-sm font-medium text-gray-700">Penimbang</label>
                <select name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Penimbang</option>
                    <?php 
                    $users->data_seek(0);
                    while ($user = $users->fetch_assoc()): 
                    ?>
                        <option value="<?php echo $user['id']; ?>" <?php echo $user_id == $user['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($user['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="category_id" onchange="loadFilterProducts(this.value)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    <?php 
                    $categories->data_seek(0);
                    while ($category = $categories->fetch_assoc()): 
                    ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo isset($_GET['category_id']) && $_GET['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Produk</label>
                <select name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Produk</option>
                    <?php 
                    if (isset($_GET['category_id']) && $_GET['category_id'] != '') {
                        $products = $conn->query("SELECT id, name FROM products WHERE category_id = " . intval($_GET['category_id']) . " ORDER BY name");
                        while ($product = $products->fetch_assoc()): 
                    ?>
                        <option value="<?php echo $product['id']; ?>" <?php echo isset($_GET['product_id']) && $_GET['product_id'] == $product['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($product['name']); ?>
                        </option>
                    <?php 
                        endwhile;
                    }
                    ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Cari</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                           placeholder="Cari nama penimbang..."
                           class="block w-full rounded-l-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-gray-500 hover:bg-gray-100">
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
                <h2 class="text-lg font-medium text-gray-900">Data Upah</h2>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penimbang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat (kg)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Upah/kg</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Upah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($row = $result->fetch_assoc()): 
                            $total_wage = $row['weight'] * $row['wage_per_kg'];
                        ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo number_format($row['weight'], 2); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp <?php echo number_format($row['wage_per_kg']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp <?php echo number_format($total_wage); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
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
                                </td>
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
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Data Upah</h3>
            <form id="editForm">
                <input type="hidden" id="edit_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Penimbang</label>
                    <select id="edit_user_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <?php 
                        $users->data_seek(0);
                        while ($user = $users->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Berat (kg)</label>
                    <input type="number" step="0.01" id="edit_weight" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('editModal')" 
                            class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
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
let currentItems = [];
let isScaleStable = false;

function stabilizeScale() {
    isScaleStable = true;
}

function resetScale() {
    document.getElementById('scale-value').textContent = '0.00';
    isScaleStable = false;
}

// Form handling
document.getElementById('weighing-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!isScaleStable) {
        alert('Harap stabilkan timbangan terlebih dahulu');
        return;
    }

    const user_id = document.getElementById('user_id').value;
    const category_id = document.getElementById('category_id').value;
    const product_id = document.getElementById('product_id').value;
    const weight = parseFloat(document.getElementById('scale-value').textContent);

    if (!user_id || !category_id || !product_id) {
        alert('Harap pilih penimbang, kategori, dan produk');
        return;
    }

    const userSelect = document.getElementById('user_id');
    const userName = userSelect.options[userSelect.selectedIndex].text;

    const categorySelect = document.getElementById('category_id');
    const productSelect = document.getElementById('product_id');
    const categoryName = categorySelect.options[categorySelect.selectedIndex].text;
    const productName = productSelect.options[productSelect.selectedIndex].text;

    const item = {
        user_id: user_id,
        user_name: userName,
        category_id: category_id,
        category_name: categoryName,
        product_id: product_id,
        product_name: productName,
        weight: weight
    };

    currentItems.push(item);
    updateItemsTable();
    resetScale();
});

function updateItemsTable() {
    const tbody = document.getElementById('items-table');
    tbody.innerHTML = currentItems.map((item, index) => `
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.user_name}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.category_name}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.product_name}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.weight.toFixed(2)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="deleteItem(${index})" class="text-red-600 hover:text-red-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </td>
        </tr>
    `).join('');
}

function deleteItem(index) {
    currentItems.splice(index, 1);
    updateItemsTable();
}

function cancelWeighing() {
    if (confirm('Apakah Anda yakin ingin membatalkan timbangan ini?')) {
        currentItems = [];
        updateItemsTable();
        resetScale();
    }
}

function exportToExcel() {
    const urlParams = new URLSearchParams(window.location.search);
    const queryString = urlParams.toString();
    window.location.href = `api/export_wages.php?${queryString}`;
}

function editData(id) {
    fetch(`api/wages.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_user_id').value = data.user_id;
                document.getElementById('edit_weight').value = data.weight;
                document.getElementById('editModal').classList.remove('hidden');
            } else {
                alert('Gagal mengambil data: ' + data.message);
            }
        });
}

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('id', document.getElementById('edit_id').value);
    formData.append('user_id', document.getElementById('edit_user_id').value);
    formData.append('weight', document.getElementById('edit_weight').value);

    fetch('api/wages.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('editModal').classList.add('hidden');
            location.reload();
        } else {
            alert('Gagal mengupdate data: ' + data.message);
        }
    });
});

function deleteData(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        fetch('api/wages.php', {
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

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function loadProducts(categoryId) {
    const productSelect = document.getElementById('product_id');
    productSelect.innerHTML = '<option value="">Pilih Produk</option>';
    
    if (!categoryId) return;

    fetch(`api/get_products.php?category_id=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            data.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = product.name;
                productSelect.appendChild(option);
            });
        });
}

function loadFilterProducts(categoryId) {
    const productSelect = document.querySelector('select[name="product_id"]');
    productSelect.innerHTML = '<option value="">Semua Produk</option>';
    
    if (!categoryId) return;

    fetch(`api/get_products.php?category_id=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            data.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = product.name;
                productSelect.appendChild(option);
            });
        });
}

function saveData() {
    if (currentItems.length === 0) {
        alert('Tidak ada data untuk disimpan');
        return;
    }

    fetch('api/save_wages.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ items: currentItems })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Data berhasil disimpan');
            currentItems = [];
            updateItemsTable();
            location.reload(); // Refresh to show new data
        } else {
            alert('Gagal menyimpan data: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    });
}

function updateScale(value) {
    value = value*100;
    const scaleValueElement = document.getElementById('scale-value');
    if (scaleValueElement) {
        scaleValueElement.textContent = value*1;
    }
}
</script>