// Definisikan BASE_URL di awal file
const BASE_URL = '/pc/timbangan'; // Sesuaikan dengan path aplikasi Anda

function loadKwitansiList() {
    fetch(`${BASE_URL}/api/get_kwitansi_list.php`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tableBody = document.querySelector('#kwitansi-table tbody');
                tableBody.innerHTML = ''; // Clear existing content
                data.kwitansiList.sort((a, b) => new Date(b.waktu) - new Date(a.waktu)); // Urutkan berdasarkan waktu terbaru
                data.kwitansiList.forEach(kwitansi => {
                    const row = `
                        <tr>
                            <td>${kwitansi.id_kwitansi}</td>
                            <td>${kwitansi.waktu}</td>
                            <td>${kwitansi.nama}</td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="showDetail('${kwitansi.id_kwitansi}', '${kwitansi.nama}')" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteKwitansi('${kwitansi.id_kwitansi}')" title="Hapus">
                                    <i class="fas fa-trash"></i>
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
            alert('Terjadi kesalahan saat memuat daftar kwitansi');
        });
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