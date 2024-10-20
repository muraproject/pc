// Definisikan BASE_URL di awal file
const BASE_URL = '/pc/timbangan'; // Sesuaikan dengan path aplikasi Anda

let kwitansiData = [];
let currentSort = { column: 'waktu', direction: 'desc' };

function loadKwitansiList() {
    fetch(`${BASE_URL}/api/get_kwitansi_list.php`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                kwitansiData = data.kwitansiList;
                setupSearchAndSort();
                sortAndRenderKwitansiList();
            } else {
                alert('Gagal memuat daftar kwitansi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat daftar kwitansi');
        });
}


function sortAndRenderKwitansiList() {
    const sortedData = [...kwitansiData].sort((a, b) => {
        let comparison = 0;
        if (currentSort.column === 'waktu') {
            // Pastikan waktu adalah string yang valid sebelum membuat objek Date
            const dateA = new Date(a.waktu || '');
            const dateB = new Date(b.waktu || '');
            comparison = dateB - dateA; // Urutkan dari yang terbaru ke terlama
        } else {
            // Gunakan metode yang aman untuk membandingkan nilai
            const valueA = (a[currentSort.column] || '').toString();
            const valueB = (b[currentSort.column] || '').toString();
            comparison = valueA.localeCompare(valueB);
        }
        return currentSort.direction === 'asc' ? comparison : -comparison;
    });
    renderKwitansiList(sortedData);
}

function renderKwitansiList(dataToRender = kwitansiData) {
    const tableBody = document.querySelector('#kwitansi-table tbody');
    tableBody.innerHTML = ''; // Clear existing content
    if (dataToRender.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Tidak ada data kwitansi</td></tr>';
        return;
    }
    dataToRender.forEach(kwitansi => {
        const row = `
            <tr>
                <td>${kwitansi.id_kwitansi || ''}</td>
                <td>${kwitansi.waktu || ''}</td>
                <td>${kwitansi.nama || ''}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="showDetail('${kwitansi.id_kwitansi || ''}', '${(kwitansi.nama || '').replace(/'/g, "\\'")}')" title="Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-danger user-hide" onclick="deleteKwitansi('${kwitansi.id_kwitansi || ''}')" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
}

function handleSort(column) {
    if (!column) {
        console.error('Column is undefined');
        return;
    }
    if (currentSort.column === column) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.column = column;
        currentSort.direction = 'asc';
    }
    sortAndRenderKwitansiList();
}

function setupSearchAndSort() {
    // Tambahkan input pencarian jika belum ada
    if (!document.getElementById('kwitansi-search')) {
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.id = 'kwitansi-search';
        searchInput.placeholder = 'Cari kwitansi...';
        searchInput.className = 'form-control mb-3';
        searchInput.addEventListener('input', handleSearch);
        
        const table = document.querySelector('#kwitansi-table');
        table.parentNode.insertBefore(searchInput, table);
    }

    // Tambahkan event listener untuk pengurutan pada header tabel
    const headers = document.querySelectorAll('#kwitansi-table th');
    headers.forEach(header => {
        const column = header.dataset.column;
        if (column) {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => handleSort(column));
        }
    });
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







function deleteKwitansi(idKwitansi) {
    if (confirm('Apakah Anda yakin ingin menghapus kwitansi ini?')) {
        fetch(`${BASE_URL}/api/delete_kwitansi.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_kwitansi=${idKwitansi}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Kwitansi berhasil dihapus');
                loadKwitansiList(); // Muat ulang daftar kwitansi
            } else {
                alert('Gagal menghapus kwitansi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus kwitansi');
        });
    }
}

// ... (kode sebelumnya tetap sama)

function showDetail(idKwitansi, nama) {
    fetch(`${BASE_URL}/api/get_kwitansi_detail.php?id_kwitansi=${idKwitansi}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let modalContent = `
                    <h4>${nama}</h4>
                    <p>Id Kwitansi: ${idKwitansi}</p>
                    <p>Tanggal: ${formatDate(data.tanggal)}</p>
                    <p>Barang:</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                let groupedItems = {};
                let totalKeseluruhan = 0;
                let counter = 1;

                // Mengelompokkan item berdasarkan jenis barang
                data.details.forEach(item => {
                    const [jenis] = item.nama_produk.split(' ');
                    if (!groupedItems[jenis]) {
                        groupedItems[jenis] = [];
                    }
                    groupedItems[jenis].push(item);
                });

                // Menampilkan item yang dikelompokkan
                Object.keys(groupedItems).forEach(jenis => {
                    let subtotal = 0;
                    groupedItems[jenis].forEach((item, index) => {
                        const itemTotal = item.nilai_timbang * item.harga;
                        subtotal += itemTotal;
                        modalContent += `
                            <tr>
                                <td>${counter}${String.fromCharCode(97 + index)}.</td>
                                <td>${item.nama_produk}</td>
                                <td>${item.nilai_timbang} kg</td>
                                <td>Rp ${item.harga.toLocaleString()}</td>
                                <td>Rp ${itemTotal.toLocaleString()}</td>
                            </tr>
                        `;
                    });
                    modalContent += `
                        <tr>
                            <td colspan="4" style="text-align: left;"><strong>Total ${jenis}:</strong></td>
                            <td><strong>Rp ${subtotal.toLocaleString()}</strong></td>
                        </tr>
                    `;
                    totalKeseluruhan += subtotal;
                    counter++;
                });

                modalContent += `
                        <tr>
                            <td colspan="4" style="text-align: left;"><strong>Total ${nama}:</strong></td>
                            <td><strong>Rp ${totalKeseluruhan.toLocaleString()}</strong></td>
                        </tr>
                    </tbody>
                </table>
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
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

// ... (kode lainnya tetap sama)

// Panggil loadKwitansiList saat dokumen selesai dimuat
document.addEventListener('DOMContentLoaded', loadKwitansiList);