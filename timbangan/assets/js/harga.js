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
                const tableBody = document.querySelector('#kwitansi-table tbody');
                tableBody.innerHTML = '';
                data.kwitansiList.forEach(kwitansi => {
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
            } else {
                alert('Gagal memuat daftar kwitansi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat daftar kwitansi: ' + error.message);
        });
}

function showDetail(idKwitansi, nama) {
    currentKwitansiId = idKwitansi;
    fetch(`${BASE_URL}/api/get_kwitansi_detail.php?id_kwitansi=${idKwitansi}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let modalContent = `
                    <h4>Nama: ${nama}</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Nilai Timbang</th>
                                <th>Harga per kg</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                let totalHarga = 0;
                data.details.forEach((item, index) => {
                    const itemTotal = item.nilai_timbang * item.harga;
                    totalHarga += itemTotal;
                    modalContent += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.nama_produk}</td>
                            <td>${item.nilai_timbang} kg</td>
                            <td>
                                <input type="number" class="form-control harga-input" 
                                       data-id="${item.id}" value="${item.harga}" 
                                       onchange="updateItemTotal(this)">
                            </td>
                            <td class="item-total">${itemTotal.toFixed(2)}</td>
                        </tr>
                    `;
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

function updateItemTotal(input) {
    const row = input.closest('tr');
    const nilaiTimbang = parseFloat(row.cells[2].textContent);
    const harga = parseFloat(input.value);
    const total = nilaiTimbang * harga;
    row.querySelector('.item-total').textContent = total.toFixed(2);
    updateTotalHarga();
}

function updateTotalHarga() {
    const totals = Array.from(document.querySelectorAll('.item-total')).map(el => parseFloat(el.textContent));
    const totalHarga = totals.reduce((a, b) => a + b, 0);
    document.getElementById('totalHarga').textContent = totalHarga.toFixed(2);
}

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

    const updatedData = Array.from(document.querySelectorAll('.harga-input')).map(input => ({
        id: input.getAttribute('data-id'),
        harga: parseFloat(input.value)
    }));

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
            alert(`Perubahan harga berhasil disimpan. ${data.updated_rows} baris diupdate.`);
            $('#detailModal').modal('hide');
            loadKwitansiList();
        } else {
            alert('Gagal menyimpan perubahan harga: ' + data.message);
        }
        
        if (data.debug_info) {
            console.log('Debug info:', data.debug_info);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan perubahan harga: ' + error.message);
    })
    .finally(() => {
        isSaving = false;
        if (saveButton) {
            saveButton.disabled = false;
        }
    });
}