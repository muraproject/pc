 
<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'aplikasi_timbangan');

// Application settings
define('APP_NAME', 'Aplikasi Timbangan');
define('APP_VERSION', '1.0.0');
define('BASE_URL', '/timbangan_rekap');

// Session timeout in seconds (2 hours)
define('SESSION_TIMEOUT', 7200);

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Upload settings
define('UPLOAD_PATH', __DIR__ . '/../uploads');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf']);