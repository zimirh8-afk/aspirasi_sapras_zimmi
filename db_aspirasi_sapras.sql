-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2026 at 08:32 AM
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
-- Database: `db_aspirasi_sapras`
--

-- --------------------------------------------------------

--
-- Table structure for table `aspirasi`
--

CREATE TABLE `aspirasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL DEFAULT '',
  `lokasi` varchar(200) NOT NULL DEFAULT '',
  `deskripsi` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `isi_aspirasi` text NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('Diajukan','Diproses','Selesai','Ditolak') DEFAULT 'Diajukan',
  `progress` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aspirasi`
--

INSERT INTO `aspirasi` (`id`, `user_id`, `kategori_id`, `judul`, `lokasi`, `deskripsi`, `foto`, `isi_aspirasi`, `tanggal`, `status`, `progress`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '', '', '', NULL, 'Toilet di lantai 2 bocor dan menggenang ke koridor, mohon segera diperbaiki.', '2026-08-01', 'Diproses', 50, '2026-08-26 11:39:11', '2026-10-15 13:27:05'),
(2, 2, 1, '', '', '', NULL, 'Atap kelas XII IPA 1 retak, saat hujan air masuk ke dalam kelas.', '2026-08-03', '', 0, '2026-08-26 11:39:11', '2026-08-26 11:39:11'),
(3, 3, 4, '', '', '', NULL, 'Terdapat 5 kursi di kelas XI IPS 2 yang kakinya patah, berbahaya untuk siswa.', '2026-08-05', 'Selesai', 0, '2026-08-26 11:39:11', '2026-08-26 11:39:11'),
(4, 4, 2, '', '', '', NULL, 'Lampu bagian kiri aula utama tidak menyala sudah 3 hari.', '2026-08-07', 'Ditolak', 0, '2026-08-26 11:39:11', '2026-11-03 12:02:15'),
(5, 1, 5, '', '', '', NULL, 'Sebanyak 4 unit komputer di lab komputer 1 tidak bisa dinyalakan.', '2026-08-10', 'Ditolak', 0, '2026-08-26 11:39:11', '2026-11-03 12:02:02'),
(6, 2, 3, '', '', '', NULL, 'Wastafel di kantin mampet, air tidak mengalir sama sekali.', '2026-08-12', 'Ditolak', 0, '2026-08-26 11:39:11', '2026-11-03 12:01:48'),
(7, 3, 1, '', '', '', NULL, 'Cat dinding kelas X A mengelupas di beberapa bagian tembok.', '2026-08-15', 'Diproses', 0, '2026-08-26 11:39:11', '2026-08-26 11:39:11'),
(8, 2, 3, 'toilet bau', 'toilet', 'bau anjir', 'pengaduan_6a954dcecd14a0.27205904.png', '', '0000-00-00', '', 100, '2026-08-31 09:47:58', '2026-09-23 01:14:54'),
(11, 9, 5, 'hii', 'dimana ajalah', 'hi aku almairaaaa', 'pengaduan_6ab10310381667.39363931.jpeg', '', '0000-00-00', 'Diproses', 25, '2026-09-21 10:12:32', '2026-10-15 11:30:24'),
(15, 11, 5, 'wifi ngeleg', 'RPL 4', 'elek', 'pengaduan_6ab338d205af35.52729624.jpeg', '', '0000-00-00', 'Diproses', 25, '2026-09-23 02:26:26', '2026-10-15 11:38:28'),
(16, 4, 1, 'tembok perlu di cat ulang', 'XI RPL 2', 'jadi jelek ga enak di pandang', 'pengaduan_6ad0bba69a15a2.88666656.jpeg', '', '0000-00-00', 'Diproses', 50, '2026-10-15 11:40:22', '2026-10-15 13:35:04'),
(17, 4, 2, 'minta uang min', 'xi rpl 3', 'minta uang buat bensin', 'pengaduan_6ad0d5aaba8942.25569069.jpeg', '', '0000-00-00', 'Ditolak', 0, '2026-10-15 13:31:22', '2026-10-15 13:31:59'),
(18, 13, 5, 'mintol min', 'Di Tepa', 'Pengen Jajan', 'pengaduan_6ad0d7812fc8f2.01499872.jpeg', '', '0000-00-00', 'Selesai', 100, '2026-10-15 13:39:13', '2026-10-15 13:40:07'),
(20, 4, 2, 'toilet bau', 'XI RPL 2', 'testing', 'pengaduan_6ae8a70512b0a9.03820938.jpg', '', '0000-00-00', 'Selesai', 100, '2026-11-02 15:06:45', '2026-11-02 15:07:18'),
(22, 4, 5, 'minta uang min', 'XI RPL 2', 'testing', 'pengaduan_6ae9cbcd272b19.55691550.jpg', '', '0000-00-00', 'Ditolak', 0, '2026-11-03 11:55:57', '2026-11-03 12:00:41'),
(23, 4, 4, 'meja ancur', 'xi rpl 3', 'test', 'pengaduan_6ae9cf410d6843.92775295.jpeg', '', '0000-00-00', 'Ditolak', 0, '2026-11-03 12:10:41', '2026-11-03 12:11:55'),
(24, 14, 4, 'meja ancur', 'XI RPL 4', 'tesssssssssstttttttttt', NULL, '', '0000-00-00', 'Ditolak', 0, '2026-11-03 12:14:13', '2026-11-03 13:26:42'),
(25, 14, 1, 'dmn aja', 'XI RPL 4', 'wleeeee', NULL, '', '0000-00-00', 'Selesai', 100, '2026-11-03 13:32:32', '2026-11-03 13:32:55'),
(26, 14, 4, 'rmh', 'Di Tepa', 'pucing', NULL, '', '0000-00-00', 'Selesai', 100, '2026-11-03 13:38:42', '2026-11-03 13:39:09');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `pengaduan_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `isi` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `pengaduan_id`, `admin_id`, `isi`, `created_at`) VALUES
(1, 4, 1, 'Harap bersabar', '2026-08-26 11:44:37'),
(2, 8, 1, 'love you', '2026-08-31 09:49:17'),
(3, 11, 1, 'siapp', '2026-09-21 14:02:23'),
(4, 11, 1, 'siapp esss', '2026-09-21 14:05:42'),
(5, 11, 1, 'siap kk', '2026-09-21 14:07:43'),
(6, 15, 1, 'nanti di perbaiki', '2026-10-15 11:38:28'),
(7, 16, 1, 'harap bersabar', '2026-10-15 11:41:32'),
(8, 20, 1, 'okkey done', '2026-11-02 15:07:18');

-- --------------------------------------------------------

--
-- Table structure for table `histori_pengaduan`
--

CREATE TABLE `histori_pengaduan` (
  `id` int(11) NOT NULL,
  `pengaduan_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `progress` int(11) DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `histori_pengaduan`
