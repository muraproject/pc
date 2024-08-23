<?php
class Test {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // public function startTest($user_id) {
    //     $query = "INSERT INTO user_tests (user_id) VALUES (?)";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute([$user_id]);
    //     return $this->conn->lastInsertId();
    // }

    public function endTest($test_id, $score) {
        $query = "UPDATE user_tests SET end_time = CURRENT_TIMESTAMP, score = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$score, $test_id]);
    }

    // public function saveAnswer($test_id, $question_id, $answer) {
    //     // Periksa struktur tabel terlebih dahulu
    //     $checkQuery = "DESCRIBE user_answers";
    //     $stmt = $this->conn->prepare($checkQuery);
    //     $stmt->execute();
    //     $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
    //     // Log kolom yang ada
    //     error_log("Columns in user_answers table: " . implode(", ", $columns));

    //     // Tentukan nama kolom yang benar
    //     $testIdColumn = in_array('test_id', $columns) ? 'test_id' : 'user_test_id';

    //     $query = "INSERT INTO user_answers ($testIdColumn, question_id, user_answer) 
    //               VALUES (?, ?, ?) 
    //               ON DUPLICATE KEY UPDATE user_answer = ?";
    //     $stmt = $this->conn->prepare($query);
    //     $result = $stmt->execute([$test_id, $question_id, $answer, $answer]);
        
    //     if (!$result) {
    //         error_log("Error in saveAnswer: " . implode(", ", $stmt->errorInfo()));
    //     }
        
    //     return $result;
    // }

    public function getTestResults($test_id) {
        $query = "SELECT ut.*, u.username, COUNT(ua.id) as total_questions, SUM(ua.is_correct) as correct_answers
                  FROM user_tests ut
                  JOIN users u ON ut.user_id = u.id
                  LEFT JOIN user_answers ua ON ut.id = ua.user_test_id
                  WHERE ut.id = ?
                  GROUP BY ut.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$test_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // public function getUserTestHistory($user_id) {
    //     $query = "SELECT * FROM user_tests WHERE user_id = ? ORDER BY start_time DESC";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute([$user_id]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    // public function getTestById($id) {
    //     $query = "SELECT * FROM user_tests WHERE id = ?";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute([$id]);
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }

    // public function getTestAnswers($test_id) {
    //     $query = "SELECT question_id, user_answer FROM user_answers WHERE user_test_id = ?";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute([$test_id]);
    //     $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     $answers = [];
    //     foreach ($results as $row) {
    //         $answers[$row['question_id']] = $row['user_answer'];
    //     }
    //     return $answers;
    // }

    public function getUserTestHistory($user_id) {
        $query = "SELECT * FROM user_tests WHERE user_id = ? AND is_cancelled = 0 ORDER BY start_time DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cancelTest($test_id) {
        try {
            $this->conn->beginTransaction();

            // Alih-alih menghapus, kita akan menandai tes sebagai dibatalkan
            $query = "UPDATE user_tests SET is_cancelled = 1 WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([$test_id]);
            error_log("Menandai tes sebagai dibatalkan. Affected rows: " . $stmt->rowCount());

            $this->conn->commit();
            error_log("Pembatalan tes berhasil untuk ID: " . $test_id);
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error in cancelTest: " . $e->getMessage());
            return false;
        }
    }

    public function getUnfinishedTest($user_id) {
        $query = "SELECT * FROM user_tests WHERE user_id = ? AND end_time IS NULL LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // public function getTestAnswers($test_id) {
    //     $query = "SELECT question_id, user_answer FROM user_answers WHERE user_test_id = ?";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute([$test_id]);
    //     $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     $answers = [];
    //     foreach ($results as $row) {
    //         $answers[$row['question_id']] = $row['user_answer'];
    //     }
    //     return $answers;
    // }

    public function getAllTestResults() {
        $query = "SELECT ut.id, ut.user_id, ut.start_time, ut.score, u.username 
                  FROM user_tests ut 
                  JOIN users u ON ut.user_id = u.id 
                  WHERE ut.end_time IS NOT NULL 
                  ORDER BY ut.start_time DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTestDetails($test_id) {
        $query = "SELECT ut.*, u.username 
                  FROM user_tests ut 
                  JOIN users u ON ut.user_id = u.id 
                  WHERE ut.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$test_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Modifikasi getTestAnswers() yang sudah ada untuk mencakup informasi pertanyaan

    public function saveAnswer($test_id, $question_id, $answer) {
        $query = "INSERT INTO user_answers (user_test_id, question_id, user_answer) 
                  VALUES (?, ?, ?) 
                  ON DUPLICATE KEY UPDATE user_answer = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$test_id, $question_id, $answer, $answer]);
    }

    public function updateTestScore($test_id, $score) {
        $query = "UPDATE user_tests SET score = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$score, $test_id]);
    }

    public function finishTest($test_id) {
        $query = "UPDATE user_tests SET end_time = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$test_id]);
    }
    public function getTestAnswers($test_id) {
        $query = "SELECT question_id, user_answer FROM user_answers WHERE user_test_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$test_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $answers = [];
        foreach ($results as $row) {
            $answers[$row['question_id']] = $row['user_answer'];
        }
        return $answers;
    }

    // public function updateTestScore($test_id, $score) {
    //     $query = "UPDATE user_tests SET score = ? WHERE id = ?";
    //     $stmt = $this->conn->prepare($query);
    //     return $stmt->execute([$score, $test_id]);
    // }


    public function startTest($user_id) {
        $query = "INSERT INTO user_tests (user_id) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $this->conn->lastInsertId();
    }

    public function assignPackageToTest($test_id, $package_id) {
        $query = "INSERT INTO test_packages (test_id, package_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$test_id, $package_id]);
    }

    public function getTestById($test_id) {
        $query = "SELECT * FROM user_tests WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$test_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTestPackage($test_id) {
        $query = "SELECT package_id FROM test_packages WHERE test_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$test_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['package_id'] : null;
    }

    // public function saveAnswer($test_id, $question_id, $answer) {
    //     $query = "INSERT INTO user_answers (test_id, question_id, user_answer) 
    //               VALUES (?, ?, ?) 
    //               ON DUPLICATE KEY UPDATE user_answer = ?";
    //     $stmt = $this->conn->prepare($query);
    //     return $stmt->execute([$test_id, $question_id, $answer, $answer]);
    // }

    // public function finishTest($test_id) {
    //     $query = "UPDATE user_tests SET end_time = CURRENT_TIMESTAMP WHERE id = ?";
    //     $stmt = $this->conn->prepare($query);
    //     return $stmt->execute([$test_id]);
    // }
}
?>
