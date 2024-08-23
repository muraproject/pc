<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Question.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Test.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to log messages
function logMessage($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/debug.log');
}

logMessage("Script started");

if (!isset($_SESSION['user_id'])) {
    logMessage("User not logged in, redirecting to login page");
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$question = new Question($db);
$test = new Test($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $test_id = isset($_POST['test_id']) ? intval($_POST['test_id']) : 0;
    $answers = isset($_POST['answers']) ? $_POST['answers'] : [];

    logMessage("Submitting test ID: $test_id");
    logMessage("Answers received: " . print_r($answers, true));

    $test_data = $test->getTestById($test_id);
    logMessage("Test data: " . print_r($test_data, true));

    if (!$test_data || $test_data['user_id'] != $_SESSION['user_id']) {
        logMessage("Invalid test data or user access");
        die("Tes tidak ditemukan atau Anda tidak memiliki akses.");
    }

    // Simpan jawaban
    foreach ($answers as $question_id => $answer) {
        $result = $test->saveAnswer($test_id, $question_id, $answer);
        logMessage("Saving answer for question $question_id: " . ($result ? 'Success' : 'Failed'));
    }

    // Hitung skor
    $questions = $question->getQuestionsForTest($test_id);
    logMessage("Questions retrieved: " . print_r($questions, true));

    $total_questions = count($questions);
    logMessage("Total questions: $total_questions");

    if ($total_questions == 0) {
        logMessage("Error: No questions found for test ID $test_id");
        die("Error: Tidak ada pertanyaan ditemukan untuk tes ini.");
    }

    $correct_answers = 0;
    foreach ($questions as $q) {
        logMessage("Checking question ID: " . $q['id']);
        logMessage("User answer: " . (isset($answers[$q['id']]) ? $answers[$q['id']] : 'Not answered'));
        logMessage("Correct answer: " . $q['correct_answer']);
        
        if (isset($answers[$q['id']]) && $answers[$q['id']] == $q['correct_answer']) {
            $correct_answers++;
        }
    }

    logMessage("Correct answers: $correct_answers");

    // Avoid division by zero
    if ($total_questions > 0) {
        $score = ($correct_answers / $total_questions) * 100;
    } else {
        $score = 0;
    }

    logMessage("Calculated score: $score");

    // Update skor tes
    $update_result = $test->updateTestScore($test_id, $score);
    logMessage("Updating test score: " . ($update_result ? 'Success' : 'Failed'));

    // Selesaikan tes
    $finish_result = $test->finishTest($test_id);
    logMessage("Finishing test: " . ($finish_result ? 'Success' : 'Failed'));

    // Redirect ke halaman hasil
    logMessage("Redirecting to test result page");
    header("Location: test_result.php?test_id=" . $test_id);
    exit();
} else {
    logMessage("Invalid request method, redirecting to history page");
    header("Location: /pc/cat/user/history.php");
    exit();
}
?>