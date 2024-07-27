<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IoT Monitoring and Control</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html {
            font-size: 14px;
        }
        @media (min-width: 768px) {
            html {
                font-size: 16px;
            }
        }
        html {
            position: relative;
            min-height: 100%;
        }
        body {
            margin-bottom: 60px; /* Margin bottom by footer height */
        }
        main.container {
            max-width: 960px;
            padding-top: 80px;
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px;
            line-height: 60px;
            background-color: #f5f5f5;
        }
        .card-deck .card {
            min-width: 200px;
            min-height: 200px;
        }
        .border-top {
            border-top: 1px solid #e5e5e5;
        }
        .border-bottom {
            border-bottom: 1px solid #e5e5e5;
        }
        .box-shadow {
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        }
        .gauge-container {
            padding: 10px;
            margin-bottom: auto;
            margin-top: auto;
        }
        .gauge-container > .gauge > .dial {
            stroke: rgb(47, 227, 255);
            stroke-width: 2;
            fill: rgba(0, 0, 0, 0);
        }
        .gauge-container > .gauge > .value {
            stroke: #03a9f4;
            stroke-width: 4;
            fill: rgba(0, 0, 0, 0);
        }
        .gauge-container > .gauge > .value-text {
            fill: rgb(47, 227, 255);
            font-family: sans-serif;
            font-weight: bold;
            font-size: 0.8em;
        }
        .bg-gradient {
            color: #ffffff !important;
            background: #07a7e3;
            background: -moz-linear-gradient(-45deg, #07a7e3 0%, #32dac3 100%);
            background: -webkit-linear-gradient(-45deg, #07a7e3 0%, #32dac3 100%);
            background: linear-gradient(135deg, #07a7e3 0%, #32dac3 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(
                startColorstr=$qp-color-1,
                endColorstr=$qp-color-2,
                GradientType=1
            );
            -webkit-transition: opacity 0.2s ease-out;
            -moz-transition: opacity 0.2s ease-out;
            -o-transition: opacity 0.2s ease-out;
            transition: opacity 0.2s ease-out;
        }
        .card.bg-gradient > .gauge-container > .gauge > .dial {
            stroke: rgba(255, 255, 255, 0.2);
            stroke-width: 2;
        }
        .card.bg-gradient > .gauge-container > .gauge > .value {
            stroke: rgba(255, 255, 255, 0.5);
            stroke-width: 4;
        }
        .card.bg-gradient > .gauge-container > .gauge > .value-text {
            fill: #ffffff;
            font-family: "Montserrat", sans-serif !important;
            font-size: 12px;
            font-weight: 400;
        }
    </style>
    <script src="https://bernii.github.io/gauge.js/dist/gauge.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-white fixed-top bg-white border-bottom box-shadow">
        <a class="navbar-brand" href="#">IOT MONITORING & CONTROL</a>
    </nav>

    <main class="container">
        <div class="card-deck text-center">
            <div class="card mb-4 box-shadow bg-gradient">
                <div class="card-body gauge-container">
                    <canvas id="gauge2"></canvas>
                    <p>Temperature</p>
                </div>
            </div>
            <div class="card mb-4 box-shadow bg-gradient">
                <div class="card-body gauge-container">
                    <canvas id="gauge3"></canvas>
                    <p>Humidity</p>
                </div>
            </div>
        </div>
        <div class="text-center">
            <!-- <button class="btn btn-primary" onclick="sendDirection('cw')">Putar CW</button> -->
            <h3>Putar Motor</h3>
            <button class="btn btn-danger" onclick="sendDirection('ccw')">Putar CCW</button>
            <p id="motor-status">Motor Status: -</p>
        </div>

        <div class="text-center">
            <h3>Kontrol Lampu</h3>
            <div>
                <!-- <h5>Teras</h5> -->
                <button class="btn btn-primary" onclick="controlLight('teras', 'on')">Nyalakan Lampu</button>
                <!-- <button class="btn btn-danger" onclick="controlLight('teras', 'off')">OFF</button> -->
            </div>
            <!-- <div>
                <h5>Ruang Tamu</h5>
                <button class="btn btn-primary" onclick="controlLight('ruang_tamu', 'on')">ON</button>
                <button class="btn btn-danger" onclick="controlLight('ruang_tamu', 'off')">OFF</button>
            </div>
            <div>
                <h5>Ruang Keluarga</h5>
                <button class="btn btn-primary" onclick="controlLight('ruang_keluarga', 'on')">ON</button>
                <button class="btn btn-danger" onclick="controlLight('ruang_keluarga', 'off')">OFF</button>
            </div>
            <div>
                <h5>Kamar</h5>
                <button class="btn btn-primary" onclick="controlLight('kamar', 'on')">ON</button>
                <button class="btn btn-danger" onclick="controlLight('kamar', 'off')">OFF</button>
            </div>
            <div>
                <h5>Dapur</h5>
                <button class="btn btn-primary" onclick="controlLight('dapur', 'on')">ON</button>
                <button class="btn btn-danger" onclick="controlLight('dapur', 'off')">OFF</button>
            </div> -->
        </div>

    </main>
    
    <footer class="footer">
        <div class="container border-top box-shadow">
            <span class="text-muted">2024</span>
        </div>
    </footer> 

    <script>
        let gauge2, gauge3;

        function createGauge(containerId, maxValue, zones) {
            var opts = {
                angle: 0.15, // The span of the gauge arc
                lineWidth: 0.44, // The line thickness
                radiusScale: 1, // Relative radius
                pointer: {
                    length: 0.6, // Relative to gauge radius
                    strokeWidth: 0.035, // The thickness
                    color: '#000000' // Fill color
                },
                limitMax: false,     // If false, max value increases automatically if value > maxValue
                limitMin: false,     // If true, the min value of the gauge will be fixed
                colorStart: '#6FADCF',   // Colors
                colorStop: '#8FC0DA',    // just experiment with them
                strokeColor: '#E0E0E0',  // to see which ones work best for you
                generateGradient: true,
                highDpiSupport: true,     // High resolution support

                staticZones: zones,
                staticLabels: {
                    font: "10px sans-serif",  // Specifies font
                    labels: zones.map(zone => zone.max),  // Print labels at these values
                    color: "#000000",  // Optional: Label text color
                    fractionDigits: 0  // Optional: Numerical precision. 0=round off.
                }
            };
            var target = document.getElementById(containerId); // your canvas element
            var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
            gauge.maxValue = maxValue; // set max gauge value
            gauge.setMinValue(0);  // Prefer setter over gauge.minValue = 0
            gauge.animationSpeed = 32; // set animation speed (32 is default value)
            gauge.set(0); // set actual value
            return gauge;
        }

        function updateSensorValues() {
            fetch('get_ldr_value.php')
                .then(response => response.json())
                .then(data => {
                    gauge2.set(data.temperature);
                    gauge3.set(data.humidity);
                    // document.getElementById('motor-status').innerText = 'Motor Status: ' + data.motor_status;
                });
        }

        function sendDirection(direction) {
            fetch(`control_motor.php?direction=${direction}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('motor-status').innerText = 'Motor Status: ' + data;
});
}
window.onload = function() {
        gauge2 = createGauge('gauge2', 50, [
            {strokeStyle: "#F03E3E", min: 0, max: 20}, // Red from 0 to 20
            {strokeStyle: "#FFDD00", min: 20, max: 30}, // Yellow
            {strokeStyle: "#30B32D", min: 30, max: 50}  // Green
        ]);
        gauge3 = createGauge('gauge3', 100, [
            {strokeStyle: "#F03E3E", min: 0, max: 30}, // Red from 0 to 30
            {strokeStyle: "#FFDD00", min: 30, max: 60}, // Yellow
            {strokeStyle: "#30B32D", min: 60, max: 100}  // Green
        ]);
        
        setInterval(updateSensorValues, 2000); // Update every 5 seconds
    };

    function sendDirection(direction) {
            fetch(`control_motor.php?direction=${direction}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('motor-status').innerText = 'Motor Status: ' + data;
                });
        }

        function controlLight(room, action) {
            fetch(`control_light.php?room=${room}&action=${action}`)
                .then(response => response.json())
                .then(data => {
                    alert(`Light in ${room} is now ${data.status}`);
                });
        }
</script>
</body>
</html>
