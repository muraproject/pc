<?php if (!isset($_SESSION['user_id'])) header('Location: ../login.html'); ?>

<div class="container mx-auto px-4 py-8">
    <!-- Form Timbang Biaya -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-6">Timbang Biaya Tenaga Kerja</h2>
        
        <form id="form-timbang-biaya" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Karyawan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Karyawan <span class="text-red-500">*</span>
                    </label>
                    <select id="karyawan_id" name="karyawan_id" required 
                            class="form-select w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Karyawan</option>
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

                <!-- Biaya per KG -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Biaya per KG (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="biaya_per_kg" name="biaya_per_kg" required
                           class="form-input w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <!-- Total Biaya (Read Only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Total Biaya
                    </label>
                    <input type="text" id="total_biaya" readonly
                           class="form-input w-full rounded-md border-gray-300 bg-gray-50">
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

    <!-- Tabel Data Biaya -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-6">Data Biaya Tenaga Kerja</h2>
        
        <!-- Filter -->
        <div class="flex flex-wrap gap-4 mb-6">
            <select id="filter-karyawan" class="form-select rounded-md border-gray-300 shadow-sm">
                <option value="">Semua Karyawan</option>
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
                            Karyawan
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
                            Biaya/kg
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Keterangan
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="table-biaya" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be loaded here -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="px-6 py-4 font-bold text-right">Total:</td>
                        <td id="total-berat" class="px-6 py-4 font-bold">0 kg</td>
                        <td></td>
                        <td id="grand-total" class="px-6 py-4 font-bold">Rp 0</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script src="">
    let karyawan = [];
let categories = [];
let products = [];
let totalData = {
    berat: 0,
    biaya: 0
};

document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    loadKaryawan();
    loadCategories();
    loadBiayaData();
    
    // Setup event listeners
    setupEventListeners();
    
    // Setup form submission
    document.getElementById('form-timbang-biaya').addEventListener('submit', handleSubmit);
    
    // Setup filters
    setupFilters();
});

function setupEventListeners() {
    // Timbang button
    document.getElementById('timbang-btn').addEventListener('click', handleTimbang);
    
    // Auto calculate total
    ['berat', 'biaya_per_kg'].forEach(id => {
        document.getElementById(id).addEventListener('input', calculateTotal);
    });
    
    // Category change
    document.getElementById('kategori_id').addEventListener('change', function(e) {
        if (e.target.value) {
            loadProducts(e.target.value);
        } else {
            resetProductDropdown();
        }
    });
}

function loadKaryawan() {
    fetch('../api/master/karyawan.php')
        .then(response => response.json())
        .then(data => {
            karyawan = data;
            updateKaryawanDropdowns();
        })
        .catch(error => console.error('Error:', error));
}

function loadCategories() {
    fetch('../api/master/kategori.php')
        .then(response => response.json())
        .then(data => {
            categories = data;
            updateCategoryDropdowns();
        })
        .catch(error => console.error('Error:', error));
}

function loadProducts(kategoriId) {
    fetch(`../api/master/produk.php?kategori_id=${kategoriId}`)
        .then(response => response.json())
        .then(data => {
            products = data;
            updateProductDropdown();
        })
        .catch(error => console.error('Error:', error));
}

