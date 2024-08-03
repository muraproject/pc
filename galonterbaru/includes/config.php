<?php
// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'galon_order_db');

// Konfigurasi RSA
define('RSA_P', 17);
define('RSA_Q', 11);
define('RSA_E', 7);

// Kunci publik RSA (e, n)
define('RSA_PUBLIC_KEY', [RSA_E, RSA_P * RSA_Q]);

// Hitung kunci privat RSA (d)
function calculate_d($e, $phi) {
    $d = 1;
    while ((($d * $e) % $phi) != 1) {
        $d++;
    }
    return $d;
}

$phi = (RSA_P - 1) * (RSA_Q - 1);
define('RSA_PRIVATE_KEY', [calculate_d(RSA_E, $phi), RSA_P * RSA_Q]);