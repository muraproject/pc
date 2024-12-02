 
<?php if (!isset($_SESSION['user_id'])) header('Location: ../login.html'); ?>

<div class="container mx-auto px-4 py-8">
    <!-- Form Input Barang Masuk -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-6">Input Barang Masuk</h2>
        
        <form id="form-barang-masuk" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Supplier -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Supplier <span class="text-red-500">*</span>
                    </label>
                    <select id="supplier_id" name="supplier_id" required 
                            class="form-select w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Supplier</option>
                    </select>
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="kategori_id" name="kategori_id" required 
                            class="form-select w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Kategori</option>
                    </select>
                </div>

                <!-- Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Produk <span class="text-red-500">*</span>
                    </label>
                    <select id="produk_id" name="produk_id" required 
                            class="form-select w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Produk</option>
                    </select>
                </div>

                <!-- Berat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Berat (kg) <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center">
                        <input type="number" id="berat" name="berat" step="0.01" required
                               class="form-input flex-1 rounded-md border-gray-300 shadow-sm">
                        <button type="button" id="timbang-btn" 
                                class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Timbang
                        </button>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Keterangan
                </label>
                <textarea id="keterangan" name="keterangan" rows="3"
                          class="form-textarea w-full rounded-md border-gray-300 shadow-sm"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Data Barang Masuk -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-6">Data Barang Masuk</h2>
        
        <!-- Filter -->
        <div class="flex flex-wrap gap-4 mb-6">
            <select id="filter-supplier" class="form-select rounded-md border-gray-300 shadow-sm">
                <option value="">Semua Supplier</option>
            </select>
            <select id="filter-kategori" class="form-select rounded-md border-gray-300 shadow-sm">
                <option value="">Semua Kategori</option>
            </select>
            <input type="date" id="filter-tanggal" 
                   class="form-input rounded-md border-gray-300 shadow-sm">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Supplier
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Produk
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Berat (kg)
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Keterangan
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="table-barang-masuk" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg w-full max-w-2xl mx-4">
        <div class="p-6">
            <h3 class="text-xl font-bold mb-6">Edit Barang Masuk</h3>
            <!-- Form edit akan dimuat di sini -->
        </div>
    </div>
</div>

<script>
// Global variables
let suppliers = [];
let categories = [];
let products = [];
let selectedProduct = null;

document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    loadSuppliers();
    loadCategories();
    loadBarangMasuk();
    
    // Setup form submission
    document.getElementById('form-barang-masuk').addEventListener('submit', handleSubmit);
    
    // Setup timbang button
    document.getElementById('timbang-btn').addEventListener('click', handleTimbang);
    
    // Setup filters
    setupFilters();
});

function loadSuppliers() {
    fetch('../timbangan_rekap/api/master/supplier.php')
        .then(response => response.json())
        .then(data => {
            suppliers = data;
            updateSupplierDropdowns();
        })
        .catch(error => console.error('Error:', error));
}

function loadCategories() {
    fetch('../timbangan_rekap/api/master/kategori.php')
        .then(response => response.json())
        .then(data => {
            categories = data;
            updateCategoryDropdowns();
        })
        .catch(error => console.error('Error:', error));
}

function loadProducts(kategoriId) {
    fetch(`../timbangan_rekap/api/master/produk.php?kategori_id=${kategoriId}`)
        .then(response => response.json())
        .then(data => {
            products = data;
            updateProductDropdown();
        })
        .catch(error => console.error('Error:', error));
}