--

INSERT INTO `histori_pengaduan` (`id`, `pengaduan_id`, `status`, `progress`, `keterangan`, `created_at`) VALUES
(1, 1, 'Diproses', 0, '', '2026-08-29 16:26:32'),
(2, 8, 'Diajukan', 0, 'Pengaduan berhasil dibuat oleh siswa.', '2026-08-31 09:47:58'),
(3, 8, 'Diajukan', 100, '', '2026-08-31 09:49:26'),
(4, 1, 'Diproses', 50, '', '2026-08-31 10:55:59'),
(5, 1, 'Diproses', 100, '', '2026-09-15 14:37:35'),
(6, 11, 'Diajukan', 0, 'Pengaduan berhasil dibuat oleh siswa.', '2026-09-21 10:12:32'),
(13, 11, 'Diajukan', 0, 'p', '2026-09-21 14:01:57'),
(14, 11, 'Diajukan', 0, 'siapp', '2026-09-21 14:02:15'),
(15, 11, 'Diajukan', 25, 'baik', '2026-09-21 14:06:10'),
(16, 11, 'Diajukan', 25, 'siap kk', '2026-09-21 14:07:34'),
(17, 8, 'Diajukan', 100, '', '2026-09-23 01:13:21'),
(18, 8, 'Diajukan', 100, '', '2026-09-23 01:13:30'),
(19, 8, 'Selesai', 100, '', '2026-09-23 01:13:35'),
(20, 8, 'Dalam Perbaikan', 100, '', '2026-09-23 01:13:41'),
(21, 8, 'Diproses', 100, '', '2026-09-23 01:14:25'),
(22, 8, 'Diajukan', 100, '', '2026-09-23 01:14:34'),
(23, 8, 'Diproses', 100, '', '2026-09-23 01:14:42'),
(24, 8, 'Ditolak', 100, '', '2026-09-23 01:14:48'),
(25, 8, 'Dalam Perbaikan', 100, '', '2026-09-23 01:14:54'),
(26, 15, 'Diajukan', 0, 'Pengaduan berhasil dibuat oleh siswa.', '2026-09-23 02:26:26'),
(27, 11, 'Diproses', 25, '', '2026-10-15 11:30:24'),
(28, 15, 'Diproses', 25, 'okeyy', '2026-10-15 11:38:28'),
(29, 16, 'Diajukan', 0, 'Pengaduan berhasil dibuat oleh siswa.', '2026-10-15 11:40:22'),
(30, 16, 'Diproses', 25, 'okey siap diperbaiki', '2026-10-15 11:41:32'),
(31, 1, 'Selesai', 100, '', '2026-10-15 13:20:43'),
(32, 1, 'Diproses', 50, '', '2026-10-15 13:27:05'),
(33, 17, 'Diajukan', 0, 'Pengaduan berhasil dibuat oleh siswa.', '2026-10-15 13:31:22'),
(34, 17, 'Ditolak', 0, '', '2026-10-15 13:31:59'),
(35, 16, 'Diproses', 25, '', '2026-10-15 13:34:49'),
(36, 16, 'Selesai', 100, '', '2026-10-15 13:34:58'),
(37, 16, 'Diproses', 50, '', '2026-10-15 13:35:03'),
(38, 16, 'Diproses', 50, '', '2026-10-15 13:35:04'),
(39, 18, 'Diajukan', 0, 'Pengaduan berhasil dibuat oleh siswa.', '2026-10-15 13:39:13'),
(40, 18, 'Diproses', 50, '', '2026-10-15 13:39:56'),
(41, 18, 'Selesai', 100, '', '2026-10-15 13:40:07'),
(43, 20, 'Diajukan', 0, 'Pengaduan berhasil dibuat oleh siswa.', '2026-11-02 15:06:45'),
(44, 20, 'Selesai', 100, '', '2026-11-02 15:07:18'),
(46, 22, 'Diajukan', 0, 'Pengaduan berhasil dibuat oleh siswa.', '2026-11-03 11:55:57'),
(47, 22, 'Ditolak', 0, '', '2026-11-03 12:00:41'),
(48, 6, 'Ditolak', 0, '', '2026-11-03 12:01:48'),
(49, 5, 'Ditolak', 0, '', '2026-11-03 12:02:02'),
(50, 4, 'Ditolak', 0, '', '2026-11-03 12:02:15'),
(51, 23, 'Diajukan', 0, 'Pengaduan berhasil dibuat oleh siswa.', '2026-11-03 12:10:41'),
(52, 23, 'Ditolak', 0, '', '2026-11-03 12:11:55'),
(53, 24, 'Diajukan', 0, 'Pengaduan berhasil dibuat oleh siswa.', '2026-11-03 12:14:13'),
(54, 24, 'Ditolak', 0, '', '2026-11-03 13:26:42'),
(55, 25, 'Diajukan', 0, 'Pengaduan berhasil dibuat oleh siswa.', '2026-11-03 13:32:32'),
(56, 25, 'Selesai', 100, '', '2026-11-03 13:32:55'),
(57, 26, 'Diajukan', 0, 'Pengaduan berhasil dibuat oleh siswa.', '2026-11-03 13:38:42'),
(58, 26, 'Selesai', 100, '', '2026-11-03 13:39:09');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama`, `keterangan`) VALUES
(1, 'Gedung & Bangunan', 'Kerusakan pada struktur gedung, atap, dinding, dan lantai'),
(2, 'Listrik & Elektronik', 'Masalah instalasi listrik, lampu, dan perangkat elektronik'),
(3, 'Toilet & Sanitasi', 'Kerusakan toilet, wastafel, saluran air, dan kebersihan'),
(4, 'Meja & Kursi', 'Kerusakan furniture kelas seperti meja dan kursi siswa'),
(5, 'Komputer & Lab', 'Masalah perangkat komputer dan perlengkapan laboratorium');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pengaduan_id` int(11) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `tipe` enum('pengaduan_baru','status_update','info') DEFAULT 'info',
  `status` enum('belum','sudah') DEFAULT 'belum',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `user_id`, `pengaduan_id`, `judul`, `pesan`, `tipe`, `status`, `created_at`) VALUES
