<?php

function performClustering($pdo) {
    $mahasiswa = getAllMahasiswaForClustering($pdo);
    $centroids = initializeCentroids($mahasiswa);
    $maxIterations = 100;
    $iteration = 0;
    $previousCentroids = [];

    do {
        $clusters = assignToClusters($mahasiswa, $centroids);
        $previousCentroids = $centroids;
        $centroids = updateCentroids($clusters);
        $iteration++;
    } while (!centroidsConverged($centroids, $previousCentroids) && $iteration < $maxIterations);

    saveClusteringResults($pdo, $clusters);
}

function getAllMahasiswaForClustering($pdo) {
    $stmt = $pdo->query("SELECT id, nama, ipk, penghasilan_ayah, penghasilan_ibu, angkatan, jumlah_tanggungan FROM mahasiswa");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function calculateScore($mhs) {
    $ipkScore = $mhs['ipk'] * 25; // IPK 4.0 akan mendapat skor 100
    $penghasilanAyahScore = (6 - convertPenghasilanToNumber($mhs['penghasilan_ayah'])) * 10; // Maksimum 50
    $penghasilanIbuScore = (6 - convertPenghasilanToNumber($mhs['penghasilan_ibu'])) * 10; // Maksimum 50
    $tanggunganScore = min($mhs['jumlah_tanggungan'], 5) * 4; // Maksimum 20 (untuk 5 tanggungan atau lebih)
    return $ipkScore + $penghasilanAyahScore + $penghasilanIbuScore + $tanggunganScore; // Total maksimum 220
}

function initializeCentroids($mahasiswa) {
    $centroids = array_rand($mahasiswa, 3);
    return array_map(function($index) use ($mahasiswa) {
        return [
            'ipk' => $mahasiswa[$index]['ipk'],
            'penghasilan' => isset($mahasiswa[$index]['penghasilan_ayah']) ? 
                convertPenghasilanToNumber($mahasiswa[$index]['penghasilan_ayah']) : 0
        ];
    }, $centroids);
}


// function calculateScore($mhs) {
//     $ipkScore = $mhs['ipk'] * 25; // IPK 4.0 akan mendapat skor 100
//     $penghasilanScore = convertPenghasilanToNumber($mhs['penghasilan_ayah']) * 25; // Maksimum 100
//     return $ipkScore + $penghasilanScore; // Total maksimum 200
// }

function assignToClusters($mahasiswa, $centroids) {
    $clusters = [[], [], []];
    foreach ($mahasiswa as $mhs) {
        $score = calculateScore($mhs);
        if ($score >= 170) {
            $clusterIndex = 0; // Layak
        } elseif ($score >= 120) {
            $clusterIndex = 1; // Dipertimbangkan
        } else {
            $clusterIndex = 2; // Tidak Layak
        }
        $clusters[$clusterIndex][] = $mhs;
        
        // Logging untuk debugging
        error_log("Mahasiswa: " . $mhs['nama'] . " | IPK: " . $mhs['ipk'] . 
                  " | Penghasilan Ayah: " . $mhs['penghasilan_ayah'] . 
                  " | Penghasilan Ibu: " . $mhs['penghasilan_ibu'] . 
                  " | Tanggungan: " . $mhs['jumlah_tanggungan'] .
                  " | Score: " . $score . " | Cluster: " . ($clusterIndex + 1));
    }
    return $clusters;
}

function updateCentroids($clusters) {
    $newCentroids = [];
    foreach ($clusters as $cluster) {
        if (empty($cluster)) continue;
        $sumIPK = $sumPenghasilan = 0;
        foreach ($cluster as $mhs) {
            $sumIPK += $mhs['ipk'];
            $sumPenghasilan += isset($mhs['penghasilan_ayah']) ? 
                convertPenghasilanToNumber($mhs['penghasilan_ayah']) : 0;
        }
        $count = count($cluster);
        $newCentroids[] = [
            'ipk' => $sumIPK / $count,
            'penghasilan' => $sumPenghasilan / $count
        ];
    }
    return $newCentroids;
}

function centroidsConverged($centroids, $previousCentroids) {
    if (empty($previousCentroids)) return false;
    $threshold = 0.0001;
    for ($i = 0; $i < count($centroids); $i++) {
        if (calculateDistance($centroids[$i], $previousCentroids[$i]) > $threshold) {
            return false;
        }
    }
    return true;
}

function calculateDistance($mhs, $centroid) {
    $ipkDiff = $mhs['ipk'] - $centroid['ipk'];
    $penghasilan = isset($mhs['penghasilan_ayah']) ? 
        convertPenghasilanToNumber($mhs['penghasilan_ayah']) : 0;
    $penghasilanDiff = $penghasilan - $centroid['penghasilan'];
    return sqrt($ipkDiff*$ipkDiff + $penghasilanDiff*$penghasilanDiff);
}

function convertPenghasilanToNumber($penghasilan) {
    switch ($penghasilan) {
        case '0 - 500.000': return 1;
        case '500.000 - 999.999': return 2;
        case '1.000.000 - 1.999.999': return 3;
        case '2.000.000 - 4.999.999': return 4;
        case '> 5.000.000': return 5;
        default: return 0;
    }
}

function saveClusteringResults($pdo, $clusters) {
    $pdo->beginTransaction();
    try {
        // Hapus hasil clustering sebelumnya
        $pdo->exec("DELETE FROM hasil_clustering");
        
        foreach ($clusters as $clusterIndex => $cluster) {
            foreach ($cluster as $mhs) {
                $sql = "INSERT INTO hasil_clustering (mahasiswa_id, cluster_id) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$mhs['id'], $clusterIndex + 1]); // +1 karena cluster_id dimulai dari 1
            }
        }
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function getClusteringResults($pdo) {
    $sql = "SELECT m.id, m.nama, m.ipk, m.penghasilan_ayah, m.penghasilan_ibu, m.angkatan, m.jumlah_tanggungan, c.nama as cluster_nama, c.keterangan
            FROM mahasiswa m
            JOIN hasil_clustering hc ON m.id = hc.mahasiswa_id
            JOIN cluster c ON hc.cluster_id = c.id
            ORDER BY c.id, m.ipk DESC";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Debug: Tampilkan query SQL dan hasil
    // echo "SQL Query: " . $sql . "<br>";
    // var_dump($results[0]);
    
    return $results;
}

function getLastClusteringDate($pdo) {
    $stmt = $pdo->query("SELECT MAX(tanggal_clustering) as last_date FROM hasil_clustering");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['last_date'] ? date('d-m-Y H:i:s', strtotime($result['last_date'])) : 'Belum ada clustering';
}

function getClusterSummary($pdo) {
    $sql = "SELECT c.nama, c.keterangan, COUNT(hc.mahasiswa_id) as count
            FROM cluster c
            LEFT JOIN hasil_clustering hc ON c.id = hc.cluster_id
            GROUP BY c.id
            ORDER BY c.id";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}