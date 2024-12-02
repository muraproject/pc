<?php if (!isset($_SESSION['user_id'])) header('Location: ../login.html'); ?>

<div class="container mx-auto px-4 py-8">
    <!-- Stock Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Kategori</h3>
            <p class="text-3xl font-bold text-blue-600" id="total-kategori">0</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Produk</h3>
            <p class="text-3xl font-bold text-green-600" id="total-produk">0</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Stock</h3>
            <p class="text-3xl font-bold text-purple-600" id="total-stock">0 kg</p>
        </div>
    </div>

    <!-- Stock Per Category -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Stock per Kategori</h2>
            <div id="category-list" class="space-y-6">
                <!-- Category items will be inserted here -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadStock();
});

function loadStock() {
    fetch('../timbangan_rekap/api/inventory/stock.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateDashboard(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

function updateDashboard(data) {
    if (!data || !data.categories) return;

    document.getElementById('total-kategori').textContent = data.categories.length;
    
    let totalProducts = 0;
    let totalStock = 0;
    
    const categoryList = document.getElementById('category-list');
    categoryList.innerHTML = '';

    data.categories.forEach(category => {
        totalProducts += category.products.length;
        totalStock += parseFloat(category.total || 0);

        const categoryElement = `
            <div class="border rounded-lg p-4 mb-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">${category.nama}</h3>
                    <span class="text-lg font-bold ${getStockColorClass(category.total)}">
                        ${formatNumber(category.total)} kg
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    ${category.products.map(product => `
                        <div class="bg-gray-50 rounded p-3">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">${product.nama}</span>
                                <span class="font-semibold ${getStockColorClass(product.stock)}">
                                    ${formatNumber(product.stock)} kg
                                </span>
                            </div>
                            <div class="text-sm text-gray-500 mt-1">
                                <span>Masuk: ${formatNumber(product.total_masuk)} kg</span>
                                <span class="mx-2">|</span>
                                <span>Keluar: ${formatNumber(product.total_keluar)} kg</span>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
        categoryList.innerHTML += categoryElement;
    });

    document.getElementById('total-produk').textContent = totalProducts;
    document.getElementById('total-stock').textContent = formatNumber(totalStock) + ' kg';
}

function getStockColorClass(stock) {
    stock = parseFloat(stock) || 0;
    if (stock <= 0) return 'text-red-600';
    if (stock < 100) return 'text-yellow-600';
    return 'text-green-600';
}


function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(parseFloat(number) || 0);
}
</script>
