const BASE_URL = '/pc/timbangan';
let currentKwitansiId;
let isSaving = false;

document.addEventListener('DOMContentLoaded', function() {
    loadKwitansiList();
    setupEventListeners();
});

function setupEventListeners() {
    const saveButton = document.querySelector('#detailModal .modal-footer .btn-primary');
    if (saveButton) {
        saveButton.removeEventListener('click', saveChanges);
        saveButton.addEventListener('click', saveChanges);
    }
}

////////////////////////////////////////////////////

let kwitansiData = [];
let currentSort = { column: '', direction: '' };

function loadKwitansiList() {
    fetch(`${BASE_URL}/api/get_kwitansi_list.php`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                kwitansiData = data.kwitansiList;
                renderKwitansiList();
                setupSearchAndSort();
            } else {
                alert('Gagal memuat daftar kwitansi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat daftar kwitansi: ' + error.message);
        });
}

function renderKwitansiList(filteredData = kwitansiData) {
    const tableBody = document.querySelector('#kwitansi-table tbody');
    tableBody.innerHTML = '';
    filteredData.forEach(kwitansi => {
        const row = `
            <tr>
                <td>${kwitansi.id_kwitansi}</td>
                <td>${kwitansi.waktu}</td>
                <td>${kwitansi.nama}</td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="showDetail('${kwitansi.id_kwitansi}', '${kwitansi.nama}')">
                        Detail
                    </button>
                </td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
}

function setupSearchAndSort() {
    // Tambahkan input pencarian
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'Cari kwitansi...';
    searchInput.className = 'form-control mb-3';
    searchInput.addEventListener('input', handleSearch);
    
    // Tambahkan header untuk pengurutan
    const headers = document.querySelectorAll('#kwitansi-table th');
    headers.forEach(header => {
        if (header.textContent !== 'Aksi') {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => handleSort(header.textContent.toLowerCase()));
        }
    });

    // Masukkan input pencarian sebelum tabel
    const table = document.querySelector('#kwitansi-table');
    table.parentNode.insertBefore(searchInput, table);
}

function handleSearch(event) {
    const searchTerm = event.target.value.toLowerCase();
    const filteredData = kwitansiData.filter(kwitansi => 
        kwitansi.id_kwitansi.toLowerCase().includes(searchTerm) ||
        kwitansi.waktu.toLowerCase().includes(searchTerm) ||
        kwitansi.nama.toLowerCase().includes(searchTerm)
    );
    renderKwitansiList(filteredData);
}

function handleSort(column) {
    if (currentSort.column === column) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.column = column;
        currentSort.direction = 'asc';
    }

    kwitansiData.sort((a, b) => {
        if (a[column] < b[column]) return currentSort.direction === 'asc' ? -1 : 1;
        if (a[column] > b[column]) return currentSort.direction === 'asc' ? 1 : -1;
        return 0;
    });

    renderKwitansiList();
}


///////////////////////////////////////////////////





function saveChanges() {
    if (isSaving) {
        console.log('Penyimpanan sedang berlangsung, mencegah submission ganda');
        return;
    }

    isSaving = true;
    const saveButton = document.querySelector('#detailModal .modal-footer .btn-primary');
    if (saveButton) {
        saveButton.disabled = true;
    }

    const updatedData = [];
    const itemInputs = document.querySelectorAll('.nilai-timbang-input[data-id], .harga-input-item[data-id]');
    
    itemInputs.forEach(input => {
        const id = input.getAttribute('data-id');
        const row = input.closest('tr');
        const nilaiTimbangInput = row.querySelector('.nilai-timbang-input');
        const hargaInput = row.querySelector('.harga-input-item');
        
        updatedData.push({
            id: id,
            nilai_timbang: parseFloat(nilaiTimbangInput.value),
            harga: parseFloat(hargaInput.value)
        });
    });

    console.log('Sending data:', {
        id_kwitansi: currentKwitansiId,
        data: updatedData
    });

    fetch(`${BASE_URL}/api/update_harga_kwitansi.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id_kwitansi: currentKwitansiId,
            data: updatedData
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Server response:', data);
        if (data.success) {
            alert(`Perubahan berhasil disimpan. ${data.updated_rows} baris diupdate.`);
            $('#detailModal').modal('hide');
            loadKwitansiList();
        } else {
            alert('Gagal menyimpan perubahan: ' + data.message);
        }
        
        if (data.debug_info) {
            console.log('Debug info:', data.debug_info);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan perubahan: ' + error.message);
    })
    .finally(() => {
        isSaving = false;
        if (saveButton) {
            saveButton.disabled = false;
        }
    });
}




function updateItemTotal(input) {
    const row = input.closest('tr');
    const nilaiTimbangInput = row.querySelector('.nilai-timbang-input');
    const hargaInput = row.querySelector('.harga-input-item');
    const itemTotalCell = row.querySelector('.item-total');
    
    const nilaiTimbang = parseFloat(nilaiTimbangInput.value);
    const harga = parseFloat(hargaInput.value);
    
    if (isNaN(nilaiTimbang) || isNaN(harga)) {
        alert('Mohon masukkan angka yang valid untuk nilai timbang dan harga.');
        return;
    }
    
    const total = nilaiTimbang * harga;
    
    itemTotalCell.textContent = total.toFixed(2);
    updateSubtotals();
    updateTotalHarga();
}

function updateSubtotals() {
    const rows = document.querySelectorAll('#modalBody table tbody tr:not(.table-secondary)');
    const subtotals = {};

    rows.forEach(row => {
        const cells = row.cells;
        if (cells.length < 5) {
            console.error('Jumlah sel dalam baris tidak cukup');
            return;
        }
        
        const product = cells[1].textContent.trim();
        const nilaiTimbangInput = row.querySelector('.nilai-timbang-input');
        const nilaiTimbang = parseFloat(nilaiTimbangInput.value);
        const itemTotalCell = row.querySelector('.item-total');
        const total = parseFloat(itemTotalCell.textContent);

        if (!subtotals[product]) {
            subtotals[product] = { nilaiTimbang: 0, total: 0 };
        }
        subtotals[product].nilaiTimbang += nilaiTimbang;
        subtotals[product].total += total;
    });

    Object.keys(subtotals).forEach(product => {
        updateSubtotalRow(product, subtotals[product].nilaiTimbang, subtotals[product].total);
    });
}

function updateSubtotalRow(product, nilaiTimbang, subtotal) {
    const subtotalRowId = `subtotal_${product.toLowerCase().replace(/\s+/g, '_')}`;
    const subtotalRow = document.getElementById(subtotalRowId);

    if (!subtotalRow) {
        console.error(`Baris subtotal dengan ID "${subtotalRowId}" tidak ditemukan`);
        return;
    }

    const nilaiTimbangElement = subtotalRow.querySelector('.subtotal-nilai-timbang');
    const subtotalElement = subtotalRow.querySelector('.subtotal-total');

    if (nilaiTimbangElement) {
        nilaiTimbangElement.textContent = `${nilaiTimbang.toFixed(2)} kg`;
    } else {
        console.error(`Elemen nilai timbang tidak ditemukan dalam baris subtotal ${subtotalRowId}`);
    }

    if (subtotalElement) {
        subtotalElement.textContent = subtotal.toFixed(2);
    } else {
        console.error(`Elemen subtotal tidak ditemukan dalam baris subtotal ${subtotalRowId}`);
    }
}

function showDetail(idKwitansi, nama) {
    currentKwitansiId = idKwitansi;
    fetch(`${BASE_URL}/api/get_kwitansi_detail.php?id_kwitansi=${idKwitansi}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mengurutkan data berdasarkan nama_produk
                data.details.sort((a, b) => a.nama_produk.localeCompare(b.nama_produk));

                let modalContent = `
                    <h4>Nama: ${nama}</h4>
                    <p>Tanggal: ${data.tanggal}</p>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Nilai Timbang (kg)</th>
                                <th>Harga per kg</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                let totalHarga = 0;
                let currentProduct = '';
                let subtotal = 0;
                let subtotalNilaiTimbang = 0;
                let rowCount = 0;
                let lastHarga = 0;

                data.details.forEach((item, index) => {
                    if (currentProduct !== item.nama_produk) {
                        // Jika produk berubah, tampilkan subtotal produk sebelumnya
                        if (currentProduct !== '') {
                            const subtotalId = `subtotal_${currentProduct.toLowerCase().replace(/\s+/g, '_')}`;
                            modalContent += `
                                <tr id="${subtotalId}" class="table-secondary">
                                    <td colspan="2"><strong>Subtotal ${currentProduct}</strong></td>
                                    <td class="subtotal-nilai-timbang"><strong>${subtotalNilaiTimbang.toFixed(2)} kg</strong></td>
                                    <td>
                                        <input type="number" class="form-control harga-input" 
                                               data-product="${currentProduct}"
                                               value="${lastHarga}" 
                                               onchange="updateSubtotalPrice(this)">
                                    </td>
                                    <td class="subtotal-total"><strong>${subtotal.toFixed(2)}</strong></td>
                                </tr>
                            `;
                        }
                        // Reset subtotal untuk produk baru
                        currentProduct = item.nama_produk;
                        subtotal = 0;
                        subtotalNilaiTimbang = 0;
                        rowCount = 0;
                    }

                    rowCount++;
                    const itemTotal = item.nilai_timbang * item.harga;
                    subtotal += itemTotal;
                    subtotalNilaiTimbang += parseFloat(item.nilai_timbang);
                    totalHarga += itemTotal;
                    lastHarga = item.harga;

                    modalContent += `
                        <tr>
                            <td>${rowCount}</td>
                            <td>${item.nama_produk}</td>
                            <td>
                                <input type="number" class="form-control nilai-timbang-input" 
                                       data-id="${item.id}" value="${item.nilai_timbang}" 
                                       step="0.01" min="0"
                                       onchange="updateItemTotal(this)">
                            </td>
                            <td>
                                <input type="number" class="form-control harga-input-item" 
                                       data-id="${item.id}" value="${item.harga}" 
                                       step="100" min="0"
                                       onchange="updateItemTotal(this)" readOnly>
                            </td>
                            <td class="item-total">${itemTotal.toFixed(2)}</td>
                        </tr>
                    `;

                    // Jika ini adalah item terakhir, tampilkan subtotal
                    if (index === data.details.length - 1) {
                        const subtotalId = `subtotal_${currentProduct.toLowerCase().replace(/\s+/g, '_')}`;
                        modalContent += `
                            <tr id="${subtotalId}" class="table-secondary">
                                <td colspan="2"><strong>Subtotal ${currentProduct}</strong></td>
                                <td class="subtotal-nilai-timbang"><strong>${subtotalNilaiTimbang.toFixed(2)} kg</strong></td>
                                <td>
                                    <input type="number" class="form-control harga-input" 
                                           data-product="${currentProduct}"
                                           value="${lastHarga}" 
                                           onchange="updateSubtotalPrice(this)">
                                </td>
                                <td class="subtotal-total"><strong>${subtotal.toFixed(2)}</strong></td>
                            </tr>
                        `;
                    }
                });

                modalContent += `
                        </tbody>
                    </table>
                    <h5>Total Harga: <span id="totalHarga">${totalHarga.toFixed(2)}</span></h5>
                `;

                document.getElementById('modalBody').innerHTML = modalContent;
                $('#detailModal').modal('show');
            } else {
                alert('Gagal mengambil detail kwitansi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil detail kwitansi');
        });

    $('#detailModal').on('shown.bs.modal', setupEventListeners);
}

