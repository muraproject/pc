<?php
// Get today's date
$today = date('Y-m-d');
$firstDayMonth = date('Y-m-01');
$lastDayMonth = date('Y-m-t');

// Function to safely execute queries
function executeQuery($conn, $query, $params = []) {
    try {
        $stmt = $conn->prepare($query);
        if ($params) {
            $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        }
        $stmt->execute();
        return $stmt->get_result();
    } catch (Exception $e) {
        error_log("Query Error: " . $e->getMessage());
        return false;
    }
}

// Get today's summary
$today_query = "
    SELECT 
        (SELECT COUNT(*) FROM weighing_in WHERE DATE(created_at) = ?) as total_in_count,
        (SELECT COUNT(*) FROM weighing_out WHERE DATE(created_at) = ?) as total_out_count,
        (SELECT COALESCE(SUM(weight), 0) FROM weighing_in WHERE DATE(created_at) = ?) as total_weight_in,
        (SELECT COALESCE(SUM(weight), 0) FROM weighing_out WHERE DATE(created_at) = ?) as total_weight_out,
        (SELECT COALESCE(SUM(weight * price), 0) FROM weighing_out WHERE DATE(created_at) = ?) as total_amount_out
";

$today_result = executeQuery($conn, $today_query, [$today, $today, $today, $today, $today]);
$today_stats = $today_result ? $today_result->fetch_assoc() : [
    'total_in_count' => 0,
    'total_out_count' => 0,
    'total_weight_in' => 0,
    'total_weight_out' => 0,
    'total_amount_out' => 0
];

// Get per-category summary for current month
$category_query = "
    SELECT 
        c.name as category_name,
        COALESCE(SUM(wi.weight), 0) as weight_in,
        COALESCE(SUM(wo.weight), 0) as weight_out,
        COALESCE(SUM(wo.weight * wo.price), 0) as amount_out
    FROM categories c
    LEFT JOIN products p ON c.id = p.category_id
    LEFT JOIN weighing_in wi ON p.id = wi.product_id 
        AND DATE(wi.created_at) BETWEEN ? AND ?
    LEFT JOIN weighing_out wo ON p.id = wo.product_id 
        AND DATE(wo.created_at) BETWEEN ? AND ?
    GROUP BY c.id, c.name
    ORDER BY c.name
";

$category_result = executeQuery($conn, $category_query, [
    $firstDayMonth, $lastDayMonth,
    $firstDayMonth, $lastDayMonth
]);
$category_stats = $category_result ? $category_result->fetch_all(MYSQLI_ASSOC) : [];

// Get latest transactions
$latest_query = "
    (
        SELECT 
            'IN' as type,
            wi.receipt_id,
            wi.created_at,
            s.name as name,
            SUM(wi.weight) as total_weight,
            0 as total_amount
        FROM weighing_in wi
        LEFT JOIN suppliers s ON wi.supplier_id = s.id
        GROUP BY wi.receipt_id
        ORDER BY wi.created_at DESC
        LIMIT 5
    )
    UNION ALL
    (
        SELECT 
            'OUT' as type,
            wo.receipt_id,
            wo.created_at,
            u.name as name,
            SUM(wo.weight) as total_weight,
            SUM(wo.weight * wo.price) as total_amount
        FROM weighing_out wo
        LEFT JOIN users u ON wo.user_id = u.id
        GROUP BY wo.receipt_id
        ORDER BY wo.created_at DESC
        LIMIT 5
    )
    ORDER BY created_at DESC
    LIMIT 10
";

$latest_result = executeQuery($conn, $latest_query);
$latest_transactions = $latest_result ? $latest_result->fetch_all(MYSQLI_ASSOC) : [];
?>

