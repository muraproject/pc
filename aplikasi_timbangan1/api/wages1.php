<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if user is admin
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Permission denied']);
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'details') {
    getTransactionDetails();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getTransactionDetails() {
    global $conn;
    
    $start_date = $_GET['start_date'] ?? date('Y-m-01');
    $end_date = $_GET['end_date'] ?? date('Y-m-t');
    $user_id = $_GET['user_id'] ?? '';

    // Prepare base queries
    $query_in = "
        SELECT 
            wi.id,
            wi.receipt_id,
            wi.created_at as date,
            wi.weight,
            u.name as user_name
        FROM weighing_in wi
        LEFT JOIN users u ON wi.user_id = u.id
        WHERE DATE(wi.created_at) BETWEEN ? AND ?
    ";

    $query_out = "
        SELECT 
            wo.id,
            wo.receipt_id,
            wo.created_at as date,
            wo.weight,
            u.name as user_name
        FROM weighing_out wo
        LEFT JOIN users u ON wo.user_id = u.id
        WHERE DATE(wo.created_at) BETWEEN ? AND ?
    ";

    if ($user_id) {
        $query_in .= " AND wi.user_id = ?";
        $query_out .= " AND wo.user_id = ?";
    }

    $query_in .= " ORDER BY wi.created_at DESC";
    $query_out .= " ORDER BY wo.created_at DESC";

    // Get weighing in records
    $stmt_in = $conn->prepare($query_in);
    if ($user_id) {
        $stmt_in->bind_param("sss", $start_date, $end_date, $user_id);
    } else {
        $stmt_in->bind_param("ss", $start_date, $end_date);
    }
    $stmt_in->execute();
    $weighing_in = $stmt_in->get_result()->fetch_all(MYSQLI_ASSOC);

    // Get weighing out records
    $stmt_out = $conn->prepare($query_out);
    if ($user_id) {
        $stmt_out->bind_param("sss", $start_date, $end_date, $user_id);
    } else {
        $stmt_out->bind_param("ss", $start_date, $end_date);
    }
    $stmt_out->execute();
    $weighing_out = $stmt_out->get_result()->fetch_all(MYSQLI_ASSOC);

    // Format dates
    $weighing_in = array_map(function($item) {
        $item['date'] = date('d/m/Y H:i', strtotime($item['date']));
        return $item;
    }, $weighing_in);

    $weighing_out = array_map(function($item) {
        $item['date'] = date('d/m/Y H:i', strtotime($item['date']));
        return $item;
    }, $weighing_out);

    echo json_encode([
        'success' => true,
        'weighing_in' => $weighing_in,
        'weighing_out' => $weighing_out
    ]);
}