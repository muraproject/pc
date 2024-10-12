// Fungsi untuk memuat produk
function loadProducts() {
    fetch('api/setting.php?action=getProducts')
    .then(response => response.json())
    .then(data => {
        const tableBody = document.querySelector('#product-table tbody');
        if (tableBody) {
            tableBody.innerHTML = '';
            if (Array.isArray(data)) {
                data.forEach(product => {
                    const row = `
                        <tr>
                            <td>${product.nama}</td>
                            <td>
                                <button onclick="editProduct(${product.id}, '${product.nama}')">Edit</button>
                                <button onclick="deleteProduct(${product.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else if (data.error) {
                tableBody.innerHTML = `<tr><td colspan="2">Error: ${data.error}</td></tr>`;
            } else {
                tableBody.innerHTML = '<tr><td colspan="2">Tidak ada data produk</td></tr>';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const tableBody = document.querySelector('#product-table tbody');
        if (tableBody) {
            tableBody.innerHTML = `<tr><td colspan="2">Error: ${error.message}</td></tr>`;
        }
    });
}


// ... (fungsi-fungsi lain yang sudah ada) ...

// Fungsi untuk menambahkan produk baru
function addProduct() {
    const productName = document.getElementById('new-product-name').value;
    if (!productName) {
        alert('Nama produk tidak boleh kosong');
        return;
    }

    fetch('api/setting.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=addProduct&nama_produk=${encodeURIComponent(productName)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Produk berhasil ditambahkan');
            document.getElementById('new-product-name').value = '';
            loadProducts();
        } else {
            alert('Gagal menambahkan produk: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan produk');
    });
}

// Tambahkan ini di awal file
function checkAccess() {
    if (typeof userType !== 'undefined' && userType !== 'admin' && window.location.href.includes('page=harga')) {
        alert('Anda tidak memiliki akses ke halaman ini');
        window.location.href = 'index.php?page=timbang&user_type=' + userType;
    }
}

// Panggil fungsi ini saat halaman dimuat
document.addEventListener('DOMContentLoaded', checkAccess);

// ... (kode lainnya tetap sama)

// Fungsi untuk memuat produk
function loadProducts() {
    fetch('api/setting.php?action=getProducts')
    .then(response => response.json())
    .then(data => {
        const tableBody = document.querySelector('#product-table tbody');
        if (tableBody) {
            tableBody.innerHTML = '';
            if (Array.isArray(data)) {
                data.forEach(product => {
                    const row = `
                        <tr>
                            <td>${product.nama}</td>
                            <td>
                                <button onclick="editProduct(${product.id}, '${product.nama}')">Edit</button>
                                <button onclick="deleteProduct(${product.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else if (data.error) {
                tableBody.innerHTML = `<tr><td colspan="2">Error: ${data.error}</td></tr>`;
            } else {
                tableBody.innerHTML = '<tr><td colspan="2">Tidak ada data produk</td></tr>';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const tableBody = document.querySelector('#product-table tbody');
        if (tableBody) {
            tableBody.innerHTML = `<tr><td colspan="2">Error: ${error.message}</td></tr>`;
        }
    });
}

// Fungsi untuk mengedit produk
function editProduct(id, name) {
    const newName = prompt("Masukkan nama baru untuk produk:", name);
    if (newName) {
        fetch('api/setting.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=editProduct&id=${id}&nama=${encodeURIComponent(newName)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Produk berhasil diupdate');
                loadProducts();
            } else {
                alert('Gagal mengupdate produk: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengedit produk');
        });
    }
}

// Fungsi untuk menghapus produk
function deleteProduct(id) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        fetch('api/setting.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=removeProduct&id_produk=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Produk berhasil dihapus');
                loadProducts();
            } else {
                alert('Gagal menghapus produk: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus produk');
        });
    }
}


function updateScale(value) {
    const scaleValueElement = document.getElementById('scale-value');
    if (scaleValueElement) {
        scaleValueElement.textContent = value;
    }
}

// Fungsi untuk menambahkan data timbangan
function addWeighingData() {
    const nama = document.getElementById('nama').value;
    const produk = document.getElementById('produk').value;
    const nilaiTimbang = document.getElementById('scale-value').textContent;

    if (!nama || !produk || !nilaiTimbang) {
        alert('Semua field harus diisi');
        return;
    }

    // Kirim data ke server menggunakan AJAX
    fetch('api/timbang.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=save&nama=${encodeURIComponent(nama)}&id_produk=${encodeURIComponent(produk)}&nilai_timbang=${encodeURIComponent(nilaiTimbang)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Data berhasil disimpan');
            document.getElementById('nama').value = '';
            document.getElementById('produk').value = '';
            document.getElementById('scale-value').textContent = '0';
            loadWeighingData();
        } else {
            alert('Gagal menyimpan data: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    });
}

// Fungsi untuk memuat data timbangan
function loadWeighingData() {
    fetch('api/timbang.php?action=get')
    .then(response => response.json())
    .then(data => {
        const tableBody = document.querySelector('#weighing-table tbody');
        if (tableBody) {
            tableBody.innerHTML = '';
            if (Array.isArray(data)) {
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
            } else if (data.error) {
                tableBody.innerHTML = `<tr><td colspan="5">Error: ${data.error}</td></tr>`;
            } else {
                tableBody.innerHTML = '<tr><td colspan="5">Tidak ada data timbangan</td></tr>';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const tableBody = document.querySelector('#weighing-table tbody');
        if (tableBody) {
            tableBody.innerHTML = `<tr><td colspan="5">Error: ${error.message}</td></tr>`;
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('weighing-table')) {
        loadWeighingData();
    }
    if (document.getElementById('product-table')) {
        loadProducts();
    }
});