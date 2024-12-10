<?php
require_once 'config.php';

// Security functions
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function generateHash($password) {
    return password_hash($password, HASH_ALGORITHM, ['cost' => HASH_COST]);
}

function verifyHash($password, $hash) {
    return password_verify($password, $hash);
}

function generateToken() {
    return bin2hex(random_bytes(32));
}

// Session functions
function startSecureSession() {
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }
    
    session_name(SESSION_NAME);
    session_start();
}

function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . APP_URL . '/login.php');
        exit;
    }
}

function checkAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: ' . APP_URL . '/403.php');
        exit;
    }
}

// Formatting functions
function formatNumber($number, $decimals = DECIMAL_PLACES) {
    return number_format(
        $number, 
        $decimals, 
        DECIMAL_SEPARATOR, 
        THOUSAND_SEPARATOR
    );
}

function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, DECIMAL_SEPARATOR, THOUSAND_SEPARATOR);
}

function formatWeight($weight) {
    return number_format($weight, DECIMAL_PLACES, DECIMAL_SEPARATOR, THOUSAND_SEPARATOR) . ' kg';
}

function formatDate($date) {
    return date('d/m/Y H:i', strtotime($date));
}

// Receipt functions
function generateReceiptNumber($type = 'IN') {
    $prefix = $type === 'IN' ? RECEIPT_PREFIX_IN : RECEIPT_PREFIX_OUT;
    $date = date('Ymd');
    $random = str_pad(rand(0, 999999), RECEIPT_NUMBER_LENGTH, '0', STR_PAD_LEFT);
    return $prefix . $date . $random;
}

// Validation functions
function validateNumber($number) {
    return is_numeric($number) && $number >= 0;
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// Logging functions
function logError($message, $context = []) {
    error_log(
        sprintf(
            "[%s] %s %s\n", 
            date('Y-m-d H:i:s'),
            $message,
            json_encode($context)
        ),
        3,
        ROOT_PATH . '/logs/error.log'
    );
}

function logActivity($user_id, $action, $details = []) {
    global $conn;
    $stmt = $conn->prepare("
        INSERT INTO activity_logs (user_id, action, details, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $details_json = json_encode($details);
    $stmt->bind_param('iss', $user_id, $action, $details_json);
    $stmt->execute();
}

// File handling functions
function uploadFile($file, $destination, $allowed_types = ['jpg', 'jpeg', 'png', 'pdf']) {
    try {
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($file_extension, $allowed_types)) {
            throw new Exception('File type not allowed');
        }
        
        $file_name = uniqid() . '.' . $file_extension;
        $file_path = $destination . '/' . $file_name;
        
        if (!move_uploaded_file($file['tmp_name'], $file_path)) {
            throw new Exception('Failed to move uploaded file');
        }
        
        return $file_name;
    } catch (Exception $e) {
        logError('File upload failed', [
            'error' => $e->getMessage(),
            'file' => $file['name']
        ]);
        return false;
    }
}

// Database utility functions
function getLastPrice($product_id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT price 
        FROM product_prices 
        WHERE product_id = ? 
        ORDER BY created_at DESC 
        LIMIT 1
    ");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return $row['price'];
    }
    return 0;
}

function getProductStock($product_id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT 
            COALESCE(SUM(wi.weight), 0) as total_in,
            COALESCE(SUM(wo.weight), 0) as total_out
        FROM products p
        LEFT JOIN weighing_in wi ON p.id = wi.product_id
        LEFT JOIN weighing_out wo ON p.id = wo.product_id
        WHERE p.id = ?
    ");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    return $result['total_in'] - $result['total_out'];
}

// Response functions
function jsonResponse($success = true, $message = '', $data = []) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}
?>