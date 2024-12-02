 
<?php
require_once 'config.php';

// Security Functions
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateNumber($number, $min = 0) {
    return is_numeric($number) && $number >= $min;
}

function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function generateToken() {
    return bin2hex(random_bytes(32));
}

// Session Management
function checkSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/login.html');
        exit;
    }

    if (isset($_SESSION['last_activity']) && 
        (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/login.html?timeout=1');
        exit;
    }

    $_SESSION['last_activity'] = time();
}

function checkAdmin() {
    checkSession();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: ' . BASE_URL . '/index.php?error=unauthorized');
        exit;
    }
}

// Stock Functions
function calculateStock($produk_id) {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $sql = "SELECT 
                COALESCE(SUM(bm.berat), 0) as total_masuk,
                COALESCE(SUM(bk.berat), 0) as total_keluar
            FROM tr_produk p
            LEFT JOIN tr_barang_masuk bm ON p.id = bm.produk_id
            LEFT JOIN tr_barang_keluar bk ON p.id = bk.produk_id
            WHERE p.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $produk_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    return $data['total_masuk'] - $data['total_keluar'];
}

function checkStockAvailability($produk_id, $berat) {
    $current_stock = calculateStock($produk_id);
    return $current_stock >= $berat;
}

// Format Functions
function formatDate($date, $format = 'Y-m-d H:i:s') {
    return date($format, strtotime($date));
}

function formatNumber($number, $decimals = 2) {
    return number_format($number, $decimals, ',', '.');
}

function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// File Handling
function uploadFile($file, $destination) {
    try {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new Exception('Invalid file parameter');
        }

        if ($file['size'] > MAX_FILE_SIZE) {
            throw new Exception('File too large');
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ALLOWED_EXTENSIONS)) {
            throw new Exception('Invalid file extension');
        }

        $filename = uniqid() . '.' . $extension;
        $filepath = UPLOAD_PATH . '/' . $destination . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new Exception('Failed to move uploaded file');
        }

        return $filename;
    } catch (Exception $e) {
        error_log("File Upload Error: " . $e->getMessage());
        throw $e;
    }
}

// Response Helper
function jsonResponse($success, $data = null, $message = '') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'message' => $message
    ]);
    exit;
}

// Logging Function
function logActivity($user_id, $action, $details = '') {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $sql = "INSERT INTO activity_log (user_id, action, details, ip_address) 
            VALUES (?, ?, ?, ?)";
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $action, $details, $ip);
    $stmt->execute();
}