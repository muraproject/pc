<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . APP_URL . '/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="h-full">
    <!-- Fixed Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between items-center">
                <!-- Left side -->
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900"><?php echo APP_NAME; ?></h1>
                </div>

                <!-- Center - Scale Status -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-bluetooth text-lg" id="bluetooth-icon"></i>
                        <span id="scale-status" class="text-sm font-medium">Disconnected</span>
                    </div>
                    <button id="connect-scale" class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Connect
                    </button>
                </div>

                <!-- Right side - User Menu -->
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-700">
                        <span class="font-medium"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                        <span class="text-gray-500">(<?php echo ucfirst($_SESSION['role']); ?>)</span>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm focus:outline-none">
                            <img class="h-8 w-8 rounded-full bg-gray-200" 
                                 src="<?php echo isset($_SESSION['avatar']) ? $_SESSION['avatar'] : APP_URL . '/assets/images/default-avatar.png'; ?>" 
                                 alt="User avatar">
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                            <a href="<?php echo APP_URL; ?>/profile.php" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profile
                            </a>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a href="<?php echo APP_URL; ?>/?page=settings" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Settings
                            </a>
                            <?php endif; ?>
                            <hr class="my-1">
                            <a href="<?php echo APP_URL; ?>/logout.php" 
                               class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Container with top padding for fixed header -->
    <main class="pt-16 pb-16">