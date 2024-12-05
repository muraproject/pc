 
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
// Global variables
let suppliers = [];
let categories = [];
let products = [];
let selectedProduct = null;
var produk_id1=0;

// Modal HTML template
const modalHTML = `
<div id="modal-edit" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
</div>
`;

document.addEventListener('DOMContentLoaded', function() {
    // Add modal HTML to the page if not exists
    if (!document.getElementById('modal-edit')) {
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    // Initialize event listeners
    document.getElementById('form-barang-masuk')?.addEventListener('submit', handleSubmit);
    document.getElementById('timbang-btn')?.addEventListener('click', handleTimbang);
    
    // Setup kategori change event
    document.getElementById('kategori_id')?.addEventListener('change', function(e) {
        if (e.target.value) {
            loadProducts(e.target.value);
        }
    });

    setupFilters();

    // Load initial data
    loadSuppliers();
    loadCategories();
    loadBarangMasuk();
});

// Data Loading Functions
function loadSuppliers() {
    fetch('../timbangan_rekap/api/master/supplier.php')
        .then(response => response.json())
        .then(result => {
            if (result.success && Array.isArray(result.data)) {
                suppliers = result.data;
                updateSupplierDropdowns();
            }
        })
        .catch(error => console.error('Error loading suppliers:', error));
}

function loadCategories() {
    fetch('../timbangan_rekap/api/master/kategori.php')
        .then(response => response.json())
        .then(result => {
            if (result.success && Array.isArray(result.data)) {
                categories = result.data;
                updateCategoryDropdowns();
            }
        })
        .catch(error => console.error('Error loading categories:', error));
}



function loadBarangMasuk() {
    fetch('../timbangan_rekap/api/inventory/barang_masuk.php')
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                updateTable(result.data);
            }
        })
        .catch(error => console.error('Error loading barang masuk:', error));
}

// Update Dropdowns
function updateSupplierDropdowns() {
    const supplierSelect = document.getElementById('supplier_id');
    const filterSupplier = document.getElementById('filter-supplier');
    
    const options = suppliers.map(s => 
        `<option value="${s.id}">${s.nama}</option>`
    ).join('');

    if (supplierSelect) {
        supplierSelect.innerHTML = '<option value="">Pilih Supplier</option>' + options;
    }
    if (filterSupplier) {
        filterSupplier.innerHTML = '<option value="">Semua Supplier</option>' + options;
    }
}

function updateCategoryDropdowns() {
    const categorySelect = document.getElementById('kategori_id');
    const filterCategory = document.getElementById('filter-kategori');
    
    const options = categories.map(c => 
        `<option value="${c.id}">${c.nama}</option>`
    ).join('');

    if (categorySelect) {
        categorySelect.innerHTML = '<option value="">Pilih Kategori</option>' + options;
    }
    if (filterCategory) {
        filterCategory.innerHTML = '<option value="">Semua Kategori</option>' + options;
    }
}

function updateProductDropdown() {
    console.log("Updating product dropdown...");
    console.log("Products data:", products);

    // Get the dropdown element
    const productSelect = document.getElementById('produk_id');
    
    // If dropdown not found, log and return
    if (!productSelect) {
        console.log("Product dropdown element not found");
        return;
    }

    // Create options HTML
    let optionsHtml = '<option value="">Pilih Produk</option>';
    
    // Add options from products array
    products.forEach(product => {
        optionsHtml += `<option value="${product.id}">${product.nama}</option>`;
    });

    // Set the HTML
    productSelect.innerHTML = optionsHtml;
    document.getElementById('edit-produk_id').innerHTML = optionsHtml;
    console.log(produk_id1);
    if(!produk_id1){

    }
    document.getElementById('edit-produk_id').value=produk_id1;
    
    console.log("Updated dropdown HTML:", optionsHtml);
}

