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

$questions = $question->getQuestionsForTest($test_id);
$user_answers = $test->getTestAnswers($test_id);

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar for question numbers -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Nomor Soal</div>
                <div class="card-body">
                    <div class="d-flex flex-wrap" id="question-numbers">
                        <?php foreach ($questions as $index => $q): ?>
                            <button class="btn btn-outline-secondary m-1 question-number" data-question="<?php echo $index; ?>">
                                <?php echo $index + 1; ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content for questions -->
        <div class="col-md-9">
            <h1>Tes CAT CPNS</h1>
            <div class="alert alert-info">
                Waktu tersisa: <span id="timer">90:00</span>
            </div>

            <form id="testForm" method="POST" action="submit_test.php">
                <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
                <?php foreach ($questions as $index => $q): ?>
                    <div class="card mb-3 question-card" id="question-<?php echo $index; ?>" style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;">
                        <div class="card-body">
                            <h5 class="card-title">Pertanyaan <?php echo $index + 1; ?></h5>
                            <p><?php echo htmlspecialchars($q['question']); ?></p>
                            <?php
                            $options = ['A', 'B', 'C', 'D', 'E'];
                            foreach ($options as $option):
                                $option_value = 'option_' . strtolower($option);
                                if (isset($q[$option_value]) && !empty($q[$option_value])):
                            ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[<?php echo $q['id']; ?>]" 
                                        id="answer<?php echo $q['id']; ?>_<?php echo strtolower($option); ?>" 
                                        value="<?php echo $option; ?>"
                                        <?php echo (isset($user_answers[$q['id']]) && $user_answers[$q['id']] == $option) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="answer<?php echo $q['id']; ?>_<?php echo strtolower($option); ?>">
                                        <?php echo htmlspecialchars($q[$option_value]); ?>
                                    </label>
                                </div>
                            <?php
                                endif;
                            endforeach;
                            ?>
                            <div class="mt-3">
                                <button type="button" class="btn btn-warning btn-sm clear-answer">Hapus Jawaban</button>
                                <button type="button" class="btn btn-info btn-sm skip-question">Lewati</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-secondary" id="prevQuestion">Sebelumnya</button>
                    <button type="button" class="btn btn-primary" id="nextQuestion">Selanjutnya</button>
                </div>
                
                <button type="submit" class="btn btn-success mt-3" id="finishTest">Selesai dan Kirim</button>
            </form>
        </div>
    </div>
</div>

<script>
let currentQuestion = 0;
const totalQuestions = <?php echo count($questions); ?>;

function showQuestion(index) {
    document.querySelectorAll('.question-card').forEach(card => card.style.display = 'none');
    document.getElementById(`question-${index}`).style.display = 'block';
    currentQuestion = index;
    updateNavigationButtons();
    updateQuestionNumbers();
}

function updateNavigationButtons() {
    document.getElementById('prevQuestion').disabled = (currentQuestion === 0);
    document.getElementById('nextQuestion').disabled = (currentQuestion === totalQuestions - 1);
}

function updateQuestionNumbers() {
    document.querySelectorAll('.question-number').forEach((btn, index) => {
        btn.classList.remove('btn-primary', 'btn-success', 'btn-outline-secondary');
        if (index === currentQuestion) {
            btn.classList.add('btn-primary');
        } else if (isQuestionAnswered(index)) {
            btn.classList.add('btn-success');
        } else {
            btn.classList.add('btn-outline-secondary');
        }
    });
}

function isQuestionAnswered(index) {
    const questionId = document.getElementById(`question-${index}`).querySelector('input[type="radio"]').name.match(/\d+/)[0];
    return document.querySelector(`input[name="answers[${questionId}]"]:checked`) !== null;
}

document.getElementById('prevQuestion').addEventListener('click', () => {
    if (currentQuestion > 0) showQuestion(currentQuestion - 1);
});

document.getElementById('nextQuestion').addEventListener('click', () => {
    if (currentQuestion < totalQuestions - 1) showQuestion(currentQuestion + 1);
});

document.querySelectorAll('.question-number').forEach(btn => {
    btn.addEventListener('click', () => {
        showQuestion(parseInt(btn.getAttribute('data-question')));
    });
});

document.querySelectorAll('input[type="radio"]').forEach(input => {
    input.addEventListener('change', updateQuestionNumbers);
});

document.querySelectorAll('.clear-answer').forEach(btn => {
    btn.addEventListener('click', () => {
        const card = btn.closest('.question-card');
        card.querySelectorAll('input[type="radio"]').forEach(input => input.checked = false);
        updateQuestionNumbers();
    });
});

document.querySelectorAll('.skip-question').forEach(btn => {
    btn.addEventListener('click', () => {
        if (currentQuestion < totalQuestions - 1) showQuestion(currentQuestion + 1);
    });
});

// Timer logic
function startTimer(duration, display) {
    let timer = duration, minutes, seconds;
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
    const display = document.querySelector('#timer');
    startTimer(90 * 60, display);
    showQuestion(0);
};

document.getElementById('testForm').addEventListener('submit', function(e) {
    if (!confirm('Apakah Anda yakin ingin menyelesaikan tes?')) {
        e.preventDefault();
    }
});
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>