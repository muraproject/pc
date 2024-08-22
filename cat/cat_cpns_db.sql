-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Agu 2024 pada 11.37
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
-- Database: `cat_cpns_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` text NOT NULL,
  `option_b` text NOT NULL,
  `option_c` text NOT NULL,
  `option_d` text NOT NULL,
  `correct_answer` enum('A','B','C','D') NOT NULL,
  `category` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `package_id` int(11) DEFAULT NULL,
  `question_type` enum('TWK','TKP','TIU') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `questions`
--

INSERT INTO `questions` (`id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `category`, `created_at`, `package_id`, `question_type`) VALUES
(1, 'Apa singkatan dari UUD 1945?', 'Undang-Undang Dasar 1945', 'Undangan Undangan Dasar 1945', 'Undang-Undang Daerah 1945', 'Undangan Undangan Daerah 1945', 'A', 'Kewarganegaraan', '2024-08-22 07:23:39', NULL, 'TWK'),
(2, 'Siapa presiden pertama Indonesia?', 'Soekarno', 'Soeharto', 'B.J. Habibie', 'Megawati', 'A', 'Sejarah', '2024-08-22 07:23:39', NULL, 'TWK'),
(3, 'Berapakah hasil dari 7 x 8?', '54', '56', '58', '60', 'B', 'Matematika', '2024-08-22 07:23:39', NULL, 'TWK'),
(4, 'Apa ibukota Indonesia?', 'Jakarta', 'Surabaya', 'Bandung', 'Yogyakarta', 'A', 'Pengetahuan Umum', '2024-08-22 07:23:39', NULL, 'TWK'),
(5, 'Manakah yang bukan merupakan sila Pancasila?', 'Ketuhanan Yang Maha Esa', 'Keadilan Sosial', 'Persatuan Indonesia', 'Kemakmuran Rakyat', 'D', 'Kewarganegaraan', '2024-08-22 07:23:39', NULL, 'TWK'),
(7, 'tanggal lahir panca sila', '1 juni', '1 mei', '17 agustus', '1 juli', 'A', '', '2024-08-22 09:16:01', 1, 'TWK'),
(8, 'aaa', 'aaa', 'aaasdad', 'adad', 'adada', 'B', '', '2024-08-22 09:35:29', 2, 'TWK'),
(9, 'asada', 'fdgdfgh', 'fhfhfh', 'fhfh', 'fhfh', 'D', '', '2024-08-22 09:35:39', 2, 'TWK'),
(10, 'adas', 'srrytry', 'fhfh', 'h5y5', 'ryh5y4', 'D', '', '2024-08-22 09:36:08', 1, 'TWK');

-- --------------------------------------------------------

--
-- Struktur dari tabel `question_packages`
--

CREATE TABLE `question_packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `question_packages`
--

INSERT INTO `question_packages` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'try out 1', 'uji coba', '2024-08-22 09:09:27'),
(2, 'try out 2', '2', '2024-08-22 09:35:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `test_packages`
--

CREATE TABLE `test_packages` (
  `id` int(11) NOT NULL,
  `test_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `test_packages`
--

INSERT INTO `test_packages` (`id`, `test_id`, `package_id`) VALUES
(1, 18, 1),
(2, 19, 1),
(3, 20, 1),
(4, 21, 1),
(5, 22, 1),
(6, 23, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$xQB/tgaIGfBqvXjf8bfB9Oli7YKZ8V1QAMt2BdwgEUVXNfdx3SqUe', 'admin', '2024-08-22 07:23:38'),
(2, 'user1', 'user1@example.com', 'password1', 'user', '2024-08-22 07:23:38'),
(3, 'user2', 'user2@example.com', 'password2', 'user', '2024-08-22 07:23:38'),
(4, 'userku', 'userku@gmail.com', '$2y$10$xQB/tgaIGfBqvXjf8bfB9Oli7YKZ8V1QAMt2BdwgEUVXNfdx3SqUe', 'user', '2024-08-22 07:48:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_answers`
--

CREATE TABLE `user_answers` (
  `id` int(11) NOT NULL,
  `user_test_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_answer` enum('A','B','C','D') NOT NULL,
  `is_correct` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_answers`
--

INSERT INTO `user_answers` (`id`, `user_test_id`, `question_id`, `user_answer`, `is_correct`) VALUES
(1, 1, 1, 'A', 1),
(2, 1, 2, 'A', 1),
(3, 1, 3, 'B', 1),
(4, 1, 4, 'A', 1),
(5, 1, 5, 'C', 0),
(6, 7, 1, 'A', 1),
(7, 7, 3, 'B', 1),
(8, 7, 5, 'D', 1),
(9, 7, 4, 'A', 1),
(10, 7, 2, 'A', 1),
(11, 13, 4, 'A', 1),
(12, 13, 2, 'D', 0),
(13, 13, 5, 'D', 1),
(14, 13, 3, 'A', 0),
(15, 13, 1, 'A', 1),
(16, 14, 5, 'D', 1),
(17, 14, 2, 'C', 0),
(18, 14, 4, 'B', 0),
(19, 14, 3, 'A', 0),
(20, 14, 1, 'B', 0),
(21, 15, 4, 'A', 1),
(22, 15, 3, 'B', 1),
(23, 15, 1, 'A', 1),
(24, 15, 5, 'D', 1),
(25, 15, 2, 'A', 1),
(26, 22, 7, 'A', 0),
(27, 23, 7, 'A', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_tests`
--

CREATE TABLE `user_tests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_time` timestamp NULL DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `is_cancelled` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_tests`
--

INSERT INTO `user_tests` (`id`, `user_id`, `start_time`, `end_time`, `score`, `is_cancelled`) VALUES
(1, 2, '2023-08-22 03:00:00', '2023-08-22 04:00:00', 80, 0),
(7, 4, '2024-08-22 08:01:49', '2024-08-22 08:02:09', 100, 0),
(9, 1, '2024-08-22 08:03:56', NULL, NULL, 0),
(10, 1, '2024-08-22 08:04:04', NULL, NULL, 0),
(13, 4, '2024-08-22 08:51:12', '2024-08-22 08:51:30', 60, 0),
(14, 4, '2024-08-22 08:52:16', '2024-08-22 08:52:32', 20, 0),
(15, 4, '2024-08-22 08:56:14', '2024-08-22 08:57:18', 100, 0),
(17, 4, '2024-08-22 09:17:24', NULL, NULL, 1),
(18, 4, '2024-08-22 09:19:26', NULL, NULL, 1),
(19, 4, '2024-08-22 09:19:32', NULL, NULL, 1),
(20, 4, '2024-08-22 09:24:10', NULL, NULL, 1),
(21, 4, '2024-08-22 09:30:28', NULL, NULL, 1),
(22, 4, '2024-08-22 09:33:00', '2024-08-22 09:33:04', NULL, 0),
(23, 4, '2024-08-22 09:34:43', '2024-08-22 09:34:50', 100, 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indeks untuk tabel `question_packages`
--
ALTER TABLE `question_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `test_packages`
--
ALTER TABLE `test_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_test_id` (`user_test_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indeks untuk tabel `user_tests`
--
ALTER TABLE `user_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `question_packages`
--
ALTER TABLE `question_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `test_packages`
--
ALTER TABLE `test_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `user_answers`
--
ALTER TABLE `user_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `user_tests`
--
ALTER TABLE `user_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `question_packages` (`id`);

--
-- Ketidakleluasaan untuk tabel `test_packages`
--
ALTER TABLE `test_packages`
  ADD CONSTRAINT `test_packages_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `user_tests` (`id`),
  ADD CONSTRAINT `test_packages_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `question_packages` (`id`);

--
-- Ketidakleluasaan untuk tabel `user_answers`
--
ALTER TABLE `user_answers`
  ADD CONSTRAINT `user_answers_ibfk_1` FOREIGN KEY (`user_test_id`) REFERENCES `user_tests` (`id`),
  ADD CONSTRAINT `user_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Ketidakleluasaan untuk tabel `user_tests`
--
ALTER TABLE `user_tests`
  ADD CONSTRAINT `user_tests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
