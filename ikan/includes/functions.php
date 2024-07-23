<?php
// Fungsi untuk membersihkan input
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk memeriksa apakah user sudah login
function is_logged_in() {
    return isset($_SESSION['admin_id']);
}

// Fungsi untuk mengalihkan ke halaman login jika belum login
function require_login() {
    if (!is_logged_in()) {
        header("Location: index.php");
        exit();
    }
}

// Fungsi untuk menampilkan pesan error
function display_error($message) {
    return "<div class='alert alert-danger' role='alert'>$message</div>";
}

// Fungsi untuk menampilkan pesan sukses
function display_success($message) {
    return "<div class='alert alert-success' role='alert'>$message</div>";
}

// Fungsi untuk mengenkripsi password
function encrypt_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Fungsi untuk memverifikasi password
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Fungsi untuk menghasilkan token CSRF
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Fungsi untuk memverifikasi token CSRF
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        die("CSRF token verification failed");
    }
}

// Fungsi untuk memformat tanggal
function format_date($date, $format = 'd-m-Y H:i:s') {
    return date($format, strtotime($date));
}

// Fungsi untuk membatasi panjang teks
function truncate_text($text, $length = 100, $append = '...') {
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length) . $append;
    }
    return $text;
}

// Fungsi untuk mengecek apakah request adalah AJAX
function is_ajax_request() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

// Fungsi untuk redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Fungsi untuk menghasilkan pagination
function generate_pagination($total_pages, $current_page, $url) {
    $pagination = '<nav aria-label="Page navigation"><ul class="pagination">';
    
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $current_page) ? 'active' : '';
        $pagination .= "<li class='page-item $active'><a class='page-link' href='$url?page=$i'>$i</a></li>";
    }
    
    $pagination .= '</ul></nav>';
    return $pagination;
}

// Fungsi untuk logging
function log_action($action, $details = '') {
    $log_file = '../logs/admin_actions.log';
    $timestamp = date('Y-m-d H:i:s');
    $user = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Unknown';
    $log_entry = "$timestamp | $user | $action | $details\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}