<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.html');
    exit;
}
?>

<div class="container mx-auto px-4 py-8">
    <!-- Header with Title and Breadcrumb -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Master Data</h1>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-6 bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="kategori">
                    <i class="fas fa-tags mr-2"></i>Kategori
                </button>
                <button class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="produk">
                    <i class="fas fa-box mr-2"></i>Produk
                </button>
                <button class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="supplier">
                    <i class="fas fa-truck mr-2"></i>Supplier
                </button>
                <button class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="customer">
                    <i class="fas fa-users mr-2"></i>Customer
                </button>
            </nav>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Panel - Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow">
                <!-- Form Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900" id="form-title">Tambah Data</h3>
                </div>

                <!-- Form Content -->
                <div class="p-6">
                    <form id="master-form" class="space-y-6">
                        <input type="hidden" id="id" name="id">
                        <!-- Form fields will be dynamically inserted here -->
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Panel - Table -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <!-- Table Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Data</h3>
                        <div class="relative">
                            <input type="text" 
                                   id="search-input" 
                                   placeholder="Cari..." 
                                   class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div id="data-table" class="overflow-x-auto">
                    <!-- Table will be dynamically inserted here -->
                </div>

                <!-- Table Footer - for future use (pagination, etc) -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            <span id="total-records">0</span> data ditemukan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="hidden fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-4 rounded-lg shadow-lg flex items-center">
        <svg class="animate-spin h-5 w-5 mr-3 text-blue-500" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        <span class="text-gray-700">Loading...</span>
    </div>
</div>

<!-- Toast Messages Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-4"></div>

<!-- Success Modal Template -->
<div id="success-modal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm mx-auto">
        <div class="flex items-center justify-center text-green-500 mb-4">
            <i class="fas fa-check-circle text-4xl"></i>
        </div>
        <h3 class="text-lg font-medium text-center mb-4" id="modal-message"></h3>
        <div class="text-center">
            <button onclick="document.getElementById('success-modal').classList.add('hidden')" 
                    class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                OK
            </button>
        </div>
    </div>
</div>

<!-- Confirmation Modal Template -->
<div id="confirm-modal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm mx-auto">
        <div class="flex items-center justify-center text-yellow-500 mb-4">
            <i class="fas fa-exclamation-triangle text-4xl"></i>
        </div>
        <h3 class="text-lg font-medium text-center mb-4" id="confirm-message"></h3>
        <div class="flex justify-center space-x-4">
            <button id="confirm-yes" 
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                Ya
            </button>
            <button onclick="document.getElementById('confirm-modal').classList.add('hidden')" 
                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                Batal
            </button>
        </div>
    </div>
</div>

<script src="../timbangan_rekap/assets/js/master.js"></script>