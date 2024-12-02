// Modul untuk menangani perhitungan supplier dan tenaga kerja
const Hitung = {
    activeTab: 'supplier', // supplier atau tenaga
    selectedData: [],
    totals: {
        berat: 0,
        biaya: 0
    },

    init() {
        this.setupTabListeners();
        this.setupFilterListeners();
        this.setupEventListeners();
        this.loadData();
    },

    setupTabListeners() {
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', (e) => {
                this.switchTab(e.target.dataset.tab);
            });
        });
    },

    setupFilterListeners() {
        // Filter tanggal
        ['start-date', 'end-date'].forEach(id => {
            document.getElementById(id)?.addEventListener('change', () => {
                this.loadData();
            });
        });

        // Filter supplier/karyawan
        const filterSelect = document.getElementById('filter-person');
        if (filterSelect) {
            filterSelect.addEventListener('change', () => this.loadData());
        }
    },

    setupEventListeners() {
        // Handle hitung button
        document.getElementById('hitung-btn')?.addEventListener('click', () => {
            this.calculateSelected();
        });

        // Handle save button
        document.getElementById('save-btn')?.addEventListener('click', () => {
            this.saveCalculation();
        });

        // Handle select all checkbox
        document.getElementById('select-all')?.addEventListener('change', (e) => {
            this.toggleSelectAll(e.target.checked);
        });
    },

    async switchTab(tab) {
        this.activeTab = tab;
        this.selectedData = [];
        this.updateTotals();
        
        // Update UI
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.toggle('active', button.dataset.tab === tab);
        });

        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.toggle('hidden', content.dataset.tab !== tab);
        });

        // Update filter label and placeholder
        const filterLabel = this.activeTab === 'supplier' ? 'Supplier' : 'Karyawan';
        document.getElementById('filter-label').textContent = filterLabel;
        document.getElementById('filter-person').placeholder = `Pilih ${filterLabel}`;

        await this.loadPersons();
        await this.loadData();
    },

    async loadPersons() {
        try {
            const endpoint = this.activeTab === 'supplier' ? 'supplier' : 'karyawan';
            const response = await fetch(`../api/master/${endpoint}.php`);
            const data = await response.json();

            if (data.success) {
                const select = document.getElementById('filter-person');
                select.innerHTML = `<option value="">Semua ${this.activeTab === 'supplier' ? 'Supplier' : 'Karyawan'}</option>`;
                data.data.forEach(person => {
                    select.innerHTML += `<option value="${person.id}">${person.nama}</option>`;
                });
            }
        } catch (error) {
            console.error('Error loading persons:', error);
            this.showMessage('Gagal memuat data', 'error');
        }
    },

    async loadData() {
        const filters = this.getFilters();
        const endpoint = this.activeTab === 'supplier' ? 'barang_masuk' : 'biaya_tenaga';

        try {
            const response = await fetch(`../api/inventory/${endpoint}.php?${new URLSearchParams(filters)}`);
            const data = await response.json();

            if (data.success) {
                this.renderTable(data.data);
            } else {
                this.showMessage(data.message, 'error');
            }
        } catch (error) {
            console.error('Error loading data:', error);
            this.showMessage('Gagal memuat data', 'error');
        }
    },

    getFilters() {
        return {
            start_date: document.getElementById('start-date').value,
            end_date: document.getElementById('end-date').value,
            person_id: document.getElementById('filter-person').value
        };
    },

    renderTable(data) {
        const table = document.getElementById('data-table');
        if (!table) return;

        const columns = this.getTableColumns();
        
        table.innerHTML = `
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="w-8">
                            <input type="checkbox" id="select-all" class="form-checkbox rounded">
                        </th>
                        ${columns.map(col => `
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ${col.label}
                            </th>
                        `).join('')}
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${data.map(item => `
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <input type="checkbox" class="form-checkbox rounded row-select"
                                       data-id="${item.id}" 
                                       data-berat="${item.berat}"
                                       onchange="Hitung.handleRowSelect(this)">
                            </td>
                            ${columns.map(col => `
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ${this.formatCell(item[col.name], col.type)}
                                </td>
                            `).join('')}
                        </tr>
                    `).join('')}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="px-6 py-4 font-bold">Total:</td>
                        <td class="px-6 py-4 font-bold" id="total-berat">0 kg</td>
                        <td id="total-biaya" class="px-6 py-4 font-bold">Rp 0</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        `;

        // Reattach event listeners
        document.querySelectorAll('.row-select').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => this.handleRowSelect(e.target));
        });
    },

    getTableColumns() {
        const commonColumns = [
            { name: 'tanggal', label: 'Tanggal', type: 'date' },
            { name: 'nama', label: this.activeTab === 'supplier' ? 'Supplier' : 'Karyawan', type: 'text' },
            { name: 'berat', label: 'Berat (kg)', type: 'number' },
            { name: this.activeTab === 'supplier' ? 'harga' : 'biaya', 
              label: this.activeTab === 'supplier' ? 'Harga/kg' : 'Biaya/kg', 
              type: 'currency' },
            { name: 'total', label: 'Total', type: 'currency' }
        ];

        return commonColumns;
    },

    formatCell(value, type) {
        if (value === null || value === undefined) return '-';

        switch (type) {
            case 'date':
                return new Date(value).toLocaleDateString('id-ID');
            case 'number':
                return new Intl.NumberFormat('id-ID').format(value);
            case 'currency':
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(value);
            default:
                return value;
        }
    },

    handleRowSelect(checkbox) {
        const id = checkbox.dataset.id;
        const berat = parseFloat(checkbox.dataset.berat);

        if (checkbox.checked) {
            this.selectedData.push({ id, berat });
        } else {
            this.selectedData = this.selectedData.filter(item => item.id !== id);
        }

        this.updateTotals();
    },

    toggleSelectAll(checked) {
        document.querySelectorAll('.row-select').forEach(checkbox => {
            checkbox.checked = checked;
            this.handleRowSelect(checkbox);
        });
    },

    updateTotals() {
        this.totals.berat = this.selectedData.reduce((sum, item) => sum + item.berat, 0);
        document.getElementById('total-berat').textContent = 
            `${new Intl.NumberFormat('id-ID').format(this.totals.berat)} kg`;
    },

    calculateSelected() {
        if (this.selectedData.length === 0) {
            this.showMessage('Pilih data terlebih dahulu', 'warning');
            return;
        }

        const biayaPerKg = document.getElementById('biaya-per-kg').value;
        if (!biayaPerKg || biayaPerKg <= 0) {
            this.showMessage('Masukkan biaya per kg', 'warning');
            return;
        }

        this.totals.biaya = this.totals.berat * parseFloat(biayaPerKg);
        document.getElementById('total-biaya').textContent = 
            new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(this.totals.biaya);
    },

    async saveCalculation() {
        if (this.selectedData.length === 0 || this.totals.biaya === 0) {
            this.showMessage('Hitung biaya terlebih dahulu', 'warning');
            return;
        }

        try {
            const response = await fetch(`../api/inventory/save_calculation.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    type: this.activeTab,
                    items: this.selectedData,
                    biaya_per_kg: parseFloat(document.getElementById('biaya-per-kg').value)
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showMessage('Perhitungan berhasil disimpan', 'success');
                this.selectedData = [];
                this.loadData();
            } else {
                this.showMessage(data.message, 'error');
            }
        } catch (error) {
            console.error('Error saving calculation:', error);
            this.showMessage('Gagal menyimpan perhitungan', 'error');
        }
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
    Hitung.init();
});

// Export untuk akses global
window.Hitung = Hitung;