function loadBiayaData() {
    const karyawanId = document.getElementById('filter-karyawan').value;
    const kategoriId = document.getElementById('filter-kategori').value;
    const tanggal = document.getElementById('filter-tanggal').value;

    let url = '../api/inventory/biaya_tenaga.php';
    const params = new URLSearchParams();
    
    if (karyawanId) params.append('karyawan_id', karyawanId);
    if (kategoriId) params.append('kategori_id', kategoriId);
    if (tanggal) params.append('tanggal', tanggal);
    
    if (params.toString()) {
        url += '?' + params.toString();
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateTable(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

function updateKaryawanDropdowns() {
    const karyawanSelect = document.getElementById('karyawan_id');
    const filterKaryawan = document.getElementById('filter-karyawan');
    
    const options = karyawan.map(k => 
        `<option value="${k.id}">${k.nama}</option>`
    ).join('');

    karyawanSelect.innerHTML = '<option value="">Pilih Karyawan</option>' + options;
    filterKaryawan.innerHTML = '<option value="">Semua Karyawan</option>' + options;
}

function updateCategoryDropdowns() {
    const categorySelect = document.getElementById('kategori_id');
    const filterCategory = document.getElementById('filter-kategori');
    
    const options = categories.map(c => 
        `<option value="${c.id}">${c.nama}</option>`
    ).join('');

    categorySelect.innerHTML = '<option value="">Pilih Kategori</option>' + options;
    filterCategory.innerHTML = '<option value="">Semua Kategori</option>' + options;
}

function updateProductDropdown() {
    const productSelect = document.getElementById('produk_id');
    
    const options = products.map(p => 
        `<option value="${p.id}">${p.nama}</option>`
    ).join('');

    productSelect.innerHTML = '<option value="">Pilih Produk</option>' + options;
}

function resetProductDropdown() {
    document.getElementById('produk_id').innerHTML = 
        '<option value="">Pilih Produk</option>';
}

function calculateTotal() {
    const berat = parseFloat(document.getElementById('berat').value) || 0;
    const biayaPerKg = parseFloat(document.getElementById('biaya_per_kg').value) || 0;
    const total = berat * biayaPerKg;
    
    document.getElementById('total_biaya').value = formatCurrency(total);
}

function handleTimbang() {
    if (typeof Android !== 'undefined') {
        try {
            const weight = Android.getWeight();
            document.getElementById('berat').value = weight;
            calculateTotal();
        } catch (error) {
            console.error('Error getting weight:', error);
            alert('Gagal membaca timbangan');
        }
    } else {
        // Demo mode
        const randomWeight = (Math.random() * (10 - 0.1) + 0.1).toFixed(2);
        document.getElementById('berat').value = randomWeight;
        calculateTotal();
    }
}

function handleSubmit(e) {
    e.preventDefault();
    
    const formData = {
        karyawan_id: document.getElementById('karyawan_id').value,
        produk_id: document.getElementById('produk_id').value,
        berat: document.getElementById('berat').value,
        biaya_per_kg: document.getElementById('biaya_per_kg').value,
        keterangan: document.getElementById('keterangan').value
    };

    fetch('../api/inventory/biaya_tenaga.php', {
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
            loadBiayaData();
        } else {
            alert('Gagal menyimpan data: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateTable(data) {
    const tbody = document.getElementById('table-biaya');
    tbody.innerHTML = '';
    
    totalData.berat = 0;
    totalData.biaya = 0;

    data.forEach(item => {
        const totalBiaya = item.berat * item.biaya_per_kg;
        totalData.berat += parseFloat(item.berat);
        totalData.biaya += totalBiaya;

        const row = `
            <tr>
                <td class="px-6 py-4">${formatDate(item.tanggal)}</td>
                <td class="px-6 py-4">${item.karyawan}</td>
                <td class="px-6 py-4">${item.kategori}</td>
                <td class="px-6 py-4">${item.produk}</td>
                <td class="px-6 py-4">${formatNumber(item.berat)} kg</td>
                <td class="px-6 py-4">${formatCurrency(item.biaya_per_kg)}</td>
                <td class="px-6 py-4">${formatCurrency(totalBiaya)}</td>
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

    // Update totals
    document.getElementById('total-berat').textContent = `${formatNumber(totalData.berat)} kg`;
    document.getElementById('grand-total').textContent = formatCurrency(totalData.biaya);
}

function setupFilters() {
    const filters = ['filter-karyawan', 'filter-kategori', 'filter-tanggal'];
    filters.forEach(id => {
        document.getElementById(id).addEventListener('change', loadBiayaData);
    });
}

function editItem(id) {
    fetch(`../api/inventory/biaya_tenaga.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showEditModal(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

function deleteItem(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        fetch(`../api/inventory/biaya_tenaga.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Data berhasil dihapus');
                loadBiayaData();
            } else {
                alert('Gagal menghapus data: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function resetForm() {
    document.getElementById('form-timbang-biaya').reset();
    document.getElementById('total_biaya').value = '';
}

// Utility Functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount);
}

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
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
</script>
