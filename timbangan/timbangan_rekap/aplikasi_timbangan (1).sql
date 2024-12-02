-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2024 at 04:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `harga`
--

CREATE TABLE `harga` (
  `id` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `harga`
--

INSERT INTO `harga` (`id`, `id_produk`, `harga`, `tanggal`) VALUES
(2, 2, 15000.00, '2024-10-03 14:31:27'),
(4, 4, 25000.00, '2024-10-03 14:31:27');

-- --------------------------------------------------------

--
-- Table structure for table `orang`
--

CREATE TABLE `orang` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orang`
--

INSERT INTO `orang` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(2, 'haji oman', '2024-10-20 08:29:20', '2024-10-20 08:45:13'),
(3, 'haji sabar', '2024-10-20 08:48:32', '2024-10-20 08:48:32'),
(4, 'haji husain', '2024-10-20 08:48:40', '2024-10-20 08:56:49'),
(5, 'haji tono', '2024-10-20 08:49:30', '2024-10-20 08:49:30'),
(6, 'haji isam', '2024-10-20 08:51:49', '2024-10-20 08:51:49');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(2, 'gula1', '2024-10-03 14:31:27', '2024-10-20 08:57:03'),
(4, 'Kopi', '2024-10-03 14:31:27', '2024-10-03 14:31:27'),
(7, 'beras', '2024-10-20 09:00:15', '2024-10-20 09:00:15');

-- --------------------------------------------------------

--
-- Table structure for table `timbangan`
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
-- Dumping data for table `timbangan`
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
-- Table structure for table `tr_activity_log`
--

CREATE TABLE `tr_activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_barang_keluar`
--

CREATE TABLE `tr_barang_keluar` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `berat` decimal(10,2) NOT NULL DEFAULT 0.00,
  `harga_per_kg` decimal(10,2) DEFAULT 0.00,
  `total_harga` decimal(10,2) DEFAULT 0.00,
  `keterangan` text DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_bayar` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_barang_masuk`
--

CREATE TABLE `tr_barang_masuk` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `berat` decimal(10,2) NOT NULL DEFAULT 0.00,
  `harga_per_kg` decimal(10,2) DEFAULT 0.00,
  `total_harga` decimal(10,2) DEFAULT 0.00,
  `keterangan` text DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_bayar` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tr_barang_masuk`
--

INSERT INTO `tr_barang_masuk` (`id`, `supplier_id`, `produk_id`, `berat`, `harga_per_kg`, `total_harga`, `keterangan`, `tanggal`, `status_bayar`) VALUES
(1, 1, 1, 40.04, NULL, 0.00, 'halo', '2024-12-02 14:28:57', 0),
(2, 1, 1, 33.32, NULL, 0.00, '', '2024-12-02 14:32:48', 0),
(3, 1, 1, 1.75, NULL, 0.00, 'Cekk', '2024-12-02 15:26:29', 0),
(4, 1, 1, 1.75, NULL, 0.00, 'Cekk', '2024-12-02 15:26:40', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tr_biaya_tenaga`
--

CREATE TABLE `tr_biaya_tenaga` (
  `id` int(11) NOT NULL,
  `karyawan_id` int(11) DEFAULT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `berat` decimal(10,2) NOT NULL DEFAULT 0.00,
  `biaya_per_kg` decimal(10,2) DEFAULT 0.00,
  `total_biaya` decimal(10,2) DEFAULT 0.00,
  `keterangan` text DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_bayar` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_customer`
--

CREATE TABLE `tr_customer` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `kontak_person` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tr_customer`
--

INSERT INTO `tr_customer` (`id`, `nama`, `alamat`, `telepon`, `kontak_person`, `created_at`) VALUES
(1, 'PT Customer X', 'Jl. Customer No. 1', '08111222333', 'Bob Smith', '2024-12-02 13:27:10'),
(2, 'PT Customer Y', 'Jl. Customer No. 2', '08444555666', 'Alice Johnson', '2024-12-02 13:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `tr_karyawan`
--

CREATE TABLE `tr_karyawan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tr_karyawan`
--

INSERT INTO `tr_karyawan` (`id`, `nama`, `alamat`, `telepon`, `created_at`) VALUES
(1, 'Karyawan 1', 'Jl. Karyawan No. 1', '08123123123', '2024-12-02 13:27:10'),
(2, 'Karyawan 2', 'Jl. Karyawan No. 2', '08456456456', '2024-12-02 13:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `tr_kategori`
--

CREATE TABLE `tr_kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tr_kategori`
--

INSERT INTO `tr_kategori` (`id`, `nama`, `keterangan`, `created_at`) VALUES
(1, 'Biji Plastik', 'Kategori untuk biji plastik', '2024-12-02 13:27:10'),
(2, 'Pewarna', 'Kategori untuk pewarna', '2024-12-02 13:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `tr_produk`
--

CREATE TABLE `tr_produk` (
  `id` int(11) NOT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tr_produk`
--

INSERT INTO `tr_produk` (`id`, `kategori_id`, `nama`, `keterangan`, `created_at`) VALUES
(1, 1, 'Biji PE', 'Biji plastik PE', '2024-12-02 13:27:10'),
(2, 1, 'Biji PP', 'Biji plastik PP', '2024-12-02 13:27:10'),
(3, 2, 'Pewarna Merah', 'Pewarna plastik merah', '2024-12-02 13:27:10'),
(4, 2, 'Pewarna Biru', 'Pewarna plastik biru', '2024-12-02 13:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `tr_supplier`
--

CREATE TABLE `tr_supplier` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `kontak_person` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tr_supplier`
--

INSERT INTO `tr_supplier` (`id`, `nama`, `alamat`, `telepon`, `kontak_person`, `created_at`) VALUES
(1, 'PT Supplier A', 'Jl. Supplier No. 1', '08123456789', 'John Doe', '2024-12-02 13:27:10'),
(2, 'PT Supplier B', 'Jl. Supplier No. 2', '08987654321', 'Jane Doe', '2024-12-02 13:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `tr_users`
--

CREATE TABLE `tr_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('admin','user') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `user_type`, `created_at`) VALUES
(1, 'admin', 'admin', 'admin', '2024-10-23 22:13:28'),
(2, 'user', 'userku2', 'user', '2024-10-23 22:13:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `harga`
--
ALTER TABLE `harga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_harga_id_produk` (`id_produk`);

--
-- Indexes for table `orang`
--
ALTER TABLE `orang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timbangan`
--
ALTER TABLE `timbangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_timbangan_id_produk` (`id_produk`);

--
-- Indexes for table `tr_activity_log`
--
ALTER TABLE `tr_activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_activity_log_user` (`user_id`);

--
-- Indexes for table `tr_barang_keluar`
--
ALTER TABLE `tr_barang_keluar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `produk_id` (`produk_id`),
  ADD KEY `idx_barang_keluar_tanggal` (`tanggal`);

--
-- Indexes for table `tr_barang_masuk`
--
ALTER TABLE `tr_barang_masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `produk_id` (`produk_id`),
  ADD KEY `idx_barang_masuk_tanggal` (`tanggal`);

--
-- Indexes for table `tr_biaya_tenaga`
--
ALTER TABLE `tr_biaya_tenaga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `karyawan_id` (`karyawan_id`),
  ADD KEY `produk_id` (`produk_id`),
  ADD KEY `idx_biaya_tenaga_tanggal` (`tanggal`);

--
-- Indexes for table `tr_customer`
--
ALTER TABLE `tr_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_karyawan`
--
ALTER TABLE `tr_karyawan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_kategori`
--
ALTER TABLE `tr_kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_produk`
--
ALTER TABLE `tr_produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_produk_kategori` (`kategori_id`);

--
-- Indexes for table `tr_supplier`
--
ALTER TABLE `tr_supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_users`
--
ALTER TABLE `tr_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `harga`
--
ALTER TABLE `harga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orang`
--
ALTER TABLE `orang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `timbangan`
--
ALTER TABLE `timbangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `tr_activity_log`
--
ALTER TABLE `tr_activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tr_barang_keluar`
--
ALTER TABLE `tr_barang_keluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tr_barang_masuk`
--
ALTER TABLE `tr_barang_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tr_biaya_tenaga`
--
ALTER TABLE `tr_biaya_tenaga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tr_customer`
--
ALTER TABLE `tr_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tr_karyawan`
--
ALTER TABLE `tr_karyawan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tr_kategori`
--
ALTER TABLE `tr_kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tr_produk`
--
ALTER TABLE `tr_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tr_supplier`
--
ALTER TABLE `tr_supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tr_users`
--
ALTER TABLE `tr_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `harga`
--
ALTER TABLE `harga`
  ADD CONSTRAINT `harga_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `timbangan`
--
ALTER TABLE `timbangan`
  ADD CONSTRAINT `timbangan_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tr_activity_log`
--
ALTER TABLE `tr_activity_log`
  ADD CONSTRAINT `tr_activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tr_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tr_barang_keluar`
--
ALTER TABLE `tr_barang_keluar`
  ADD CONSTRAINT `tr_barang_keluar_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `tr_customer` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tr_barang_keluar_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `tr_produk` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tr_barang_masuk`
--
ALTER TABLE `tr_barang_masuk`
  ADD CONSTRAINT `tr_barang_masuk_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `tr_supplier` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tr_barang_masuk_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `tr_produk` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tr_biaya_tenaga`
--
ALTER TABLE `tr_biaya_tenaga`
  ADD CONSTRAINT `tr_biaya_tenaga_ibfk_1` FOREIGN KEY (`karyawan_id`) REFERENCES `tr_karyawan` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tr_biaya_tenaga_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `tr_produk` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tr_produk`
--
ALTER TABLE `tr_produk`
  ADD CONSTRAINT `tr_produk_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `tr_kategori` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
