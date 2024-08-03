<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuzzy Logic Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Fuzzy Logic Calculation</h1>
        <div class="form-group">
            <label for="suhu">Suhu (°C):</label>
            <input type="number" id="suhu" class="form-control" placeholder="Masukkan Suhu">
        </div>
        <div class="form-group">
            <label for="kelembaban">Kelembaban (%):</label>
            <input type="number" id="kelembaban" class="form-control" placeholder="Masukkan Kelembaban">
        </div>
        <button class="btn btn-primary" onclick="calculate()">Hitung</button>
        
        <div id="result" class="mt-4">
            <!-- Hasil akan ditampilkan di sini -->
        </div>
    </div>

    <script>
        function calculate() {
            var suhu = parseFloat(document.getElementById('suhu').value);
            var kelembaban = parseFloat(document.getElementById('kelembaban').value);
            var resultDiv = document.getElementById('result');
            var resultText = '';

            // Fuzzifikasi Suhu
            let muDingin = 0, muAgakDingin = 0, muNormal = 0, muPanas = 0;

            // Suhu Dingin
            if (suhu >= 10 && suhu <= 23) {
                muDingin = Math.max(Math.min((suhu - 10) / (15 - 10), 1, (23 - suhu) / (23 - 18)), 0);
            }

            // Suhu Agak Dingin
            if (suhu >= 19 && suhu <= 27) {
                muAgakDingin = Math.max(Math.min((suhu - 19) / (23 - 19), (27 - suhu) / (27 - 23)), 0);
            }

            // Suhu Normal
            if (suhu >= 23 && suhu <= 31) {
                muNormal = Math.max(Math.min((suhu - 23) / (27 - 23), (31 - suhu) / (31 - 27)), 0);
            }

            // Suhu Panas
            if (suhu >= 27 && suhu <= 42) {
                muPanas = Math.max(Math.min((suhu - 27) / (32 - 27), 1, (42 - suhu) / (42 - 40)), 0);
            }

            resultText += '<p>Fuzzifikasi Suhu:</p>';
            resultText += '<p>Derajat keanggotaan "Dingin": ' + muDingin.toFixed(2) + '</p>';
            resultText += '<p>Derajat keanggotaan "Agak Dingin": ' + muAgakDingin.toFixed(2) + '</p>';
            resultText += '<p>Derajat keanggotaan "Normal": ' + muNormal.toFixed(2) + '</p>';
            resultText += '<p>Derajat keanggotaan "Panas": ' + muPanas.toFixed(2) + '</p>';

            // Fuzzifikasi Kelembapan
            let muKering = 0, muLembab = 0, muBasah = 0;

            // Kelembapan Kering
            if (kelembaban >= 0 && kelembaban <= 30) {
                muKering = Math.max(Math.min((kelembaban - 0) / (20 - 0), 1, (30 - kelembaban) / (30 - 20)), 0);
            }

            // Kelembapan Lembab
            if (kelembaban >= 28 && kelembaban <= 70) {
                muLembab = Math.max(Math.min((kelembaban - 28) / (55 - 28), (70 - kelembaban) / (70 - 55)), 0);
            }

            // Kelembapan Basah
            if (kelembaban >= 68 && kelembaban <= 100) {
                muBasah = Math.max(Math.min((kelembaban - 68) / (85 - 68), 1, (100 - kelembaban) / (100 - 85)), 0);
            }

            resultText += '<p>Fuzzifikasi Kelembapan:</p>';
            resultText += '<p>Derajat keanggotaan "Kering": ' + muKering.toFixed(2) + '</p>';
            resultText += '<p>Derajat keanggotaan "Lembab": ' + muLembab.toFixed(2) + '</p>';
            resultText += '<p>Derajat keanggotaan "Basah": ' + muBasah.toFixed(2) + '</p>';

            // Inferensi
            let zValues = [];
            const rules = [
                { suhu: muDingin, kelembaban: muKering, output: 480 },
                { suhu: muAgakDingin, kelembaban: muKering, output: 720 },
                { suhu: muNormal, kelembaban: muKering, output: 840 },
                { suhu: muPanas, kelembaban: muKering, output: 840 },
                { suhu: muDingin, kelembaban: muLembab, output: 240 },
                { suhu: muAgakDingin, kelembaban: muLembab, output: 480 },
                { suhu: muNormal, kelembaban: muLembab, output: 480 },
                { suhu: muPanas, kelembaban: muLembab, output: 480 },
                { suhu: muDingin, kelembaban: muBasah, output: 120 },
                { suhu: muAgakDingin, kelembaban: muBasah, output: 120 },
                { suhu: muNormal, kelembaban: muBasah, output: 240 },
                { suhu: muPanas, kelembaban: muBasah, output: 240 }
            ];

            resultText += '<p>Inferensi:</p>';
            rules.forEach(rule => {
                let w = Math.min(rule.suhu, rule.kelembaban);
                if (w > 0) {
                    zValues.push({ weight: w, output: rule.output });
                    resultText += '<p>Rule: Output ' + rule.output + ' dengan derajat ' + w.toFixed(2) + '</p>';
                }
            });

            // Defuzzifikasi menggunakan output dengan bobot tertinggi
            let maxWeightRule = zValues.reduce((max, current) => current.weight > max.weight ? current : max, { weight: 0 });

            resultText += '<p>Defuzzifikasi:</p>';
            resultText += '<p>Output (lama penyiraman): ' + maxWeightRule.output + ' detik</p>';

            resultDiv.innerHTML = resultText;
        }
    </script>
</body>
</html>
