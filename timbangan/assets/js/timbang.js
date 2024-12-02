let weighingData = [];



function addWeighingData() {
    const nama = document.getElementById('nama').options[nama.selectedIndex].text;
    const produk = document.getElementById('produk');
    const produkId = produk.value;
    const produkNama = produk.options[produk.selectedIndex].text;
    const nilaiTimbang = document.getElementById('scale-value').textContent;
    console.log(nama);

    if (!nama || !produkId) {
        alert('Nama dan Produk harus diisi');
        return;
    }

    const now = new Date();
    const waktu = now.getFullYear() + '-' + 
                  padZero(now.getMonth() + 1) + '-' + 
                  padZero(now.getDate()) + ' ' + 
                  padZero(now.getHours()) + ':' + 
                  padZero(now.getMinutes()) + ':' + 
                  padZero(now.getSeconds());

    const newData = {
        waktu: waktu,
        nama: nama,
        produkId: produkId,
        produkNama: produkNama,
        nilaiTimbang: nilaiTimbang,
        harga: 0 // Harga awal 0, tidak ditampilkan di frontend
    };

    weighingData.push(newData);
    updateTable();
}

function padZero(num) {
    return num.toString().padStart(2, '0');
}

// ... (fungsi lainnya tetap sama)

function saveKwitansi() {
    if (weighingData.length === 0) {
        alert('Tidak ada data untuk disimpan');
        return;
    }

    const kwitansiId = generateKwitansiId();

    fetch('api/save_kwitansi.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id_kwitansi: kwitansiId,
            data: weighingData
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Kwitansi berhasil disimpan dengan ID: ' + kwitansiId);
            weighingData = [];
            updateTable();
        } else {
            alert('Gagal menyimpan kwitansi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan kwitansi');
    });
}

// ... (fungsi lainnya tetap sama)

function addWeighingData() {
    const nama = document.getElementById('nama');
    const produk = document.getElementById('produk');
    const produkId = produk.value;
    const produkNama = produk.options[produk.selectedIndex].text;
    const namaOrang = nama.options[nama.selectedIndex].text;
    const nilaiTimbang = document.getElementById('scale-value').textContent;

    if (!nama || !produkId) {
        alert('Nama dan Produk harus diisi');
        return;
    }

    const newData = {
        waktu: new Date().toLocaleString(),
        nama: namaOrang,
        produkId: produkId,
        produkNama: produkNama,
        nilaiTimbang: nilaiTimbang,
        harga: 0 // Harga awal 0, tidak ditampilkan di frontend
    };

    weighingData.push(newData);
    updateTable();
}

function updateTable() {
    const tableBody = document.querySelector('#weighing-table tbody');
    tableBody.innerHTML = '';
    weighingData.forEach((data, index) => {
        const newRow = tableBody.insertRow();
        newRow.innerHTML = `
            <td>${data.waktu}</td>
            <td>${data.nama}</td>
            <td>${data.produkNama}</td>
            <td>${data.nilaiTimbang} kg</td>
            <td>
                <button onclick="deleteWeighingData(${index})">Hapus</button>
            </td>
        `;
    });
}

function editWeighingData(index) {
    const data = weighingData[index];
    const nama = prompt('Masukkan nama baru:', data.nama);
    if (nama !== null) {
        data.nama = nama;
        updateTable();
    }
}

function deleteWeighingData(index) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        weighingData.splice(index, 1);
        updateTable();
    }
}

function saveKwitansi() {
    if (weighingData.length === 0) {
        alert('Tidak ada data untuk disimpan');
        return;
    }

    const kwitansiId = generateKwitansiId();

    fetch('api/save_kwitansi.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id_kwitansi: kwitansiId,
            data: weighingData
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Kwitansi berhasil disimpan dengan ID: ' + kwitansiId);
            weighingData = [];
            updateTable();
        } else {
            alert('Gagal menyimpan kwitansi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan kwitansi');
    });
}

function generateKwitansiId() {
    return 'KW' + Date.now();
}

document.addEventListener('DOMContentLoaded', function() {
    // updateScale();
    // setInterval(updateScale, 5000);
});