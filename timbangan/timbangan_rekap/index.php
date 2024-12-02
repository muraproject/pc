 
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get current page from URL parameter
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Validate allowed pages
$allowed_pages = ['dashboard', 'barang_masuk', 'barang_keluar', 'timbang_biaya', 'master'];
if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Timbangan</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-100">
    <!-- Top Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <!-- Left side -->
                <div class="flex items-center">
                    <span class="text-xl font-bold text-gray-800">
                        Aplikasi Timbangan
                    </span>
                </div>

                <!-- Right side -->
                <div class="flex items-center">
                    <div class="flex items-center space-x-4">
                        <!-- Timbangan Status -->
                        <div class="flex items-center text-sm">
                            <i class="fas fa-weight-scale mr-2"></i>
                            <span id="timbangan-status">Tidak Terhubung</span>
                        </div>

                        <!-- User menu -->
                        <div class="relative">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <i class="fas fa-user"></i>
                                <span><?php echo $_SESSION['username']; ?></span>
                            </button>
                        </div>

                        <!-- Logout button -->
                        <a href="logout.php" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t md:hidden">
        <div class="grid grid-cols-4 gap-1">
            <a href="?page=dashboard" class="flex flex-col items-center py-2 <?php echo $page === 'dashboard' ? 'text-blue-600' : 'text-gray-600'; ?>">
                <i class="fas fa-home mb-1"></i>
                <span class="text-xs">Dashboard</span>
            </a>
            <a href="?page=barang_masuk" class="flex flex-col items-center py-2 <?php echo $page === 'barang_masuk' ? 'text-blue-600' : 'text-gray-600'; ?>">
                <i class="fas fa-arrow-down mb-1"></i>
                <span class="text-xs">Masuk</span>
            </a>
            <a href="?page=barang_keluar" class="flex flex-col items-center py-2 <?php echo $page === 'barang_keluar' ? 'text-blue-600' : 'text-gray-600'; ?>">
                <i class="fas fa-arrow-up mb-1"></i>
                <span class="text-xs">Keluar</span>
            </a>
            <a href="?page=timbang_biaya" class="flex flex-col items-center py-2 <?php echo $page === 'timbang_biaya' ? 'text-blue-600' : 'text-gray-600'; ?>">
                <i class="fas fa-calculator mb-1"></i>
                <span class="text-xs">Hitung</span>
            </a>
        </div>
    </div>

    <!-- Sidebar (desktop) -->
    <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 pt-16">
        <div class="flex-1 flex flex-col min-h-0 bg-white border-r">
            <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                <nav class="flex-1 px-2 space-y-1">
                    <a href="?page=dashboard" class="<?php echo $page === 'dashboard' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-home mr-3"></i>
                        Dashboard
                    </a>
                    <a href="?page=barang_masuk" class="<?php echo $page === 'barang_masuk' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-arrow-down mr-3"></i>
                        Barang Masuk
                    </a>
                    <a href="?page=barang_keluar" class="<?php echo $page === 'barang_keluar' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-arrow-up mr-3"></i>
                        Barang Keluar
                    </a>
                    <a href="?page=timbang_biaya" class="<?php echo $page === 'timbang_biaya' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-calculator mr-3"></i>
                        Timbang & Biaya
                    </a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="?page=master" class="<?php echo $page === 'master' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-database mr-3"></i>
                        Master Data
                    </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="md:pl-64 flex flex-col flex-1">
        <main class="flex-1 pt-16 pb-16 md:pb-0">
            <?php include "pages/{$page}.php"; ?>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="assets/js/main.js"></script>
    <?php if ($page === 'master'): ?>
        <script src="assets/js/master.js"></script>
    <?php elseif ($page === 'timbang_biaya'): ?>
        <script src="assets/js/hitung.js"></script>
    <?php else: ?>
        <script src="assets/js/inventory.js"></script>
    <?php endif; ?>
</body>
</html>