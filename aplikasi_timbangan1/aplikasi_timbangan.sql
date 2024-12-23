-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Des 2024 pada 09.18
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
-- Database: `aplikasi_timbangan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `harga`
--

CREATE TABLE `harga` (
  `id` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `harga`
--

INSERT INTO `harga` (`id`, `id_produk`, `harga`, `tanggal`) VALUES
(2, 2, 15000.00, '2024-10-03 14:31:27'),
(4, 4, 25000.00, '2024-10-03 14:31:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orang`
--

CREATE TABLE `orang` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orang`
--

INSERT INTO `orang` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(2, 'haji oman', '2024-10-20 08:29:20', '2024-10-20 08:45:13'),
(3, 'haji sabar', '2024-10-20 08:48:32', '2024-10-20 08:48:32'),
(4, 'haji husain', '2024-10-20 08:48:40', '2024-10-20 08:56:49'),
(5, 'haji tono', '2024-10-20 08:49:30', '2024-10-20 08:49:30'),
(6, 'haji isam', '2024-10-20 08:51:49', '2024-10-20 08:51:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(2, 'gula1', '2024-10-03 14:31:27', '2024-10-20 08:57:03'),
(4, 'Kopi', '2024-10-03 14:31:27', '2024-10-03 14:31:27'),
(7, 'beras', '2024-10-20 09:00:15', '2024-10-20 09:00:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `timbangan`
--

CREATE TABLE `timbangan` (
  `id` int(11) NOT NULL,
  `id_kwitansi` varchar(50) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `nilai_timbang` decimal(10,2) NOT NULL,
  `harga` decimal(10,2) DEFAULT 0.00,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `timbangan`
--

INSERT INTO `timbangan` (`id`, `id_kwitansi`, `nama`, `id_produk`, `nilai_timbang`, `harga`, `waktu`) VALUES
(6, 'KW1728032815471', 'hardi', 2, 99.60, 0.00, '0000-00-00 00:00:00'),
(7, 'KW1728032815471', 'hardi', NULL, 99.47, 0.00, '0000-00-00 00:00:00'),
(8, 'KW1728033023521', 'huri', 2, 89.66, 0.00, '0000-00-00 00:00:00'),
(9, 'KW1728033023521', 'huri', NULL, 24.26, 0.00, '0000-00-00 00:00:00'),
(10, 'KW1728034309612', 'hop', 2, 97.36, 1000.00, '2024-10-04 09:31:46'),
(11, 'KW1728083679856', 'uji', 2, 85.20, 171.00, '2024-10-04 23:14:28'),
(12, 'KW1728212685118', 'harmin', 2, 200.25, 503.00, '2024-10-06 11:04:41'),
(20, 'KW1729371708548', 'husain', 2, 0.00, 0.00, '2024-10-19 21:01:38'),
(21, 'KW1729371708548', 'husain', 2, 0.00, 0.00, '2024-10-19 21:01:40'),
(22, 'KW1729372307497', 'hurta', 2, 10.00, 20000.00, '2024-10-19 21:11:30'),
(23, 'KW1729372307497', 'hurta', 2, 10.00, 30000.00, '2024-10-19 21:11:40'),
(24, 'KW1729372307497', 'hurta', 2, 20.00, 50000.00, '2024-10-19 21:11:45'),
(25, 'KW1729374227912', 'siha', 2, 10.04, 5006.00, '2024-10-19 21:43:10'),
(26, 'KW1729374227912', 'siha', 2, 80.00, 5006.00, '2024-10-19 21:43:26'),
(27, 'KW1729374227912', 'siha', 4, 80.00, 6503.00, '2024-10-19 21:43:31'),
(28, 'KW1729374227912', 'siha', NULL, 80.00, 7003.00, '2024-10-19 21:43:37'),
(29, 'KW1729374227912', 'siha', NULL, 80.00, 7003.00, '2024-10-19 21:43:37'),
(30, 'KW1729374227912', 'siha', 2, 80.00, 5006.00, '2024-10-19 21:43:42'),
(31, 'KW1729394329183', 'haji santo', 2, 0.00, 0.00, '2024-10-20 03:18:34'),
(32, 'KW1729394329183', 'haji santo', 2, 0.00, 0.00, '2024-10-20 03:18:37'),
(33, 'KW1729394329183', 'haji santo', 4, 0.00, 0.00, '2024-10-20 03:18:42'),
(34, 'KW1729394329183', 'haji santo', 4, 0.00, 0.00, '2024-10-20 03:18:43'),
(35, 'KW1729394613843', 'haji mamat', 2, 20.09, 5000.00, '2024-10-20 03:23:07'),
(36, 'KW1729394613843', 'haji mamat', 2, 10.00, 5000.00, '2024-10-20 03:23:10'),
(37, 'KW1729394613843', 'haji mamat', 2, 5.00, 5000.00, '2024-10-20 03:23:13'),
(38, 'KW1729394613843', 'haji mamat', 4, 10.00, 3000.00, '2024-10-20 03:23:28'),
(39, 'KW1729394613843', 'haji mamat', 4, 5.00, 3000.00, '2024-10-20 03:23:29'),
(48, 'KW1729412218336', '4', 2, 0.00, 0.00, '2024-10-20 08:16:56'),
(49, 'KW1729412631875', 'Gula', 2, 0.00, 0.00, '2024-10-20 08:23:48'),
(50, 'KW1729412631875', 'Gula', 2, 0.00, 0.00, '2024-10-20 08:23:50'),
(51, 'KW1729413038015', 'haji hamid', 2, 0.00, 0.00, '2024-10-20 08:30:36'),
(52, 'KW1729414847323', 'haji husain', 2, 0.00, 0.00, '2024-10-20 09:00:45'),
(53, 'KW1729414847323', 'haji husain', 2, 0.00, 0.00, '2024-10-20 09:00:45'),
(54, 'KW1729423396307', 'haji isam', 2, 0.00, 0.00, '0000-00-00 00:00:00'),
(55, 'KW1729423396307', 'haji isam', 2, 0.00, 0.00, '0000-00-00 00:00:00'),
(56, 'KW1729423396307', 'haji isam', 2, 0.00, 0.00, '0000-00-00 00:00:00'),
(57, 'KW1729423396307', 'haji isam', 7, 0.00, 0.00, '0000-00-00 00:00:00'),
(58, 'KW1729423441489', 'haji husain', 2, 0.00, 0.00, '0000-00-00 00:00:00'),
(59, 'KW1729423625219', 'haji husain', 7, 0.00, 0.00, '2024-10-20 11:27:02'),
(60, 'KW1729423625219', 'haji husain', 7, 0.00, 0.00, '2024-10-20 11:27:03'),
(69, 'KW1729423916004', 'haji oman', 4, 0.00, 0.00, '2024-10-20 11:31:57'),
(70, 'KW1729423916004', 'haji oman', 2, 0.00, 0.00, '2024-10-20 11:31:57'),
(71, 'KW1729424877475', 'haji husain', 2, 0.00, 0.00, '2024-10-20 11:47:57'),
(72, 'KW1729424877475', 'haji husain', 2, 0.00, 0.00, '2024-10-20 11:47:57'),
(73, 'KW1729424877475', 'haji husain', 7, 0.00, 0.00, '2024-10-20 11:47:57'),
(74, 'KW1729424877475', 'haji husain', 4, 0.00, 0.00, '2024-10-20 11:47:57'),
(75, 'KW1729424877475', 'haji husain', 2, 0.00, 0.00, '2024-10-20 11:47:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('admin','user') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `user_type`, `created_at`) VALUES
(1, 'admin', 'admin', 'admin', '2024-10-23 22:13:28'),
(2, 'user', 'userku2', 'user', '2024-10-23 22:13:28');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `harga`
--
ALTER TABLE `harga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_harga_id_produk` (`id_produk`);

--
-- Indeks untuk tabel `orang`
--
ALTER TABLE `orang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `timbangan`
--
ALTER TABLE `timbangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_timbangan_id_produk` (`id_produk`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `harga`
--
ALTER TABLE `harga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `orang`
--
ALTER TABLE `orang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `timbangan`
--
ALTER TABLE `timbangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `harga`
--
ALTER TABLE `harga`
  ADD CONSTRAINT `harga_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `timbangan`
--
ALTER TABLE `timbangan`
  ADD CONSTRAINT `timbangan_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
