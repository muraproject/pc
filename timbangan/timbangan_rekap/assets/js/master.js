if (window.Master) {
    console.warn('Master already defined');
} else {
    const Master = {
        activeTab: 'kategori',
        selectedId: null,

        init() {
            this.setupTabListeners();
            this.setupFormListeners();
            this.loadData();
            
            // Set initial active tab and style
            const firstTab = document.querySelector('.tab-button');
            if (firstTab) {
                this.switchTab(firstTab.dataset.tab);
            }
        },

        setupTabListeners() {
            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', (e) => {
                    const tab = e.currentTarget.dataset.tab;
                    this.switchTab(tab);
                });
            });
        },

        setupFormListeners() {
            // Remove existing event listeners first if any
            const form = document.getElementById('master-form');
            if (form) {
                // Remove old listeners
                const oldForm = form.cloneNode(true);
                form.parentNode.replaceChild(oldForm, form);
                
                // Add new listener
                oldForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.handleSubmit(e.target);
                });
            }
        
            const resetBtn = document.getElementById('reset-btn');
            if (resetBtn) {
                const oldResetBtn = resetBtn.cloneNode(true);
                resetBtn.parentNode.replaceChild(oldResetBtn, resetBtn);
                oldResetBtn.addEventListener('click', () => this.resetForm());
            }
        
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                const oldSearchInput = searchInput.cloneNode(true);
                searchInput.parentNode.replaceChild(oldSearchInput, searchInput);
                oldSearchInput.addEventListener('input', (e) => {
                    this.handleSearch(e.target.value);
                });
            }
        },

        resetForm() {
            const form = document.getElementById('master-form');
            const idInput = document.getElementById('id');
            
            if (form) {
                form.reset();
                this.selectedId = null;
                
                // Check if id input exists before trying to access it
                if (idInput) {
                    idInput.value = '';
                }
            }
        },
        
        switchTab(tab) {
            if (!tab) return;
        
            this.activeTab = tab;
            this.selectedId = null;
            
            // Only call resetForm if form exists
            const form = document.getElementById('master-form');
            if (form) {
                this.resetForm();
            }
        
            // Update UI
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.tab === tab);
                btn.classList.toggle('border-blue-500', btn.dataset.tab === tab);
                btn.classList.toggle('text-blue-600', btn.dataset.tab === tab);
            });
        
            this.updateForm();
            this.loadData();
        },

        updateForm() {
            const form = document.getElementById('master-form');
            if (!form) return;

            const fields = this.getFormFields();
            form.innerHTML = `
                <input type="hidden" name="id" id="id">
                ${fields.map(field => this.createFormField(field)).join('')}
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="reset-btn" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Reset
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Simpan
                    </button>
                </div>
            `;

            // Reinitialize form listeners after updating form
            this.setupFormListeners();
        },

        getFormFields() {
            const fields = {
                kategori: [
                    { name: 'nama', label: 'Nama Kategori', type: 'text', required: true },
                    { name: 'keterangan', label: 'Keterangan', type: 'textarea' }
                ],
                produk: [
                    { name: 'kategori_id', label: 'Kategori', type: 'select', required: true },
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

        createFormField(field) {
            let input;

            switch (field.type) {
                case 'textarea':
                    input = `<textarea 
                        id="${field.name}" 
                        name="${field.name}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" 
                        rows="3"
                        ${field.required ? 'required' : ''}
                    ></textarea>`;
                    break;

                case 'select':
                    input = `<select 
                        id="${field.name}" 
                        name="${field.name}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
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
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
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

        async handleSubmit(form) {
            try {
                const formData = new FormData(form);
                const payload = Object.fromEntries(formData.entries());

                const method = this.selectedId ? 'PUT' : 'POST';
                if (this.selectedId) {
                    payload.id = this.selectedId;
                }

                const response = await fetch(`../timbangan_rekap/api/master/${this.activeTab}.php`, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    this.showMessage(
                        result.message || `Data berhasil ${this.selectedId ? 'diupdate' : 'disimpan'}`,
                        'success'
                    );
                    this.resetForm();
                    await this.loadData();
                } else {
                    this.showMessage(result.message || 'Terjadi kesalahan', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showMessage('Gagal menyimpan data', 'error');
            }
        },

        async loadData() {
            try {
                const response = await fetch(`../timbangan_rekap/api/master/${this.activeTab}.php`);
                const result = await response.json();

                if (result.success) {
                    this.renderTable(result.data);
                    if (this.activeTab === 'produk') {
                        await this.loadKategoriOptions();
                    }
                } else {
                    this.showMessage(result.message || 'Gagal memuat data', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showMessage('Gagal memuat data', 'error');
            }
        },

        async loadKategoriOptions() {
            try {
                const response = await fetch('../timbangan_rekap/api/master/kategori.php');
                const result = await response.json();

                if (result.success && Array.isArray(result.data)) {
                    const select = document.getElementById('kategori_id');
                    if (select) {
                        select.innerHTML = '<option value="">Pilih Kategori</option>' + 
                            result.data.map(item => 
                                `<option value="${item.id}">${item.nama}</option>`
                            ).join('');
                    }
                }
            } catch (error) {
                console.error('Error loading kategori:', error);
                this.showMessage('Gagal memuat data kategori', 'error');
            }
        },

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
                        ${Array.isArray(data) ? data.map(item => `
                            <tr>
                                ${columns.map(col => `
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        ${this.formatTableCell(item[col.name])}
                                    </td>
                                `).join('')}
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <button onclick="Master.editItem(${item.id})" 
                                            class="text-blue-600 hover:text-blue-900 mr-2">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button onclick="Master.deleteItem(${item.id})" 
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        `).join('') : ''}
                    </tbody>
                </table>
            `;
        },

        getTableColumns() {
            const columns = {
                kategori: [
                    { name: 'nama', label: 'Nama Kategori' },
                    { name: 'keterangan', label: 'Keterangan' }
                ],
                produk: [
                    { name: 'kategori_nama', label: 'Kategori' },
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

        formatTableCell(value) {
            if (value === null || value === undefined) return '-';
            return value;
        },

        async editItem(id) {
            try {
                const response = await fetch(`../timbangan_rekap/api/master/${this.activeTab}.php?id=${id}`);
                const result = await response.json();

                if (result.success && result.data) {
                    this.selectedId = id;
                    document.getElementById('id').value = id;
                    
                    Object.entries(result.data).forEach(([key, value]) => {
                        const input = document.getElementById(key);
                        if (input) {
                            input.value = value || '';
                        }
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                this.showMessage('Gagal memuat data', 'error');
            }
        },

        async deleteItem(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                return;
            }

            try {
                const response = await fetch(`../timbangan_rekap/api/master/${this.activeTab}.php?id=${id}`, {
                    method: 'DELETE'
                });
                const result = await response.json();

                if (result.success) {
                    this.showMessage(result.message || 'Data berhasil dihapus', 'success');
                    await this.loadData();
                } else {
                    this.showMessage(result.message || 'Gagal menghapus data', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showMessage('Gagal menghapus data', 'error');
            }
        },

        

        handleSearch(value) {
            const searchText = value.toLowerCase();
            const rows = document.querySelectorAll('#data-table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
        },

        showMessage(message, type = 'info') {
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
    };

    window.Master = Master;

    document.addEventListener('DOMContentLoaded', () => {
        window.Master.init();
    });
}