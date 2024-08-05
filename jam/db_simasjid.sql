-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Agu 2024 pada 10.33
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
-- Database: `db_simasjid`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `hadits_jumat`
--

CREATE TABLE `hadits_jumat` (
  `id` int(11) NOT NULL,
  `konten` text DEFAULT NULL,
  `sumber` varchar(255) DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hadits_jumat`
--

INSERT INTO `hadits_jumat` (`id`, `konten`, `sumber`, `catatan`) VALUES
(1, 'Sebaik-baik hari dimana matahari terbit padanya adalah hari Jumat...', 'HR. Muslim', 'jeda');

-- --------------------------------------------------------

--
-- Struktur dari tabel `imam_rowatib`
--

CREATE TABLE `imam_rowatib` (
  `hari` varchar(10) DEFAULT NULL,
  `imam` varchar(100) DEFAULT NULL,
  `cadangan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `imam_rowatib`
--

INSERT INTO `imam_rowatib` (`hari`, `imam`, `cadangan`) VALUES
('Senin', 'Ustadz Ahmad', 'Ustadz Mahmud'),
('Selasa', 'Ustadz Budi', 'Ustadz Hasan'),
('Rabu', 'Ustadz Chaerul', 'Ustadz Imam'),
('Kamis', 'Ustadz Dedi', 'Ustadz Joko'),
('Jumat', 'Ustadz Eko', 'Ustadz Karto'),
('Sabtu', 'Ustadz Farid', 'Ustadz Lukman'),
('Ahad', 'Ustadz Ghazali', 'Ustadz Najib');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jumatan`
--

CREATE TABLE `jumatan` (
  `id` int(11) NOT NULL,
  `tgl` date DEFAULT NULL,
  `khotib` varchar(100) DEFAULT NULL,
  `bilal` varchar(100) DEFAULT NULL,
  `muadzin` varchar(100) DEFAULT NULL,
  `photo_khotib` varchar(255) DEFAULT NULL,
  `photo_bilal` varchar(255) DEFAULT NULL,
  `photo_muadzin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jumatan`
--

INSERT INTO `jumatan` (`id`, `tgl`, `khotib`, `bilal`, `muadzin`, `photo_khotib`, `photo_bilal`, `photo_muadzin`) VALUES
(1, '2024-08-02', 'Ustadz Ali', 'Fahri', 'Ahmad', 'ali.jpg', 'fahri.jpg', 'ahmad.jpg'),
(2, '2024-08-09', 'Ustadz Ali', 'Fahri', 'Ahmad', 'ali.jpg', 'fahri.jpg', 'ahmad.jpg'),
(3, '2024-08-16', 'Ustadz Budi', 'Hasan', 'Imron', 'budi.jpg', 'hasan.jpg', 'imron.jpg'),
(4, '2024-08-23', 'Ustadz Candra', 'Irfan', 'Jamal', 'candra.jpg', 'irfan.jpg', 'jamal.jpg'),
(5, '2024-08-30', 'Ustadz Deni', 'Khoirul', 'Lutfi', 'deni.jpg', 'khoirul.jpg', 'lutfi.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kajian`
--

CREATE TABLE `kajian` (
  `pekan` int(11) DEFAULT NULL,
  `ustadz` varchar(100) DEFAULT NULL,
  `tema` text DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kajian`
--

INSERT INTO `kajian` (`pekan`, `ustadz`, `tema`, `catatan`) VALUES
(1, 'Ustadz Zainuddin', 'Fiqih Ibadah', 'ustadz1.jpg'),
(2, 'Ustadz Yusuf', 'Tafsir Al-Quran', 'ustadz2.jpg'),
(3, 'Ustadz Wahid', 'Sirah Nabawiyah', 'ustadz3.jpg'),
(4, 'Ustadz Vino', 'Akhlak', 'ustadz4.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `motivasi2`
--

CREATE TABLE `motivasi2` (
  `id` int(11) NOT NULL,
  `konten` text DEFAULT NULL,
  `sumber` varchar(255) DEFAULT NULL,
  `catatan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `motivasi2`
--

INSERT INTO `motivasi2` (`id`, `konten`, `sumber`, `catatan`) VALUES
(1, 'Shalat itu adalah tiang agama', 'HR. Baihaqi', 'shubuh'),
(2, 'Kebersihan itu sebagian dari iman', 'HR. Muslim', 'dhuhur'),
(3, 'Menuntut ilmu itu wajib atas setiap muslim', 'HR. Ibnu Majah', 'ashar'),
(4, 'Senyummu di hadapan saudaramu adalah sedekah', 'HR. Tirmidzi', 'maghrib'),
(5, 'Surga itu di bawah telapak kaki ibu', 'HR. Ahmad', 'isya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `organisasi`
--

CREATE TABLE `organisasi` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `bank` varchar(50) DEFAULT NULL,
  `norek` varchar(50) DEFAULT NULL,
  `anrek` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `organisasi`
--

INSERT INTO `organisasi` (`id`, `nama`, `alamat`, `telp`, `bank`, `norek`, `anrek`) VALUES
(1632, 'Masjid Al-Iman', 'Jl. Contoh No. 123', '08123456789', 'Bank XYZ', '1234567890', 'Yayasan Masjid Al-Iman');

-- --------------------------------------------------------

--
-- Struktur dari tabel `setup_tv`
--

CREATE TABLE `setup_tv` (
  `jeda_page` int(11) DEFAULT NULL,
  `bg_image_cover` varchar(255) DEFAULT NULL,
  `awal_ramadhan` datetime DEFAULT NULL,
  `jeda_iqomat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `setup_tv`
--

INSERT INTO `setup_tv` (`jeda_page`, `bg_image_cover`, `awal_ramadhan`, `jeda_iqomat`) VALUES
(30, 'default_bg.jpg', '2024-03-11 00:00:00', 600);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `hadits_jumat`
--
ALTER TABLE `hadits_jumat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jumatan`
--
ALTER TABLE `jumatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `motivasi2`
--
ALTER TABLE `motivasi2`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `organisasi`
--
ALTER TABLE `organisasi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `hadits_jumat`
--
ALTER TABLE `hadits_jumat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `jumatan`
--
ALTER TABLE `jumatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `motivasi2`
--
ALTER TABLE `motivasi2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
