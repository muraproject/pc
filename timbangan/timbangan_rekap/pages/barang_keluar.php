 
<?php if (!isset($_SESSION['user_id'])) header('Location: ../login.html'); ?>

<div class="container mx-auto px-4 py-8">
    <!-- Form Input Barang Keluar -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-6">Input Barang Keluar</h2>
        
        <form id="form-barang-keluar" class="space-y-4">
            <!-- Step indicator -->
            <div class="flex justify-between mb-8">
                <div class="step active" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-text">Customer</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-text">Produk</div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-circle">3</div>
                    <div class="step-text">Timbang</div>
                </div>
            </div>

            <!-- Step 1: Customer -->
            <div class="step-content" id="step-1">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Customer <span class="text-red-500">*</span>
                        </label>
                        <select id="customer_id" name="customer_id" required 
                                class="form-select w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Pilih Customer</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Step 2: Produk -->
            <div class="step-content hidden" id="step-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select id="kategori_id" name="kategori_id" required 
                                class="form-select w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Produk <span class="text-red-500">*</span>
                        </label>
                        <select id="produk_id" name="produk_id" required 
                                class="form-select w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Pilih Produk</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Stock Tersedia
                        </label>
                        <div id="stock-info" class="text-lg font-semibold text-gray-800">
                            0 kg
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Timbang -->
            <div class="step-content hidden" id="step-3">
                <div class="grid grid-cols-1 gap-6">
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea id="keterangan" name="keterangan" rows="3"
                                 class="form-textarea w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-between mt-6">
                <button type="button" id="prev-btn" 
                        class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 hidden">
                    Sebelumnya
                </button>
                <button type="button" id="next-btn" 
                        class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Selanjutnya
                </button>
                <button type="submit" id="submit-btn" 
                        class="px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 hidden">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Data Barang Keluar -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-6">Data Barang Keluar</h2>
        
        <!-- Filter -->
        <div class="flex flex-wrap gap-4 mb-6">
            <select id="filter-customer" class="form-select rounded-md border-gray-300 shadow-sm">
                <option value="">Semua Customer</option>
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
                            Customer
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
                <tbody id="table-barang-keluar" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Styles for step indicator -->
<style>
.step {
    flex: 1;
    text-align: center;
    position: relative;
}

.step:not(:last-child):after {
    content: '';
    position: absolute;
    top: 15px;
    left: 60%;
    width: 80%;
    height: 2px;
    background: #e5e7eb;
    z-index: 0;
}

.step.active:not(:last-child):after {
    background: #3b82f6;
}

.step-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    position: relative;
    z-index: 1;
}

.step.active .step-circle {
    background: #3b82f6;
    color: white;
}

.step-text {
    color: #6b7280;
    font-size: 0.875rem;
}

.step.active .step-text {
    color: #3b82f6;
    font-weight: 500;
}
</style>

<script>

let currentStep = 1;
let customers = [];
let categories = [];
let products = [];
let currentStock = 0;

console.log("cek");

document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    console.log("cek");
    loadCustomers();
    loadCategories();
    loadBarangKeluar();
    
    // Setup form navigation
    setupFormNavigation();
    
    // Setup form submission
    document.getElementById('form-barang-keluar').addEventListener('submit', handleSubmit);
    
    // Setup timbang button
    document.getElementById('timbang-btn').addEventListener('click', handleTimbang);
    
    // Setup filters
    setupFilters();
});

// Form Navigation Functions
function setupFormNavigation() {
    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    const submitBtn = document.getElementById('submit-btn');

    nextBtn.addEventListener('click', () => {
        if (validateCurrentStep()) {
            currentStep++;
            updateFormView();
        }
    });

    prevBtn.addEventListener('click', () => {
        currentStep--;
        updateFormView();
    });
}

function validateCurrentStep() {
    switch(currentStep) {
        case 1:
            const customerId = document.getElementById('customer_id').value;
            if (!customerId) {
                alert('Pilih customer terlebih dahulu');
                return false;
            }
            return true;

        case 2:
            const kategoriId = document.getElementById('kategori_id').value;
            const produkId = document.getElementById('produk_id').value;
            if (!kategoriId || !produkId) {
                alert('Pilih kategori dan produk');
                return false;
            }
            if (currentStock <= 0) {
                alert('Stock tidak tersedia');
                return false;
            }
            return true;

        case 3:
            const berat = parseFloat(document.getElementById('berat').value);
            if (!berat || berat <= 0) {
                alert('Masukkan berat yang valid');
                return false;
            }
            if (berat > currentStock) {
                alert('Berat melebihi stock tersedia');
                return false;
            }
            return true;
    }
    return true;
}

