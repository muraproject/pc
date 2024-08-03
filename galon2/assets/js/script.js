const prices = {
    refill: 15000,
    original: 50000
};

let quantities = {
    refill: 0,
    original: 0
};

function changeQuantity(type, change) {
    quantities[type] += change;
    if (quantities[type] < 0) quantities[type] = 0;
    document.getElementById(`${type}Quantity`).value = quantities[type];
    updateTotal();
}

function updateTotal() {
    const total = (quantities.refill * prices.refill) + (quantities.original * prices.original);
    document.getElementById('totalOrder').textContent = `Rp ${total.toLocaleString('id-ID')}`;
}

function validateForm() {
    const name = document.getElementById('name').value.trim();
    const whatsapp = document.getElementById('whatsapp').value.trim();
    const total = (quantities.refill * prices.refill) + (quantities.original * prices.original);

    if (total === 0) {
        alert('Pesanan belum terisi!');
        return false;
    }

    if (name === '') {
        alert('Nama harus diisi!');
        return false;
    }

    if (whatsapp === '' || !/^\d+$/.test(whatsapp)) {
        alert('Nomor WhatsApp tidak valid!');
        return false;
    }

  
    const longitude = document.getElementById('longitude').value;
    const latitude = document.getElementById('latitude').value;

    if (!longitude || !latitude) {
        alert('Silakan pilih lokasi pada peta!');
        return false;
    }

    return true;
}

// ... (kode lainnya tetap sama)

function encryptData(data, publicKey, n) {
    let encrypted = {};
    for (let key in data) {
        let encryptedValue = [];
        let value = data[key].toString();
        for (let i = 0; i < value.length; i++) {
            let charCode = value.charCodeAt(i);
            let encryptedChar = BigInt(charCode) ** BigInt(publicKey) % BigInt(n);
            encryptedValue.push(encryptedChar.toString());
        }
        encrypted[key] = encryptedValue.join(',');
    }
    return encrypted;
}

document.getElementById('orderForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!validateForm()) return;

    const orderData = {
        name: document.getElementById('name').value,
        whatsapp: document.getElementById('whatsapp').value,
        address: document.getElementById('address').value,
        longitude: document.getElementById('longitude').value,
        latitude: document.getElementById('latitude').value,
        refillQuantity: quantities.refill,
        originalQuantity: quantities.original,
        total: (quantities.refill * prices.refill) + (quantities.original * prices.original)
    };

    // Enkripsi data
    const publicKey = 7; // Sesuai dengan nilai e yang telah ditentukan
    const n = 187; // Hasil dari p * q (17 * 11)
    const encryptedData = encryptData(orderData, publicKey, n);

    // Kirim data terenkripsi ke server
    fetch('process_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(encryptedData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pesanan berhasil dikirim!');
            // Reset form
            document.getElementById('orderForm').reset();
            quantities.refill = 0;
            quantities.original = 0;
            document.getElementById('refillQuantity').value = 0;
            document.getElementById('originalQuantity').value = 0;
            updateTotal();
        } else {
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
});


let map, marker;

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi peta
    map = L.map('map').setView([-7.851402, 111.463408], 12); // Koordinat Jakarta
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Tambahkan event listener untuk klik pada peta
    map.on('click', function(e) {
        setLocation(e.latlng.lat, e.latlng.lng);
    });
});

function setLocation(lat, lng) {
    // Hapus marker sebelumnya jika ada
    if (marker) {
        map.removeLayer(marker);
    }

    // Tambahkan marker baru
    marker = L.marker([lat, lng]).addTo(map);

    // Update nilai input hidden
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;

    // Update teks lokasi yang dipilih
    document.getElementById('selectedLocation').textContent = `Lokasi dipilih: ${lat}, ${lng}`;
}