// Handle Form Actions
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
    .then(result => {
        if (result.success) {
            showMessage('Data berhasil disimpan', 'success');
            resetForm();
            loadBarangMasuk();
        } else {
            showMessage(result.message || 'Gagal menyimpan data', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Gagal menyimpan data', 'error');
    });
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
    .then(result => {
        if (result.success) {
            showMessage('Data berhasil diupdate', 'success');
            closeEditModal();
            loadBarangMasuk();
        } else {
            showMessage(result.message || 'Gagal mengupdate data', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Gagal mengupdate data', 'error');
    });
}

function handleTimbang() {
    // Implement timbangan integration here
    const randomWeight = (Math.random() * (100 - 0.1) + 0.1).toFixed(2);
    document.getElementById('berat').value = randomWeight;
}

// Modal Functions
function showEditModal(item) {
    const modal = document.getElementById('modal-edit');
    produk_id1= item.produk_id;
    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    modal.innerHTML = `
        <div class="bg-white rounded-lg w-full max-w-lg mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Edit Barang Masuk</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form id="form-edit" class="p-6">
                <input type="hidden" name="id" value="${item.id}">
                
                <!-- Supplier -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Supplier <span class="text-red-500">*</span>
                    </label>
                    <select id="edit-supplier_id" name="supplier_id" required 
                            class="form-select w-full rounded-md border-gray-300">
                        <option value="">Pilih Supplier</option>
                        ${suppliers.map(s => 
                            `<option value="${s.id}" ${s.id == item.supplier_id ? 'selected' : ''}>
                                ${s.nama}
                            </option>`
                        ).join('')}
                    </select>
                </div>

                <!-- Kategori -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="edit-kategori_id" name="kategori_id" required 
                            class="form-select w-full rounded-md border-gray-300">
                        <option value="">Pilih Kategori</option>
                        ${categories.map(c => 
                            `<option value="${c.id}" ${c.id == item.kategori_id ? 'selected' : ''}>
                                ${c.nama}
                            </option>`
                        ).join('')}
                    </select>
                </div>

                <!-- Produk -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Produk <span class="text-red-500">*</span>
                    </label>
                    <select id="edit-produk_id" name="produk_id" required 
                            class="form-select w-full rounded-md border-gray-300">
                        <option value="">Pilih Produk</option>
                        ${products.map(p => 
                            `<option value="${p.id}" ${p.id == item.produk_id ? 'selected' : ''}>
                                ${p.nama}
                            </option>`
                        ).join('')}
                    </select>
                </div>

                <!-- Berat -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Berat (kg) <span class="text-red-500">*</span>
                    </label>
                    <div class="flex">
                        <input type="number" name="berat" step="0.01" required 
                               value="${item.berat}"
                               class="form-input flex-1 rounded-md border-gray-300">
                        <button type="button" onclick="handleTimbang()"
                                class="ml-2 px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Timbang
                        </button>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan
                    </label>
                    <textarea name="keterangan" rows="3" 
                              class="form-textarea w-full rounded-md border-gray-300">${item.keterangan || ''}</textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    `;

    // Setup event listeners
    document.getElementById('form-edit').addEventListener('submit', handleEdit);
    document.getElementById('edit-kategori_id').addEventListener('change', function(e) {
        produk_id1="";
        if (e.target.value) {
            loadProducts(e.target.value);
        }
    });
}

function closeEditModal() {
    const modal = document.getElementById('modal-edit');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Table Functions
function updateTable(data) {
    const tbody = document.getElementById('table-barang-masuk');
    if (!tbody) return;

    tbody.innerHTML = '';

    data.forEach(item => {
        const row = `
            <tr>
                <td class="px-6 py-4">${formatDate(item.tanggal)}</td>
                <td class="px-6 py-4">${item.supplier_nama}</td>
                <td class="px-6 py-4">${item.kategori_nama}</td>
                <td class="px-6 py-4">${item.produk_nama}</td>
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

// Filter Functions
function setupFilters() {
    const filters = ['filter-supplier', 'filter-kategori', 'filter-tanggal'].map(
        id => document.getElementById(id)
    );

    filters.forEach(filter => {
        if (filter) {
            filter.addEventListener('change', applyFilters);
        }
    });
}

function applyFilters() {
    const supplier_id = document.getElementById('filter-supplier')?.value || '';
    const kategori_id = document.getElementById('filter-kategori')?.value || '';
    const tanggal = document.getElementById('filter-tanggal')?.value || '';

    const params = new URLSearchParams();
    if (supplier_id) params.append('supplier_id', supplier_id);
    if (kategori_id) params.append('kategori_id', kategori_id);
    if (tanggal) params.append('tanggal', tanggal);

    fetch(`../timbangan_rekap/api/inventory/barang_masuk.php?${params}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                updateTable(result.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Utility Functions
function resetForm() {
    const form = document.getElementById('form-barang-masuk');
    if (form) {
        form.reset();
        selectedProduct = null;
    }
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function showMessage(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-lg ${
        type === 'success' ? 'bg-green-100 text-green-700' :
        type === 'error' ? 'bg-red-100 text-red-700' :
        'bg-blue-100 text-blue-700'
    }`;
    alertDiv.textContent = message;
    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// Global functions for edit and delete
window.editItem = function(id) {
    fetch(`../timbangan_rekap/api/inventory/barang_masuk.php?id=${id}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Load necessary data before showing modal
                Promise.all([
                    // Load kategori if not loaded
                    categories.length ? Promise.resolve() : loadCategories(),
                    // Load suppliers if not loaded
                    suppliers.length ? Promise.resolve() : loadSuppliers(),
                    // Load products for the item's kategori
                    loadProducts(result.data.kategori_id)
                ]).then(() => {
                    showEditModal(result.data);
                });
            } else {
                showMessage(result.message || 'Gagal memuat data', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Gagal memuat data', 'error');
        });
};

window.deleteItem = function(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        return;
    }

    fetch(`../timbangan_rekap/api/inventory/barang_masuk.php?id=${id}`, {
        method: 'DELETE'
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showMessage('Data berhasil dihapus', 'success');
                loadBarangMasuk();
            } else {
                showMessage(result.message || 'Gagal menghapus data', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Gagal menghapus data', 'error');
        });
};

// Check if timbangan is connected
let timbanganConnected = false;

// Function to connect to timbangan
async function connectTimbangan() {
    try {
        // Implement your timbangan connection logic here
        timbanganConnected = true;
        showMessage('Timbangan terhubung', 'success');
    } catch (error) {
        console.error('Error connecting to timbangan:', error);
        showMessage('Gagal menghubungkan timbangan', 'error');
        timbanganConnected = false;
    }
}

// Function to read from timbangan
async function readTimbangan() {
    if (!timbanganConnected) {
        showMessage('Timbangan tidak terhubung', 'error');
        return null;
    }

    try {
        // Implement your timbangan reading logic here
        // For now, return random value
        return (Math.random() * (100 - 0.1) + 0.1).toFixed(2);
    } catch (error) {
        console.error('Error reading timbangan:', error);
        showMessage('Gagal membaca timbangan', 'error');
        return null;
    }
}

// Initial setup
document.addEventListener('DOMContentLoaded', function() {
    // Try to connect to timbangan
    connectTimbangan();

    // Check if we're on the barang masuk page
    if (document.getElementById('form-barang-masuk')) {
        // Load initial data
        loadSuppliers();
        loadCategories();
        loadBarangMasuk();

        // Setup event listeners for the form
        setupFormListeners();
    }
});


// Setup Form Listeners Function
function setupFormListeners() {
    // Form barang masuk
    const form = document.getElementById('form-barang-masuk');
    if (form) {
        form.addEventListener('submit', handleSubmit);
    }

    // Timbang button
    const timbangBtn = document.getElementById('timbang-btn');
    if (timbangBtn) {
        timbangBtn.addEventListener('click', handleTimbang);
    }

    // Kategori change
    const kategoriSelect = document.getElementById('kategori_id');
    if (kategoriSelect) {
        kategoriSelect.addEventListener('change', function(e) {
            if (e.target.value) {
                loadProducts(e.target.value);
            }
        });
    }

    // Setup filters
    setupFilters();
}

// Perbaiki path API
function loadSuppliers() {
    fetch('../timbangan_rekap/api/master/supplier.php')  // Perbaiki path
        .then(response => response.json())
        .then(result => {
            if (result.success && Array.isArray(result.data)) {
                suppliers = result.data;
                updateSupplierDropdowns();
            }
        })
        .catch(error => console.error('Error loading suppliers:', error));
}

function loadCategories() {
    fetch('../timbangan_rekap/api/master/kategori.php')  // Perbaiki path
        .then(response => response.json())
        .then(result => {
            if (result.success && Array.isArray(result.data)) {
                categories = result.data;
                updateCategoryDropdowns();
            }
        })
        .catch(error => console.error('Error loading categories:', error));
}

function loadProducts(kategoriId) {
    fetch(`../timbangan_rekap/api/master/produk.php?kategori_id=${kategoriId}`)  // Perbaiki path
        .then(response => response.json())
        .then(result => {
            
            if (result.success && Array.isArray(result.data)) {
                products = result.data;
                console.log(result.data);
                updateProductDropdown();
            }
        })
        .catch(error => console.error('Error loading products:', error));
}

function loadBarangMasuk() {
    fetch('../timbangan_rekap/api/inventory/barang_masuk.php')  // Perbaiki path
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                updateTable(result.data);
            }
        })
        .catch(error => console.error('Error loading barang masuk:', error));
}

// Initial setup
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the barang masuk page
    const formBarangMasuk = document.getElementById('form-barang-masuk');
    if (formBarangMasuk) {
        // Setup event listeners
        setupFormListeners();
        
        // Load initial data
        loadSuppliers();
        loadCategories();
        loadBarangMasuk();
    }
});
</script>