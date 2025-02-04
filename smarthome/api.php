<?php
require_once 'config.php';

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

function logDeviceChange($db, $device_name, $type, $old_status, $new_status) {
    try {
        $stmt = $db->prepare("INSERT INTO logs (device_name, type, old_status, new_status) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$device_name, $type, $old_status, $new_status]);
    } catch (PDOException $e) {
        error_log("Error logging change: " . $e->getMessage());
        return false;
    }
}

function updateDeviceStatus($db, $devices) {
    $response = ['success' => true, 'messages' => []];

    try {
        foreach ($devices as $device) {
            $name = $device['name'] ?? '';
            $value = $device['value'] ?? '';
            $type = $device['type'] ?? '';

            if (empty($name) || !isset($value)) {
                $response['messages'][] = "Missing name or value for a device";
                continue;
            }

            // Handle sensor updates (tanpa log, hanya update nilai terakhir)
            if ($type === 'sensor') {
                // Get sensor ID
                $stmt = $db->prepare("SELECT id FROM monitoring_points WHERE name = ?");
                $stmt->execute([$name]);
                $sensor = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($sensor) {
                    // Update atau insert nilai terakhir
                    $stmt = $db->prepare("
                        INSERT INTO monitoring_logs (point_id, value, status) 
                        VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE 
                        value = VALUES(value),
                        status = VALUES(status)
                    ");
                    if (!$stmt->execute([$sensor['id'], $value, 'Normal'])) {
                        $response['messages'][] = "Failed to update sensor: $name";
                    }
                }
            }
            // Handle control updates dengan logging
            else {
                // Get current status
                $stmt = $db->prepare("SELECT status FROM control_points WHERE name = ? AND type = ?");
                $stmt->execute([$name, $type]);
                $current = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($current && $current['status'] !== $value) {
                    // Update status
                    $stmt = $db->prepare("UPDATE control_points SET status = ? WHERE name = ? AND type = ?");
                    if ($stmt->execute([$value, $name, $type])) {
                        // Log perubahan hanya untuk kontrol
                        logDeviceChange($db, $name, $type, $current['status'], $value);
                        $response['messages'][] = "Updated and logged control: $name from {$current['status']} to $value";
                    } else {
                        $response['messages'][] = "Failed to update control: $name";
                    }
                }
            }
        }
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => "Database error: " . $e->getMessage()
        ];
    }

    return $response;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read raw POST data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid JSON data'
        ]);
        exit;
    }

    if (!isset($data['devices']) || !is_array($data['devices'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid data format'
        ]);
        exit;
    }

    $result = updateDeviceStatus($db, $data['devices']);
    echo json_encode($result);
    exit;
}

// Handle invalid request method
echo json_encode([
    'success' => false,
    'message' => 'Invalid request method'
]);