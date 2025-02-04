<?php
require_once 'config.php';

class HomeAutomation {
   private $db;
   
   public function __construct($db) {
       $this->db = $db;
   }

   // Get semua data monitoring
   public function getMonitoringData() {
       try {
           $query = "SELECT 
                       mp.id,
                       mp.name,
                       mp.type,
                       mp.unit,
                       ml.value,
                       ml.status,
                       ml.timestamp 
                    FROM monitoring_points mp 
                    LEFT JOIN (
                        SELECT ml1.*
                        FROM monitoring_logs ml1
                        INNER JOIN (
                            SELECT point_id, MAX(timestamp) as max_time
                            FROM monitoring_logs
                            GROUP BY point_id
                        ) ml2 
                        ON ml1.point_id = ml2.point_id 
                        AND ml1.timestamp = ml2.max_time
                    ) ml ON mp.id = ml.point_id
                    ORDER BY mp.type DESC, mp.name ASC";
           
           return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
       } catch (PDOException $e) {
           error_log("Error getting monitoring data: " . $e->getMessage());
           return [];
       }
   }

   // Get data kontrol
   public function getControlData() {
       try {
           $query = "SELECT * FROM control_points ORDER BY type, name";
           return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
       } catch (PDOException $e) {
           error_log("Error getting control data: " . $e->getMessage());
           return [];
       }
   }

   // Tambah titik monitoring baru
   public function addMonitoringPoint($name, $type, $unit = null) {
       try {
           // Cek apakah nama sensor sudah ada
           $stmt = $this->db->prepare("SELECT COUNT(*) FROM monitoring_points WHERE name = ?");
           $stmt->execute([$name]);
           if ($stmt->fetchColumn() > 0) {
               return ['success' => false, 'message' => 'Sensor dengan nama tersebut sudah ada'];
           }

           $stmt = $this->db->prepare(
               "INSERT INTO monitoring_points (name, type, unit) VALUES (?, ?, ?)"
           );
           $result = $stmt->execute([$name, $type, $unit]);
           
           if ($result) {
               // Get the new sensor data
               $newId = $this->db->lastInsertId();
               $stmt = $this->db->prepare("SELECT * FROM monitoring_points WHERE id = ?");
               $stmt->execute([$newId]);
               $newSensor = $stmt->fetch(PDO::FETCH_ASSOC);
               
               // Insert initial value
               $stmt = $this->db->prepare(
                   "INSERT INTO monitoring_logs (point_id, value, status) VALUES (?, ?, ?)"
               );
               $stmt->execute([$newId, 0, 'Normal']);
               
               return ['success' => true, 'sensor' => $newSensor];
           }
           return ['success' => false];
           
       } catch (PDOException $e) {
           error_log("Error adding monitoring point: " . $e->getMessage());
           return ['success' => false, 'message' => $e->getMessage()];
       }
   }

   // Tambah kontrol baru
   public function addControl($name, $type) {
       try {
           // Cek apakah nama kontrol sudah ada
           $stmt = $this->db->prepare("SELECT COUNT(*) FROM control_points WHERE name = ?");
           $stmt->execute([$name]);
           if ($stmt->fetchColumn() > 0) {
               return ['success' => false, 'message' => 'Kontrol dengan nama tersebut sudah ada'];
           }

           // Set status default berdasarkan tipe
           $defaultStatus = ($type === 'light') ? 'Mati' : 'Terkunci';

           $stmt = $this->db->prepare(
               "INSERT INTO control_points (name, type, status) VALUES (?, ?, ?)"
           );
           $result = $stmt->execute([$name, $type, $defaultStatus]);
           
           if ($result) {
               $newId = $this->db->lastInsertId();
               $stmt = $this->db->prepare("SELECT * FROM control_points WHERE id = ?");
               $stmt->execute([$newId]);
               $newControl = $stmt->fetch(PDO::FETCH_ASSOC);
               return ['success' => true, 'control' => $newControl];
           }
           return ['success' => false];
           
       } catch (PDOException $e) {
           error_log("Error adding control point: " . $e->getMessage());
           return ['success' => false, 'message' => $e->getMessage()];
       }
   }

   // Toggle status device
   public function toggleDevice($device_name, $type) {
       try {
           switch ($type) {
               case 'light':
                   $query = "UPDATE control_points 
                            SET status = CASE 
                                WHEN status = 'Mati' THEN 'Nyala' 
                                ELSE 'Mati' 
                            END 
                            WHERE name = ? AND type = ?";
                   break;
                   
               case 'door':
                   $query = "UPDATE control_points 
                            SET status = CASE 
                                WHEN status = 'Terkunci' THEN 'Terbuka' 
                                ELSE 'Terkunci' 
                            END 
                            WHERE name = ? AND type = ?";
                   break;
                   
               default:
                   return ['success' => false, 'message' => 'Invalid device type'];
           }

           $stmt = $this->db->prepare($query);
           $result = $stmt->execute([$device_name, $type]);
           
           if ($result) {
               // Get updated status
               $stmt = $this->db->prepare("SELECT status FROM control_points WHERE name = ? AND type = ?");
               $stmt->execute([$device_name, $type]);
               $newStatus = $stmt->fetch(PDO::FETCH_ASSOC);
               return ['success' => true, 'status' => $newStatus['status']];
           }
           return ['success' => false, 'message' => 'Failed to update status'];
           
       } catch (PDOException $e) {
           error_log("Error toggling device: " . $e->getMessage());
           return ['success' => false, 'message' => $e->getMessage()];
       }
   }

