<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Test.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Question.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$test = new Test($db);
$question = new Question($db);

$test_id = isset($_POST['test_id']) ? intval($_POST['test_id']) : 0;
$answers = isset($_POST['answer']) ? $_POST['answer'] : [];

$test_data = $test->getTestById($test_id);

if (!$test_data || $test_data['user_id'] != $_SESSION['user_id']) {
    header("Location: /pc/cat/user/history.php");
    exit();
}

// Calculate score
$total_questions = count($answers);
$correct_answers = 0;

foreach ($answers as $question_id => $user_answer) {
    $correct_answer = $question->getCorrectAnswer($question_id);
    $is_correct = ($user_answer === $correct_answer);
    $test->saveUserAnswer($test_id, $question_id, $user_answer, $is_correct);
    if ($is_correct) {
        $correct_answers++;
    }
}

$score = ($correct_answers / $total_questions) * 100;

// End the test
$test->endTest($test_id, $score);

// Redirect to results page
header("Location: /pc/cat/user/view_result.php?id=" . $test_id);
exit();
?>