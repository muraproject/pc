<?php
if ($_SESSION['role'] !== 'admin') {
    echo '<div class="p-4 bg-red-100 text-red-700 rounded">Anda tidak memiliki akses ke halaman ini</div>';
    exit;
}

// Get data for all tables
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
$products = $conn->query("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    ORDER BY p.name
");
$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY name");
$buyers = $conn->query("SELECT * FROM buyers ORDER BY name");
$users = $conn->query("SELECT * FROM users ORDER BY name");
?>

<div class="space-y-6">
    <!-- Tab Navigation -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="showTab('categories')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Kategori
            </button>
            <button onclick="showTab('products')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Produk
            </button>
            <button onclick="showTab('suppliers')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Supplier
            </button>
            <button onclick="showTab('buyers')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Pembeli
            </button>
            <button onclick="showTab('users')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                User
            </button>
        </nav>
    </div>

    <!-- Categories Tab -->
    <div id="categories-tab" class="tab-content hidden">
        <div class="bg-white shadow rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Daftar Kategori</h2>
                    <button onclick="showCategoryModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Tambah Kategori
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($category = $categories->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo date('d/m/Y H:i', strtotime($category['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editCategory(<?php echo htmlspecialchars(json_encode($category)); ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                            Edit
                                        </button>
                                        <button onclick="deleteCategory(<?php echo $category['id']; ?>)" class="text-red-600 hover:text-red-900">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Tab -->
    <div id="products-tab" class="tab-content hidden">
        <div class="bg-white shadow rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Daftar Produk</h2>
                    <button onclick="showProductModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Tambah Produk
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($product = $products->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($product['category_name']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo date('d/m/Y H:i', strtotime($product['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                            Edit
                                        </button>
                                        <button onclick="deleteProduct(<?php echo $product['id']; ?>)" class="text-red-600 hover:text-red-900">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Suppliers Tab -->
    <div id="suppliers-tab" class="tab-content hidden">
        <div class="bg-white shadow rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Daftar Supplier</h2>
                    <button onclick="showSupplierModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Tambah Supplier
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($supplier = $suppliers->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?php echo htmlspecialchars($supplier['name']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?php echo htmlspecialchars($supplier['address']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?php echo htmlspecialchars($supplier['phone']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editSupplier(<?php echo htmlspecialchars(json_encode($supplier)); ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                            Edit
                                        </button>
                                        <button onclick="deleteSupplier(<?php echo $supplier['id']; ?>)" class="text-red-600 hover:text-red-900">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Buyers Tab -->
    <div id="buyers-tab" class="tab-content hidden">
        <div class="bg-white shadow rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Daftar Pembeli</h2>
                    <button onclick="showBuyerModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Tambah Pembeli
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($buyer = $buyers->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?php echo htmlspecialchars($buyer['name']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?php echo htmlspecialchars($buyer['address']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?php echo htmlspecialchars($buyer['phone']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editBuyer(<?php echo htmlspecialchars(json_encode($buyer)); ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                            Edit
                                        </button>
                                        <button onclick="deleteBuyer(<?php echo $buyer['id']; ?>)" class="text-red-600 hover:text-red-900">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Tab -->
    <div id="users-tab" class="tab-content hidden">
        <div class="bg-white shadow rounded-lg">
        <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Daftar User</h2>
                    <button onclick="showUserModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Tambah User
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($user = $users->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?php echo htmlspecialchars($user['username']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?php echo htmlspecialchars($user['name']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?php echo ucfirst($user['role']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                            Edit
                                        </button>
                                        <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                        <button onclick="deleteUser(<?php echo $user['id']; ?>)" class="text-red-600 hover:text-red-900">
                                            Hapus
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="categoryModalTitle">Tambah Kategori</h3>
            <form id="categoryForm">
                <input type="hidden" id="category_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" id="category_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('categoryModal')" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div id="productModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="productModalTitle">Tambah Produk</h3>
            <form id="productForm">
                <input type="hidden" id="product_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="product_category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Kategori</option>
                        <?php 
                        $categories->data_seek(0);
                        while ($category = $categories->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" id="product_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('productModal')" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Supplier Modal -->
<div id="supplierModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="supplierModalTitle">Tambah Supplier</h3>
            <form id="supplierForm">
                <input type="hidden" id="supplier_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama Supplier</label>
                    <input type="text" id="supplier_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea id="supplier_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input type="text" id="supplier_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('supplierModal')" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Buyer Modal -->
<div id="buyerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="buyerModalTitle">Tambah Pembeli</h3>
            <form id="buyerForm">
                <input type="hidden" id="buyer_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama Pembeli</label>
                    <input type="text" id="buyer_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea id="buyer_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input type="text" id="buyer_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('buyerModal')" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Modal -->
<div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="userModalTitle">Tambah User</h3>
            <form id="userForm">
                <input type="hidden" id="user_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" id="user_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="user_role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('userModal')" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Initialize by showing first tab
document.addEventListener('DOMContentLoaded', function() {
    showTab('categories');
});

// Tab functions
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-blue-500', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to selected tab button
    event.currentTarget.classList.remove('border-transparent', 'text-gray-500');
    event.currentTarget.classList.add('border-blue-500', 'text-blue-600');
}

// Modal functions
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Category functions
function showCategoryModal(category = null) {
    const modalTitle = document.getElementById('categoryModalTitle');
    const form = document.getElementById('categoryForm');
    
    if (category) {
        modalTitle.textContent = 'Edit Kategori';
        document.getElementById('category_id').value = category.id;
        document.getElementById('category_name').value = category.name;
    } else {
        modalTitle.textContent = 'Tambah Kategori';
        form.reset();
        document.getElementById('category_id').value = '';
    }
    
    document.getElementById('categoryModal').classList.remove('hidden');
}

document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append('name', document.getElementById('category_name').value);
    
    const categoryId = document.getElementById('category_id').value;
    formData.append('action', categoryId ? 'update' : 'create');
    if (categoryId) formData.append('id', categoryId);

    fetch('api/settings.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
});

// Buyer functions
function showBuyerModal(buyer = null) {
    const modalTitle = document.getElementById('buyerModalTitle');
    const form = document.getElementById('buyerForm');
    
    if (buyer) {
        modalTitle.textContent = 'Edit Pembeli';
        document.getElementById('buyer_id').value = buyer.id;
        document.getElementById('buyer_name').value = buyer.name;
        document.getElementById('buyer_address').value = buyer.address;
        document.getElementById('buyer_phone').value = buyer.phone;
    } else {
        modalTitle.textContent = 'Tambah Pembeli';
        form.reset();
        document.getElementById('buyer_id').value = '';
    }
    
    document.getElementById('buyerModal').classList.remove('hidden');
}

document.getElementById('buyerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append('name', document.getElementById('buyer_name').value);
    formData.append('address', document.getElementById('buyer_address').value);
    formData.append('phone', document.getElementById('buyer_phone').value);
    
    const buyerId = document.getElementById('buyer_id').value;
    formData.append('action', buyerId ? 'update_buyer' : 'create_buyer');
    if (buyerId) formData.append('id', buyerId);

    fetch('api/settings.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
});

function deleteBuyer(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pembeli ini?')) {
        fetch('api/settings.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete_buyer&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

// Product functions
function showProductModal(product = null) {
    const modalTitle = document.getElementById('productModalTitle');
    const form = document.getElementById('productForm');
    
    if (product) {
        modalTitle.textContent = 'Edit Produk';
        document.getElementById('product_id').value = product.id;
        document.getElementById('product_category_id').value = product.category_id;
        document.getElementById('product_name').value = product.name;
    } else {
        modalTitle.textContent = 'Tambah Produk';
        form.reset();
        document.getElementById('product_id').value = '';
    }
    
    document.getElementById('productModal').classList.remove('hidden');
}

document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append('name', document.getElementById('product_name').value);
    formData.append('category_id', document.getElementById('product_category_id').value);
    
    const productId = document.getElementById('product_id').value;
    formData.append('action', productId ? 'update_product' : 'create_product');
    if (productId) formData.append('id', productId);

    fetch('api/settings.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
});

function deleteProduct(id) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        fetch('api/settings.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete_product&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

// Similar functions for Supplier and User...
// Add event listeners for forms
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips or other UI elements if needed
    const forms = ['categoryForm', 'productForm', 'supplierForm', 'buyerForm', 'userForm'];
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', handleSubmit);
        }
    });
});

function handleSubmit(e) {
    e.preventDefault();
    // Form submission logic here based on form ID
}

// Buyer functions

</script>