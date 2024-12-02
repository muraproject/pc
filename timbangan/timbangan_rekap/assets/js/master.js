// Modul untuk manajemen data master (kategori, produk, supplier, customer)
const Master = {
    activeTab: 'kategori',
    selectedId: null,

    init() {
        this.setupTabListeners();
        this.setupFormListeners();
        this.loadData();
    },

    // Setup tab navigation
    setupTabListeners() {
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', (e) => {
                const tab = e.target.dataset.tab;
                this.switchTab(tab);
            });
        });
    },

    // Setup form event listeners
    setupFormListeners() {
        // Form submit handler
        const form = document.getElementById('master-form');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleSubmit(e.target);
            });
        }

        // Reset button handler
        const resetBtn = document.getElementById('reset-btn');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => this.resetForm());
        }

        // Search input handler
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.handleSearch(e.target.value);
            });
        }
    },

    // Switch between tabs
    switchTab(tab) {
        this.activeTab = tab;
        
        // Update active tab UI
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.toggle('active', button.dataset.tab === tab);
        });

        // Update form
        this.updateForm();
        
        // Load data for selected tab
        this.loadData();
    },

    // Update form based on active tab
    updateForm() {
        const form = document.getElementById('master-form');
        if (!form) return;

        const fields = this.getFormFields();
        form.innerHTML = `
            <input type="hidden" name="id" id="id">
            ${fields.map(field => this.createFormField(field)).join('')}
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="reset-btn" 
                        class="btn btn-secondary">
                    Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </div>
        `;
    },

    // Get form fields based on active tab
    getFormFields() {
        const fields = {
            kategori: [
                { name: 'nama', label: 'Nama Kategori', type: 'text', required: true },
                { name: 'keterangan', label: 'Keterangan', type: 'textarea' }
            ],
            produk: [
                { name: 'kategori_id', label: 'Kategori', type: 'select', required: true,
                  options: 'getKategoriOptions' },
                { name: 'nama', label: 'Nama Produk', type: 'text', required: true },
                { name: 'keterangan', label: 'Keterangan', type: 'textarea' }
            ],
            supplier: [
                { name: 'nama', label: 'Nama Supplier', type: 'text', required: true },
                { name: 'alamat', label: 'Alamat', type: 'textarea', required: true },
                { name: 'telepon', label: 'Telepon', type: 'text' },
                { name: 'kontak_person', label: 'Kontak Person', type: 'text' }
            ],
            customer: [
                { name: 'nama', label: 'Nama Customer', type: 'text', required: true },
                { name: 'alamat', label: 'Alamat', type: 'textarea', required: true },
                { name: 'telepon', label: 'Telepon', type: 'text' },
                { name: 'kontak_person', label: 'Kontak Person', type: 'text' }
            ]
        };

        return fields[this.activeTab] || [];
    },

    // Create form field HTML
    createFormField(field) {
        let input;

        switch (field.type) {
            case 'textarea':
                input = `<textarea 
                    id="${field.name}" 
                    name="${field.name}" 
                    class="form-textarea w-full rounded-md" 
                    rows="3"
                    ${field.required ? 'required' : ''}
                ></textarea>`;
                break;

            case 'select':
                input = `<select 
                    id="${field.name}" 
                    name="${field.name}" 
                    class="form-select w-full rounded-md"
                    ${field.required ? 'required' : ''}
                >
                    <option value="">Pilih ${field.label}</option>
                </select>`;
                break;

            default:
                input = `<input 
                    type="${field.type}" 
                    id="${field.name}" 
                    name="${field.name}" 
                    class="form-input w-full rounded-md"
                    ${field.required ? 'required' : ''}
                >`;
        }

        return `
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1" 
                       for="${field.name}">
                    ${field.label}
                    ${field.required ? '<span class="text-red-500">*</span>' : ''}
                </label>
                ${input}
            </div>
        `;
    },

    // Load data from server
    async loadData() {
        try {
            const response = await fetch(`../api/master/${this.activeTab}.php`);
            const data = await response.json();

            if (data.success) {
                this.renderTable(data.data);
                if (this.activeTab === 'produk') {
                    await this.loadKategoriOptions();
                }
            } else {
                this.showMessage(data.message, 'error');
            }
        } catch (error) {
            console.error('Error loading data:', error);
            this.showMessage('Gagal memuat data', 'error');
        }
    },

    // Load kategori options for produk form
    async loadKategoriOptions() {
        try {
            const response = await fetch('../api/master/kategori.php');
            const data = await response.json();

            if (data.success) {
                const select = document.getElementById('kategori_id');
                if (select) {
                    const options = data.data.map(item => 
                        `<option value="${item.id}">${item.nama}</option>`
                    ).join('');
                    select.innerHTML += options;
                }
            }
        } catch (error) {
            console.error('Error loading kategori:', error);
        }
    },

    // Render data table
    renderTable(data) {
        const container = document.getElementById('data-table');
        if (!container) return;

        const columns = this.getTableColumns();
        
        container.innerHTML = `
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        ${columns.map(col => `
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ${col.label}
                            </th>
                        `).join('')}
                        <th class="px-6 py-3 bg-gray-50"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${data.map(item => `
                        <tr>
                            ${columns.map(col => `
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ${item[col.name] || '-'}
                                </td>
                            `).join('')}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <button onclick="Master.editItem(${item.id})" 
                                        class="text-indigo-600 hover:text-indigo-900">
                                    Edit
                                </button>
                                <button onclick="Master.deleteItem(${item.id})" 
                                        class="ml-2 text-red-600 hover:text-red-900">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    },

    // Get table columns based on active tab
    getTableColumns() {
        const columns = {
            kategori: [
                { name: 'nama', label: 'Nama Kategori' },
                { name: 'keterangan', label: 'Keterangan' }
            ],
            produk: [
                { name: 'kategori', label: 'Kategori' },
                { name: 'nama', label: 'Nama Produk' },
                { name: 'keterangan', label: 'Keterangan' }
            ],
            supplier: [
                { name: 'nama', label: 'Nama Supplier' },
                { name: 'alamat', label: 'Alamat' },
                { name: 'telepon', label: 'Telepon' },
                { name: 'kontak_person', label: 'Kontak Person' }
            ],
            customer: [
                { name: 'nama', label: 'Nama Customer' },
                { name: 'alamat', label: 'Alamat' },
                { name: 'telepon', label: 'Telepon' },
                { name: 'kontak_person', label: 'Kontak Person' }
            ]
        };

        return columns[this.activeTab] || [];
    },

    // Handle form submit
    async handleSubmit(form) {
        const formData = new FormData(form);
        const method = this.selectedId ? 'PUT' : 'POST';

        try {
            const response = await fetch(`../api/master/${this.activeTab}.php`, {
                method: method,
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                this.showMessage(
                    `Data berhasil ${this.selectedId ? 'diupdate' : 'disimpan'}`,
                    'success'
                );
                this.resetForm();
                this.loadData();
            } else {
                this.showMessage(data.message, 'error');
            }
        } catch (error) {
            console.error('Error saving data:', error);
            this.showMessage('Gagal menyimpan data', 'error');
        }
    },

    // Edit item
    async editItem(id) {
        try {
            const response = await fetch(`../api/master/${this.activeTab}.php?id=${id}`);
            const data = await response.json();

            if (data.success) {
                this.selectedId = id;
                const form = document.getElementById('master-form');
                if (form) {
                    Object.keys(data.data).forEach(key => {
                        const input = form.elements[key];
                        if (input) {
                            input.value = data.data[key];
                        }
                    });
                }
            }
        } catch (error) {
            console.error('Error loading item:', error);
            this.showMessage('Gagal memuat data', 'error');
        }
    },

    // Delete item
    async deleteItem(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            return;
        }

        try {
            const response = await fetch(`../api/master/${this.activeTab}.php?id=${id}`, {
                method: 'DELETE'
            });
            const data = await response.json();

            if (data.success) {
                this.showMessage('Data berhasil dihapus', 'success');
                this.loadData();
            } else {
                this.showMessage(data.message, 'error');
            }
        } catch (error) {
            console.error('Error deleting item:', error);
            this.showMessage('Gagal menghapus data', 'error');
        }
    },

    // Reset form
    resetForm() {
        const form = document.getElementById('master-form');
        if (form) {
            form.reset();
            this.selectedId = null;
            document.getElementById('id').value = '';
        }
    },

    // Handle search
    handleSearch(value) {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(value.toLowerCase()) ? '' : 'none';
        });
    },

    // Show message
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
    Master.init();
});

// Export untuk akses global
window.Master = Master;