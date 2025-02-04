<?php
require_once 'config.php';

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Prepare response
$response = [
    'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 1,
    'data' => [],
    'recordsTotal' => 0,
    'recordsFiltered' => 0
];

try {
    // Base query untuk total records
    $stmt = $db->query("SELECT COUNT(*) FROM logs");
    $response['recordsTotal'] = $stmt->fetchColumn();

    // Base query untuk data
    $query = "SELECT * FROM logs WHERE 1=1";
    $whereParams = [];

    // Apply filters
    if (!empty($_POST['type'])) {
        $query .= " AND type = ?";
        $whereParams[] = $_POST['type'];
    }

    if (!empty($_POST['dateStart'])) {
        $query .= " AND DATE(timestamp) >= ?";
        $whereParams[] = $_POST['dateStart'];
    }

    if (!empty($_POST['dateEnd'])) {
        $query .= " AND DATE(timestamp) <= ?";
        $whereParams[] = $_POST['dateEnd'];
    }

    // Search
    if (!empty($_POST['search']['value'])) {
        $searchValue = '%' . $_POST['search']['value'] . '%';
        $query .= " AND (device_name LIKE ? OR type LIKE ? OR old_status LIKE ? OR new_status LIKE ?)";
        $whereParams[] = $searchValue;
        $whereParams[] = $searchValue;
        $whereParams[] = $searchValue;
        $whereParams[] = $searchValue;
    }

    // Get filtered count
    $countQuery = "SELECT COUNT(*) FROM logs WHERE 1=1";
    if (!empty($whereParams)) {
        $countQuery .= substr($query, strpos($query, "AND"));
    }
    $stmt = $db->prepare($countQuery);
    $stmt->execute($whereParams);
    $response['recordsFiltered'] = $stmt->fetchColumn();

    // Ordering
    if (isset($_POST['order'])) {
        $columns = ['timestamp', 'device_name', 'type', 'old_status', 'new_status'];
        $query .= " ORDER BY " . $columns[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'];
    } else {
        $query .= " ORDER BY timestamp DESC";
    }

    // Pagination
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
    $query .= " LIMIT " . $start . ", " . $length;

    // Execute final query
    $stmt = $db->prepare($query);
    $stmt->execute($whereParams);
    $response['data'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug info
    error_log("Query: " . $query);
    error_log("Params: " . print_r($whereParams, true));
    error_log("Response: " . print_r($response, true));

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $response['error'] = $e->getMessage();
}

echo json_encode($response);