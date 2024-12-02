<?php
// Tentukan base URL
$base_url = '/pc/timbangan/timbangan_rekap';

// Definisikan halaman yang valid
$valid_pages = ['timbang', 'histori', 'setting', 'harga'];
$user_type = isset($_GET['user_type']) ? $_GET['user_type'] : 'user';

// Ambil halaman dari parameter GET, default ke 'timbang' jika tidak ada
$page = isset($_GET['page']) && in_array($_GET['page'], $valid_pages) ? $_GET['page'] : 'timbang';

// Include koneksi database
require_once 'includes/db_connect.php';

// Fungsi untuk memuat halaman
function loadPage($page) {
    global $conn, $base_url;  // Tambahkan $conn dan $base_url sebagai global
    $file = "pages/{$page}.php";
    if (file_exists($file)) {
        include $file;
    } else {
        echo "Halaman tidak ditemukan.";
    }
}

// Fungsi untuk mendapatkan judul halaman
function getPageTitle($page) {
    $titles = [
        'timbang' => 'Timbang',
        'histori' => 'Histori',
        'setting' => 'Pengaturan',
        'harga' => 'Harga'
    ];
    return $titles[$page] ?? 'Aplikasi Timbangan';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle($page); ?> - Aplikasi Timbangan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/styles.css">
    <style>
          body {
            padding-top: 56px;
            padding-bottom: 70px;
        }
        .android-header {
            background-color: #333333; /* Sesuaikan dengan warna footer Anda */
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .android-header h1 {
            font-size: 20px;
            margin: 0;
        }
        .content-wrapper {
            margin-top: 20px;
        }
        .bluetooth-status {
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        .bluetooth-status i {
            margin-right: 5px;
        }
        .bluetooth-toggle {
            margin-left: 10px;
            padding: 5px 10px;
            font-size: 12px;
            background-color: #ffffff;
            color: #333333; /* Sesuaikan dengan warna header */
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header class="android-header">
        <h1><?php echo getPageTitle($page); ?></h1>
        <div class="bluetooth-status">
            <i class="fa fa-bluetooth"></i>
            <span id="bluetoothStatus">Not Connected</span>
            <button id="bluetoothToggle" class="bluetooth-toggle">Connect</button>
            <!-- <h1 class="h4 m-0"><?php echo ucfirst($page); ?></h1> -->
            <button id="logoutBtn" class="btn btn-outline-light btn-sm">Logout</button>
        </div>
    </header>

    <div class="container content-wrapper">
        <main>
            <?php loadPage($page); ?>
        </main>
    </div>

    <?php include 'includes/navbar.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        let isBluetoothConnected = false;
        var userType = '<?php echo $user_type; ?>';

        setTimeout(function() {
            if(userType==='user'){
            // Pilih semua elemen dengan class 'btn-danger'
            var buttons = document.querySelectorAll('.user-hide');

            // Loop melalui elemen-elemen tersebut dan hapus dari DOM
            buttons.forEach(function(button) {
                button.remove();
            });

             }
            }, 200);
        

        function bluetoothConnected() {
            isBluetoothConnected = true;
            document.getElementById('bluetoothStatus').textContent = 'Connected';
            document.querySelector('.bluetooth-status i').style.color = '#4CAF50';
            document.getElementById('bluetoothToggle').textContent = 'Disconnect';
        }

        function bluetoothNotConnected() {
            isBluetoothConnected = false;
            document.getElementById('bluetoothStatus').textContent = 'Not Connected';
            document.querySelector('.bluetooth-status i').style.color = '#F44336';
            document.getElementById('bluetoothToggle').textContent = 'Connect';
        }

        document.getElementById('bluetoothToggle').addEventListener('click', function() {
            if (isBluetoothConnected) {
                console.log('Disconnecting Bluetooth...');
                // bluetoothNotConnected();
            } else {
                console.log('Connecting Bluetooth...');
                // bluetoothConnected();
            }
        });

        function updateScale(input) {
            // const randomWeight = Math.random() * 100;
            input= input.replace("ww", "");
            input =input*1;
            document.getElementById('scale-value').textContent = input;
        }

        document.getElementById('logoutBtn').addEventListener('click', function() {
            console.log('sayalogout');
            // Di sini Anda bisa menambahkan logika logout sebenarnya
            // Misalnya, mengarahkan ke halaman login
            setTimeout(function() {
                window.location.href = 'login.php';
            }, 1000);
        });

    </script>
</body>
</html>
<?php $conn->close(); ?>