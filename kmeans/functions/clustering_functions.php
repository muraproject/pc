<?php

function performClustering($pdo) {
    $mahasiswa = getAllMahasiswaForClustering($pdo);
    $poData = calculatePO($mahasiswa);
    $normalizedData = normalizeData($mahasiswa, $poData);
    $centroids = initializeFixedCentroids();
    
    $maxIterations = 1000; // Meningkatkan jumlah iterasi maksimum
    $iteration = 0;
    $previousCentroids = [];

    do {
        $clusters = assignToClusters($normalizedData, $centroids);
        $previousCentroids = $centroids;
        $centroids = updateCentroids($clusters);
        $iteration++;
    } while (!centroidsConverged($centroids, $previousCentroids) && $iteration < $maxIterations);

    saveClusteringResults($pdo, $clusters, $mahasiswa);
}

function getAllMahasiswaForClustering($pdo) {
    $stmt = $pdo->query("SELECT id, nama, ipk, penghasilan_ayah, penghasilan_ibu, jumlah_tanggungan FROM mahasiswa");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function calculatePO($mahasiswa) {
    $poData = [];
    foreach ($mahasiswa as $mhs) {
        $totalPenghasilan = convertPenghasilanToNumber($mhs['penghasilan_ayah']) + convertPenghasilanToNumber($mhs['penghasilan_ibu']);
        $po = $mhs['jumlah_tanggungan'] > 0 ? $totalPenghasilan / $mhs['jumlah_tanggungan'] : $totalPenghasilan;
        $poData[] = $po;
    }
    return $poData;
}

function normalizeData($mahasiswa, $poData) {
    $normalizedData = [];
    $avgPO = array_sum($poData) / count($poData);
    $stdDevPO = calculateStandardDeviation($poData);
    
    foreach ($mahasiswa as $index => $mhs) {
        $normalizedData[] = [
            'id' => $mhs['id'],
            'ipk' => $mhs['ipk'],
            'po_category' => categorizePO($poData[$index], $avgPO, $stdDevPO)
        ];
    }
    return $normalizedData;
}

function calculateStandardDeviation($data) {
    $mean = array_sum($data) / count($data);
    $variance = array_sum(array_map(function($x) use ($mean) {
        return pow($x - $mean, 2);
    }, $data)) / count($data);
    return sqrt($variance);
}

function categorizePO($po, $avg, $stdDev) {
    if ($po <= $avg - $stdDev) return 4; // Penghasilan sangat rendah
    if ($po < $avg) return 3; // Penghasilan rendah
    if ($po < $avg + $stdDev) return 2; // Penghasilan sedang
    return 1; // Penghasilan tinggi
}

function initializeFixedCentroids() {
    return [
        ['ipk' => 3.5, 'po_category' => 4], // Layak (IPK tinggi, penghasilan sangat rendah)
        ['ipk' => 3.0, 'po_category' => 3], // Dipertimbangkan
        ['ipk' => 2.5, 'po_category' => 1]  // Tidak Layak (IPK rendah, penghasilan tinggi)
    ];
}

function assignToClusters($normalizedData, $centroids) {
    $clusters = [[], [], []];
    foreach ($normalizedData as $data) {
        $minDistance = PHP_FLOAT_MAX;
        $clusterIndex = 0;
        for ($i = 0; $i < 3; $i++) {
            $distance = calculateDistance($data, $centroids[$i]);
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $clusterIndex = $i;
            }
        }
        $clusters[$clusterIndex][] = $data;
    }
    return $clusters;
}

function calculateDistance($data1, $data2) {
    $ipkDiff = ($data1['ipk'] - $data2['ipk']) / 4; // Normalisasi IPK
    $poDiff = ($data1['po_category'] - $data2['po_category']) / 4; // Normalisasi PO kategori
    return sqrt($ipkDiff*$ipkDiff + $poDiff*$poDiff);
}

function updateCentroids($clusters) {
    $newCentroids = [];
    foreach ($clusters as $cluster) {
        if (empty($cluster)) {
            $newCentroids[] = ['ipk' => 0, 'po_category' => 0];
            continue;
        }
        $sumIPK = $sumPO = 0;
        foreach ($cluster as $data) {
            $sumIPK += $data['ipk'];
            $sumPO += $data['po_category'];
        }
        $count = count($cluster);
        $newCentroids[] = [
            'ipk' => $sumIPK / $count,
            'po_category' => $sumPO / $count
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

function saveClusteringResults($pdo, $clusters, $originalData) {
    $pdo->beginTransaction();
    try {
        $pdo->exec("DELETE FROM hasil_clustering");
        
        foreach ($clusters as $clusterIndex => $cluster) {
            foreach ($cluster as $data) {
                $sql = "INSERT INTO hasil_clustering (mahasiswa_id, cluster_id) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$data['id'], $clusterIndex + 1]);
            }
        }
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function convertPenghasilanToNumber($penghasilan) {
    switch ($penghasilan) {
        case '0 - 500.000': return 300000;
        case '500.000 - 999.999': return 600000;
        case '1.000.000 - 1.999.999': return 1500000;
        case '2.000.000 - 4.999.999': return 3000000;
        case '> 5.000.000': return 5000000;
        default: return 0;
    }
}

function getClusteringResults($pdo) {
    $sql = "SELECT m.id, m.nama, m.ipk, m.penghasilan_ayah, m.penghasilan_ibu, m.jumlah_tanggungan, 
            c.nama as cluster_nama, c.keterangan
            FROM mahasiswa m
            JOIN hasil_clustering hc ON m.id = hc.mahasiswa_id
            JOIN cluster c ON hc.cluster_id = c.id
            ORDER BY c.id, m.ipk DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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