<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total Transaksi Masuk -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Transaksi Masuk Hari Ini</p>
                    <p class="text-lg font-semibold"><?php echo number_format($today_stats['total_in_count']); ?></p>
                </div>
            </div>
        </div>

        <!-- Total Transaksi Keluar -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Transaksi Keluar Hari Ini</p>
                    <p class="text-lg font-semibold"><?php echo number_format($today_stats['total_out_count']); ?></p>
                </div>
            </div>
        </div>

        <!-- Total Berat Masuk -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2M6 7l-3-1m3 1l3 9a5.002 5.002 0 006.001 0M18 7l-3-1m3 1l3 9a5.002 5.002 0 006.001 0"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Berat Masuk</p>
                    <p class="text-lg font-semibold"><?php echo number_format($today_stats['total_weight_in'], 2); ?> kg</p>
                </div>
            </div>
        </div>

        <!-- Total Berat Keluar -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2M6 7l-3-1m3 1l3 9a5.002 5.002 0 006.001 0M18 7l-3-1m3 1l3 9a5.002 5.002 0 006.001 0"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Berat Keluar</p>
                    <p class="text-lg font-semibold"><?php echo number_format($today_stats['total_weight_out'], 2); ?> kg</p>
                </div>
            </div>
        </div>

        <!-- Total Nominal -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Nominal Keluar</p>
                    <p class="text-lg font-semibold">Rp <?php echo number_format($today_stats['total_amount_out']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Statistics -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik per Kategori (Bulan Ini)</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Masuk (kg)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Keluar (kg)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Stok (kg)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($category_stats as $stat): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($stat['category_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo number_format($stat['weight_in'], 2); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo number_format($stat['weight_out'], 2); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo number_format($stat['weight_in'] - $stat['weight_out'], 2); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Rp <?php echo number_format($stat['amount_out']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Latest Transactions -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Transaksi Terakhir</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Kwitansi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Berat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($latest_transactions as $trans): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('d/m/Y H:i', strtotime($trans['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($trans['receipt_id']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($trans['type'] === 'IN'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Masuk
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Keluar
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($trans['name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo number_format($trans['total_weight'], 2); ?> kg
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php if ($trans['type'] === 'OUT'): ?>
                                        Rp <?php echo number_format($trans['total_amount']); ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" style="display:none;">
        <!-- Weight Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Grafik Berat (7 Hari Terakhir)</h2>
            <canvas id="weightChart" class="w-full h-64"></canvas>
        </div>

        <!-- Amount Chart -->
        <div class="bg-white rounded-lg shadow p-6" style="display:none;">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Grafik Nominal (7 Hari Terakhir)</h2>
            <canvas id="amountChart" class="w-full h-64"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Get chart data
<?php
// Prepare data for last 7 days
$dates = [];
$weights_in = [];
$weights_out = [];
$amounts = [];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates[] = date('d/m', strtotime($date));
    
    // Get weights for the day
    $weight_query = "
        SELECT 
            (SELECT COALESCE(SUM(weight), 0) FROM weighing_in WHERE DATE(created_at) = ?) as weight_in,
            (SELECT COALESCE(SUM(weight), 0) FROM weighing_out WHERE DATE(created_at) = ?) as weight_out,
            (SELECT COALESCE(SUM(weight * price), 0) FROM weighing_out WHERE DATE(created_at) = ?) as amount
    ";
    
    $weight_result = executeQuery($conn, $weight_query, [$date, $date, $date]);
    $weight_data = $weight_result ? $weight_result->fetch_assoc() : [
        'weight_in' => 0,
        'weight_out' => 0,
        'amount' => 0
    ];
    
    $weights_in[] = $weight_data['weight_in'];
    $weights_out[] = $weight_data['weight_out'];
    $amounts[] = $weight_data['amount'];
}
?>

// Initialize Weight Chart
const weightCtx = document.getElementById('weightChart').getContext('2d');
new Chart(weightCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($dates); ?>,
        datasets: [
            {
                label: 'Berat Masuk',
                data: <?php echo json_encode($weights_in); ?>,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.1,
                fill: true
            },
            {
                label: 'Berat Keluar',
                data: <?php echo json_encode($weights_out); ?>,
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.1,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Berat (kg)'
                }
            }
        }
    }
});

// Initialize Amount Chart
const amountCtx = document.getElementById('amountChart').getContext('2d');
new Chart(amountCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($dates); ?>,
        datasets: [{
            label: 'Total Nominal',
            data: <?php echo json_encode($amounts); ?>,
            backgroundColor: 'rgb(124, 58, 237)',
            borderColor: 'rgb(109, 40, 217)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Nominal (Rp)'
                }
            }
        }
    }
});
</script>