(1, 1, 22, 'Pengaduan Baru', ' membuat pengaduan baru: minta uang min', 'pengaduan_baru', 'sudah', '2026-11-03 11:55:57'),
(2, 4, 22, 'Update Status Pengaduan', 'Maaf, pengaduan Anda ditolak. Silakan cek detail untuk informasi lebih lanjut.', 'status_update', 'belum', '2026-11-03 12:00:41'),
(3, 2, 6, 'Update Status Pengaduan', 'Maaf, pengaduan Anda ditolak. Silakan cek detail untuk informasi lebih lanjut.', 'status_update', 'belum', '2026-11-03 12:01:48'),
(4, 1, 5, 'Update Status Pengaduan', 'Maaf, pengaduan Anda ditolak. Silakan cek detail untuk informasi lebih lanjut.', 'status_update', 'sudah', '2026-11-03 12:02:02'),
(5, 4, 4, 'Update Status Pengaduan', 'Maaf, pengaduan Anda ditolak. Silakan cek detail untuk informasi lebih lanjut.', 'status_update', 'belum', '2026-11-03 12:02:15'),
(6, 1, 23, 'Pengaduan Baru', ' membuat pengaduan baru: meja ancur', 'pengaduan_baru', 'sudah', '2026-11-03 12:10:41'),
(7, 4, 23, 'Update Status Pengaduan', 'Maaf, pengaduan Anda ditolak. Silakan cek detail untuk informasi lebih lanjut.', 'status_update', 'belum', '2026-11-03 12:11:55'),
(8, 1, 24, 'Pengaduan Baru', ' membuat pengaduan baru: meja ancur', 'pengaduan_baru', 'sudah', '2026-11-03 12:14:13'),
(9, 14, 24, 'Update Status Pengaduan', 'Maaf, pengaduan Anda ditolak. Silakan cek detail untuk informasi lebih lanjut.', 'status_update', 'belum', '2026-11-03 13:26:42'),
(10, 1, 25, 'Pengaduan Baru', ' membuat pengaduan baru: dmn aja', 'pengaduan_baru', 'sudah', '2026-11-03 13:32:32'),
(11, 14, 25, 'Update Status Pengaduan', 'Pengaduan Anda telah selesai ditangani.', 'status_update', 'belum', '2026-11-03 13:32:55'),
(12, 16, 25, 'Update Status Pengaduan', 'Pengaduan Anda telah selesai ditangani.', 'status_update', 'sudah', '2026-11-03 13:32:55'),
(13, 1, 26, 'Pengaduan Baru', ' membuat pengaduan baru: rmh', 'pengaduan_baru', 'sudah', '2026-11-03 13:38:42'),
(14, 14, 26, 'Update Status Pengaduan', 'Pengaduan Anda telah selesai ditangani.', 'status_update', 'belum', '2026-11-03 13:39:09'),
(15, 16, 26, 'Update Status Pengaduan', 'Pengaduan Anda telah selesai ditangani.', 'status_update', 'sudah', '2026-11-03 13:39:09');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `kelas` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `id_user`, `nis`, `nama_siswa`, `kelas`) VALUES
(1, 1, '2021001', 'Budi Santoso', 'XII IPA 1'),
(2, 2, '2021002', 'Sari Dewi', 'XI IPS 2'),
(3, 3, '2021003', 'Andi Pratama', 'X A'),
(4, 4, '2021004', 'Rina Agustina', 'XII IPS 1'),
(9, 6, 'NIS6', 'almasum', '-'),
(11, 12, '', 'shodik', ''),
(13, 15, '12345678', 'Almaira', '-'),
(14, 16, '040408', 'Zimmi Rezkyana', '-');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','siswa') NOT NULL,
  `status_akun` enum('pending','aktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `foto`, `username`, `email`, `password`, `role`, `status_akun`, `created_at`, `updated_at`) VALUES
(1, 'Zimmi Rezkyana', 'profil_1_1789994479.jpeg', 'admin', NULL, '$2y$10$KYtt1TKp83zp4hmLBbeOsuj7Dh5lYaEBXYEAOm1h/pk.Kho.DJZUG', 'admin', 'aktif', '2026-08-26 11:19:34', '2026-09-21 12:41:19'),
(2, 'Budi Santoso', NULL, 'budi', NULL, '$2y$10$Q9KupY.DSs.ZECVvu7E3YuyQbDGae6KjZi9KHvgHBA4s4YqGGujXu', 'siswa', 'aktif', '2026-08-26 11:19:34', '2026-08-26 11:32:49'),
(3, 'Sari Dewi', NULL, 'sari', NULL, '$2y$10$2Pi5TYTmcVze9AfptemFxecH5XhYvL7HaOWdgTiTrnLrvcIgmeA5e', 'siswa', 'aktif', '2026-08-26 11:19:34', '2026-08-26 11:32:49'),
(4, 'Andi Pratama', 'profil_4_1793632141.jpg', 'andi', NULL, '$2y$10$SqK3Fj8f/zjH/purqN/l..palDx3up/ul46/IyS/qW9fIJqH2l29G', 'siswa', 'aktif', '2026-08-26 11:19:34', '2026-11-02 15:09:01'),
(6, 'almasum', 'profil_6_1789994426.jpeg', 'almasum', NULL, '$2y$10$02SP4U1IwS3cc68squlDseGIN/csv/FDXww7BMTgeh29Xxy7F8N4K', 'siswa', 'aktif', '2026-08-31 11:08:23', '2026-09-21 14:10:05'),
(8, 'zimrezky', NULL, 'zimrezky', NULL, '$2y$10$ieCrQET7hbIWY8AkqkiGPuIqtU9.wKMp0F.mfdM8djmltpCV0Jfn.', 'siswa', 'aktif', '2026-09-21 10:49:17', '2026-09-21 12:16:05'),
(9, 'riookta', NULL, 'rio', NULL, '$2y$10$JqiJUAM/H3eOy0l9G4Y4mO3fLNvmgOQ9xST/YbPYRsmwZ.Hh5Yq8C', 'siswa', 'aktif', '2026-09-21 12:16:43', '2026-09-21 12:17:09'),
(11, 'shodik', 'profil_11_1790125507.png', 'shodik aja', NULL, '$2y$10$wgzyoxxNhu/wZIaXw44ZAuguDRl0E3oUSYo/d.8xUcE9CPECxG6tW', 'siswa', 'aktif', '2026-09-23 01:03:36', '2026-09-23 01:05:07'),
(12, 'shodik', 'profil_12_1790136538.jpeg', 'sodik', NULL, '$2y$10$7P9DJWPB4A0Q2DVXRZYCKOkzeTBj9wzOjx.5UkBhFrbxwfEApewbS', 'siswa', 'aktif', '2026-09-23 02:25:38', '2026-09-23 04:08:58'),
(14, 'kahfi', NULL, 'kahfi', NULL, '$2y$10$xUP2Vn60w/NWsefTRmSH4Ov4cBnjtYcjZw1wwYWeCjlhzHbOKvHLG', 'siswa', 'aktif', '2026-09-23 05:55:25', '2026-09-23 05:59:58'),
(15, 'Almaira', NULL, 'alma', NULL, '$2y$10$.1DcILjQDp4CI2zYJOCXqeSrqWO4UqvsfWlhzLdvCqB7w3mwIkSsC', 'siswa', 'aktif', '2026-10-15 13:17:02', '2026-10-15 13:17:21'),
(16, 'Zimmi Rezkyana', NULL, 'zimmi', 'zimirh8@gmail.com', '$2y$10$bW0cOOSj4jP3Vr8loYL6e.2dWBtsilLcCu2bpgaY0W8RqoIuV.d8G', 'siswa', 'aktif', '2026-11-03 12:13:13', '2026-11-03 12:13:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_siswa` (`user_id`),
  ADD KEY `id_kategori` (`kategori_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengaduan_id` (`pengaduan_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `histori_pengaduan`
--
ALTER TABLE `histori_pengaduan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengaduan_id` (`pengaduan_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengaduan_id` (`pengaduan_id`),
  ADD KEY `idx_notifikasi_user` (`user_id`),
  ADD KEY `idx_notifikasi_status` (`status`),
  ADD KEY `idx_notifikasi_tipe` (`tipe`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_users_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aspirasi`
--
ALTER TABLE `aspirasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `histori_pengaduan`
--
ALTER TABLE `histori_pengaduan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD CONSTRAINT `aspirasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `aspirasi_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`pengaduan_id`) REFERENCES `aspirasi` (`id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `histori_pengaduan`
--
ALTER TABLE `histori_pengaduan`
  ADD CONSTRAINT `histori_pengaduan_ibfk_1` FOREIGN KEY (`pengaduan_id`) REFERENCES `aspirasi` (`id`);

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifikasi_ibfk_2` FOREIGN KEY (`pengaduan_id`) REFERENCES `aspirasi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
