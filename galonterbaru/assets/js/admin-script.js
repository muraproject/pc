document.addEventListener('DOMContentLoaded', function() {
    const sidebarLinks = document.querySelectorAll('#sidebar .nav-link');
    const tables = {
        pending: document.getElementById('pendingTable'),
        confirmed: document.getElementById('confirmedTable')
    };

    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const tableToShow = this.getAttribute('data-table');
            
            // Hide all tables
            Object.values(tables).forEach(table => table.style.display = 'none');
            
            // Show the selected table
            tables[tableToShow].style.display = 'block';
            
            // Update active link
            sidebarLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

// Inisialisasi peta
var map = L.map('map').setView([-7.851402, 111.463408], 12);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

    // Fungsi untuk menambahkan marker
    function addMarker(lat, lon, popupContent, isConfirmed) {
        var markerColor = isConfirmed ? 'green' : 'red';
        var markerIcon = new L.Icon({
            iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-${markerColor}.png`,
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        L.marker([lat, lon], {icon: markerIcon}).addTo(map)
            .bindPopup(popupContent);
    }

    // Tambahkan marker untuk setiap pesanan yang belum dikonfirmasi
    pendingOrders.forEach(function(order) {
        addMarker(order.latitude, order.longitude, 
            "Pesanan #" + order.id + "<br>" + order.name, false);
    });

    // Tambahkan marker untuk setiap pesanan yang sudah dikonfirmasi
    confirmedOrders.forEach(function(order) {
        addMarker(order.latitude, order.longitude, 
            "Pesanan Terkonfirmasi #" + order.id + "<br>" + order.name, true);
    });

    // Sesuaikan tampilan peta
    var allMarkers = [...pendingOrders, ...confirmedOrders];
    if (allMarkers.length > 0) {
        var lats = allMarkers.map(order => order.latitude);
        var lons = allMarkers.map(order => order.longitude);
        var minLat = Math.min(...lats);
        var maxLat = Math.max(...lats);
        var minLon = Math.min(...lons);
        var maxLon = Math.max(...lons);
        map.fitBounds([
            [minLat, minLon],
            [maxLat, maxLon]
        ]);
    }

    // Handle confirm button click
    const confirmButtons = document.querySelectorAll('.confirm-btn');
    confirmButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            if (confirm('Apakah Anda yakin ingin mengonfirmasi pesanan ini?')) {
                confirmOrder(orderId);
            }
        });
    });

    function confirmOrder(orderId) {
        fetch('confirm_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pesanan berhasil dikonfirmasi');
                location.reload(); // Reload the page to update the tables
            } else {
                alert('Terjadi kesalahan saat mengonfirmasi pesanan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengonfirmasi pesanan');
        });
    }

    // Handle delete button click
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            const orderType = this.getAttribute('data-type');
            if (confirm('Apakah Anda yakin ingin menghapus pesanan ini?')) {
                deleteOrder(orderId, orderType);
            }
        });
    });

    function deleteOrder(orderId, orderType) {
        fetch('delete_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}&order_type=${orderType}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pesanan berhasil dihapus');
                location.reload(); // Reload the page to update the tables
            } else {
                alert('Terjadi kesalahan saat menghapus pesanan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus pesanan');
        });
    }
});