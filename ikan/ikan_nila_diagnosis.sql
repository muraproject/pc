-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Jul 2024 pada 10.33
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ikan_nila_diagnosis`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cf_pakar`
--

CREATE TABLE `cf_pakar` (
  `id` int(11) NOT NULL,
  `gejala_id` varchar(10) DEFAULT NULL,
  `penyakit_id` varchar(10) DEFAULT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cf_pakar`
--

INSERT INTO `cf_pakar` (`id`, `gejala_id`, `penyakit_id`, `nilai`) VALUES
(1, 'G001', 'P1', 0.8),
(2, 'G002', 'P1', 0.6),
(3, 'G003', 'P1', 0.7),
(4, 'G004', 'P1', 0.9),
(5, 'G003', 'P2', 0.6),
(6, 'G004', 'P2', 0.65),
(7, 'G005', 'P2', 0.9),
(8, 'G006', 'P2', 0.9),
(9, 'G007', 'P2', 0.75),
(10, 'G008', 'P3', 0.2),
(11, 'G009', 'P3', 0.8),
(12, 'G010', 'P4', 0.85),
(13, 'G011', 'P4', 0.85),
(14, 'G012', 'P4', 0.8),
(15, 'G003', 'P5', 0.7),
(16, 'G007', 'P5', 0.7),
(17, 'G013', 'P5', 0.85),
(18, 'G014', 'P5', 0.8),
(20, 'G015', 'P5', 0.7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `gejala`
--

CREATE TABLE `gejala` (
  `id` varchar(10) NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `gejala`
--

INSERT INTO `gejala` (`id`, `deskripsi`) VALUES
('G001', 'Tampak bintik putih kecil pada kulit, sirip, dan insang'),
('G002', 'Ikan menggosokkan badan pada benda sekitar'),
('G003', 'Nafsu makan ikan berkurang'),
('G004', 'Ikan engap dan cenderung mengapung'),
('G005', 'Ikan kesulitan saat bernafas'),
('G006', 'Insang rusak dan meradang'),
('G007', 'Pertumbuhan ikan akan melambat'),
('G008', 'Pertumbuhan hifa (miselia) berbulu putih kecoklatan'),
('G009', 'Terdapat benang halus seperti kapas'),
('G010', 'Ada luka di sekitar mulut, kepala, badan atau sirip'),
('G011', 'Infeksi seperti benang di area sekitar mulut'),
('G012', 'Disekitar luka tertutup pigmen berwarna kurang cerah'),
('G013', 'Luka pada kulit ikan'),
('G014', 'Mata ikan mengalami pembengkakan'),
('G015', 'Berenang tidak teratur');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keterangan`
--

CREATE TABLE `keterangan` (
  `id` int(11) NOT NULL,
  `konten` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `keterangan`
--

INSERT INTO `keterangan` (`id`, `konten`) VALUES
(1, 'Selamat datang di sistem diagnosa penyakit ikan nila. Silakan pilih gejala-gejala yang Anda amati pada ikan Anda.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyakit`
--

CREATE TABLE `penyakit` (
  `id` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `penyebab` text DEFAULT NULL,
  `pengendalian` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penyakit`
--

INSERT INTO `penyakit` (`id`, `nama`, `penyebab`, `pengendalian`) VALUES
('P1', 'White Spot', 'Parasit Protozoa Ichthyophthirius Multifiliis, Stres pada ikan', '1. Mengisolasi ikan yang terinfeksi\n2. Memberikan obat antibiotik seperti formalin, malachite green, dan praziquantel sesuai dengan dosis yang umum digunakan\n3. Menambahkan garam non-iodin dengan dosis 2-3 sendok teh per liter air'),
('P2', 'Dactylograsis', 'Cacing Dactylograsis, Kepadatan ikan', '1. Memberikan obat Praziquantel dengan dosis 2-5 mg/L dan direndam selama 24 jam\n2. Merendam menggunakan formalin dengan konsentrasi 25-50 ppm selama 1 jam dan dilakukan selama 2-3 hari selama beberapa minggu tergantung tingkat keparahan'),
('P3', 'Saprolegniasis', 'Jamur Saprolegnia', '1. Berikan obat Malachite Green dengan dosis umum 0,1-0,2 mg/L dan direndam selama 1 jam\n2. Merendam ikan menggunakan formalin dengan dosis 25-50 ppm dalam 1 jam perendaman\n3. Memberikan garam non-iodin sebanyak 1-3 gram per liter'),
('P4', 'Colomuniaris', 'Bakteri Flavobacterium columnare', '1. Menyiapkan tempat karantina baru untuk ikan yang terinfeksi\n2. Melarutkan obat oksitetrasiklin dalam air dengan konsentrasi 10-20 mg/L\n3. Rendam ke dalam larutan selama 1-2 jam selama 7-10 hari'),
('P5', 'Streptococcus', 'Bakteri Streptococcus agalactiae, S.Iniae', '1. Ikan diangkat dan dikarantina, wadah/kolam dibersihkan 2. Ikan rendam dengan air garam dengan dosis 10 ppm selama 10 menit 3. Lalu Rendam selam 30 menit dengan dengan vaksin vaprivac dosis 30 ppm 4. Untuk indukan suntik dengan vaksin vaprivac');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `cf_pakar`
--
ALTER TABLE `cf_pakar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gejala_id` (`gejala_id`),
  ADD KEY `penyakit_id` (`penyakit_id`);

--
-- Indeks untuk tabel `gejala`
--
ALTER TABLE `gejala`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `keterangan`
--
ALTER TABLE `keterangan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penyakit`
--
ALTER TABLE `penyakit`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `cf_pakar`
--
ALTER TABLE `cf_pakar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `cf_pakar`
--
ALTER TABLE `cf_pakar`
  ADD CONSTRAINT `cf_pakar_ibfk_1` FOREIGN KEY (`gejala_id`) REFERENCES `gejala` (`id`),
  ADD CONSTRAINT `cf_pakar_ibfk_2` FOREIGN KEY (`penyakit_id`) REFERENCES `penyakit` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
