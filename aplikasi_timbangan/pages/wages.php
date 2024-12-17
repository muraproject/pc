<?php
if ($_SESSION['role'] !== 'admin') {
    echo '<div class="p-4 bg-red-100 text-red-700 rounded">Anda tidak memiliki akses ke halaman ini</div>';
    exit;
}

// Get filter values
$start_date = $_GET['start_date'] ?? date('Y-m-01'); // Default to first day of current month
$end_date = $_GET['end_date'] ?? date('Y-m-t');     // Default to last day of current month
$user_id = $_GET['user_id'] ?? '';

// Base query for weighing records
$query = "
    SELECT 
        u.id as user_id,
        u.name as user_name,
        u.wage_per_kg,
        (SELECT SUM(weight) FROM weighing_in WHERE user_id = u.id AND DATE(created_at) BETWEEN ? AND ?) as total_weight_in,
        (SELECT SUM(weight) FROM weighing_out WHERE user_id = u.id AND DATE(created_at) BETWEEN ? AND ?) as total_weight_out,
        (SELECT COUNT(DISTINCT DATE(created_at)) FROM weighing_in WHERE user_id = u.id AND DATE(created_at) BETWEEN ? AND ?) +
        (SELECT COUNT(DISTINCT DATE(created_at)) FROM weighing_out WHERE user_id = u.id AND DATE(created_at) BETWEEN ? AND ?) as total_days
    FROM users u
    WHERE u.role = 'user'
";

// Add user filter if specified
if ($user_id) {
    $query .= " AND u.id = ?";
    $params = [$start_date, $end_date, $start_date, $end_date, $start_date, $end_date, $start_date, $end_date, $user_id];
    $types = "ssssssssi";
} else {
    $params = [$start_date, $end_date, $start_date, $end_date, $start_date, $end_date, $start_date, $end_date];
    $types = "ssssssss";
}

$query .= " ORDER BY u.name";

// Prepare and execute query
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Get users for filter
$users = $conn->query("SELECT id, name FROM users WHERE role = 'user' ORDER BY name");
?>

<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="hidden" name="page" value="wages">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" name="start_date" value="<?php echo $start_date; ?>" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                <input type="date" name="end_date" value="<?php echo $end_date; ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">User</label>
                <select name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua User</option>
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <option value="<?php echo $user['id']; ?>" <?php echo $user_id == $user['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($user['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="md:col-span-3 flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
        $total_wages = 0;
        while ($row = $result->fetch_assoc()):
            $total_weight = ($row['total_weight_in'] ?? 0) + ($row['total_weight_out'] ?? 0);
            $wages = $total_weight * $row['wage_per_kg'];
            $total_wages += $wages;
        ?>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($row['user_name']); ?></h3>
            <dl class="mt-5 space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Berat Masuk</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900"><?php echo number_format($row['total_weight_in'] ?? 0, 2); ?> kg</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Berat Keluar</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900"><?php echo number_format($row['total_weight_out'] ?? 0, 2); ?> kg</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Hari Kerja</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900"><?php echo $row['total_days']; ?> hari</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Upah per Kg</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">Rp <?php echo number_format($row['wage_per_kg'], 0); ?></dd>
                </div>
                <div class="pt-4 border-t border-gray-200">
                    <dt class="text-sm font-medium text-gray-500">Total Upah</dt>
                    <dd class="mt-1 text-3xl font-semibold text-green-600">Rp <?php echo number_format($wages, 0); ?></dd>
                </div>
            </dl>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Print Summary -->
    <div class="bg-white rounded-lg shadow p-6 print:block hidden">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Upah</h2>
        <p class="mb-2">Periode: <?php echo date('d/m/Y', strtotime($start_date)); ?> - <?php echo date('d/m/Y', strtotime($end_date)); ?></p>
        <p class="mb-4">Total Upah: Rp <?php echo number_format($total_wages, 0); ?></p>
    </div>
</div>

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detail Transaksi</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="detailsContent" class="mt-4">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function showDetails() {
    fetch(`api/wages.php?action=details&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&user_id=<?php echo $user_id; ?>`)
        .then(response => response.json())
        .then(data => {
            let content = `
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900">Transaksi Barang Masuk</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Berat (kg)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kwitansi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
            `;

            data.weighing_in.forEach(item => {
                content += `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.date}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.user_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${Number(item.weight).toFixed(2)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.receipt_id}</td>
                    </tr>
                `;
            });

            content += `
                        </tbody>
                    </table>
                </div>

                <h4 class="font-medium text-gray-900 mt-8">Transaksi Barang Keluar</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Berat (kg)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kwitansi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
            `;

            data.weighing_out.forEach(item => {
                content += `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.date}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.user_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${Number(item.weight).toFixed(2)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.receipt_id}</td>
                    </tr>
                `;
            });

            content += `
                        </tbody>
                    </table>
                </div>
            </div>
            `;

            document.getElementById('detailsContent').innerHTML = content;
            document.getElementById('detailsModal').classList.remove('hidden');
        });
}

function closeModal() {
    document.getElementById('detailsModal').classList.add('hidden');
}
</script>