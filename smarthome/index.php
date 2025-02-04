<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Home Monitoring</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gauge.js/1.3.7/gauge.min.js"></script>
</head>
<body class="bg-gray-100">
    <?php 
    require_once 'functions.php';
    $monitoring_data = $automation->getMonitoringData();
    $control_data = $automation->getControlData();
    ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Smart Home Monitoring System</h1>
            <button onclick="openAddModal()" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                Tambah Sensor
            </button>
        </div>

        <!-- CCTV Section -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">CCTV Monitoring</h2>
                    <button onclick="openCCTVModal()" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Lihat CCTV
                    </button>
                </div>
                <div class="text-sm text-gray-600">
                    Status: <span id="cctv-status" class="text-green-600">Aktif</span>
                </div>
            </div>
        </div>

        <!-- Sensors Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <?php foreach ($monitoring_data as $sensor): ?>
                <?php if ($sensor['type'] == 'sensor'): ?>
                <div class="bg-white rounded-xl shadow-lg p-6" id="sensor-<?= $sensor['id'] ?>">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4"><?= htmlspecialchars($sensor['name']) ?></h3>
                    <?php if ($sensor['name'] == 'Sensor Api'): ?>
                        <div class="text-center">
                            <div id="fire-status" class="w-32 h-32 mx-auto mb-4 flex items-center justify-center bg-green-100 rounded-full">
                                <span class="text-4xl">🟢</span>
                            </div>
                            <span class="text-xl font-semibold text-green-600">Aman</span>
                        </div>
                    <?php else: ?>
                        <canvas id="gauge-<?= $sensor['id'] ?>" class="mx-auto mb-4"></canvas>
                        <div class="text-center">
                            <span id="value-<?= $sensor['id'] ?>" class="text-3xl font-bold text-gray-700">
                                <?= $sensor['value'] ?? '0' ?>
                            </span>
                            <span class="text-xl text-gray-600"><?= htmlspecialchars($sensor['unit']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- Control Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Door Controls -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Kontrol Pintu</h3>
                <div class="space-y-4">
                    <?php 
                    $doors = array_filter($control_data, function($item) { 
                        return $item['type'] == 'door'; 
                    });
                    foreach ($doors as $door): ?>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-700"><?= htmlspecialchars($door['name']) ?></span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" 
                                       onchange="toggleDevice('<?= htmlspecialchars($door['name']) ?>', 'door', event)"
                                       <?= $door['status'] == 'Terbuka' ? 'checked' : '' ?>>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer 
                                          peer-checked:after:translate-x-full peer-checked:after:border-white 
                                          after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                          after:bg-white after:border after:rounded-full after:h-5 after:w-5 
                                          after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">
                                    <?= $door['status'] ?>
                                </span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Light Controls -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Kontrol Lampu</h3>
                <div class="space-y-4">
                    <?php 
                    $lights = array_filter($control_data, function($item) { 
                        return $item['type'] == 'light'; 
                    });
                    foreach ($lights as $light): ?>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-700"><?= htmlspecialchars($light['name']) ?></span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" 
                                       onchange="toggleDevice('<?= htmlspecialchars($light['name']) ?>', 'light', event)"
                                       <?= $light['status'] == 'Nyala' ? 'checked' : '' ?>>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer 
                                          peer-checked:after:translate-x-full peer-checked:after:border-white 
                                          after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                          after:bg-white after:border after:rounded-full after:h-5 after:w-5 
                                          after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">
                                    <?= $light['status'] ?>
                                </span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Sensor Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium mb-4">Tambah Sensor Baru</h3>
                <!-- Update bagian form di dalam Add Modal -->
                <form id="addSensorForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama</label>
                        <input type="text" name="name" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tipe</label>
                        <select name="type" required id="deviceType" onchange="handleTypeChange()"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="sensor">Sensor</option>
                            <option value="door">Pintu</option>
                            <option value="light">Lampu</option>
                        </select>
                    </div>
                    <div class="mb-4" id="unitField">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Satuan</label>
                        <input type="text" name="unit"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeAddModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CCTV Modal -->
    <div id="cctvModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-4/5 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">CCTV Live Feed</h3>
                <button onclick="closeCCTVModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="aspect-w-16 aspect-h-9 bg-black rounded-lg">
                <img src="/api/placeholder/800/450" alt="CCTV Feed" class="rounded-lg">
            </div>
        </div>
    </div>

    <script>
    // Inisialisasi variabel global untuk menyimpan semua gauge
    const gauges = {};

    // Fungsi untuk membuat gauge baru
    function createGauge(elementId, min, max, value) {
        const opts = {
            angle: -0.2,
            lineWidth: 0.2,
            radiusScale: 0.9,
            pointer: {
                length: 0.6,
                strokeWidth: 0.035,
                color: '#000000'
            },
            staticLabels: {
                font: "10px sans-serif",
                labels: [min, (max-min)/2, max],
                color: "#000000",
                fractionDigits: 0
            },
            staticZones: [
                {strokeStyle: "#30B32D", min: min, max: max*0.33},
                {strokeStyle: "#FFDD00", min: max*0.33, max: max*0.66},
                {strokeStyle: "#F03E3E", min: max*0.66, max: max}
            ],
            limitMax: false,
            limitMin: false,
            highDpiSupport: true
        };

        const gauge = new Gauge(document.getElementById(elementId)).setOptions(opts);
        gauge.maxValue = max;
        gauge.setMinValue(min);
        gauge.animationSpeed = 32;
        gauge.set(value);
        return gauge;
    }

// Fungsi untuk toggle device
function toggleDevice(device, type, event) {
        $.ajax({
            url: 'functions.php',
            type: 'POST',
            data: {
                action: 'toggle',
                device: device,
                type: type,
                ajax: true
            },
            success: function(response) {
                if (response.success) {
                    const checkbox = event.target;
                    const statusSpan = checkbox.parentElement.querySelector('span');
                    
                    if (type === 'light') {
                        statusSpan.textContent = checkbox.checked ? 'Nyala' : 'Mati';
                    } else if (type === 'door') {
                        statusSpan.textContent = checkbox.checked ? 'Terbuka' : 'Terkunci';
                    }
                } else {
                    alert('Error: ' + (response.message || 'Gagal mengubah status'));
                    event.target.checked = !event.target.checked;
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan sistem');
                event.target.checked = !event.target.checked;
            }
        });
    }

    // Modal functions
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
        document.getElementById('addSensorForm').reset();
    }

    function openCCTVModal() {
        document.getElementById('cctvModal').classList.remove('hidden');
    }

    function closeCCTVModal() {
        document.getElementById('cctvModal').classList.add('hidden');
    }

    function handleTypeChange() {
        const deviceType = document.getElementById('deviceType').value;
        const unitField = document.getElementById('unitField');
        
        if (deviceType === 'sensor') {
            unitField.style.display = 'block';
        } else {
            unitField.style.display = 'none';
        }
    }

    document.getElementById('addSensorForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const deviceType = formData.get('type');

        let endpoint = 'functions.php';
        let action = deviceType === 'sensor' ? 'add_point' : 'add_control';
        
        formData.append('action', action);
        formData.append('ajax', true);

        $.ajax({
            url: 'functions.php',
            type: 'POST',
            data: Object.fromEntries(formData),
            success: function(response) {
                console.log('Response:', response);
                if (response.success) {
                    location.reload(); // Reload untuk menampilkan perubahan
                } else {
                    alert('Error: ' + (response.message || 'Gagal menambah perangkat'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan sistem');
            }
        });
    });
    // Update sensor values
    function updateSensors() {
        $.ajax({
            url: 'functions.php',
            type: 'POST',
            data: {
                action: 'get_sensor_data',
                ajax: true
            },
            success: function(response) {
                if (response.success && response.data) {
                    response.data.forEach(function(sensor) {
                        if (sensor.type === 'sensor') {
                            const valueElement = document.getElementById(`value-${sensor.id}`);
                            const gauge = gauges[sensor.id];
                            
                            if (valueElement && gauge && sensor.value !== null) {
                                valueElement.textContent = sensor.value;
                                gauge.set(parseFloat(sensor.value));
                            }
                            
                            if (sensor.name === 'Sensor Api') {
                                updateFireSensor(sensor.value, sensor.status);
                            }
                        }
                    });
                }
            }
        });
    }

    // Update fire sensor display
    function updateFireSensor(value, status) {
        const fireStatus = document.getElementById('fire-status');
        const statusText = fireStatus.nextElementSibling;
        
        if (status === 'Bahaya') {
            fireStatus.innerHTML = '<span class="text-4xl">🔴</span>';
            fireStatus.classList.remove('bg-green-100');
            fireStatus.classList.add('bg-red-100');
            statusText.textContent = 'Bahaya';
            statusText.classList.remove('text-green-600');
            statusText.classList.add('text-red-600');
        } else {
            fireStatus.innerHTML = '<span class="text-4xl">🟢</span>';
            fireStatus.classList.remove('bg-red-100');
            fireStatus.classList.add('bg-green-100');
            statusText.textContent = 'Aman';
            statusText.classList.remove('text-red-600');
            statusText.classList.add('text-green-600');
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize gauges
        const sensorElements = document.querySelectorAll('[id^="gauge-"]');
        sensorElements.forEach(element => {
            const sensorId = element.id.replace('gauge-', '');
            const valueElement = document.getElementById(`value-${sensorId}`);
            const value = valueElement ? parseFloat(valueElement.textContent) : 0;
            
            // Set max value based on sensor type
            let maxValue = 100;
            if (element.closest('.bg-white').querySelector('h3').textContent.includes('Suhu')) {
                maxValue = 50;
            }
            
            gauges[sensorId] = createGauge(element.id, 0, maxValue, value);
        });

        // Start periodic updates
        updateSensors();
        setInterval(updateSensors, 5000);

        // Close modals when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            const cctvModal = document.getElementById('cctvModal');
            if (event.target === addModal) {
                closeAddModal();
            }
            if (event.target === cctvModal) {
                closeCCTVModal();
            }
        }
    });
    </script>
</body>
</html>