<?php
// Tentukan path root
define('ROOT_PATH', __DIR__);

require_once ROOT_PATH . '/includes/config.php';
require_once ROOT_PATH . '/includes/functions.php';

// Definisikan halaman yang valid
$valid_pages = ['timbang', 'histori', 'setting', 'harga'];

// Ambil halaman dari parameter GET, default ke 'timbang' jika tidak ada
$page = isset($_GET['page']) && in_array($_GET['page'], $valid_pages) ? $_GET['page'] : 'timbang';

// Fungsi untuk memuat halaman
function loadPage($page) {
    $file = ROOT_PATH . "/pages/{$page}.php";
    if (file_exists($file)) {
        include $file;
    } else {
        echo "Halaman tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($page); ?> - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header>
        <h1><?php echo APP_NAME; ?></h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php?page=timbang" <?php if($page == 'timbang') echo 'class="active"'; ?>>Timbang</a></li>
            <li><a href="index.php?page=histori" <?php if($page == 'histori') echo 'class="active"'; ?>>Histori</a></li>
            <li><a href="index.php?page=setting" <?php if($page == 'setting') echo 'class="active"'; ?>>Setting</a></li>
            <li><a href="index.php?page=harga" <?php if($page == 'harga') echo 'class="active"'; ?>>Harga</a></li>
        </ul>
    </nav>

    <main>
        <?php loadPage($page); ?>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?></p>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>