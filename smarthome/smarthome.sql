-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Feb 2025 pada 09.46
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
-- Database: `smarthome`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `control_points`
--

CREATE TABLE `control_points` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `control_points`
--

INSERT INTO `control_points` (`id`, `name`, `type`, `status`, `last_update`) VALUES
(1, 'Garasi', 'door', 'Terbuka', '2025-02-04 08:09:25'),
(2, 'Pintu Utama', 'door', 'Terkunci', '2025-02-03 08:39:24'),
(3, 'Kamar 1', 'door', 'Terkunci', '2025-02-04 08:09:38'),
(4, 'Kamar 2', 'door', 'Terbuka', '2025-02-04 07:56:59'),
(5, 'Halaman', 'light', 'Nyala', '2025-02-04 08:09:42'),
(6, 'Ruang Keluarga', 'light', 'Nyala', '2025-02-03 08:50:47'),
(7, 'Dapur', 'light', 'Mati', '2025-02-04 07:54:43'),
(8, 'Kamar Mandi', 'light', 'Mati', '2025-02-03 08:39:24'),
(9, 'pintu samping', 'door', 'Terbuka', '2025-02-03 08:50:45'),
(10, 'kamar tamu', 'light', 'Nyala', '2025-02-03 08:51:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `device_name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `logs`
--

INSERT INTO `logs` (`id`, `device_name`, `type`, `old_status`, `new_status`, `timestamp`) VALUES
(1, 'Garasi', 'door', 'Terbuka', 'Terkunci', '2025-02-04 07:47:56'),
(2, 'Garasi', 'door', 'Terkunci', 'Terbuka', '2025-02-04 07:48:41'),
(3, 'Dapur', 'light', 'Nyala', 'Mati', '2025-02-04 07:48:41'),
(4, 'Dapur', 'light', 'Mati', 'Nyala', '2025-02-04 07:52:58'),
(5, 'Garasi', 'door', 'Terkunci', 'Terbuka', '2025-02-04 07:54:21'),
(6, 'Garasi', 'door', 'Terbuka', 'Terkunci', '2025-02-04 07:54:43'),
(7, 'Dapur', 'light', 'Nyala', 'Mati', '2025-02-04 07:54:43'),
(8, 'Garasi', 'door', 'Terkunci', 'Terbuka', '2025-02-04 07:56:55'),
(9, 'Kamar 2', 'door', 'Terkunci', 'Terbuka', '2025-02-04 07:56:59'),
(10, 'Garasi', 'door', 'Terbuka', 'Terkunci', '2025-02-04 08:03:28'),
(11, 'Garasi', 'door', 'Terkunci', 'Terbuka', '2025-02-04 08:09:25'),
(12, 'Kamar 1', 'door', 'Terbuka', 'Terkunci', '2025-02-04 08:09:38'),
(13, 'Halaman', 'light', 'Mati', 'Nyala', '2025-02-04 08:09:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `monitoring_logs`
--

CREATE TABLE `monitoring_logs` (
  `id` int(11) NOT NULL,
  `point_id` int(11) DEFAULT NULL,
  `value` float DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `monitoring_logs`
--

INSERT INTO `monitoring_logs` (`id`, `point_id`, `value`, `status`, `timestamp`) VALUES
(1, 2, 25.5, 'Normal', '2025-02-04 07:47:56'),
(2, 2, 25.5, 'Normal', '2025-02-04 07:48:41'),
(3, 2, 25.5, 'Normal', '2025-02-04 07:52:22'),
(4, 2, 25.5, 'Normal', '2025-02-04 07:52:58'),
(5, 2, 25.5, 'Normal', '2025-02-04 07:54:21'),
(6, 2, 26.5, 'Normal', '2025-02-04 07:54:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `monitoring_points`
--

CREATE TABLE `monitoring_points` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `monitoring_points`
--

INSERT INTO `monitoring_points` (`id`, `name`, `type`, `unit`, `created_at`) VALUES
(1, 'CCTV', 'camera', '', '2025-02-03 08:39:24'),
(2, 'Suhu Ruangan', 'sensor', '°C', '2025-02-03 08:39:24'),
(3, 'Kelembaban Udara', 'sensor', '%', '2025-02-03 08:39:24'),
(4, 'Kelembaban Tanah', 'sensor', '%', '2025-02-03 08:39:24'),
(5, 'Sensor Api', 'sensor', '', '2025-02-03 08:39:24'),
(6, 'Sensor Cahaya', 'sensor', 'lux', '2025-02-03 08:39:24'),
(7, 'sensor cahaya 2', 'sensor', 'lux', '2025-02-03 08:39:51');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `control_points`
--
ALTER TABLE `control_points`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `monitoring_logs`
--
ALTER TABLE `monitoring_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `point_id` (`point_id`);

--
-- Indeks untuk tabel `monitoring_points`
--
ALTER TABLE `monitoring_points`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `control_points`
--
ALTER TABLE `control_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `monitoring_logs`
--
ALTER TABLE `monitoring_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `monitoring_points`
--
ALTER TABLE `monitoring_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `monitoring_logs`
--
ALTER TABLE `monitoring_logs`
  ADD CONSTRAINT `monitoring_logs_ibfk_1` FOREIGN KEY (`point_id`) REFERENCES `monitoring_points` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
