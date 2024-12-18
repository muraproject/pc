-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Des 2024 pada 03.37
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_timbangan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `created_at`) VALUES
(1, 1, 'LOGIN', '{\"ip\": \"127.0.0.1\", \"user_agent\": \"Mozilla/5.0\"}', '2024-12-10 12:46:49'),
(2, 2, 'WEIGHING_IN', '{\"receipt_id\": \"IN202312100001\", \"total_weight\": 175.75}', '2024-12-10 12:46:49'),
(3, 3, 'WEIGHING_OUT', '{\"receipt_id\": \"OUT202312100002\", \"total_amount\": 403800}', '2024-12-10 12:46:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `buyers`
--

CREATE TABLE `buyers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buyers`
--

INSERT INTO `buyers` (`id`, `name`, `address`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'PT Daur Ulang Indonesia', 'Jl. Industri No. 123, Jakarta', '08123456789', '2024-12-10 13:10:52', '2024-12-10 13:10:52'),
(2, 'CV Plastik Jaya', 'Jl. Raya Bogor No. 45, Bogor', '08234567890', '2024-12-10 13:10:52', '2024-12-10 13:10:52'),
(3, 'UD Recycle Sejahtera', 'Jl. Pemuda No. 67, Bekasi', '08345678901', '2024-12-10 13:10:52', '2024-12-10 13:10:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Plastik', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(2, 'Kertas', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(3, 'Logam', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(4, 'Botol', '2024-12-10 12:46:49', '2024-12-10 12:46:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Plastik HD', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(2, 1, 'Plastik PE', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(3, 1, 'Plastik PP', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(4, 2, 'Kertas HVS', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(5, 2, 'Kardus', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(6, 2, 'Koran', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(7, 3, 'Besi', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(8, 3, 'Aluminium', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(9, 3, 'Tembaga', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(10, 4, 'Botol Plastik', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(11, 4, 'Botol Kaca', '2024-12-10 12:46:49', '2024-12-10 12:46:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_prices`
--

CREATE TABLE `product_prices` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product_prices`
--

INSERT INTO `product_prices` (`id`, `product_id`, `price`, `created_at`) VALUES
(1, 1, 8000.00, '2024-12-10 12:46:49'),
(2, 2, 10000.00, '2024-12-10 12:46:49'),
(3, 3, 9000.00, '2024-12-10 12:46:49'),
(4, 4, 3000.00, '2024-12-10 12:46:49'),
(5, 5, 2500.00, '2024-12-10 12:46:49'),
(6, 6, 2000.00, '2024-12-10 12:46:49'),
(7, 7, 8000.00, '2024-12-10 12:46:49'),
(8, 8, 15000.00, '2024-12-10 12:46:49'),
(9, 9, 75000.00, '2024-12-10 12:46:49'),
(10, 10, 4000.00, '2024-12-10 12:46:49'),
(11, 11, 1000.00, '2024-12-10 12:46:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `address`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'PT Maju Jaya', 'Jl. Raya No. 123, Jakarta', '08123456789', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(2, 'CV Berkah', 'Jl. Industri No. 45, Bekasi', '08234567890', '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(3, 'UD Sejahtera', 'Jl. Pasar Baru No. 67, Tangerang', '08345678901', '2024-12-10 12:46:49', '2024-12-10 12:46:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `wage_per_kg` decimal(10,2) DEFAULT 0.00,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `role`, `wage_per_kg`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', 0.00, NULL, '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(2, 'operator1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Operator', 'user', 500.00, NULL, '2024-12-10 12:46:49', '2024-12-10 12:46:49'),
(3, 'operator2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Operator', 'user', 500.00, NULL, '2024-12-10 12:46:49', '2024-12-10 12:46:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `weighing_in`
--

CREATE TABLE `weighing_in` (
  `id` int(11) NOT NULL,
  `receipt_id` varchar(50) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `weight` decimal(10,2) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `weighing_in`
--

INSERT INTO `weighing_in` (`id`, `receipt_id`, `supplier_id`, `product_id`, `weight`, `user_id`, `created_at`) VALUES
(1, 'IN202312100001', 1, 1, 100.50, 2, '2024-12-10 12:46:49'),
(2, 'IN202312100001', 1, 2, 75.25, 2, '2024-12-10 12:46:49'),
(3, 'IN202312100002', 2, 4, 200.00, 3, '2024-12-10 12:46:49'),
(4, 'IN202312100002', 2, 5, 150.75, 3, '2024-12-10 12:46:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `weighing_out`
--

CREATE TABLE `weighing_out` (
  `id` int(11) NOT NULL,
  `receipt_id` varchar(50) NOT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `weight` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `weighing_out`
--

INSERT INTO `weighing_out` (`id`, `receipt_id`, `buyer_id`, `product_id`, `weight`, `price`, `user_id`, `created_at`) VALUES
(10, 'OUT20241210221014836', 1, 8, 0.00, 0.00, 1, '2024-12-10 15:10:14'),
(11, 'OUT20241211051114407', 2, 11, 10.21, 0.00, 1, '2024-12-10 22:11:14'),
(12, 'OUT20241211051114407', 2, 10, 10.21, 5.00, 1, '2024-12-10 22:11:14'),
(13, 'OUT20241211051114407', 2, 11, 10.21, 9.00, 1, '2024-12-10 22:11:14'),
(14, 'OUT20241211051114407', 2, 9, 10.21, 0.00, 1, '2024-12-10 22:11:14'),
(15, 'OUT20241211051114407', 2, 9, 10.21, 0.00, 1, '2024-12-10 22:11:14'),
(16, 'OUT20241211051114407', 2, 9, 10.21, 0.00, 1, '2024-12-10 22:11:14');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_activity_logs_date` (`created_at`);

--
-- Indeks untuk tabel `buyers`
--
ALTER TABLE `buyers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeks untuk tabel `product_prices`
--
ALTER TABLE `product_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_product_prices_date` (`created_at`);

--
-- Indeks untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `weighing_in`
--
ALTER TABLE `weighing_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_weighing_in_date` (`created_at`),
  ADD KEY `idx_receipt_id_in` (`receipt_id`);

--
-- Indeks untuk tabel `weighing_out`
--
ALTER TABLE `weighing_out`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_weighing_out_date` (`created_at`),
  ADD KEY `idx_receipt_id_out` (`receipt_id`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `buyers`
--
ALTER TABLE `buyers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `product_prices`
--
ALTER TABLE `product_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `weighing_in`
--
ALTER TABLE `weighing_in`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `weighing_out`
--
ALTER TABLE `weighing_out`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Ketidakleluasaan untuk tabel `product_prices`
--
ALTER TABLE `product_prices`
  ADD CONSTRAINT `product_prices_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ketidakleluasaan untuk tabel `weighing_in`
--
ALTER TABLE `weighing_in`
  ADD CONSTRAINT `weighing_in_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `weighing_in_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `weighing_in_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `weighing_out`
--
ALTER TABLE `weighing_out`
  ADD CONSTRAINT `weighing_out_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `weighing_out_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `weighing_out_ibfk_3` FOREIGN KEY (`buyer_id`) REFERENCES `buyers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
