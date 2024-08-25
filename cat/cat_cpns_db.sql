-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Agu 2024 pada 17.17
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
  `option_e` text DEFAULT NULL,
  `correct_answer` enum('A','B','C','D') NOT NULL,
  `category` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `package_id` int(11) DEFAULT NULL,
  `question_type` enum('TWK','TKP','TIU') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `questions`
--

INSERT INTO `questions` (`id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `option_e`, `correct_answer`, `category`, `created_at`, `package_id`, `question_type`) VALUES
(1, 'Apa singkatan dari UUD 1945?', 'Undang-Undang Dasar 1945', 'Undangan Undangan Dasar 1945', 'Undang-Undang Daerah 1945', 'Undangan Undangan Daerah 1945', NULL, 'A', 'Kewarganegaraan', '2024-08-22 07:23:39', NULL, 'TWK'),
(2, 'Siapa presiden pertama Indonesia?', 'Soekarno', 'Soeharto', 'B.J. Habibie', 'Megawati', NULL, 'A', 'Sejarah', '2024-08-22 07:23:39', NULL, 'TWK'),
(3, 'Berapakah hasil dari 7 x 8?', '54', '56', '58', '60', NULL, 'B', 'Matematika', '2024-08-22 07:23:39', NULL, 'TWK'),
(4, 'Apa ibukota Indonesia?', 'Jakarta', 'Surabaya', 'Bandung', 'Yogyakarta', NULL, 'A', 'Pengetahuan Umum', '2024-08-22 07:23:39', NULL, 'TWK'),
(5, 'Manakah yang bukan merupakan sila Pancasila?', 'Ketuhanan Yang Maha Esa', 'Keadilan Sosial', 'Persatuan Indonesia', 'Kemakmuran Rakyat', NULL, 'D', 'Kewarganegaraan', '2024-08-22 07:23:39', NULL, 'TWK'),
(7, 'tanggal lahir panca sila', '1 juni', '1 mei', '17 agustus', '1 juli', NULL, 'A', '', '2024-08-22 09:16:01', 1, 'TWK'),
(10, 'adas', 'srrytry', 'fhfh', 'h5y5', 'ryh5y4', NULL, 'D', '', '2024-08-22 09:36:08', 1, 'TWK'),
(11, 'Pancasila sebagai dasar negara Indonesia disahkan pada tanggal...', '17 Agustus 1945', '18 Agustus 1945', '1 Juni 1945', '22 Juni 1945', '1 Oktober 1945', 'C', '', '2024-08-25 08:22:22', 3, 'TWK'),
(12, 'Jika 2x + 3y = 20 dan x - y = 4, maka nilai x adalah...', '7', '8', '9', '10', '11', 'B', '', '2024-08-25 08:22:22', 3, 'TIU'),
(13, 'Anda adalah seorang pegawai baru di sebuah instansi pemerintah. Anda melihat rekan kerja senior Anda melakukan tindakan korupsi. Apa yang akan Anda lakukan?', 'Melaporkan langsung ke atasan', 'Mendiskusikan dengan rekan kerja lain', 'Menegur rekan tersebut secara langsung', 'Mengabaikannya karena Anda masih baru', 'Mengumpulkan bukti terlebih dahulu sebelum melaporkan', '', '', '2024-08-25 08:22:22', 3, 'TKP'),
(14, 'Siapakah proklamator kemerdekaan Indonesia?', 'Soekarno dan Hatta', 'Soeharto dan Habibie', 'Soekarno dan Soeharto', 'Hatta dan Habibie', 'Soekarno dan Sjahrir', 'A', '', '2024-08-25 08:22:22', 3, 'TWK'),
(15, 'Sebuah deret aritmatika memiliki suku pertama 3 dan suku kelima 15. Berapakah suku kesepuluh dari deret tersebut?', '27', '30', '33', '36', '39', 'C', '', '2024-08-25 08:22:22', 3, 'TIU'),
(16, 'Pancasila sebagai dasar negara Indonesia disahkan pada tanggal...', '17 Agustus 1945', '18 Agustus 1945', '1 Juni 1945', '22 Juni 1945', '1 Oktober 1945', 'C', '', '2024-08-25 09:02:03', 4, 'TWK'),
(17, 'Jika 2x + 3y = 20 dan x - y = 4, maka nilai x adalah...', '7', '8', '9', '10', '11', 'B', '', '2024-08-25 09:02:03', 4, 'TIU'),
(18, 'Anda melihat rekan kerja melakukan tindakan korupsi. Apa yang akan Anda lakukan?', 'Melaporkan langsung ke atasan', 'Mendiskusikan dengan rekan lain', 'Menegur rekan tersebut langsung', 'Mengabaikannya', 'Mengumpulkan bukti dahulu', '', '', '2024-08-25 09:02:03', 4, 'TKP'),
(19, 'Siapakah proklamator kemerdekaan Indonesia?', 'Soekarno dan Hatta', 'Soeharto dan Habibie', 'Soekarno dan Soeharto', 'Hatta dan Habibie', 'Soekarno dan Sjahrir', 'A', '', '2024-08-25 09:02:03', 4, 'TWK'),
(20, 'Berapa hasil dari 15% dari 80?', '10', '12', '14', '16', '18', 'B', '', '2024-08-25 09:02:03', 4, 'TIU'),
(21, 'UUD 1945 telah mengalami berapa kali amandemen?', '1 kali', '2 kali', '3 kali', '4 kali', '5 kali', 'D', '', '2024-08-25 09:02:03', 4, 'TWK'),
(22, 'Pola bilangan: 2, 6, 12, 20, 30, ... Bilangan selanjutnya adalah...', '40', '41', '42', '43', '44', 'B', '', '2024-08-25 09:02:03', 4, 'TIU'),
(23, 'Jika Anda diberi tugas mendadak saat akan pulang kantor, apa yang Anda lakukan?', 'Menolak dengan sopan', 'Menerima tapi dikerjakan besok', 'Menerima dan lembur', 'Meminta rekan lain menggantikan', 'Pura-pura tidak mendengar', 'C', '', '2024-08-25 09:02:03', 4, 'TKP'),
(24, 'Apa kepanjangan dari BPUPKI?', 'Badan Penyelidik Usaha Persiapan Kemerdekaan Indonesia', 'Badan Pengurus Urusan Persiapan Kemerdekaan Indonesia', 'Badan Pelaksana Upaya Persiapan Kemerdekaan Indonesia', 'Badan Pembantu Usaha Persiapan Kemerdekaan Indonesia', 'Badan Pengelola Urusan Persiapan Kemerdekaan Indonesia', 'A', '', '2024-08-25 09:02:03', 4, 'TWK'),
(25, 'Jika sebuah mobil melaju dengan kecepatan 60 km/jam, berapa jam waktu yang dibutuhkan untuk menempuh jarak 270 km?', '3 jam', '3,5 jam', '4 jam', '4,5 jam', '5 jam', 'C', '', '2024-08-25 09:02:03', 4, 'TIU'),
(26, 'Anda menemukan dompet berisi uang dan identitas pemilik di jalan. Apa yang Anda lakukan?', 'Mengambil uangnya saja', 'Mengembalikan ke polisi', 'Mencoba menghubungi pemiliknya', 'Mengabaikannya', 'Memasang pengumuman di media sosial', 'C', '', '2024-08-25 09:02:03', 4, 'TKP'),
(27, 'Siapakah pencipta lagu Indonesia Raya?', 'Kusbini', 'W.R. Supratman', 'Ismail Marzuki', 'C. Simanjuntak', 'Ibu Sud', 'B', '', '2024-08-25 09:02:03', 4, 'TWK'),
(28, 'Jika A + B = C, A + C = 10, dan B + C = 13, maka nilai A adalah...', '3', '4', '5', '6', '7', 'C', '', '2024-08-25 09:02:03', 4, 'TIU'),
(29, 'Atasan Anda memberi tugas yang menurut Anda tidak sesuai dengan job desc. Apa yang Anda lakukan?', 'Menolak tugas tersebut', 'Menerima tapi tidak mengerjakannya', 'Menerima dan mendiskusikannya', 'Menerima tapi mengeluh ke rekan', 'Menyuruh bawahan mengerjakan', 'C', '', '2024-08-25 09:02:03', 4, 'TKP'),
(30, 'Pada masa Orde Baru, pemilu pertama kali diadakan pada tahun...', '1965', '1970', '1971', '1975', '1980', 'C', '', '2024-08-25 09:02:03', 4, 'TWK');

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
(3, 'Paket Latihan CPNS 2024', 'Paket soal latihan untuk persiapan tes CPNS tahun 2024', '2024-08-25 08:22:22'),
(4, 'baru', 'baru banget', '2024-08-25 08:57:14');

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
(6, 23, 1),
(7, 24, 1),
(8, 25, 3),
(9, 26, 4);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `remember_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `remember_token`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$xQB/tgaIGfBqvXjf8bfB9Oli7YKZ8V1QAMt2BdwgEUVXNfdx3SqUe', 'admin', '2024-08-22 07:23:38', NULL),
(2, 'user1', 'user1@example.com', 'password1', 'user', '2024-08-22 07:23:38', NULL),
(3, 'user2', 'user2@example.com', 'password2', 'user', '2024-08-22 07:23:38', NULL),
(4, 'userku', 'userku@gmail.com', '$2y$10$xQB/tgaIGfBqvXjf8bfB9Oli7YKZ8V1QAMt2BdwgEUVXNfdx3SqUe', 'user', '2024-08-22 07:48:36', '443401cdb8577c9cb189a7875cd3e674');

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
(27, 23, 7, 'A', 0),
(28, 24, 7, 'C', 0),
(29, 24, 10, 'A', 0),
(30, 25, 11, 'C', 0),
(31, 25, 12, 'A', 0),
(32, 25, 13, '', 0),
(33, 25, 14, 'A', 0),
(34, 25, 15, 'B', 0);

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
(23, 4, '2024-08-22 09:34:43', '2024-08-22 09:34:50', 100, 0),
(24, 4, '2024-08-25 08:18:20', '2024-08-25 08:18:35', 0, 0),
(25, 4, '2024-08-25 08:22:50', '2024-08-25 08:23:55', 40, 0),
(26, 4, '2024-08-25 15:14:42', NULL, NULL, 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `question_packages`
--
ALTER TABLE `question_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `test_packages`
--
ALTER TABLE `test_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `user_answers`
--
ALTER TABLE `user_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `user_tests`
--
ALTER TABLE `user_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
