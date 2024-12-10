<?php
// Get required data
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
$buyers = $conn->query("SELECT id, name FROM buyers ORDER BY name");
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
                        <label class="block text-sm font-medium text-gray-700">Pembeli</label>
                        <select id="buyer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Pembeli</option>
                            <?php while ($buyer = $buyers->fetch_assoc()): ?>
                                <option value="<?php echo $buyer['id']; ?>"><?php echo htmlspecialchars($buyer['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select id="category_id" onchange="loadProducts(this.value)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Kategori</option>
                            <?php while ($category = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Produk</label>
                        <select id="product_id" onchange="loadLastPrice(this.value)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Produk</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Harga per kg</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" id="price" step="1" min="0"
                                class="pl-12 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>
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
                    <table id="items-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Berat (kg)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga/kg</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
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
            <button onclick="saveReceipt()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Simpan Kwitansi
            </button>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="receiptModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Preview Kwitansi</h3>
            <button onclick="closeReceiptModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                    Batal
                </button>
                <button onclick="confirmSaveReceipt()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                    Simpan & Cetak
                </button>
            <div id="receipt-content">

            </div>
            <div class="mt-4 flex justify-end space-x-4">
                
            </div>
        </div>
    </div>
</div>

<script>
let currentItems = [];
let isScaleStable = false;

function loadProducts(categoryId) {
    if (!categoryId) {
        document.getElementById('product_id').innerHTML = '<option value="">Pilih Produk</option>';
        return;
    }

    fetch(`api/get_products.php?category_id=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('product_id');
            select.innerHTML = '<option value="">Pilih Produk</option>' +
                (Array.isArray(data) ? data.map(product => 
                    `<option value="${product.id}">${product.name}</option>`
                ).join('') : '');
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('product_id').innerHTML = '<option value="">Error loading products</option>';
        });
}

// Form handling
document.getElementById('weighing-form').addEventListener('submit', function(e) {
    e.preventDefault();
    if (!isScaleStable) {
        alert('Harap stabilkan timbangan terlebih dahulu');
        return;
    }

    const buyer_id = document.getElementById('buyer_id').value;
    const product_id = document.getElementById('product_id').value;
    const price = 0;
    const weight = parseFloat(document.getElementById('scale-value').textContent);

    if (!buyer_id || !product_id) {
        alert('Harap lengkapi semua data dengan benar');
        return;
    }

    const buyerSelect = document.getElementById('buyer_id');
    const productSelect = document.getElementById('product_id');

    const item = {
        buyer_id: buyer_id,
        buyer_name: buyerSelect.options[buyerSelect.selectedIndex].text,
        product_id: product_id,
        product_name: productSelect.options[productSelect.selectedIndex].text,
        weight: weight,
        price: price
    };

    currentItems.push(item);
    updateItemsTable();
    resetForm();
});

function updateItemsTable() {
    const tbody = document.getElementById('items-table').getElementsByTagName('tbody')[0];
    tbody.innerHTML = '';
    let totalAmount = 0;

    currentItems.forEach((item, index) => {
        const total = item.weight * item.price;
        totalAmount += total;

        const row = tbody.insertRow();
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.product_name}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.weight.toFixed(2)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp ${item.price.toLocaleString()}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp ${total.toLocaleString()}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="deleteItem(${index})" class="text-red-600 hover:text-red-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </td>
        `;
    });
}

function saveReceipt() {
    if (currentItems.length === 0) {
        alert('Tidak ada item untuk disimpan');
        return;
    }

    const buyer_id = document.getElementById('buyer_id').value;
    if (!buyer_id) {
        alert('Pilih pembeli terlebih dahulu');
        return;
    }

    const buyerSelect = document.getElementById('buyer_id');
    const buyer_name = buyerSelect.options[buyerSelect.selectedIndex].text;

    let receiptContent = `
        <div class="space-y-4">
            <div>
                <p class="font-medium">Pembeli: ${buyer_name}</p>
                <p class="text-sm text-gray-500">Tanggal: ${new Date().toLocaleString()}</p>
                <input type="hidden" id="preview_buyer_id" value="${buyer_id}">
            </div>
    `;

    let totalAmount = 0;
    currentItems.forEach(item => {
        const itemTotal = item.weight * item.price;
        totalAmount += itemTotal;
        receiptContent += `
            <div>
                <h4 class="font-medium">${item.product_name}</h4>
                <p class="text-sm">${item.weight.toFixed(2)} kg x Rp ${item.price.toLocaleString()}</p>
                <p class="text-sm">Total: Rp ${itemTotal.toLocaleString()}</p>
            </div>
        `;
    });

    receiptContent += `
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="font-medium">Total Keseluruhan:</p>
                <p class="text-lg font-bold">Rp ${totalAmount.toLocaleString()}</p>
            </div>
        </div>
    `;

    document.getElementById('receipt-content').innerHTML = receiptContent;
    document.getElementById('receiptModal').classList.remove('hidden');
}

function confirmSaveReceipt() {
    const buyer_id = document.getElementById('preview_buyer_id').value;
    
    const receipt_data = {
        buyer_id: buyer_id,
        items: currentItems.map(item => ({
            product_id: item.product_id,
            weight: parseFloat(item.weight),
            price: parseFloat(item.price)
        }))
    };

    fetch('api/save_weighing_out.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(receipt_data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            printReceipt(data.receipt_id);
            currentItems = [];
            updateItemsTable();
            resetForm();
            closeReceiptModal();
            alert('Data berhasil disimpan');
        } else {
            alert('Gagal menyimpan data: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    });
}

function printReceipt(receiptId) {
    fetch(`api/get_receipt_out.php?id=${receiptId}`)
        .then(response => response.json())
        .then(data => {
            const printContent = document.createElement('div');
            printContent.innerHTML = `
                <style>
                    @media print {
                        @page { size: 80mm auto; margin: 0; }
                        body { font-family: Arial, sans-serif; padding: 10mm; }
                        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                        th, td { padding: 5px; text-align: left; }
                        .text-center { text-align: center; }
                        .text-right { text-align: right; }
                        .heading { font-size: 16px; font-weight: bold; margin: 10px 0; }
                        .info { font-size: 12px; margin: 5px 0; }
                        .total { font-weight: bold; border-top: 1px solid #000; }
                    }
                </style>
                <div class="text-center heading">KWITANSI BARANG KELUAR</div>
                <div class="info">No: ${data.receipt_id}</div>
                <div class="info">Tanggal: ${data.date}</div>
                <div class="info">Pembeli: ${data.buyer_name}</div>
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-right">Berat</th>
                            <th class="text-right">Harga/kg</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            let totalAmount = 0;
            data.items.forEach(item => {
                const itemTotal = item.weight * item.price;
                totalAmount += itemTotal;
                printContent.innerHTML += `
                    <tr>
                        <td>${item.product_name}</td>
                        <td class="text-right">${parseFloat(item.weight).toFixed(2)} kg</td>
                        <td class="text-right">Rp ${parseFloat(item.price).toLocaleString()}</td>
                        <td class="text-right">Rp ${itemTotal.toLocaleString()}</td>
                    </tr>
                `;
            });

            printContent.innerHTML += `
                        <tr class="total">
                            <td colspan="3">Total</td>
                            <td class="text-right">Rp ${totalAmount.toLocaleString()}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center info">Terima kasih</div>
            `;

            const printWindow = window.open('', 'PRINT', 'height=600,width=800');
            printWindow.document.write(printContent.innerHTML);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        });
}

function loadLastPrice(productId) {
    if (!productId) {
        document.getElementById('price').value = '';
        return;
    }

    fetch(`api/get_last_price.php?product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.price) {
                document.getElementById('price').value = data.price;
            }
        });
}

function closeReceiptModal() {
    document.getElementById('receiptModal').classList.add('hidden');
}

function resetForm() {
    // document.getElementById('weighing-form').reset();
    // resetScale();
}

// Scale related functions
function stabilizeScale() {
    isScaleStable = true;
}

function resetScale() {
    document.getElementById('scale-value').textContent = '0.00';
    isScaleStable = false;
}

function mockScale() {
    setInterval(() => {
        if (!isScaleStable) {
            const randomWeight = Math.random() * 100;
            document.getElementById('scale-value').textContent = randomWeight.toFixed(2);
        }
    }, 500);
}

function deleteItem(index) {
    if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
        currentItems.splice(index, 1);
        updateItemsTable();
    }
}

function cancelWeighing() {
    if (confirm('Apakah Anda yakin ingin membatalkan timbangan ini?')) {
        currentItems = [];
        updateItemsTable();
        resetForm();
    }
}

function mockScale() {
    setInterval(() => {
        if (!isScaleStable) {
            const randomWeight = Math.random() * 100;
            document.getElementById('scale-value').textContent = randomWeight.toFixed(2);
        }
    }, 500);
}

if (location.hostname === 'localhost' || location.hostname === '127.0.0.1') {
    mockScale();
}
</script>
