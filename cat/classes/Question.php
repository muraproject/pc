<?php
class Question {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addQuestion($package_id, $question_type, $question, $option_a, $option_b, $option_c, $option_d, $correct_answer) {
        $query = "INSERT INTO questions (package_id, question_type, question, option_a, option_b, option_c, option_d, correct_answer) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$package_id, $question_type, $question, $option_a, $option_b, $option_c, $option_d, $correct_answer]);
    }

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
        $query = "DELETE FROM questions WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function updateQuestion($id, $question_type, $question, $option_a, $option_b, $option_c, $option_d, $correct_answer) {
        $query = "UPDATE questions 
                  SET question_type = ?, question = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_answer = ? 
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$question_type, $question, $option_a, $option_b, $option_c, $option_d, $correct_answer, $id]);
    }

    public function getQuestionsForTest($test_id) {
        $query = "SELECT q.* FROM questions q
                  JOIN test_packages tp ON q.package_id = tp.package_id
                  WHERE tp.test_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$test_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>