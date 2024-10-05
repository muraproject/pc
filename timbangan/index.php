<?php
// Tentukan base URL
$base_url = '/pc/timbangan';

// Definisikan halaman yang valid
$valid_pages = ['timbang', 'histori', 'setting', 'harga'];

// Ambil halaman dari parameter GET, default ke 'timbang' jika tidak ada
$page = isset($_GET['page']) && in_array($_GET['page'], $valid_pages) ? $_GET['page'] : 'timbang';

// Include koneksi database
require_once 'includes/db_connect.php';

// Fungsi untuk memuat halaman
function loadPage($page) {
    global $conn, $base_url;  // Tambahkan $conn dan $base_url sebagai global
    $file = "pages/{$page}.php";
    if (file_exists($file)) {
        include $file;
    } else {
        echo "Halaman tidak ditemukan.";
    }
}

// Fungsi untuk mendapatkan judul halaman
function getPageTitle($page) {
    $titles = [
        'timbang' => 'Timbang',
        'histori' => 'Histori',
        'setting' => 'Pengaturan',
        'harga' => 'Harga'
    ];
    return $titles[$page] ?? 'Aplikasi Timbangan';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle($page); ?> - Aplikasi Timbangan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/styles.css">
    <style>
        body {
            padding-top: 56px; /* Adjust this value based on your header height */
            padding-bottom: 70px; /* Adjust this value based on your navbar height */
        }
        .android-header {
            background-color: #33330f;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            height: 56px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .android-header h1 {
            font-size: 20px;
            margin: 0;
        }
        .content-wrapper {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header class="android-header">
        <h1><?php echo getPageTitle($page); ?></h1>
    </header>

    <div class="container content-wrapper">
        <main>
            <?php loadPage($page); ?>
        </main>
    </div>

    <?php include 'includes/navbar.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>