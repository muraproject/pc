<?php
function getAllMahasiswa($pdo) {
    $stmt = $pdo->query("SELECT * FROM mahasiswa ORDER BY id DESC");
    return $stmt->fetchAll();
}

function getMahasiswaById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addMahasiswa($pdo, $data) {
    $sql = "INSERT INTO mahasiswa (nama, ipk, penghasilan_ayah, penghasilan_ibu, angkatan) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['nama'], 
        $data['ipk'], 
        $data['penghasilan_ayah'], 
        $data['penghasilan_ibu'], 
        $data['angkatan']
    ]);
}

function updateMahasiswa($pdo, $id, $data) {
    $sql = "UPDATE mahasiswa SET nama = ?, ipk = ?, penghasilan_ayah = ?, penghasilan_ibu = ?, 
            angkatan = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['nama'], 
        $data['ipk'], 
        $data['penghasilan_ayah'], 
        $data['penghasilan_ibu'], 
        $data['angkatan'],
        $id
    ]);
}

function deleteMahasiswa($pdo, $id) {
    try {
        $pdo->beginTransaction();

        // Hapus data terkait di tabel hasil_clustering
        $stmt = $pdo->prepare("DELETE FROM hasil_clustering WHERE mahasiswa_id = ?");
        $stmt->execute([$id]);

        // Hapus data mahasiswa
        $stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE id = ?");
        $stmt->execute([$id]);

        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Error deleting mahasiswa: " . $e->getMessage());
        return false;
    }
}

function getTotalMahasiswa($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM mahasiswa");
    return $stmt->fetchColumn();
}

function getAverageIPK($pdo) {
    $stmt = $pdo->query("SELECT AVG(ipk) FROM mahasiswa");
    return $stmt->fetchColumn();
}