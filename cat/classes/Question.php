<?php
class Question {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function updatePackage($id, $name, $description) {
        $query = "UPDATE question_packages SET name = ?, description = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$name, $description, $id]);
    }

    public function addQuestion($package_id, $question_type, $question, $option_a, $option_b, $option_c, $option_d, $option_e, $correct_answer) {
        $query = "INSERT INTO questions (package_id, question_type, question, option_a, option_b, option_c, option_d, option_e, correct_answer) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$package_id, $question_type, $question, $option_a, $option_b, $option_c, $option_d, $option_e, $correct_answer]);
    }

    public function updateQuestion($id, $question_type, $question, $option_a, $option_b, $option_c, $option_d, $option_e, $correct_answer) {
        $query = "UPDATE questions SET question_type = ?, question = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, option_e = ?, correct_answer = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$question_type, $question, $option_a, $option_b, $option_c, $option_d, $option_e, $correct_answer, $id]);
    }

    // public function addQuestion($package_id, $question_type, $question, $option_a, $option_b, $option_c, $option_d, $correct_answer) {
    //     $query = "INSERT INTO questions (package_id, question_type, question, option_a, option_b, option_c, option_d, correct_answer) 
    //               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    //     $stmt = $this->conn->prepare($query);
    //     return $stmt->execute([$package_id, $question_type, $question, $option_a, $option_b, $option_c, $option_d, $correct_answer]);
    // }

    public function getQuestionsByPackage($package_id) {
        $query = "SELECT * FROM questions WHERE package_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$package_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuestionById($id) {
        $query = "SELECT * FROM questions WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // public function updateQuestion($id, $question_type, $question, $option_a, $option_b, $option_c, $option_d, $correct_answer) {
    //     $query = "UPDATE questions SET question_type = ?, question = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_answer = ? WHERE id = ?";
    //     $stmt = $this->conn->prepare($query);
    //     return $stmt->execute([$question_type, $question, $option_a, $option_b, $option_c, $option_d, $correct_answer, $id]);
    // }

    public function deleteQuestion($id) {
        try {
            $this->conn->beginTransaction();

            // Hapus jawaban terkait terlebih dahulu
            $query = "DELETE FROM user_answers WHERE question_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);

            // Kemudian hapus pertanyaan
            $query = "DELETE FROM questions WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error deleting question: " . $e->getMessage());
            return false;
        }
    }

    // public function updateQuestion($id, $question_type, $question, $option_a, $option_b, $option_c, $option_d, $correct_answer) {
    //     $query = "UPDATE questions 
    //               SET question_type = ?, question = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_answer = ? 
    //               WHERE id = ?";
    //     $stmt = $this->conn->prepare($query);
    //     return $stmt->execute([$question_type, $question, $option_a, $option_b, $option_c, $option_d, $correct_answer, $id]);
    // }

    public function getQuestionsForTest($test_id) {
        error_log("Getting questions for test ID: $test_id");
        
        $query = "SELECT q.* FROM questions q
                  JOIN test_packages tp ON q.package_id = tp.package_id
                  WHERE tp.test_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$test_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        error_log("Query executed. Number of questions retrieved: " . count($result));
        error_log("Questions for test $test_id: " . print_r($result, true));
        
        return $result;
    }

}
?>