function updateSubtotalPrice(input) {
    const product = input.dataset.product;
    const newPrice = parseFloat(input.value);
    
    if (isNaN(newPrice)) {
        alert('Mohon masukkan angka yang valid untuk harga.');
        input.value = input.defaultValue; // Reset ke nilai sebelumnya
        return;
    }
    
    const subtotalRow = document.getElementById(`subtotal_${product.toLowerCase().replace(/\s+/g, '_')}`);
    const itemRows = document.querySelectorAll(`tr:not(.table-secondary) td:nth-child(2)`);
    
    let subtotalNilaiTimbang = 0;
    let subtotalHarga = 0;

    itemRows.forEach(cell => {
        if (cell.textContent.trim() === product) {
            const row = cell.closest('tr');
            const nilaiTimbangInput = row.querySelector('.nilai-timbang-input');
            const hargaInput = row.querySelector('.harga-input-item');
            const totalCell = row.querySelector('.item-total');
            
            const nilaiTimbang = parseFloat(nilaiTimbangInput.value);
            
            if (!isNaN(nilaiTimbang)) {
                subtotalNilaiTimbang += nilaiTimbang;
                const newTotal = nilaiTimbang * newPrice;
                subtotalHarga += newTotal;
                
                hargaInput.value = newPrice.toFixed(2);
                totalCell.textContent = newTotal.toFixed(2);
            }
        }
    });

    if (subtotalRow) {
        subtotalRow.querySelector('.subtotal-nilai-timbang').textContent = `${subtotalNilaiTimbang.toFixed(2)} kg`;
        subtotalRow.querySelector('.subtotal-total').textContent = subtotalHarga.toFixed(2);
    }

    updateTotalHarga();
}

function updateTotalHarga() {
    const totalElements = document.querySelectorAll('.subtotal-total');
    const totalHarga = Array.from(totalElements)
        .map(el => parseFloat(el.textContent) || 0)
        .reduce((a, b) => a + b, 0);
    
    const totalHargaElement = document.getElementById('totalHarga');
    if (totalHargaElement) {
        totalHargaElement.textContent = totalHarga.toFixed(2);
    } else {
        console.error('Elemen totalHarga tidak ditemukan');
    }
}



