// Fungsi untuk memperbarui nilai timbangan
function updateScale(value) {
    document.getElementById('scale-value').textContent = value;
}

// Fungsi untuk menambahkan data timbangan
function addWeighingData() {
    const nama = document.getElementById('nama').value;
    const produk = document.getElementById('produk').value;
    const nilaiTimbang = document.getElementById('scale-value').textContent;

    // Kirim data ke server menggunakan AJAX
    fetch('api/timbang.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=save&nama=${nama}&id_produk=${produk}&nilai_timbang=${nilaiTimbang}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Data berhasil disimpan');
            loadWeighingData();
        } else {
            alert('Gagal menyimpan data: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Fungsi untuk memuat data timbangan
function loadWeighingData() {
    fetch('api/timbang.php?action=get')
    .then(response => response.json())
    .then(data => {
        const tableBody = document.querySelector('#weighing-table tbody');
        tableBody.innerHTML = '';
        data.forEach(item => {
            const row = `
                <tr>
                    <td>${item.waktu}</td>
                    <td>${item.nama}</td>
                    <td>${item.nama_produk}</td>
                    <td>${item.nilai_timbang}</td>
                    <td>
                        <button onclick="editWeighingData(${item.id})">Edit</button>
                        <button onclick="deleteWeighingData(${item.id})">Hapus</button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    })
    .catch(error => console.error('Error:', error));
}

// Fungsi untuk mengedit data timbangan
function editWeighingData(id) {
    // Implementasi edit data
    console.log('Edit data dengan ID:', id);
}

// Fungsi untuk menghapus data timbangan
function deleteWeighingData(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        fetch('api/timbang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Data berhasil dihapus');
                loadWeighingData();
            } else {
                alert('Gagal menghapus data: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Fungsi untuk memuat produk ke dropdown
function loadProducts() {
    fetch('api/setting.php?action=getProducts')
    .then(response => response.json())
    .then(data => {
        const select = document.getElementById('produk');
        select.innerHTML = '';
        data.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id;
            option.textContent = product.nama;
            select.appendChild(option);
        });
    })
    .catch(error => console.error('Error:', error));
}

// Event listener ketika DOM telah dimuat
document.addEventListener('DOMContentLoaded', function() {
    loadWeighingData();
    loadProducts();
});