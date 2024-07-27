<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Ketinggian Air Sungai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Monitoring Ketinggian Air Sungai</h1>
        <div class="card mt-3">
            <div class="card-body">
                <h2 class="card-title">Nilai Sensor: <span id="sensor-value"></span></h2>
            </div>
        </div>
        <canvas id="sensorChart" class="mt-4"></canvas>
        <div id="alert-container" class="mt-4"></div>
        <div class="mt-4 text-center">
            <h3>Kontrol Pintu Air</h3>
            <div class="row">
                <div class="col-md-6">
                    <h4>Pintu Air 1</h4>
                    <button class="btn btn-primary" onclick="controlGate(1, 1)">On</button>
                    <button class="btn btn-danger" onclick="controlGate(1, 0)">Off</button>
                </div>
                <div class="col-md-6">
                    <h4>Pintu Air 2</h4>
                    <button class="btn btn-primary" onclick="controlGate(2, 1)">On</button>
                    <button class="btn btn-danger" onclick="controlGate(2, 0)">Off</button>
                </div>
            </div>
        </div>
        
    </div>

    <script>
        const sensorValueElement = document.getElementById('sensor-value');
        const ctx = document.getElementById('sensorChart').getContext('2d');
        let sensorData = [];
        let sensorChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Nilai Sensor',
                    data: sensorData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function fetchSensorData() {
            fetch('get_sensor.php')
                .then(response => response.json())
                .then(data => {
                    if (data.sensor_data.length > 0) {
                        updateChart(data.sensor_data);
                        sensorValueElement.textContent = data.sensor_data[0].sensor_value;
                    }
                });
        }

        function updateChart(sensorDataArray) {
            sensorChart.data.labels = sensorDataArray.map(item => new Date(item.timestamp).toLocaleTimeString());
            sensorChart.data.datasets[0].data = sensorDataArray.map(item => item.sensor_value);

            sensorChart.update();
        }

        function controlGate(gateNumber, status) {
            const formData = new FormData();
            if (gateNumber === 1) {
                formData.append('gate1_status', status);
            } else if (gateNumber === 2) {
                formData.append('gate2_status', status);
            }

            fetch('set_pintu_air.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                showAlert(data, 'success');
                fetchSensorData();
            })
            .catch(error => {
                showAlert('Error: ' + error, 'danger');
            });
        }

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alert-container');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.role = 'alert';
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            alertContainer.appendChild(alert);

            setTimeout(() => {
                alert.classList.remove('show');
                alert.addEventListener('transitionend', () => alert.remove());
            }, 3000);
        }

        setInterval(fetchSensorData, 5000);
        fetchSensorData();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
