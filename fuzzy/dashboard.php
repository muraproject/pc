<?php
include('includes/db.php');
include('includes/functions.php');
check_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-custom {
            background: #ffffff;
            background: -webkit-linear-gradient(to right, rgba(255, 255, 255, 1), rgba(255, 236, 210, 1));
            background: linear-gradient(to right, rgba(255, 255, 255, 1), rgba(255, 236, 210, 1));
        }

        .gradient-custom2 {
            background: #ffffff;
            background: -webkit-linear-gradient(to right, rgba(255, 255, 255, 1), rgba(255, 236, 210, 1));
            background: linear-gradient(to right, rgba(255, 255, 255, 1), rgba(25, 126, 180, 1));
        }
        body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .container {
        flex: 1;
    }

    .footer {
        margin-top: auto;
    }
    </style>
</head>
<body>
<nav class="navbar  navbar-light bg-light">
 
  <a class="navbar-brand" href="#">Monitoring Fuzzy logic</a>
  <!-- <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button> -->
  <a href="logout.php" class="btn btn-danger">Logout</a>
  <!-- <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li> -->
  
</nav>
<div class="container mt-5">
    <div class="row">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h3>Kontrol Motor</h3>
            <p>Status: <span id="motor-status">Tidak Diketahui</span></p>
            <button id="motorOn" class="btn btn-success">Nyalakan</button>
            <button id="motorOff" class="btn btn-danger">Matikan</button>
        </div>
    </div>
        <div class="col-md-6">
            <div id="temp-container" class="d-flex justify-content-between align-items-center px-5 gradient-custom" style="height: 230px">
                <div>
                    <h2 id="temp-value" class="text-dark display-2"><strong>23°C</strong></h2>
                    <p id="temp-class" class="text-dark mb-0">Suhu | Dingin</p>
                </div>
                <div>
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-weather/ilu3.webp" width="150px">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="hum-container" class="d-flex justify-content-between align-items-center px-5 gradient-custom2" style="height: 230px">
                <div>
                    <h2 id="hum-value" class="text-dark display-2"><strong>70%</strong></h2>
                    <p id="hum-class" class="text-dark mb-0">Kelembaban | Basah</p>
                </div>
                <div>
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-weather/ilu3.webp" width="150px">
                </div>
            </div>
        </div>
        
    </div>
    <div class="row mt-5">
        <div class="col-12 text-center">
        <div id="water-container" class="align-items-center px-5 bg-info text center" style="height: 230px">
               
                    <h2 id="water-value" class="text-dark display-2"><strong>1</strong></h2>
                    <p id="water-class" class="text-dark mb-0">Penyiraman</p>    
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-weather/ilu3.webp" width="150px">
                
           
        </div>
    </div>
    
</div>

<footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <span class="text-muted">© 2024 Sistem Monitoring IoT. All rights reserved.</span>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="#" class="text-muted">Kebijakan Privasi</a> | 
                <a href="#" class="text-muted">Syarat dan Ketentuan</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.js"></script>
<script>
function updateDashboard() {
    fetch('api/getdata.php')
        .then(response => response.json())
        .then(data => {
            if (!data.error) {
                document.getElementById('temp-value').innerHTML = `<strong>${data.temperature}°C</strong>`;
                document.getElementById('temp-class').innerText = `Suhu | ${data.temp_class}`;
                document.getElementById('hum-value').innerHTML = `<strong>${data.humidity}%</strong>`;
                document.getElementById('hum-class').innerText = `Kelembaban | ${data.hum_class}`;
                document.getElementById('water-value').innerHTML = `<strong>${data.watering}</strong>`;
                document.getElementById('water-class').innerText = `Penyiraman`;
            }
        })
        .catch(error => console.error('Error fetching data:', error));
}

setInterval(updateDashboard, 3000);
updateDashboard();

function updateMotorStatus() {
    fetch('api/getmotorstatus.php')
        .then(response => response.json())
        .then(data => {
            if (!data.error) {
                document.getElementById('motor-status').innerText = data.status == 'on' ? 'Menyala' : 'Mati';
            }
        })
        .catch(error => console.error('Error mengambil status motor:', error));
}

document.getElementById('motorOn').addEventListener('click', function() {
    setMotorStatus('on');
});

document.getElementById('motorOff').addEventListener('click', function() {
    setMotorStatus('off');
});

function setMotorStatus(status) {
    fetch(`api/setmotorstatus.php?status=${status}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateMotorStatus();
            }
        })
        .catch(error => console.error('Error mengatur status motor:', error));
}

// Perbarui status motor setiap 5 detik
setInterval(updateMotorStatus, 1000);
updateMotorStatus(); // Pembaruan awal

</script>
</body>
</html>
