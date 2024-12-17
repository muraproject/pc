<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_timbangan');

// Application configuration
define('APP_NAME', 'Aplikasi Timbangan');
define('APP_VERSION', '1.0.0');
define('APP_URL', '/pc/timbangan');

// Session configuration
define('SESSION_NAME', 'TIMBANGAN_SESSION');
define('SESSION_LIFETIME', 86400); // 24 hours

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Formatting
define('DECIMAL_PLACES', 2);
define('THOUSAND_SEPARATOR', ',');
define('DECIMAL_SEPARATOR', '.');

// File paths
define('ROOT_PATH', dirname(__DIR__));
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// Security
define('HASH_ALGORITHM', PASSWORD_DEFAULT);
define('HASH_COST', 10);

// Pagination
define('ITEMS_PER_PAGE', 10);

// Receipt settings
define('RECEIPT_PREFIX_IN', 'IN');
define('RECEIPT_PREFIX_OUT', 'OUT');
define('RECEIPT_NUMBER_LENGTH', 6);

// Scale settings
define('SCALE_BAUD_RATE', 9600);
define('SCALE_DATA_BITS', 8);
define('SCALE_PARITY', 'none');
define('SCALE_STOP_BITS', 1);

// Access levels
define('ACCESS_LEVELS', [
    'admin' => [
        'settings',
        'users',
        'reports',
        'categories',
        'products',
        'suppliers'
    ],
    'user' => [
        'weighing_in',
        'weighing_out',
        'history_in',
        'history_out'
    ]
]);
?>