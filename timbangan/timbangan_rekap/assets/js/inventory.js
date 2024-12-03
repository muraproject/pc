// Modul untuk menangani operasi stock dan inventaris
const Inventory = {
    // Menyimpan data stock saat ini
    stockData: null,

    // Inisialisasi
    init() {
        this.setupEventListeners();
        this.loadStock();
    },

    // Setup event listeners
    setupEventListeners() {
        // Filter dan pencarian
        document.querySelectorAll('.stock-filter').forEach(filter => {
            filter.addEventListener('change', () => this.loadStock());
        });

        // Timbangan
        const timbangBtn = document.getElementById('timbang-btn');
        if (timbangBtn) {
            timbangBtn.addEventListener('click', this.handleTimbang);
        }

        // Form barang masuk
        const formBarangMasuk = document.getElementById('form-barang-masuk');
        if (formBarangMasuk) {
            formBarangMasuk.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleBarangMasuk(e.target);
            });
        }

        // Form barang keluar
        const formBarangKeluar = document.getElementById('form-barang-keluar');
        if (formBarangKeluar) {
            formBarangKeluar.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleBarangKeluar(e.target);
            });
        }

        // Kategori change event
        const kategoriSelect = document.getElementById('kategori_id');
        if (kategoriSelect) {
            kategoriSelect.addEventListener('change', (e) => {
                this.loadProdukByKategori(e.target.value);
            });
        }
    },

    // Load data stock
    async loadStock() {
        try {
            const response = await fetch('../timbangan_rekap/api/inventory/stock.php');
            const data = await response.json();
            
            if (data.success) {
                this.stockData = data.data;
                this.updateStockDisplay();
            } else {
                this.showMessage(data.message, 'error');
            }
        } catch (error) {
            console.error('Error loading stock:', error);
            this.showMessage('Gagal memuat data stock', 'error');
        }
    },

    // Update tampilan stock
    updateStockDisplay() {
        const stockContainer = document.getElementById('stock-container');
        if (!stockContainer || !this.stockData) return;

        let html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">';
        
        this.stockData.categories.forEach(category => {
            html += `
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold text-gray-700">${category.nama}</h3>
                    <div class="mt-2 space-y-2">
                        ${category.products.map(product => `
                            <div class="flex justify-between items-center">
                                <span>${product.nama}</span>
                                <span class="font-medium ${this.getStockColorClass(product.stock)}">
                                    ${this.formatNumber(product.stock)} kg
                                </span>
                            </div>
                        `).join('')}
                    </div>
                    <div class="mt-3 pt-2 border-t">
                        <div class="flex justify-between text-sm font-semibold">
                            <span>Total:</span>
                            <span>${this.formatNumber(category.total)} kg</span>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        stockContainer.innerHTML = html;
    },

    // Handle timbang button click
    handleTimbang() {
        // Simulasi timbangan untuk development
        const weight = (Math.random() * (100 - 0.1) + 0.1).toFixed(2);
        document.getElementById('berat').value = weight;
    },

    // Handle form barang masuk submit
    async handleBarangMasuk(form) {
        // const formData = new FormData(form);
        
        // try {
        //     const response = await fetch('../timbangan_rekap/api/inventory/barang_masuk.php', {
        //         method: 'POST',
        //         body: formData
        //     });
        //     const data = await response.json();
            
        //     if (data.success) {
        //         this.showMessage('Barang masuk berhasil disimpan', 'success');
        //         form.reset();
        //         this.loadStock();
        //     } else {
        //         this.showMessage(data.message, 'error');
        //     }
        // } catch (error) {
        //     console.error('Error saving barang masuk:', error);
        //     this.showMessage('Gagal menyimpan barang masuk', 'error');
        // }
    },

    // Handle form barang keluar submit
    async handleBarangKeluar(form) {
        const formData = new FormData(form);
        
        try {
            // Cek stock sebelum submit
            const produkId = formData.get('produk_id');
            const berat = parseFloat(formData.get('berat'));
            
            if (!this.checkStock(produkId, berat)) {
                this.showMessage('Stock tidak mencukupi', 'error');
                return;
            }

            const response = await fetch('../timbangan_rekap/api/inventory/barang_keluar.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            if (data.success) {
                this.showMessage('Barang keluar berhasil disimpan', 'success');
                form.reset();
                this.loadStock();
            } else {
                this.showMessage(data.message, 'error');
            }
        } catch (error) {
            console.error('Error saving barang keluar:', error);
            this.showMessage('Gagal menyimpan barang keluar', 'error');
        }
    },

    // Load produk berdasarkan kategori
    async loadProdukByKategori(kategoriId) {
        if (!kategoriId) return;

        try {
            const response = await fetch(`../timbangan_rekap/api/master/produk.php?kategori_id=${kategoriId}`);
            const data = await response.json();
            
            const produkSelect = document.getElementById('produk_id');
            produkSelect.innerHTML = '<option value="">Pilih Produk</option>';
            
            if (data.success) {
                data.data.forEach(produk => {
                    produkSelect.innerHTML += `
                        <option value="${produk.id}">${produk.nama}</option>
                    `;
                });
            }
        } catch (error) {
            console.error('Error loading produk:', error);
            this.showMessage('Gagal memuat data produk', 'error');
        }
    },

    // Check stock availability
    checkStock(produkId, berat) {
        const product = this.findProduct(produkId);
        if (!product) return false;
        return product.stock >= berat;
    },

    // Find product in stockData
    findProduct(produkId) {
        for (const category of this.stockData.categories) {
            const product = category.products.find(p => p.id === produkId);
            if (product) return product;
        }
        return null;
    },

    // Utility functions
    formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    },

    getStockColorClass(stock) {
        if (stock <= 0) return 'text-red-600';
        if (stock < 100) return 'text-yellow-600';
        return 'text-green-600';
    },

    showMessage(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} fixed top-4 right-4 z-50`;
        alertDiv.textContent = message;
        document.body.appendChild(alertDiv);

        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
};

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', () => {
    Inventory.init();
});

// Export untuk akses global
window.Inventory = Inventory;