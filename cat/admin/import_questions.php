<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/QuestionPackage.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/classes/Question.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /pc/cat/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$package = new QuestionPackage($db);
$question = new Question($db);

$packages = $package->getAllPackages();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['import'])) {
    $package_id = intval($_POST['package_id']);
    
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        $file = $_FILES['csv_file']['tmp_name'];
        
        if (($handle = fopen($file, "r")) !== FALSE) {
            $success_count = 0;
            $error_count = 0;
            
            // Skip header row
            fgetcsv($handle);
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($data) == 8) { // Assuming 8 columns: question_type, question, option_a, option_b, option_c, option_d, option_e, correct_answer
                    $result = $question->addQuestion(
                        $package_id,
                        $data[0], // question_type
                        $data[1], // question
                        $data[2], // option_a
                        $data[3], // option_b
                        $data[4], // option_c
                        $data[5], // option_d
                        $data[6], // option_e
                        $data[7]  // correct_answer
                    );
                    
                    if ($result) {
                        $success_count++;
                    } else {
                        $error_count++;
                    }
                } else {
                    $error_count++;
                }
            }
            fclose($handle);
            
            $message = "Impor selesai. Berhasil: $success_count, Gagal: $error_count";
        } else {
            $error_message = "Gagal membuka file CSV.";
        }
    } else {
        $error_message = "Silakan pilih file CSV untuk diimpor.";
    }
}

include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/header.php';
?>

<h1>Impor Soal dari CSV</h1>

<?php if (isset($message)): ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="package_id" class="form-label">Pilih Paket Soal</label>
        <select class="form-select" id="package_id" name="package_id" required>
            <?php foreach ($packages as $p): ?>
                <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="csv_file" class="form-label">File CSV</label>
        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
    </div>
    <button type="submit" name="import" class="btn btn-primary">Impor</button>
</form>

<h2 class="mt-4">Format CSV</h2>
<p>Pastikan file CSV Anda memiliki format berikut (termasuk header):</p>
<pre>
question_type,question,option_a,option_b,option_c,option_d,option_e,correct_answer
TWK,"Pertanyaan 1","Opsi A","Opsi B","Opsi C","Opsi D","Opsi E",A
TIU,"Pertanyaan 2","Opsi A","Opsi B","Opsi C","Opsi D","Opsi E",B
TKP,"Pertanyaan 3","Opsi A","Opsi B","Opsi C","Opsi D","Opsi E",C
</pre>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/footer.php'; ?>