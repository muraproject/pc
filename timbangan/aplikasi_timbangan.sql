-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2024 at 12:22 AM
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
(3, 3, 10000.00, '2024-10-03 14:31:27'),
(4, 4, 25000.00, '2024-10-03 14:31:27');

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
(2, 'Gula', '2024-10-03 14:31:27', '2024-10-03 14:31:27'),
(3, 'Tepung', '2024-10-03 14:31:27', '2024-10-03 14:31:27'),
(4, 'Kopi', '2024-10-03 14:31:27', '2024-10-03 14:31:27');

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
(5, 'kwi', 'hasan', 2, 0.00, 0.00, '2024-10-04 08:05:27'),
(6, 'KW1728032815471', 'hardi', 2, 99.60, 0.00, '0000-00-00 00:00:00'),
(7, 'KW1728032815471', 'hardi', 3, 99.47, 0.00, '0000-00-00 00:00:00'),
(8, 'KW1728033023521', 'huri', 2, 89.66, 0.00, '0000-00-00 00:00:00'),
(9, 'KW1728033023521', 'huri', 3, 24.26, 0.00, '0000-00-00 00:00:00'),
(10, 'KW1728034309612', 'hop', 2, 97.36, 1000.00, '2024-10-04 09:31:46'),
(11, 'KW1728083679856', 'uji', 2, 85.20, 171.00, '2024-10-04 23:14:28'),
(12, 'KW1728212685118', 'harmin', 2, 200.25, 503.00, '2024-10-06 11:04:41'),
(13, 'KW1728219843046', 'Hudi', 2, 0.00, 0.00, '2024-06-10 13:03:53'),
(14, 'KW1728219843046', 'Hudi', 4, 0.00, 0.00, '2024-06-10 13:04:01'),
(15, 'KW1728219900789', 'Yana', 2, 1.75, 50.00, '2024-06-10 13:04:54'),
(16, 'KW1728219900789', 'Yana', 4, 1.75, 500.00, '2024-06-10 13:04:58'),
(17, 'KW1728220100934', 'Cipto', 2, 1.64, 1000.00, '2024-06-10 13:07:43'),
(18, 'KW1728220100934', 'Cipto', 4, 2.37, 2000.00, '2024-06-10 13:08:01'),
(19, 'KW1728220100934', 'Cipto', 3, 2.48, 1500.00, '2024-06-10 13:08:17');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `harga`
--
ALTER TABLE `harga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `timbangan`
--
ALTER TABLE `timbangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
