 
<?php checkSession(); ?>

<nav class="bg-gray-800 fixed w-full z-50 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-white text-lg font-bold"><?php echo APP_NAME; ?></span>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="<?php echo BASE_URL; ?>/index.php" 
                           class="<?php echo $page == 'dashboard' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> px-3 py-2 rounded-md text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="<?php echo BASE_URL; ?>/pages/barang_masuk.php" 
                           class="<?php echo $page == 'barang_masuk' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> px-3 py-2 rounded-md text-sm font-medium">
                            Barang Masuk
                        </a>
                        <a href="<?php echo BASE_URL; ?>/pages/barang_keluar.php" 
                           class="<?php echo $page == 'barang_keluar' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> px-3 py-2 rounded-md text-sm font-medium">
                            Barang Keluar
                        </a>
                        <a href="<?php echo BASE_URL; ?>/pages/timbang_biaya.php" 
                           class="<?php echo $page == 'timbang_biaya' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> px-3 py-2 rounded-md text-sm font-medium">
                            Biaya Tenaga
                        </a>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="<?php echo BASE_URL; ?>/pages/master.php" 
                           class="<?php echo $page == 'master' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> px-3 py-2 rounded-md text-sm font-medium">
                            Master Data
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <div class="relative">
                        <button id="user-menu-button" 
                                class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            <?php echo $_SESSION['username']; ?>
                        </button>
                        <a href="<?php echo BASE_URL; ?>/logout.php" 
                           class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="<?php echo BASE_URL; ?>/index.php" 
               class="<?php echo $page == 'dashboard' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> block px-3 py-2 rounded-md text-base font-medium">
                Dashboard
            </a>
            <a href="<?php echo BASE_URL; ?>/pages/barang_masuk.php" 
               class="<?php echo $page == 'barang_masuk' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> block px-3 py-2 rounded-md text-base font-medium">
                Barang Masuk
            </a>
            <a href="<?php echo BASE_URL; ?>/pages/barang_keluar.php" 
               class="<?php echo $page == 'barang_keluar' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> block px-3 py-2 rounded-md text-base font-medium">
                Barang Keluar
            </a>
            <a href="<?php echo BASE_URL; ?>/pages/timbang_biaya.php" 
               class="<?php echo $page == 'timbang_biaya' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> block px-3 py-2 rounded-md text-base font-medium">
                Biaya Tenaga
            </a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="<?php echo BASE_URL; ?>/pages/master.php" 
               class="<?php echo $page == 'master' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> block px-3 py-2 rounded-md text-base font-medium">
                Master Data
            </a>
            <?php endif; ?>
        </div>
    </div>
</nav>