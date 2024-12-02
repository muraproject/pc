 
<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.html');
    exit;
}
?>

<div class="container mx-auto px-4 py-8">
    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-6">
            <button class="tab-button px-3 py-2 border-b-2 font-medium text-sm" data-tab="kategori">
                <i class="fas fa-tags mr-2"></i>Kategori
            </button>
            <button class="tab-button px-3 py-2 border-b-2 font-medium text-sm" data-tab="produk">
                <i class="fas fa-box mr-2"></i>Produk
            </button>
            <button class="tab-button px-3 py-2 border-b-2 font-medium text-sm" data-tab="supplier">
                <i class="fas fa-truck mr-2"></i>Supplier
            </button>
            <button class="tab-button px-3 py-2 border-b-2 font-medium text-sm" data-tab="customer">
                <i class="fas fa-users mr-2"></i>Customer
            </button>
        </nav>
    </div>

    <!-- Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Panel -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Form Data</h3>
                <form id="master-form" class="space-y-4">
                    <input type="hidden" id="data_id" name="id">
                    <div id="form-fields">
                        <!-- Fields will be dynamically inserted here -->
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="resetForm()" 
                                class="btn btn-secondary">
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Panel -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold" id="table-title">Data</h3>
                        <div class="flex space-x-2">
                            <input type="text" 
                                   id="search" 
                                   placeholder="Cari..." 
                                   class="form-input text-sm rounded-lg">
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr id="table-headers">
                                <!-- Headers will be dynamically inserted here -->
                            </tr>
                        </thead>
                        <tbody id="table-body" class="bg-white divide-y divide-gray-200">
                            <!-- Data will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            Total: <span id="total-records">0</span> records
                        </div>
                        <div id="pagination" class="flex space-x-1">
                            <!-- Pagination will be dynamically inserted here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
</div>

<!-- Toast Notifications -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50"></div>

<script src="assets/js/master.js"></script>