function updateFormView() {
    // Update step indicators
    document.querySelectorAll('.step').forEach(step => {
        const stepNum = parseInt(step.dataset.step);
        step.classList.toggle('active', stepNum <= currentStep);
    });

    // Show/hide step content
    document.querySelectorAll('.step-content').forEach((content, index) => {
        content.classList.toggle('hidden', index + 1 !== currentStep);
    });

    // Update navigation buttons
    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    const submitBtn = document.getElementById('submit-btn');

    prevBtn.classList.toggle('hidden', currentStep === 1);
    nextBtn.classList.toggle('hidden', currentStep === 3);
    submitBtn.classList.toggle('hidden', currentStep !== 3);
}

// Data Loading Functions
function loadCustomers() {
    fetch('../timbangan_rekap/api/master/customer.php')
        .then(response => response.json())
        .then(data => {
            customers = data;
            updateCustomerDropdowns();
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

function loadStock(produkId) {
    fetch(`../timbangan_rekap/api/inventory/stock.php?produk_id=${produkId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentStock = data.stock;
                console.log(data);
                updateStockInfo();
            }
        })
        .catch(error => console.error('Error:', error));
}

// Update UI Functions
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

function updateCustomerDropdowns() {
    const customerSelect = document.getElementById('customer_id');
    const filtercustomer = document.getElementById('filter-customer');
    
    fetch('../timbangan_rekap/api/master/customer.php')
        .then(response => response.json())
        .then(result => {
            if (result.success && Array.isArray(result.data)) {
                const options = result.data.map(s => 
                    `<option value="${s.id}">${s.nama}</option>`
                ).join('');

                customerSelect.innerHTML = '<option value="">Pilih customer</option>' + options;
                filtercustomer.innerHTML = '<option value="">Semua customer</option>' + options;
            }
        })
        .catch(error => console.error('Error loading customers:', error));
}

function updateProductDropdown() {
    const productSelect = document.getElementById('produk_id');
    console.log(productSelect ? "produk_id ditemukan" : "produk_id tidak ditemukan");
    
    const options = products.map(p => 
        `<option value="${p.id}">${p.nama}</option>`
    ).join('');

    productSelect.innerHTML = '<option value="">Pilih Produk</option>' + options;

    // Event listener untuk perubahan produk
    productSelect.addEventListener('change', (e) => {
        console.log("hai");
        if (e.target.value) {
            loadStock(e.target.value);
            
        } else {
            currentStock = 0;
            updateStockInfo();
        }
    });

    // productSelect.onchange = function(){
    //     console.log("hoha");
    // };
}

updateProductDropdown();

function updateStockInfo() {
    const stockInfo = document.getElementById('stock-info');
    stockInfo.textContent = `${formatNumber(currentStock)} kg`;
    stockInfo.className = currentStock > 0 ? 'text-green-600' : 'text-red-600';
}

// Form Handling Functions
function handleSubmit(e) {
    e.preventDefault();
    
    const formData = {
        customer_id: document.getElementById('customer_id').value,
        produk_id: document.getElementById('produk_id').value,
        berat: document.getElementById('berat').value,
        keterangan: document.getElementById('keterangan').value
    };

    fetch('../timbangan_rekap/api/inventory/barang_keluar.php', {
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
            loadBarangKeluar();
        } else {
            alert('Gagal menyimpan data: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Data Loading and Table Functions
function loadBarangKeluar() {
    fetch('../timbangan_rekap/api/inventory/barang_keluar.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateTable(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

function updateTable(data) {
    const tbody = document.getElementById('table-barang-keluar');
    tbody.innerHTML = '';

    data.forEach(item => {
        const row = `
            <tr>
                <td class="px-6 py-4">${formatDate(item.tanggal)}</td>
                <td class="px-6 py-4">${item.customer}</td>
                <td class="px-6 py-4">${item.kategori}</td>
                <td class="px-6 py-4">${item.produk}</td>
                <td class="px-6 py-4">${formatNumber(item.berat)} kg</td>
                <td class="px-6 py-4">${item.keterangan || '-'}</td>
                <td class="px-6 py-4">
                    <button onclick="editItem(${item.id})" 
                            class="text-blue-600 hover:text-blue-800 mr-2"
                            title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteItem(${item.id})"
                            class="text-red-600 hover:text-red-800"
                            title="Hapus">
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
    const filterCustomer = document.getElementById('filter-customer');
    const filterCategory = document.getElementById('filter-kategori');
    const filterDate = document.getElementById('filter-tanggal');

    const filters = [filterCustomer, filterCategory, filterDate];
    filters.forEach(filter => {
        filter.addEventListener('change', applyFilters);
    });
}

function applyFilters() {
    const customerId = document.getElementById('filter-customer').value;
    const categoryId = document.getElementById('filter-kategori').value;
    const date = document.getElementById('filter-tanggal').value;

    let url = '../timbangan_rekap/api/inventory/barang_keluar.php?';
    const params = new URLSearchParams();

    if (customerId) params.append('customer_id', customerId);
    if (categoryId) params.append('kategori_id', categoryId);
    if (date) params.append('tanggal', date);

    url += params.toString();

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateTable(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

// CRUD Functions
function editItem(id) {
    fetch(`../timbangan_rekap/api/inventory/barang_keluar.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showEditModal(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

function showEditModal(item) {
    const modal = createModal(`
        <h3 class="text-xl font-bold mb-6">Edit Barang Keluar</h3>
        <form id="form-edit" class="space-y-4">
            <input type="hidden" name="id" value="${item.id}">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                <select name="customer_id" required class="form-select w-full">
                    ${customers.map(c => `
                        <option value="${c.id}" ${c.id === item.customer_id ? 'selected' : ''}>
                            ${c.nama}
                        </option>
                    `).join('')}
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="kategori_id" required class="form-select w-full">
                    ${categories.map(c => `
                        <option value="${c.id}" ${c.id === item.kategori_id ? 'selected' : ''}>
                            ${c.nama}
                        </option>
                    `).join('')}
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Produk</label>
                <select name="produk_id" required class="form-select w-full">
                    ${products.map(p => `
                        <option value="${p.id}" ${p.id === item.produk_id ? 'selected' : ''}>
                            ${p.nama}
                        </option>
                    `).join('')}
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Berat (kg)</label>
                <input type="number" name="berat" step="0.01" required 
                       value="${item.berat}" class="form-input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                <textarea name="keterangan" rows="3" class="form-textarea w-full">${item.keterangan || ''}</textarea>
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-md">Batal</button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-md">Simpan</button>
            </div>
        </form>
    `);

    modal.querySelector('#form-edit').addEventListener('submit', handleEdit);
}

function handleEdit(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    fetch('../timbangan_rekap/api/inventory/barang_keluar.php', {
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
            closeModal();
            loadBarangKeluar();
        } else {
            alert('Gagal mengupdate data: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteItem(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        fetch(`../timbangan_rekap/api/inventory/barang_keluar.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Data berhasil dihapus');
                loadBarangKeluar();
            } else {
                alert('Gagal menghapus data: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Modal Functions
function createModal(content) {
    const modalWrapper = document.createElement('div');
    modalWrapper.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modalWrapper.id = 'modal';
    
    const modalContent = document.createElement('div');
    modalContent.className = 'bg-white rounded-lg w-full max-w-2xl mx-4 p-6';
    modalContent.innerHTML = content;
    
    modalWrapper.appendChild(modalContent);
    document.body.appendChild(modalWrapper);
    
    return modalContent;
}

function closeModal() {
    const modal = document.getElementById('modal');
    if (modal) {
        modal.remove();
    }
}

// Reset Functions
function resetForm() {
    document.getElementById('form-barang-keluar').reset();
    currentStep = 1;
    updateFormView();
    currentStock = 0;
    updateStockInfo();
}

// Timbangan Integration
function handleTimbang() {
    // Implement timbangan integration here
    // For example:
    if (typeof Android !== 'undefined') {
        // Android interface
        try {
            const weight = Android.getWeight();
            document.getElementById('berat').value = weight;
        } catch (error) {
            console.error('Error getting weight:', error);
            alert('Gagal membaca timbangan');
        }
    } else {
        // Demo/testing mode
        const randomWeight = (Math.random() * (10 - 0.1) + 0.1).toFixed(2);
        document.getElementById('berat').value = randomWeight;
    }
}
function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

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
</script>
