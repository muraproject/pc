<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Question.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Test.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$question = new Question($db);
$test = new Test($db);

$test_id = isset($_GET['test_id']) ? intval($_GET['test_id']) : 0;
$test_data = $test->getTestById($test_id);

if (!$test_data || $test_data['user_id'] != $_SESSION['user_id']) {
    die("Tes tidak ditemukan atau Anda tidak memiliki akses.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process answers
    if (isset($_POST['answers']) && is_array($_POST['answers'])) {
        foreach ($_POST['answers'] as $question_id => $answer) {
            $result = $test->saveAnswer($test_id, $question_id, $answer);
            if (!$result) {
                error_log("Failed to save answer for question $question_id in test $test_id");
            }
        }
    }
    
    if (isset($_POST['finish_test'])) {
        $test->finishTest($test_id);
        header("Location: test_result.php?test_id=" . $test_id);
        exit();
    }
}

$questions = $question->getQuestionsForTest($test_id);

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Tes CAT CPNS</h1>

<div class="alert alert-info">
    Waktu tersisa: <span id="timer">90:00</span>
</div>

<form method="POST" id="testForm">
    <?php foreach ($questions as $index => $q): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Pertanyaan <?php echo $index + 1; ?></h5>
                <p><?php echo htmlspecialchars($q['question']); ?></p>
                <?php
                $options = ['A', 'B', 'C', 'D'];
                foreach ($options as $option):
                    $option_value = 'option_' . strtolower($option);
                ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answers[<?php echo $q['id']; ?>]" 
                               id="answer<?php echo $q['id']; ?>_<?php echo strtolower($option); ?>" 
                               value="<?php echo $option; ?>">
                        <label class="form-check-label" for="answer<?php echo $q['id']; ?>_<?php echo strtolower($option); ?>">
                            <?php echo htmlspecialchars($q[$option_value]); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    
    <button type="submit" name="save_progress" class="btn btn-primary">Simpan Progres</button>
    <button type="submit" name="finish_test" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan tes?');">Selesaikan Tes</button>
</form>

<script>
function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            timer = 0;
            document.getElementById("testForm").submit();
        }
    }, 1000);
}

window.onload = function () {
    var ninetyMinutes = 90 * 60,
        display = document.querySelector('#timer');
    startTimer(ninetyMinutes, display);
};
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>