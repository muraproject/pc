<?php
require_once 'config.php';

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }

            // Set charset to utf8mb4
            $this->connection->set_charset("utf8mb4");

            // Set timezone
            $this->connection->query("SET time_zone = '+07:00'");
        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function prepare($query) {
        return $this->connection->prepare($query);
    }

    public function query($query) {
        return $this->connection->query($query);
    }

    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }

    public function getLastInsertId() {
        return $this->connection->insert_id;
    }

    public function beginTransaction() {
        $this->connection->begin_transaction();
    }

    public function commit() {
        $this->connection->commit();
    }

    public function rollback() {
        $this->connection->rollback();
    }

    public function close() {
        $this->connection->close();
    }

    public function getError() {
        return $this->connection->error;
    }
}

// Initialize database connection
$db = Database::getInstance();
$conn = $db->getConnection();

// Function to check database connection
function checkDatabaseConnection() {
    global $conn;
    try {
        if ($conn->ping()) {
            return true;
        }
        return false;
    } catch (Exception $e) {
        error_log("Database ping error: " . $e->getMessage());
        return false;
    }
}

// Function to safely close database connection
function closeDatabaseConnection() {
    global $db;
    try {
        $db->close();
    } catch (Exception $e) {
        error_log("Error closing database connection: " . $e->getMessage());
    }
}

// Register shutdown function to close database connection
register_shutdown_function('closeDatabaseConnection');
?>