function handleSubmit(e) {
    e.preventDefault();
    
    const formData = {
        supplier_id: document.getElementById('supplier_id').value,
        produk_id: document.getElementById('produk_id').value,
        berat: document.getElementById('berat').value,
        keterangan: document.getElementById('keterangan').value
    };

    fetch('../timbangan_rekap/api/inventory/barang_masuk.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Data berhasil disimpan');
            resetForm();
            loadBarangMasuk();
        } else {
            alert('Gagal menyimpan data: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

function handleTimbang() {
    // Implement timbangan integration here
    console.log('Timbang button clicked');
}

function resetForm() {
    document.getElementById('form-barang-masuk').reset();
    selectedProduct = null;
}

// ... more JavaScript functions for handling UI updates and interactions ...

// Fungsi untuk memuat dan menampilkan data
function loadBarangMasuk() {
    fetch('../timbangan_rekap/api/inventory/barang_masuk.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateTable(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

function updateTable(data) {
    const tbody = document.getElementById('table-barang-masuk');
    tbody.innerHTML = '';

    data.forEach(item => {
        const row = `
            <tr>
                <td class="px-6 py-4">${formatDate(item.tanggal)}</td>
                <td class="px-6 py-4">${item.supplier}</td>
                <td class="px-6 py-4">${item.kategori}</td>
                <td class="px-6 py-4">${item.produk}</td>
                <td class="px-6 py-4">${formatNumber(item.berat)} kg</td>
                <td class="px-6 py-4">${item.keterangan || '-'}</td>
                <td class="px-6 py-4">
                    <button onclick="editItem(${item.id})" 
                            class="text-blue-600 hover:text-blue-800 mr-2">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteItem(${item.id})"
                            class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// Fungsi untuk dropdown dan filter
function updateCategoryDropdowns() {
    const categorySelect = document.getElementById('kategori_id');
    const filterCategory = document.getElementById('filter-kategori');
    
    fetch('../timbangan_rekap/api/master/kategori.php')
        .then(response => response.json())
        .then(result => {
            if (result.success && Array.isArray(result.data)) {
                const options = result.data.map(c => 
                    `<option value="${c.id}">${c.nama}</option>`
                ).join('');

                categorySelect.innerHTML = '<option value="">Pilih Kategori</option>' + options;
                filterCategory.innerHTML = '<option value="">Semua Kategori</option>' + options;
            }
        })
        .catch(error => console.error('Error loading categories:', error));
}

function updateSupplierDropdowns() {
    const supplierSelect = document.getElementById('supplier_id');
    const filterSupplier = document.getElementById('filter-supplier');
    
    fetch('../timbangan_rekap/api/master/supplier.php')
        .then(response => response.json())
        .then(result => {
            if (result.success && Array.isArray(result.data)) {
                const options = result.data.map(s => 
                    `<option value="${s.id}">${s.nama}</option>`
                ).join('');

                supplierSelect.innerHTML = '<option value="">Pilih Supplier</option>' + options;
                filterSupplier.innerHTML = '<option value="">Semua Supplier</option>' + options;
            }
        })
        .catch(error => console.error('Error loading suppliers:', error));
}

function updateProductDropdown() {
    const productSelect = document.getElementById('produk_id');
    
    const options = products.map(p => 
        `<option value="${p.id}">${p.nama}</option>`
    ).join('');

    productSelect.innerHTML = '<option value="">Pilih Produk</option>' + options;
}

// Setup filters
function setupFilters() {
    const filterSupplier = document.getElementById('filter-supplier');
    const filterCategory = document.getElementById('filter-kategori');
    const filterDate = document.getElementById('filter-tanggal');

    const filters = [filterSupplier, filterCategory, filterDate];
    filters.forEach(filter => {
        filter.addEventListener('change', applyFilters);
    });
}

function applyFilters() {
    const supplierId = document.getElementById('filter-supplier').value;
    const categoryId = document.getElementById('filter-kategori').value;
    const date = document.getElementById('filter-tanggal').value;

    fetch(`../timbangan_rekap/api/inventory/barang_masuk.php?supplier_id=${supplierId}&kategori_id=${categoryId}&tanggal=${date}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateTable(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Fungsi CRUD
function editItem(id) {
    fetch(`../timbangan_rekap/api/inventory/barang_masuk.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showEditModal(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

function showEditModal(item) {
    const modal = document.getElementById('modal-edit');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    const form = modal.querySelector('.p-6');
    form.innerHTML = `
        <h3 class="text-xl font-bold mb-6">Edit Barang Masuk</h3>
        <form id="form-edit" class="space-y-4">
            <input type="hidden" name="id" value="${item.id}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Form fields similar to add form -->
                ...
            </div>
            
            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-md">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-md">
                    Simpan
                </button>
            </div>
        </form>
    `;

    // Setup form submission
    document.getElementById('form-edit').addEventListener('submit', handleEdit);
}

function closeEditModal() {
    const modal = document.getElementById('modal-edit');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function handleEdit(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    fetch('../timbangan_rekap/api/inventory/barang_masuk.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Data berhasil diupdate');
            closeEditModal();
            loadBarangMasuk();
        } else {
            alert('Gagal mengupdate data: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteItem(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        fetch(`../timbangan_rekap/api/inventory/barang_masuk.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Data berhasil dihapus');
                loadBarangMasuk();
            } else {
                alert('Gagal menghapus data: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Utility functions
function formatDate(dateString) {
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}
</script>