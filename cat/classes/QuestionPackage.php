<?php
class QuestionPackage {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addPackage($name, $description) {
        $query = "INSERT INTO question_packages (name, description) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$name, $description]);
    }

    public function getAllPackages() {
        $query = "SELECT * FROM question_packages";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPackageById($id) {
        $query = "SELECT * FROM question_packages WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePackage($id, $name, $description) {
        $query = "UPDATE question_packages SET name = ?, description = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$name, $description, $id]);
    }

    public function deletePackage($id) {
        try {
            $this->conn->beginTransaction();

            // Hapus jawaban terkait
            $query = "DELETE ua FROM user_answers ua
                      JOIN questions q ON ua.question_id = q.id
                      WHERE q.package_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);

            // Hapus pertanyaan dalam paket
            $query = "DELETE FROM questions WHERE package_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);

            // Hapus paket
            $query = "DELETE FROM question_packages WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error deleting package: " . $e->getMessage());
            return false;
        }
    }

    public function getTotalPackages() {
        $query = "SELECT COUNT(*) as total FROM question_packages";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>