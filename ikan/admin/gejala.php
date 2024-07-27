<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Fungsi CRUD
function tambahGejala($conn, $id, $deskripsi) {
    $stmt = $conn->prepare("INSERT INTO gejala (id, deskripsi) VALUES (?, ?)");
    $stmt->bind_param("ss", $id, $deskripsi);
    return $stmt->execute();
}

function editGejala($conn, $id, $deskripsi) {
    $stmt = $conn->prepare("UPDATE gejala SET deskripsi = ? WHERE id = ?");
    $stmt->bind_param("ss", $deskripsi, $id);
    return $stmt->execute();
}

function hapusGejala($conn, $id) {
    // Cek apakah gejala digunakan di tabel cf_pakar
    $stmt = $conn->prepare("SELECT COUNT(*) FROM cf_pakar WHERE gejala_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_row()[0];

    if ($count > 0) {
        return "Gejala ini digunakan dalam CF Pakar. Hapus dulu data ini di CF Pakar sebelum menghapus gejala.";
    }

    // Jika tidak digunakan, lanjutkan dengan penghapusan
    $stmt = $conn->prepare("DELETE FROM gejala WHERE id = ?");
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
        return "Gejala berhasil dihapus.";
    } else {
        return "Gagal menghapus gejala: " . $conn->error;
    }
}

// Logika untuk menangani form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['tambah'])) {
        if (tambahGejala($conn, $_POST['id'], $_POST['deskripsi'])) {
            $pesan = "Gejala berhasil ditambahkan.";
        } else {
            $pesan = "Gagal menambahkan gejala: " . $conn->error;
        }
    } elseif (isset($_POST['edit'])) {
        if (editGejala($conn, $_POST['id'], $_POST['deskripsi'])) {
            $pesan = "Gejala berhasil diperbarui.";
        } else {
            $pesan = "Gagal memperbarui gejala: " . $conn->error;
        }
    } elseif (isset($_POST['hapus'])) {
        $pesan = hapusGejala($conn, $_POST['id']);
    }
}

// Ambil semua data gejala
$result = $conn->query("SELECT * FROM gejala");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Gejala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .floating-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="gejala.php">Gejala</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="penyakit.php">Penyakit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cf_pakar.php">CF Pakar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="keterangan.php">Keterangan</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Kelola Gejala</h1>
        
        <?php if (isset($pesan)): ?>
            <div class="alert <?php echo strpos($pesan, 'berhasil') !== false ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                <?php echo $pesan; ?>
            </div>
        <?php endif; ?>

        <!-- Tabel untuk menampilkan gejala -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['deskripsi']; ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" 
                                data-id="<?php echo $row['id']; ?>" 
                                data-deskripsi="<?php echo htmlspecialchars($row['deskripsi']); ?>">
                            Edit
                        </button>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="hapus" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Floating button untuk menambah gejala -->
    <button class="btn btn-primary btn-lg rounded-circle floating-button" data-bs-toggle="modal" data-bs-target="#tambahModal">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Modal untuk menambah gejala -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Gejala</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="tambahId" class="form-label">ID Gejala</label>
                            <input type="text" class="form-control" id="tambahId" name="id" required>
                        </div>
                        <div class="mb-3">
                            <label for="tambahDeskripsi" class="form-label">Deskripsi Gejala</label>
                            <input type="text" class="form-control" id="tambahDeskripsi" name="deskripsi" required>
                        </div>
                        <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk mengedit gejala -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Gejala</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label for="editDeskripsi" class="form-label">Deskripsi Gejala</label>
                            <input type="text" class="form-control" id="editDeskripsi" name="deskripsi" required>
                        </div>
                        <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script untuk mengisi data ke modal edit
        var editModal = document.getElementById('editModal')
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget
            var id = button.getAttribute('data-id')
            var deskripsi = button.getAttribute('data-deskripsi')
            var modalBodyInput = editModal.querySelector('.modal-body input#editId')
            var modalBodyInputDeskripsi = editModal.querySelector('.modal-body input#editDeskripsi')

            modalBodyInput.value = id
            modalBodyInputDeskripsi.value = deskripsi
        })
    </script>
</body>
</html>