   // Update nilai sensor
   public function updateSensorValue($sensor_name, $value) {
       try {
           // Get sensor id
           $stmt = $this->db->prepare("SELECT id FROM monitoring_points WHERE name = ?");
           $stmt->execute([$sensor_name]);
           $sensor = $stmt->fetch(PDO::FETCH_ASSOC);
           
           if (!$sensor) {
               return ['success' => false, 'message' => 'Sensor tidak ditemukan'];
           }

           // Tentukan status
           $status = $this->calculateSensorStatus($sensor_name, $value);

           // Insert log baru
           $stmt = $this->db->prepare(
               "INSERT INTO monitoring_logs (point_id, value, status) VALUES (?, ?, ?)"
           );
           $result = $stmt->execute([$sensor['id'], $value, $status]);
           
           return ['success' => $result];
           
       } catch (PDOException $e) {
           error_log("Error updating sensor value: " . $e->getMessage());
           return ['success' => false, 'message' => $e->getMessage()];
       }
   }

   // Hitung status sensor berdasarkan nilai
   private function calculateSensorStatus($sensor_name, $value) {
       switch ($sensor_name) {
           case 'Suhu Ruangan':
               if ($value > 30) return 'Tinggi';
               if ($value < 20) return 'Rendah';
               return 'Normal';

           case 'Kelembaban Udara':
           case 'Kelembaban Tanah':
               if ($value > 80) return 'Tinggi';
               if ($value < 30) return 'Rendah';
               return 'Normal';

           case 'Sensor Api':
               return $value > 0 ? 'Bahaya' : 'Aman';

           case 'Sensor Cahaya':
               if ($value > 1000) return 'Terang';
               if ($value < 100) return 'Gelap';
               return 'Normal';

           default:
               return 'Normal';
       }
   }

   // Delete monitoring point
   public function deleteMonitoringPoint($id) {
       try {
           $stmt = $this->db->prepare("DELETE FROM monitoring_logs WHERE point_id = ?");
           $stmt->execute([$id]);
           
           $stmt = $this->db->prepare("DELETE FROM monitoring_points WHERE id = ?");
           return ['success' => $stmt->execute([$id])];
       } catch (PDOException $e) {
           error_log("Error deleting monitoring point: " . $e->getMessage());
           return ['success' => false, 'message' => $e->getMessage()];
       }
   }

   // Delete control point
   public function deleteControlPoint($id) {
       try {
           $stmt = $this->db->prepare("DELETE FROM control_points WHERE id = ?");
           return ['success' => $stmt->execute([$id])];
       } catch (PDOException $e) {
           error_log("Error deleting control point: " . $e->getMessage());
           return ['success' => false, 'message' => $e->getMessage()];
       }
   }
}

// Initialize automation object
$automation = new HomeAutomation($db);

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
   $response = ['success' => false, 'message' => ''];
   
   try {
       switch ($_POST['action']) {
           case 'toggle':
               if (isset($_POST['device']) && isset($_POST['type'])) {
                   $response = $automation->toggleDevice($_POST['device'], $_POST['type']);
               } else {
                   $response['message'] = 'Missing device or type parameter';
               }
               break;

           case 'add_point':
               if (isset($_POST['name']) && isset($_POST['type'])) {
                   $response = $automation->addMonitoringPoint(
                       $_POST['name'],
                       $_POST['type'],
                       $_POST['unit'] ?? null
                   );
               } else {
                   $response['message'] = 'Missing required parameters';
               }
               break;

           case 'add_control':
               if (isset($_POST['name']) && isset($_POST['type'])) {
                   $response = $automation->addControl(
                       $_POST['name'],
                       $_POST['type']
                   );
               } else {
                   $response['message'] = 'Missing required parameters';
               }
               break;

           case 'update_sensor':
               if (isset($_POST['sensor']) && isset($_POST['value'])) {
                   $response = $automation->updateSensorValue($_POST['sensor'], $_POST['value']);
               } else {
                   $response['message'] = 'Missing sensor or value parameter';
               }
               break;

           case 'delete_point':
               if (isset($_POST['id'])) {
                   $response = $automation->deleteMonitoringPoint($_POST['id']);
               } else {
                   $response['message'] = 'Missing id parameter';
               }
               break;

           case 'delete_control':
               if (isset($_POST['id'])) {
                   $response = $automation->deleteControlPoint($_POST['id']);
               } else {
                   $response['message'] = 'Missing id parameter';
               }
               break;

           case 'get_sensor_data':
               $response = [
                   'success' => true,
                   'data' => $automation->getMonitoringData()
               ];
               break;

           default:
               $response['message'] = 'Invalid action';
       }
   } catch (Exception $e) {
       $response['message'] = $e->getMessage();
   }

   if (isset($_POST['ajax'])) {
       header('Content-Type: application/json');
       echo json_encode($response);
       exit;
   }
}
?>