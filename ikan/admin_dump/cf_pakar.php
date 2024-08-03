<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Fungsi CRUD
function tambahCFPakar($conn, $gejala_id, $penyakit_id, $nilai) {
    $stmt = $conn->prepare("INSERT INTO cf_pakar (gejala_id, penyakit_id, nilai) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $gejala_id, $penyakit_id, $nilai);
    return $stmt->execute();
}

function editCFPakar($conn, $id, $gejala_id, $penyakit_id, $nilai) {
    $stmt = $conn->prepare("UPDATE cf_pakar SET gejala_id = ?, penyakit_id = ?, nilai = ? WHERE id = ?");
    $stmt->bind_param("ssdi", $gejala_id, $penyakit_id, $nilai, $id);
    return $stmt->execute();
}

function hapusCFPakar($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM cf_pakar WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Logika untuk menangani form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['tambah'])) {
        if (tambahCFPakar($conn, $_POST['gejala_id'], $_POST['penyakit_id'], $_POST['nilai'])) {
            $pesan = "CF Pakar berhasil ditambahkan.";
        } else {
            $pesan = "Gagal menambahkan CF Pakar: " . $conn->error;
        }
    } elseif (isset($_POST['edit'])) {
        if (editCFPakar($conn, $_POST['id'], $_POST['gejala_id'], $_POST['penyakit_id'], $_POST['nilai'])) {
            $pesan = "CF Pakar berhasil diperbarui.";
        } else {
            $pesan = "Gagal memperbarui CF Pakar: " . $conn->error;
        }
    } elseif (isset($_POST['hapus'])) {
        if (hapusCFPakar($conn, $_POST['id'])) {
            $pesan = "CF Pakar berhasil dihapus.";
        } else {
            $pesan = "Gagal menghapus CF Pakar: " . $conn->error;
        }
    }
}

// Ambil semua data CF Pakar
$result = $conn->query("SELECT cf.*, g.deskripsi AS gejala, p.nama AS penyakit 
                        FROM cf_pakar cf
                        JOIN gejala g ON cf.gejala_id = g.id
                        JOIN penyakit p ON cf.penyakit_id = p.id");

// Ambil daftar gejala dan penyakit untuk dropdown
$gejala_list = $conn->query("SELECT id, deskripsi FROM gejala");
$penyakit_list = $conn->query("SELECT id, nama FROM penyakit");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola CF Pakar</title>
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
                        <a class="nav-link" href="gejala.php">Gejala</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="penyakit.php">Penyakit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="cf_pakar.php">CF Pakar</a>
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
        <h1>Kelola CF Pakar</h1>
        
        <?php if (isset($pesan)): ?>
            <div class="alert <?php echo strpos($pesan, 'berhasil') !== false ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                <?php echo $pesan; ?>
            </div>
        <?php endif; ?>

        <!-- Tabel untuk menampilkan CF Pakar -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gejala</th>
                    <th>Penyakit</th>
                    <th>Nilai CF</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['gejala']; ?></td>
                    <td><?php echo $row['penyakit']; ?></td>
                    <td><?php echo $row['nilai']; ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" 
                                data-id="<?php echo $row['id']; ?>"
                                data-gejala-id="<?php echo $row['gejala_id']; ?>"
                                data-penyakit-id="<?php echo $row['penyakit_id']; ?>"
                                data-nilai="<?php echo $row['nilai']; ?>">
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

    <!-- Floating button untuk menambah CF Pakar -->
    <button class="btn btn-primary btn-lg rounded-circle floating-button" data-bs-toggle="modal" data-bs-target="#tambahModal">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Modal untuk menambah CF Pakar -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah CF Pakar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="tambahGejala" class="form-label">Gejala</label>
                            <select class="form-select" id="tambahGejala" name="gejala_id" required>
                                <?php while ($gejala = $gejala_list->fetch_assoc()): ?>
                                    <option value="<?php echo $gejala['id']; ?>"><?php echo $gejala['deskripsi']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tambahPenyakit" class="form-label">Penyakit</label>
                            <select class="form-select" id="tambahPenyakit" name="penyakit_id" required>
                                <?php while ($penyakit = $penyakit_list->fetch_assoc()): ?>
                                    <option value="<?php echo $penyakit['id']; ?>"><?php echo $penyakit['nama']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tambahNilai" class="form-label">Nilai CF</label>
                            <input type="number" class="form-control" id="tambahNilai" name="nilai" step="0.01" min="0" max="1" required>
                        </div>
                        <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk mengedit CF Pakar -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit CF Pakar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label for="editGejala" class="form-label">Gejala</label>
                            <select class="form-select" id="editGejala" name="gejala_id" required>
                                <?php 
                                $gejala_list->data_seek(0);
                                while ($gejala = $gejala_list->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $gejala['id']; ?>"><?php echo $gejala['deskripsi']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editPenyakit" class="form-label">Penyakit</label>
                            <select class="form-select" id="editPenyakit" name="penyakit_id" required>
                                <?php 
                                $penyakit_list->data_seek(0);
                                while ($penyakit = $penyakit_list->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $penyakit['id']; ?>"><?php echo $penyakit['nama']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editNilai" class="form-label">Nilai CF</label>
                            <input type="number" class="form-control" id="editNilai" name="nilai" step="0.01" min="0" max="1" required>
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
            var gejalaId = button.getAttribute('data-gejala-id')
            var penyakitId = button.getAttribute('data-penyakit-id')
            var nilai = button.getAttribute('data-nilai')
            
            var modalBodyInputId = editModal.querySelector('.modal-body input#editId')
            var modalBodySelectGejala = editModal.querySelector('.modal-body select#editGejala')
            var modalBodySelectPenyakit = editModal.querySelector('.modal-body select#editPenyakit')
            var modalBodyInputNilai = editModal.querySelector('.modal-body input#editNilai')

            modalBodyInputId.value = id
            modalBodySelectGejala.value = gejalaId
            modalBodySelectPenyakit.value = penyakitId
            modalBodyInputNilai.value = nilai
        })
    </script>
</body>
</html>