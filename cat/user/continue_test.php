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

$test_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$test_data = $test->getTestById($test_id);

if (!$test_data || $test_data['user_id'] != $_SESSION['user_id'] || $test_data['is_cancelled']) {
    header("Location: /pc/cat/user/history.php");
    exit();
}

$questions = $question->getQuestionsForTest($test_id);
$answers = $test->getTestAnswers($test_id);

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Lanjutkan Tes CAT CPNS</h1>

<div class="alert alert-info">
    Waktu tersisa: <span id="time">-- : --</span>
</div>

<form id="testForm" method="POST" action="/pc/cat/user/submit_test.php">
    <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
    <?php foreach ($questions as $index => $q): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Pertanyaan <?php echo $index + 1; ?></h5>
                <p><?php echo htmlspecialchars($q['question']); ?></p>
                <?php
                $user_answer = isset($answers[$q['id']]) ? $answers[$q['id']] : '';
                $options = ['A', 'B', 'C', 'D'];
                foreach ($options as $option):
                    $option_value = 'option_' . strtolower($option);
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer[<?php echo $q['id']; ?>]" 
                           id="answer<?php echo $q['id']; ?>_<?php echo strtolower($option); ?>" 
                           value="<?php echo $option; ?>" 
                           <?php echo ($user_answer == $option) ? 'checked' : ''; ?> required>
                    <label class="form-check-label" for="answer<?php echo $q['id']; ?>_<?php echo strtolower($option); ?>">
                        <?php echo htmlspecialchars($q[$option_value]); ?>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <button type="submit" class="btn btn-primary">Selesai dan Kirim</button>
</form>

<script>
// Add JavaScript to calculate remaining time
var startTime = new Date("<?php echo $test_data['start_time']; ?>").getTime();
var now = new Date().getTime();
var timePassed = now - startTime;
var timeLeft = (90 * 60 * 1000) - timePassed; // 90 minutes in milliseconds

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
    var display = document.querySelector('#time');
    startTimer(Math.floor(timeLeft / 1000), display);
};
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>