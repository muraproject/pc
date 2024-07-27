<?php

session_start();
require_once 'includes/config.php';
require_once 'includes/database.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();

// Fungsi untuk mengambil pesanan
function getOrders($status) {
    global $conn;
    $sql = "SELECT * FROM orders WHERE status = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

$pendingOrders = getOrders('pending');
$confirmedOrders = getOrders('confirmed');

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Pemesanan Galon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="assets/css/admin-style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-table="pending">
                                Pesanan Proses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-table="confirmed">
                                Pesanan Terkonfirmasi
                            </a>
                        </li>
                    </ul>
                </div>
                
            </nav>
            <!-- <li class="nav-item">
               <a class="nav-link" href="admin_logout.php">
                Logout
            </a>
             </li> -->

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard Admin</h1>
                    <a href="admin_logout.php" class="btn btn-info">
                    <span class="glyphicon glyphicon-log-out"></span> Log out
                    </a>
                </div>
                
                <div id="pendingTable">
                    <h2>Pesanan Proses</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>WhatsApp</th>
                                    <th>Galon Isi Ulang</th>
                                    <th>Galon Asli</th>
                                    <th>Total</th>
                                    <th>Longitude</th>
                                    <th>Latitude</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                    <th>Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingOrders as $order): ?>
                                <tr>
                                    <td><?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                                    <td><?php echo htmlspecialchars($order['whatsapp']); ?></td>
                                    <td><?php echo $order['refill_quantity']; ?></td>
                                    <td><?php echo $order['original_quantity']; ?></td>
                                    <td>Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></td>
                                    <td><?php echo $order['longitude']; ?></td>
                                    <td><?php echo $order['latitude']; ?></td>
                                    <td><?php echo $order['created_at']; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary confirm-btn" data-id="<?php echo $order['id']; ?>">Konfirmasi</button>
                                    </td>
                                    <td>
                                        <!-- <button class="btn btn-sm btn-primary confirm-btn" data-id="<?php echo $order['id']; ?>">Konfirmasi</button> -->
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $order['id']; ?>" data-type="pending">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="confirmedTable" style="display: none;">
                    <h2>Pesanan Terkonfirmasi</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>WhatsApp</th>
                                    <th>Longitude</th>
                                    <th>Latitude</th>
                                    <th>Galon Isi Ulang</th>
                                    <th>Galon Asli</th>
                                    <th>Total</th>
                                    <th>Tanggal Konfirmasi</th>
                                    <th>Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($confirmedOrders as $order): ?>
                                <tr>
                                    <td><?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                                    <td><?php echo htmlspecialchars($order['whatsapp']); ?></td>
                                    <td><?php echo $order['longitude']; ?></td>
                                    <td><?php echo $order['latitude']; ?></td>
                                    <td><?php echo $order['refill_quantity']; ?></td>
                                    <td><?php echo $order['original_quantity']; ?></td>
                                    <td>Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></td>
                                    <td><?php echo $order['created_at']; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $order['id']; ?>" data-type="confirmed">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="map" style="height: 400px;"></div>
            </main>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    var pendingOrders = <?php echo json_encode($pendingOrders); ?>;
    var confirmedOrders = <?php echo json_encode($confirmedOrders); ?>;
    </script>
    <script src="assets/js/admin-script.js"></script>
    
</body>
</html>