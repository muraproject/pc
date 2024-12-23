<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Define valid pages and default page
$valid_pages = [
    'dashboard',
    'weighing_in',
    'weighing_out',
    'history_in',
    'history_out',
    'receipt_in',
    'receipt_out',
    'wages',
    'settings'
];

// Get requested page or default to dashboard
$page = isset($_GET['page']) && in_array($_GET['page'], $valid_pages) ? $_GET['page'] : 'dashboard';

// Function to get page title
function getPageTitle($page) {
    $titles = [
        'dashboard' => 'Dashboard',
        'weighing_in' => 'Timbang Masuk',
        'weighing_out' => 'Timbang Keluar',
        'history_in' => 'Histori Masuk',
        'history_out' => 'Histori Keluar',
        'receipt_in' => 'Kwitansi Masuk',
        'receipt_out' => 'Kwitansi Keluar',
        'wages' => 'Upah Timbang',
        'settings' => 'Pengaturan'
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
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Additional CSS/JS based on page -->
    <?php if ($page === 'dashboard'): ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php endif; ?>
    <style>
.android-header {
    background-color: #333333;
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
    color: #333333;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.main-content {
    padding-top: 56px;
}

nav .overflow-x-auto {
    -ms-overflow-style: none;  /* untuk Internet Explorer dan Edge */
    scrollbar-width: none;     /* untuk Firefox */
}

nav .overflow-x-auto::-webkit-scrollbar { 
    display: none;  /* untuk Chrome, Safari dan Opera */
}
.hishis {
    z-index: 500; /* Pastikan popup di atas navbar */
}

</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold text-gray-900">
                <?php echo getPageTitle($page); ?>
            </h1>
            <div class="bluetooth-status">
                <i class="fa fa-bluetooth"></i>
                <span id="bluetoothStatus"></span>
                <button id="bluetoothToggle" class="bluetooth-toggle">Connect</button>
            </div>
            <div class="flex items-center">
                <span class="text-gray-700 mr-4">
                    <?php echo htmlspecialchars($_SESSION['name']); ?>
                    
                </span>
                <button onclick="location.href='logout.php'" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Logout
                </button>
            </div>
            
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-24">
        <?php include "pages/{$page}.php"; ?>
    </main>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200">
        <div class="px-2 overflow-x-auto"> <!-- Hapus max-w-7xl dan tambahkan overflow-x-auto -->
            <div class="flex whitespace-nowrap py-2">
                <!-- Dashboard -->
                <a href="?page=dashboard" class="flex flex-col items-center py-2 px-3 text-gray-600 hover:text-blue-500 <?php echo $page === 'dashboard' ? 'text-blue-500' : ''; ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs">Dashboard</span>
                </a>

                <!-- Timbang Masuk -->
                <a href="?page=weighing_in" class="flex flex-col items-center py-2 px-3 text-gray-600 hover:text-blue-500 <?php echo $page === 'weighing_in' ? 'text-blue-500' : ''; ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-xs">Timbang Masuk</span>
                </a>

                <!-- Timbang Keluar -->
                <a href="?page=weighing_out" class="flex flex-col items-center py-2 px-3 text-gray-600 hover:text-blue-500 <?php echo $page === 'weighing_out' ? 'text-blue-500' : ''; ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-xs">Timbang Keluar</span>
                </a>

                <!-- History -->
                <div class="relative group">
                    <a href="#" onclick="toggleHistoryDropdown()" class="flex flex-col items-center py-2 px-3 text-gray-600 hover:text-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-xs">History</span>
                    </a>
                    <div id="historyDropdown" style="display:none;" class="fixed left-0 right-0 bottom-16 mx-auto w-48 bg-white rounded-lg shadow-lg py-1">
                        <div class="bg-white rounded-lg shadow-lg">
                            <a href="?page=history_in" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">History Masuk</a>
                            <a href="?page=history_out" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">History Keluar</a>
                        </div>
                    </div>
                </div>

                <!-- Kwitansi -->
                <?php if ($_SESSION['role'] === 'admin'): ?>
                <div class="relative group">
                    <a href="#" onclick="toggleKwitansiDropdown()" class="flex flex-col items-center py-2 px-3 text-gray-600 hover:text-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="text-xs">Kwitansi</span>
                    </a>
                    <div id="kwitansiDropdown" style="display:none;" class="fixed left-0 right-0 bottom-16 mx-auto w-48 bg-white rounded-lg shadow-lg py-1">
                        <div class="bg-white rounded-lg shadow-lg">
                            <a href="?page=receipt_in" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kwitansi Masuk</a>
                            <a href="?page=receipt_out" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kwitansi Keluar</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                <!-- Admin Only Menu Items -->
                <a href="?page=wages" class="flex flex-col items-center py-2 px-3 text-gray-600 hover:text-blue-500 <?php echo $page === 'wages' ? 'text-blue-500' : ''; ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-xs">Upah</span>
                </a>

                <a href="?page=settings" class="flex flex-col items-center py-2 px-3 text-gray-600 hover:text-blue-500 <?php echo $page === 'settings' ? 'text-blue-500' : ''; ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-xs">Setting</span>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Page specific scripts -->
    <?php if ($page === 'dashboard'): ?>
        <script src="assets/js/dashboard.js"></script>
    <?php elseif ($page === 'weighing_in'): ?>
        <script src="assets/js/weighing-in.js"></script>
    <?php elseif ($page === 'weighing_out'): ?>
        <script src="assets/js/weighing-out.js"></script>
    <?php elseif ($page === 'history_in'): ?>
        <script src="assets/js/history-in.js"></script>
    <?php elseif ($page === 'history_out'): ?>
        <script src="assets/js/history-out.js"></script>
    <?php elseif ($page === 'receipt_in'): ?>
        <script src="assets/js/receipt-in.js"></script>
    <?php elseif ($page === 'receipt_out'): ?>
        <script src="assets/js/receipt-out.js"></script>
    <?php elseif ($page === 'wages'): ?>
        <script src="assets/js/wages.js"></script>
    <?php elseif ($page === 'settings'): ?>
        <script src="assets/js/settings.js"></script>
    <?php endif; ?>

    <script>
        // Handle dropdown menus
        <?php if ($_SESSION['role'] === 'admin'): ?>
            //console.log("landscape");
        <?php endif; ?>
        document.addEventListener('DOMContentLoaded', function() {
            // Show/hide dropdowns on hover
            const dropdowns = document.querySelectorAll('.group');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('mouseenter', () => {
                    const menu = dropdown.querySelector('.group-hover\\:block');
                    if (menu) menu.classList.remove('hidden');
                });
                
                dropdown.addEventListener('mouseleave', () => {
                    const menu = dropdown.querySelector('.group-hover\\:block');
                    if (menu) menu.classList.add('hidden');
                });
            });

            // Handle mobile menu
            const menuButton = document.querySelector('[data-mobile-menu]');
            if (menuButton) {
                menuButton.addEventListener('click', () => {
                    const menu = document.querySelector('[data-mobile-menu-items]');
                    menu.classList.toggle('hidden');
                });
            }
        });
        
        function toggleHistoryDropdown() {
            // Tutup semua dropdown dulu
           document.getElementById("historyDropdown").style.display = "block";
           document.getElementById("kwitansiDropdown").style.display = "none";
            
        }
        function toggleKwitansiDropdown() {
            // Tutup semua dropdown dulu
           document.getElementById("kwitansiDropdown").style.display = "block";
           document.getElementById("historyDropdown").style.display = "none";
            
        }
        // Bluetooth connection status handler
        let isBluetoothConnected = false;

        function updateBluetoothStatus(isConnected) {
            const statusElement = document.getElementById('bluetooth-status');
            if (statusElement) {
                statusElement.textContent = isConnected ? 'Connected' : 'Not Connected';
                statusElement.className = isConnected ? 'text-green-500' : 'text-red-500';
            }
        }

        // Scale value update handler
        function updateScaleValue(value) {
            const scaleElement = document.getElementById('scale-value');
            if (scaleElement) {
                scaleElement.textContent = parseFloat(value).toFixed(2);
            }
        }

        // Global error handler
        window.onerror = function(msg, url, lineNo, columnNo, error) {
            console.error('Error: ' + msg + '\nURL: ' + url + '\nLine: ' + lineNo + '\nColumn: ' + columnNo + '\nError object: ' + JSON.stringify(error));
            return false;
        };


// let isBluetoothConnected = false;

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
       bluetoothNotConnected();
   } else {
       console.log('Connecting Bluetooth...');
       bluetoothConnected();
   }
});

function updateScale(input) {
   input = input.replace("ww", "");
   input = input * 1; 
   document.getElementById('scale-value').textContent = input;
}
    </script>
</body>
</html>