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
    fetch('../api/inventory/stock.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateDashboard(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

function updateDashboard(categories) {
    // Update summary cards
    document.getElementById('total-kategori').textContent = categories.length;
    
    let totalProducts = 0;
    let totalStock = 0;
    
    categories.forEach(category => {
        totalProducts += category.products.length;
        totalStock += parseFloat(category.stock || 0);
    });
    
    document.getElementById('total-produk').textContent = totalProducts;
    document.getElementById('total-stock').textContent = totalStock.toFixed(2) + ' kg';

    // Update category list
    const categoryList = document.getElementById('category-list');
    categoryList.innerHTML = '';

    categories.forEach(category => {
        const categoryElement = document.createElement('div');
        categoryElement.className = 'border rounded-lg p-4';
        
        categoryElement.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">${category.kategori}</h3>
                <span class="text-lg font-bold ${parseFloat(category.stock) > 0 ? 'text-green-600' : 'text-red-600'}">
                    ${parseFloat(category.stock).toFixed(2)} kg
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                ${category.products.map(product => `
                    <div class="bg-gray-50 rounded p-3">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">${product.nama}</span>
                            <span class="font-semibold ${parseFloat(product.stock) > 0 ? 'text-green-600' : 'text-red-600'}">
                                ${parseFloat(product.stock).toFixed(2)} kg
                            </span>
                        </div>
                        <div class="text-sm text-gray-500 mt-1">
                            <span>Masuk: ${parseFloat(product.total_masuk).toFixed(2)} kg</span>
                            <span class="mx-2">|</span>
                            <span>Keluar: ${parseFloat(product.total_keluar).toFixed(2)} kg</span>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
        
        categoryList.appendChild(categoryElement);
    });
}
</script>
