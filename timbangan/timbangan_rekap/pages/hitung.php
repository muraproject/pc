 
<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}
?>
<div class="container mx-auto px-4 py-8">
    <!-- Tab Navigation -->
    <div class="mb-6">
        <div class="flex border-b">
            <button class="py-2 px-4 text-sm font-medium text-center border-b-2 border-blue-500" 
                    onclick="switchTab('supplier')" id="supplier-tab">
                Hitung Supplier
            </button>
            <button class="py-2 px-4 text-sm font-medium text-center text-gray-500 hover:text-gray-700" 
                    onclick="switchTab('tenaga')" id="tenaga-tab">
                Hitung Biaya Tenaga
            </button>
        </div>
    </div>

    <!-- Supplier Section -->
    <div id="supplier-section" class="tab-content">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Perhitungan Supplier</h2>
                <div class="flex gap-4">
                    <select id="supplier-select" class="form-select rounded-md border-gray-300">
                        <option value="">Pilih Supplier</option>
                    </select>
                    <input type="date" id="start-date" class="form-input rounded-md border-gray-300">
                    <input type="date" id="end-date" class="form-input rounded-md border-gray-300">
                    <button onclick="loadSupplierData()" class="btn btn-primary">
                        Tampilkan
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produk
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Berat (kg)
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga/kg
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody id="supplier-data" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be loaded here -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="px-6 py-4 font-bold">Total</td>
                            <td id="total-berat" class="px-6 py-4 font-bold">0 kg</td>
                            <td></td>
                            <td id="total-harga" class="px-6 py-4 font-bold">Rp 0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <button onclick="saveSupplierCalculation()" class="btn btn-success">
                    Simpan Perhitungan
                </button>
            </div>
        </div>
    </div>

    <!-- Tenaga Section -->
    <div id="tenaga-section" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Perhitungan Biaya Tenaga Kerja</h2>
                <div class="flex gap-4">
                    <select id="worker-select" class="form-select rounded-md border-gray-300">
                        <option value="">Pilih Karyawan</option>
                    </select>
                    <input type="date" id="worker-start-date" class="form-input rounded-md border-gray-300">
                    <input type="date" id="worker-end-date" class="form-input rounded-md border-gray-300">
                    <button onclick="loadWorkerData()" class="btn btn-primary">
                        Tampilkan
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produk
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Berat (kg)
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Biaya/kg
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody id="worker-data" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be loaded here -->
                    </tbody>