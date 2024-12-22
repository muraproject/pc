<!-- Fixed Bottom Navigation -->
<nav class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-around items-center">
            <!-- Timbang Masuk -->
            <a href="<?php echo APP_URL; ?>/?page=weighing_in" 
               class="flex flex-col items-center text-center group <?php echo $page === 'weighing_in' ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600'; ?>">
                <div class="p-2 rounded-lg <?php echo $page === 'weighing_in' ? 'bg-blue-100' : 'group-hover:bg-blue-100'; ?>">
                    <i class="fas fa-arrow-down text-lg"></i>
                </div>
                <span class="mt-1 text-xs font-medium">Timbang Masuk</span>
            </a>

            <!-- Timbang Keluar -->
            <a href="<?php echo APP_URL; ?>/?page=weighing_out" 
               class="flex flex-col items-center text-center group <?php echo $page === 'weighing_out' ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600'; ?>">
                <div class="p-2 rounded-lg <?php echo $page === 'weighing_out' ? 'bg-blue-100' : 'group-hover:bg-blue-100'; ?>">
                    <i class="fas fa-arrow-up text-lg"></i>
                </div>
                <span class="mt-1 text-xs font-medium">Timbang Keluar</span>
            </a>

            <!-- History -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex flex-col items-center text-center group <?php echo in_array($page, ['history_in', 'history_out']) ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600'; ?>">
                    <div class="p-2 rounded-lg <?php echo in_array($page, ['history_in', 'history_out']) ? 'bg-blue-100' : 'group-hover:bg-blue-100'; ?>">
                        <i class="fas fa-history text-lg"></i>
                    </div>
                    <span class="mt-1 text-xs font-medium">History</span>
                </button>

                <!-- Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-40 bg-white rounded-lg shadow-lg py-1">
                    <a href="<?php echo APP_URL; ?>/?page=history_in" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        History Masuk
                    </a>
                    <a href="<?php echo APP_URL; ?>/?page=history_out" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        History Keluar
                    </a>
                </div>
            </div>

            <!-- Kwitansi -->
             
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex flex-col items-center text-center group <?php echo in_array($page, ['receipt_in', 'receipt_out']) ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600'; ?>">
                    <div class="p-2 rounded-lg <?php echo in_array($page, ['receipt_in', 'receipt_out']) ? 'bg-blue-100' : 'group-hover:bg-blue-100'; ?>">
                        <i class="fas fa-receipt text-lg"></i>
                    </div>
                    <span class="mt-1 text-xs font-medium">Kwitansi</span>
                </button>

                <!-- Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-40 bg-white rounded-lg shadow-lg py-1">
                    <a href="<?php echo APP_URL; ?>/?page=receipt_in" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Kwitansi Masuk
                    </a>
                    <a href="<?php echo APP_URL; ?>/?page=receipt_out" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Kwitansi Keluar
                    </a>
                </div>
            </div>

            <?php if ($_SESSION['role'] === 'admin'): ?>
            <!-- Admin Only Menu -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex flex-col items-center text-center group <?php echo in_array($page, ['wages', 'settings']) ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600'; ?>">
                    <div class="p-2 rounded-lg <?php echo in_array($page, ['wages', 'settings']) ? 'bg-blue-100' : 'group-hover:bg-blue-100'; ?>">
                        <i class="fas fa-cog text-lg"></i>
                    </div>
                    <span class="mt-1 text-xs font-medium">Admin</span>
                </button>

                <!-- Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-40 bg-white rounded-lg shadow-lg py-1">
                    <a href="<?php echo APP_URL; ?>/?page=wages" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Upah Karyawan
                    </a>
                    <a href="<?php echo APP_URL; ?>/?page=settings" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Pengaturan
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Close main content and body tags -->
    </main>
    
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="<?php echo APP_URL; ?>/assets/js/scale.js"></script>
</body>
</html>