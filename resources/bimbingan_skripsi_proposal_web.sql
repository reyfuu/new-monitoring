-- phpMyAdmin SQL Dump
-- version 5.2.3-1.fc42
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 02, 2025 at 02:03 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:1;', 1760522054),
('laravel-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1760522054;', 1760522054),
('laravel-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6', 'i:1;', 1764678649),
('laravel-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6:timer', 'i:1764678649;', 1764678649),
('laravel-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1761386058),
('laravel-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1761386058;', 1761386058),
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:57:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:17:\"ViewAny:Bimbingan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:14:\"View:Bimbingan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:16:\"Create:Bimbingan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:16:\"Update:Bimbingan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:16:\"Delete:Bimbingan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:17:\"Restore:Bimbingan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:21:\"ForceDelete:Bimbingan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:24:\"ForceDeleteAny:Bimbingan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:20:\"RestoreAny:Bimbingan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:19:\"Replicate:Bimbingan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:17:\"Reorder:Bimbingan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:23:\"ViewAny:LaporanMingguan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:20:\"View:LaporanMingguan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:22:\"Create:LaporanMingguan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:22:\"Update:LaporanMingguan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:22:\"Delete:LaporanMingguan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:23:\"Restore:LaporanMingguan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:27:\"ForceDelete:LaporanMingguan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:30:\"ForceDeleteAny:LaporanMingguan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:26:\"RestoreAny:LaporanMingguan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:25:\"Replicate:LaporanMingguan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:23:\"Reorder:LaporanMingguan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:15:\"ViewAny:Laporan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:12:\"View:Laporan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:14:\"Create:Laporan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:14:\"Update:Laporan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:14:\"Delete:Laporan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:15:\"Restore:Laporan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:19:\"ForceDelete:Laporan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:22:\"ForceDeleteAny:Laporan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:18:\"RestoreAny:Laporan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:17:\"Replicate:Laporan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:15:\"Reorder:Laporan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:12:\"ViewAny:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:9:\"View:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:11:\"Create:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:11:\"Update:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:11:\"Delete:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:12:\"Restore:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:16:\"ForceDelete:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:19:\"ForceDeleteAny:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:15:\"RestoreAny:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:14:\"Replicate:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:12:\"Reorder:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:12:\"ViewAny:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:9:\"View:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:11:\"Create:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:11:\"Update:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:11:\"Delete:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:12:\"Restore:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:16:\"ForceDelete:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:19:\"ForceDeleteAny:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:15:\"RestoreAny:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:14:\"Replicate:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:12:\"Reorder:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:14:\"View:Dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:19:\"View:DashboardStats\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:11:\"super_admin\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"mahasiswa\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:5:\"dosen\";s:1:\"c\";s:3:\"web\";}}}', 1764764990);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporans`
--

CREATE TABLE `laporans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(150) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_berakhir` date DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dosen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `status_dosen` varchar(50) DEFAULT NULL,
  `type` enum('proposal','magang','skripsi') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporans`
--

INSERT INTO `laporans` (`id`, `judul`, `tanggal_mulai`, `tanggal_berakhir`, `deskripsi`, `mahasiswa_id`, `dosen_id`, `dokumen`, `status`, `status_dosen`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Judul 1', '2025-10-13', NULL, 'Skripsi ', 2, 3, 'laporan-dokumen/Laporan October 2.pdf', 'pending', 'revisi', 'proposal', '2025-10-12 20:11:47', '2025-11-13 09:19:16');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_mingguans`
--

CREATE TABLE `laporan_mingguans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dosen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan_mingguans`
--

INSERT INTO `laporan_mingguans` (`id`, `mahasiswa_id`, `dosen_id`, `isi`, `status`, `created_at`, `updated_at`) VALUES
(4, NULL, NULL, 'https://docs.google.com/document/d/1cAq3qjgfTISPHP4p_LQALMotCNqh6Y1SBl2GzmD9OmE/edit?tab=t.0#heading=h.z0tyivkqe9hf', NULL, '2025-11-16 03:02:34', '2025-11-16 03:02:34');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2010_12_14_083707_create_settings_table', 1),
(5, '2022_05_29_031430_create_notifications_templates_table', 1),
(6, '2022_05_29_031439_create_user_notifications_table', 1),
(7, '2022_05_29_032309_create_user_has_notifications_table', 1),
(8, '2022_05_29_032652_create_user_read_notifications_table', 1),
(9, '2022_06_26_135634_create_template_has_roles_table', 1),
(10, '2022_06_26_155848_create_notifications_logs_table', 1),
(11, '2023_12_55_234403_email_settings', 1),
(12, '2024_11_05_130941_create_notifications_table', 1),
(13, '2024_11_16_031430_update_notifications_templates_table', 1),
(14, '2024_11_18_031430_add_media_if_not_exists_table', 1),
(15, '2025_10_03_181031_create_bimbingans_table', 1),
(16, '2025_10_03_184523_create_permission_tables', 1),
(17, '2025_10_04_164501_create_pertemuans_table', 1),
(18, '2025_10_04_185618_drop_pertemuans_table', 1),
(19, '2025_10_04_190707_add_pertemuan_ke_to_bimbingans_table', 1),
(20, '2025_10_05_120213_add_npm_nidn_to_users_table', 1),
(21, '2025_10_05_123624_create_laporans_table', 1),
(22, '2025_10_05_123720_create_laporan_mingguans_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 7),
(3, 'App\\Models\\User', 9),
(3, 'App\\Models\\User', 10),
(4, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 5),
(4, 'App\\Models\\User', 6);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications_logs`
--

CREATE TABLE `notifications_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` text NOT NULL,
  `description` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'info',
  `provider` varchar(255) NOT NULL DEFAULT 'fcm-api',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications_templates`
--

CREATE TABLE `notifications_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`title`)),
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`body`)),
  `url` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT 'heroicon-o-check-circle',
  `type` varchar(255) DEFAULT 'success',
  `providers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`providers`)),
  `action` varchar(255) DEFAULT 'manual',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'ViewAny:Bimbingan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(2, 'View:Bimbingan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(3, 'Create:Bimbingan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(4, 'Update:Bimbingan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(5, 'Delete:Bimbingan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(6, 'Restore:Bimbingan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(7, 'ForceDelete:Bimbingan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(8, 'ForceDeleteAny:Bimbingan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(9, 'RestoreAny:Bimbingan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(10, 'Replicate:Bimbingan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(11, 'Reorder:Bimbingan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(12, 'ViewAny:LaporanMingguan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(13, 'View:LaporanMingguan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(14, 'Create:LaporanMingguan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(15, 'Update:LaporanMingguan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(16, 'Delete:LaporanMingguan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(17, 'Restore:LaporanMingguan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(18, 'ForceDelete:LaporanMingguan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(19, 'ForceDeleteAny:LaporanMingguan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(20, 'RestoreAny:LaporanMingguan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(21, 'Replicate:LaporanMingguan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(22, 'Reorder:LaporanMingguan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(23, 'ViewAny:Laporan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(24, 'View:Laporan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(25, 'Create:Laporan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(26, 'Update:Laporan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(27, 'Delete:Laporan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(28, 'Restore:Laporan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(29, 'ForceDelete:Laporan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(30, 'ForceDeleteAny:Laporan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(31, 'RestoreAny:Laporan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(32, 'Replicate:Laporan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(33, 'Reorder:Laporan', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(34, 'ViewAny:User', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(35, 'View:User', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(36, 'Create:User', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(37, 'Update:User', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(38, 'Delete:User', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(39, 'Restore:User', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(40, 'ForceDelete:User', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(41, 'ForceDeleteAny:User', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(42, 'RestoreAny:User', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(43, 'Replicate:User', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(44, 'Reorder:User', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(45, 'ViewAny:Role', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(46, 'View:Role', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(47, 'Create:Role', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(48, 'Update:Role', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(49, 'Delete:Role', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(50, 'Restore:Role', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(51, 'ForceDelete:Role', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(52, 'ForceDeleteAny:Role', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(53, 'RestoreAny:Role', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(54, 'Replicate:Role', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(55, 'Reorder:Role', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(56, 'View:Dashboard', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28'),
(57, 'View:DashboardStats', 'web', '2025-10-12 19:43:28', '2025-10-12 19:43:28');

-- --------------------------------------------------------

--
-- Table structure for table `pertemuans`
--

CREATE TABLE `pertemuans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bimbingan_id` bigint(20) UNSIGNED NOT NULL,
  `topik` varchar(255) NOT NULL,
  `isi` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `komentar` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-10-12 19:42:19', '2025-10-12 19:42:19'),
(2, 'super_admin', 'web', '2025-10-12 19:43:27', '2025-10-12 19:43:27'),
(3, 'mahasiswa', 'web', '2025-10-12 19:46:02', '2025-10-12 19:46:02'),
(4, 'dosen', 'web', '2025-10-12 19:46:15', '2025-10-12 19:46:15');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(11, 1),
(11, 2),
(12, 1),
(12, 2),
(12, 3),
(12, 4),
(13, 1),
(13, 2),
(13, 3),
(13, 4),
(14, 1),
(14, 2),
(14, 3),
(14, 4),
(15, 1),
(15, 2),
(15, 3),
(15, 4),
(16, 1),
(16, 2),
(17, 1),
(17, 2),
(18, 1),
(18, 2),
(19, 1),
(19, 2),
(20, 1),
(20, 2),
(21, 1),
(21, 2),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(23, 3),
(23, 4),
(24, 1),
(24, 2),
(24, 3),
(24, 4),
(25, 1),
(25, 2),
(25, 3),
(26, 1),
(26, 2),
(26, 3),
(26, 4),
(27, 1),
(27, 2),
(27, 3),
(28, 1),
(28, 2),
(29, 1),
(29, 2),
(30, 1),
(30, 2),
(31, 1),
(31, 2),
(32, 1),
(32, 2),
(33, 1),
(33, 2),
(34, 1),
(34, 2),
(34, 4),
(35, 1),
(35, 2),
(35, 4),
(36, 1),
(36, 2),
(36, 4),
(37, 1),
(37, 2),
(37, 4),
(38, 1),
(38, 2),
(39, 1),
(39, 2),
(40, 1),
(40, 2),
(41, 1),
(41, 2),
(42, 1),
(42, 2),
(43, 1),
(43, 2),
(44, 1),
(44, 2),
(45, 1),
(45, 2),
(46, 1),
(46, 2),
(47, 1),
(47, 2),
(48, 1),
(48, 2),
(49, 1),
(49, 2),
(50, 1),
(50, 2),
(51, 1),
(51, 2),
(52, 1),
(52, 2),
(53, 1),
(53, 2),
(54, 1),
(54, 2),
(55, 1),
(55, 2),
(56, 1),
(56, 2),
(57, 1),
(57, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2boyjrSCmNo45w8T7K6q5CXodELuHz20Vp1tVIpO', 10, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiSHVpNnRQYkh3enRTR1lhTk9YNEY2QmNPcUhyVlh4dVRKOVBUbk9nbiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDEvbGFwb3Jhbi1taW5nZ3VhbnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMDtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJElxU2oyZDBHT3RncUFwNmZtTWNPMHVoUXhGY2czMFNTcWFuWjdTUG1Tc3VIRWk1WkJXd3FLIjtzOjY6InRhYmxlcyI7YToxOntzOjQwOiI1MWE2YTIwZDJlNzliNDI3MjM3NGNkODQ1YmZjZTk2MV9jb2x1bW5zIjthOjM6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo1OiJ0b3BpayI7czo1OiJsYWJlbCI7czo1OiJ0b3BpayI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MzoiaXNpIjtzOjU6ImxhYmVsIjtzOjEwOiJJc2kgLyBMaW5rIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo2OiJzdGF0dXMiO3M6NToibGFiZWwiO3M6NjoiU3RhdHVzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX19fQ==', 1764680785);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT 0,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `group`, `name`, `locked`, `payload`, `created_at`, `updated_at`) VALUES
(1, 'email', 'mail_mailer', 0, '\"smtp\"', '2025-10-11 23:33:33', '2025-10-11 23:33:33'),
(2, 'email', 'mail_host', 0, '\"0.0.0.0\"', '2025-10-11 23:33:33', '2025-10-11 23:33:33'),
(3, 'email', 'mail_port', 0, '\"1025\"', '2025-10-11 23:33:33', '2025-10-11 23:33:33'),
(4, 'email', 'mail_username', 0, '\"\"', '2025-10-11 23:33:33', '2025-10-11 23:33:33'),
(5, 'email', 'mail_password', 0, '\"\"', '2025-10-11 23:33:33', '2025-10-11 23:33:33'),
(6, 'email', 'mail_encryption', 0, '\"\"', '2025-10-11 23:33:33', '2025-10-11 23:33:33'),
(7, 'email', 'mail_from_address', 0, '\"hello@example.com\"', '2025-10-11 23:33:33', '2025-10-11 23:33:33'),
(8, 'email', 'mail_from_name', 0, '\"3x1\"', '2025-10-11 23:33:33', '2025-10-11 23:33:33');

-- --------------------------------------------------------

--
-- Table structure for table `template_has_roles`
--

CREATE TABLE `template_has_roles` (
  `template_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `npm` varchar(20) DEFAULT NULL,
  `nidn` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `angkatan` varchar(10) DEFAULT NULL,
  `dosen_pembimbing_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `npm`, `nidn`, `status`, `angkatan`, `dosen_pembimbing_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$12$mCCs88U0iTzB5rDRKNxStu51TEvtBKw2g6akRDWA2okyrlbZ6ADa.', NULL, NULL, 'active', NULL, NULL, NULL, '2025-10-12 19:34:11', '2025-10-12 19:34:11'),
(2, 'Alvinus Yodi', 'alvinusyodi@example.com', '$2y$12$yfZD36Cx8d6V4mRPEVpxGO6oy/BBYimROObbfPYha49XblExawLPe', '203400010', NULL, 'active', '2020', 3, 'tR69GBrQC9ugXKIxPnyoVFei5eEh02FI3i8zHeAqS6JSsXCbhIsFIiqPv4Rz', '2025-10-12 19:54:42', '2025-10-12 19:58:18'),
(3, 'ryan', 'ryan@example.com', '$2y$12$elTqdw5FDVwUKwjIBXfTAeHq/CFnQ6pKMtZNSWKhsIfv7re3GVM/y', NULL, '12345', 'active', NULL, NULL, NULL, '2025-10-12 19:57:40', '2025-10-13 03:03:35'),
(4, 'andi prasetyo', 'andi@gmail.com', '$2y$12$zokj8559LLK9r4h6gCzzzur19BF71syQzeMeR0HHEpI/ZX6e7ND7C', 'andi prasetyo', NULL, 'active', '2020', NULL, NULL, '2025-10-15 00:04:02', '2025-10-15 00:04:02'),
(5, 'Ir bambang sutopo', 'bambang@gmail.com', '$2y$12$8J9tnIEXo/WDIdBzmCwuzutks3W24tK74qwlowFdloG.0lIRQ.wSi', NULL, NULL, 'active', NULL, NULL, NULL, '2025-10-15 00:35:54', '2025-10-15 00:35:54'),
(6, 'Dwi anggara', 'dwi@gmail.com', '$2y$12$h4.j13rl2.GuKoRpXn.BquzPEbQC0VpNM/BQDEAzNR8alcYsZEBGa', NULL, NULL, 'active', NULL, NULL, NULL, '2025-10-15 00:36:37', '2025-10-15 00:36:37'),
(7, 'audi test', 'audi@gmail.com', '$2y$12$Cfm8vrtrk/IcwcpVOkM1peSi8g/D.zH1sjmAoT9O.BCU1YtiFnWgu', '12e2eqwswd', NULL, 'active', '2020', 6, NULL, '2025-10-20 01:40:03', '2025-10-20 01:40:03'),
(8, 'audi', 'andii@gmail.com', '$2y$12$ZIx1EhN50OnwAVjOKbgKQuhqxr5OBs/Q/TSVHFGlg/3vSS.X4HiSW', NULL, NULL, 'active', NULL, NULL, NULL, '2025-10-25 02:52:56', '2025-10-25 02:52:56'),
(9, 'budi', 'budi@gmail.com', '$2y$12$UEEzmlnNoPun6sfo1Ix7F.EtUCUB3cO6Go.CGooGXDxM6E80nCCLC', '12e2eqwsw2', NULL, 'active', '2020', 3, NULL, '2025-10-29 22:55:36', '2025-10-29 22:55:36'),
(10, 'dudu', 'dudu@gmail.com', '$2y$12$IqSj2d0GOtgqAp6fmMcO0uhQxFcg30SSqanZ7SPmSsuHEi5ZBWwqK', '133242314321421', NULL, 'active', '2024', 3, NULL, '2025-11-13 08:31:05', '2025-11-13 08:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `user_has_notifications`
--

CREATE TABLE `user_has_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(255) DEFAULT 'pusher',
  `provider_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `template_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` text NOT NULL,
  `description` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT 'heroicon-o-check-circle',
  `type` varchar(255) DEFAULT 'success',
  `privacy` varchar(255) DEFAULT 'public',
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_read_notifications`
--

CREATE TABLE `user_read_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `notification_id` bigint(20) UNSIGNED NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `open` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporans`
--
ALTER TABLE `laporans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporans_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `laporans_dosen_id_foreign` (`dosen_id`);

--
-- Indexes for table `laporan_mingguans`
--
ALTER TABLE `laporan_mingguans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `dosen_id` (`dosen_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `notifications_logs`
--
ALTER TABLE `notifications_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications_templates`
--
ALTER TABLE `notifications_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notifications_templates_key_unique` (`key`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `pertemuans`
--
ALTER TABLE `pertemuans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pertemuans_bimbingan_id_foreign` (`bimbingan_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_group_name_unique` (`group`,`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_npm_unique` (`npm`),
  ADD UNIQUE KEY `users_nidn_unique` (`nidn`),
  ADD KEY `users_dosen_pembimbing_id_foreign` (`dosen_pembimbing_id`);

--
-- Indexes for table `user_has_notifications`
--
ALTER TABLE `user_has_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_notifications_template_id_foreign` (`template_id`),
  ADD KEY `user_notifications_created_by_foreign` (`created_by`);

--
-- Indexes for table `user_read_notifications`
--
ALTER TABLE `user_read_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_read_notifications_notification_id_foreign` (`notification_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bimbingans`
--
ALTER TABLE `bimbingans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporans`
--
ALTER TABLE `laporans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `laporan_mingguans`
--
ALTER TABLE `laporan_mingguans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `notifications_logs`
--
ALTER TABLE `notifications_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications_templates`
--
ALTER TABLE `notifications_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `pertemuans`
--
ALTER TABLE `pertemuans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_has_notifications`
--
ALTER TABLE `user_has_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_read_notifications`
--
ALTER TABLE `user_read_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bimbingans`
--
ALTER TABLE `bimbingans`
  ADD CONSTRAINT `bimbingans_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bimbingans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `laporans`
--
ALTER TABLE `laporans`
  ADD CONSTRAINT `laporans_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `laporans_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `laporan_mingguans`
--
ALTER TABLE `laporan_mingguans`
  ADD CONSTRAINT `dosen_id` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `mahasiswa_id` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pertemuans`
--
ALTER TABLE `pertemuans`
  ADD CONSTRAINT `pertemuans_bimbingan_id_foreign` FOREIGN KEY (`bimbingan_id`) REFERENCES `bimbingans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_dosen_pembimbing_id_foreign` FOREIGN KEY (`dosen_pembimbing_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `user_notifications_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_notifications_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `notifications_templates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_read_notifications`
--
ALTER TABLE `user_read_notifications`
  ADD CONSTRAINT `user_read_notifications_notification_id_foreign` FOREIGN KEY (`notification_id`) REFERENCES `user_notifications` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
