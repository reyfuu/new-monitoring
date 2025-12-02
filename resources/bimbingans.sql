-- phpMyAdmin SQL Dump
-- version 5.2.3-1.fc42
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 02, 2025 at 01:17 PM
-- Server version: 10.11.11-MariaDB
-- PHP Version: 8.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bimbingan_skripsi_proposal_web`
--

-- --------------------------------------------------------

--
-- Table structure for table `bimbingans`
--

CREATE TABLE `bimbingans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `topik` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `status_domen` varchar(50) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pertemuan_ke` int(11) DEFAULT NULL,
  `isi` varchar(255) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `komentar` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bimbingans`
--

INSERT INTO `bimbingans` (`id`, `topik`, `status`, `status_domen`, `user_id`, `dosen_id`, `tanggal`, `pertemuan_ke`, `isi`, `type`, `komentar`, `created_at`, `updated_at`) VALUES
(1, 'Topik 1', 'completed', 'fix', 2, 3, '2025-10-13', 1, 'topik1', 'proposal', 'OKe mas lanjut kerjain bab 1', '2025-10-12 20:04:17', '2025-10-12 20:06:26'),
(2, 'Topik 2', 'pending', NULL, 2, 3, '2025-10-15', 2, 'tolong idbaca inetrviewnya', 'proposal', NULL, '2025-10-15 00:01:16', '2025-10-15 00:01:16'),
(3, 'Topik 1', 'pending', NULL, 2, 3, '2025-11-13', 3, 'halo', 'proposal', NULL, '2025-11-13 08:28:17', '2025-11-13 08:28:17'),
(4, 'Topik 2', 'disetujui', 'selesai', 10, 3, '2025-11-13', 1, '123', 'proposal', NULL, '2025-11-13 08:34:48', '2025-11-21 21:45:02'),
(5, 'Topik 1', 'pending', NULL, 7, 6, '2025-11-20', 1, 'hello', 'proposal', NULL, '2025-11-19 22:47:06', '2025-11-19 22:47:06'),
(6, 'test', 'disetujui', 'fix', 10, 3, '2025-11-22', 2, 'test', 'proposal', NULL, '2025-11-21 22:24:06', '2025-11-22 01:52:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bimbingans`
--
ALTER TABLE `bimbingans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bimbingans_user_id_foreign` (`user_id`),
  ADD KEY `bimbingans_dosen_id_foreign` (`dosen_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bimbingans`
--
ALTER TABLE `bimbingans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bimbingans`
--
ALTER TABLE `bimbingans`
  ADD CONSTRAINT `bimbingans_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bimbingans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
