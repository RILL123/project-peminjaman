-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Apr 2026 pada 09.42
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
-- Database: `peminjaman_buku`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `penulis` varchar(100) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `stok` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id_buku`, `cover`, `judul`, `kategori`, `penulis`, `tahun`, `stok`, `created_at`) VALUES
(6, 'Feel_the_Nature.jpg', 'Feel the Nature', 'Non-Fiksi', 'Steven', '2018', 43, '2026-01-29 07:44:10'),
(7, 'Feel_The_Nature_Origin.jpg', 'Feel The Nature Origin', 'Non-Fiksi', 'Steven', '2020', 45, '2026-01-29 07:54:41'),
(8, 'Wild_Life.jpg', 'Wild Life', 'Ensiklopedia', 'Edi Kurniawan', '2026', 89, '2026-01-29 07:55:59'),
(9, 'Adventure_Hikes.jpg', 'Adventure Hikes', 'Non-Fiksi', 'Edi Kurniawan', '2022', 50, '2026-01-29 08:02:52'),
(10, 'The_Bike_Guy.jpg', 'The Bike Guy', 'Fiksi', 'Edi Kurniawan', '2024', 96, '2026-01-29 08:07:08'),
(11, 'The_World_Need_More_Love.jpg', 'The World Need More Love', 'Non-Fiksi', 'Steven', '2024', 67, '2026-01-29 08:12:25'),
(14, 'Wild_Adventure.jpg', 'Wild Adventure', 'Novel', 'Fira Feona', '2025', 77, '2026-01-29 09:37:45'),
(17, 'Balis_Day_of_Silence.jpg', 'Bali\'s Day of Silence', 'Novel', 'Fira Feona', '2025', 45, '2026-01-29 09:45:57'),
(18, 'Choir_Festival.jpg', 'Choir Festival', 'Novel', 'Fira Feona', '2026', 88, '2026-01-29 09:52:20'),
(19, 'Camp_V.jpg', 'Camp V', 'Novel', 'Fira Feona', '2022', 83, '2026-01-29 10:13:13'),
(20, 'Sebuah_Pohon.jpg', 'Sebuah Pohon', 'Novel', 'Fira Feona', '2026', 49, '2026-01-29 10:38:15'),
(21, 'Alam.jpg', 'Alam', 'Novel', 'Fira Feona', '2026', 31, '2026-01-29 10:40:50'),
(22, 'Celebrate_Forest.jpg', 'Celebrate Forest', 'Ensiklopedia', 'Steven', '2023', 40, '2026-01-29 10:44:01'),
(23, 'World_Forest_Day.jpg', 'World Forest Day', 'Non-Fiksi', 'Steven', '2016', 72, '2026-01-29 10:48:47'),
(24, 'Protect_Our_Forest.jpg', 'Protect Our Forest', 'Fiksi', 'Steven', '2021', 48, '2026-01-29 10:53:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id_detail` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id_detail`, `id_peminjaman`, `id_buku`, `jumlah`) VALUES
(78, 59, 20, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(11) NOT NULL,
  `jenis_laporan` enum('peminjaman','pengembalian','denda') NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `aktivitas` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `id_buku` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `keterangan`, `id_buku`, `tanggal`) VALUES
(31, 1, 'Pengembalian Buku', 'Mengembalikan buku', 10, '2026-04-02 03:21:25'),
(32, 1, 'Pengembalian Buku', 'Mengembalikan buku', 9, '2026-04-02 03:21:26'),
(33, 1, 'Pengembalian Buku', 'Mengembalikan buku', 8, '2026-04-02 03:21:26'),
(34, 1, 'Pengembalian Buku', 'Mengembalikan buku', 24, '2026-04-02 03:21:27'),
(35, 1, 'Pengembalian Buku', 'Mengembalikan buku', 24, '2026-04-02 03:21:27'),
(36, 1, 'Pengembalian Buku', 'Mengembalikan buku', 21, '2026-04-02 03:21:29'),
(37, 1, 'Pengembalian Buku', 'Mengembalikan buku', 6, '2026-04-02 03:21:30'),
(38, 1, 'Pengembalian Buku', 'Mengembalikan buku', 20, '2026-04-02 03:21:31'),
(39, 1, 'Pengembalian Buku', 'Mengembalikan buku', 10, '2026-04-02 03:21:31'),
(40, 1, 'Login User', 'User login ke sistem', NULL, '2026-04-06 03:16:06'),
(41, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-06 03:27:15'),
(53, 1, 'Hapus Buku', 'Menghapus buku: cihuy', NULL, '2026-04-06 04:01:00'),
(54, 2, 'Request Peminjaman', 'Selama 3 hari', 14, '2026-04-06 04:04:26'),
(55, 2, 'Request Peminjaman', 'Selama 2 hari', 18, '2026-04-06 04:04:33'),
(58, 2, 'Request Peminjaman', 'Selama 2 hari', 20, '2026-04-06 04:09:53'),
(60, 2, 'Request Peminjaman', 'Selama 3 hari', 23, '2026-04-06 04:15:36'),
(61, 1, 'Terima Request Peminjaman', 'Dari Rill untuk buku World Forest Day', 23, '2026-04-06 04:15:54'),
(62, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-06 04:39:09'),
(63, 2, 'Request Peminjaman', 'Selama 2 hari', 23, '2026-04-06 04:40:35'),
(64, 2, 'Request Peminjaman', 'Selama 2 hari', 23, '2026-04-06 04:40:38'),
(65, 2, 'Request Peminjaman', 'Selama 2 hari', 20, '2026-04-06 04:43:38'),
(66, 2, 'Request Peminjaman', 'Selama 2 hari', 20, '2026-04-06 04:44:23'),
(67, 1, 'Login User', 'User login ke sistem', NULL, '2026-04-06 04:44:38'),
(68, 1, 'Pengembalian Buku', 'Mengembalikan buku', 20, '2026-04-06 05:34:18'),
(69, 1, 'Tambah Peminjaman', 'Membuat peminjaman baru', 10, '2026-04-06 05:34:38'),
(70, 1, 'Pengembalian Buku', 'Mengembalikan buku', 10, '2026-04-06 05:34:42'),
(71, 1, 'Login User', 'User login ke sistem', NULL, '2026-04-06 08:29:40'),
(72, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-06 08:30:15'),
(73, 2, 'Request Peminjaman', 'Selama 2 hari', 23, '2026-04-06 08:30:21'),
(74, 1, 'Login User', 'User login ke sistem', NULL, '2026-04-06 08:30:35'),
(76, 1, 'Login User', 'User login ke sistem', NULL, '2026-04-07 08:39:43'),
(78, 10, 'Request Peminjaman', 'Selama 2 hari', 19, '2026-04-07 08:42:22'),
(79, 1, 'Login User', 'User login ke sistem', NULL, '2026-04-07 08:42:31'),
(80, 1, 'Login User', 'User login ke sistem', NULL, '2026-04-08 02:12:16'),
(88, 1, 'Tambah Buku', 'Yuru Camp', NULL, '2026-04-08 02:59:05'),
(89, 1, 'Hapus Buku', 'Yuru Camp', NULL, '2026-04-08 02:59:25'),
(90, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-08 03:03:10'),
(91, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-08 03:08:49'),
(92, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-08 03:08:58'),
(93, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-08 04:43:47'),
(94, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-09 06:18:54'),
(95, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-09 06:19:54'),
(96, 1, 'Pengembalian Buku', 'Mengembalikan buku', 23, '2026-04-09 06:21:44'),
(97, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-09 08:27:59'),
(98, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-09 09:47:35'),
(99, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-09 09:58:18'),
(100, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-09 10:00:11'),
(101, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-09 10:06:00'),
(102, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-09 10:15:43'),
(103, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-09 10:25:09'),
(104, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-10 02:44:46'),
(105, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-10 03:04:03'),
(106, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-10 03:09:48'),
(107, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-10 03:11:06'),
(108, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-10 03:12:12'),
(109, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-10 03:13:27'),
(110, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-10 03:28:07'),
(111, 2, 'Request Peminjaman', 'Selama 2 hari', 8, '2026-04-10 03:33:58'),
(112, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-10 03:34:31'),
(113, 1, 'Terima Request Peminjaman', 'Dari Rill untuk buku Wild Life', 8, '2026-04-10 03:34:48'),
(114, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-10 03:38:00'),
(115, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-10 03:38:57'),
(116, 2, 'Request Pengembalian', 'Meminta pengembalian buku: Wild Life', 8, '2026-04-10 03:41:58'),
(117, 2, 'Request Pengembalian', 'Meminta pengembalian buku: Wild Adventure', 14, '2026-04-10 03:42:22'),
(118, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-10 03:42:43'),
(119, 1, 'Tolak Pengembalian', 'Buku: Wild Life dari Rill', 8, '2026-04-10 03:43:28'),
(120, 1, 'Tolak Pengembalian', 'Buku: Wild Adventure dari Rill', 14, '2026-04-10 03:43:55'),
(121, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-10 03:45:22'),
(122, 2, 'Request Pengembalian', 'Meminta pengembalian buku: Wild Life', 8, '2026-04-10 03:45:26'),
(123, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-10 03:46:45'),
(124, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-10 03:47:02'),
(125, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-10 03:47:50'),
(126, 1, 'Tolak Pengembalian', 'Buku: Wild Life dari Rill', 8, '2026-04-10 03:51:12'),
(127, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-10 04:00:33'),
(128, 2, 'Request Pengembalian', 'Meminta pengembalian buku: Wild Life', 8, '2026-04-10 04:00:37'),
(129, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-10 04:00:45'),
(130, 1, 'Terima Request Peminjaman', 'Dari Restu untuk buku Camp V', 19, '2026-04-10 04:08:52'),
(131, 1, 'Setujui Pengembalian', 'Buku: Wild Life dari Rill', 8, '2026-04-10 04:43:42'),
(132, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-10 04:44:04'),
(133, 2, 'Pinjam Buku', 'Pinjam 20 buku: Sebuah Pohon selama 3 hari', 20, '2026-04-10 05:49:27'),
(134, 2, 'Request Peminjaman', 'Request pinjam 80 buku: Camp V selama 3 hari', 19, '2026-04-10 05:53:03'),
(135, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-10 05:53:13'),
(136, 1, 'Terima Request Peminjaman', 'Dari Rill untuk buku Camp V', 19, '2026-04-10 05:53:34'),
(137, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-10 05:53:41'),
(138, 2, 'Request Peminjaman', 'Request pinjam 30 buku: Feel the Nature selama 3 hari', 6, '2026-04-10 05:54:00'),
(139, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-10 05:54:12'),
(140, 1, 'Terima Request Peminjaman', 'Dari Rill untuk buku Feel the Nature', 6, '2026-04-10 05:54:17'),
(141, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-10 05:54:30'),
(142, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-10 05:55:37'),
(143, 1, 'Pengembalian Buku', 'Mengembalikan buku', 20, '2026-04-10 05:59:15'),
(144, 1, 'Edit Buku', 'Sebuah Pohon', 20, '2026-04-10 05:59:29'),
(145, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-10 08:01:48'),
(146, 1, 'Pengembalian Buku', 'Mengembalikan buku', 19, '2026-04-10 08:15:55'),
(147, 1, 'Pengembalian Buku', 'Mengembalikan buku', 19, '2026-04-10 08:15:58'),
(148, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-10 08:16:23'),
(149, 2, 'Request Peminjaman', 'Request pinjam 10 buku: Sebuah Pohon selama 3 hari', 20, '2026-04-10 08:16:37'),
(150, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-10 08:16:44'),
(151, 1, 'Terima Request Peminjaman', 'Dari Rill untuk buku Sebuah Pohon', 20, '2026-04-10 08:17:01'),
(152, 1, 'Pengembalian Buku', 'Mengembalikan buku', 9, '2026-04-10 08:17:34'),
(153, 1, 'Pengembalian Buku', 'Mengembalikan buku', 14, '2026-04-10 08:17:37'),
(154, 1, 'Pengembalian Buku', 'Mengembalikan buku', 6, '2026-04-10 08:17:38'),
(155, 1, 'Pengembalian Buku', 'Mengembalikan buku', 20, '2026-04-10 08:17:40'),
(156, 1, 'Terima Request Peminjaman', 'Dari Rill untuk buku World Forest Day', 23, '2026-04-10 08:18:37'),
(157, 1, 'Terima Request Peminjaman', 'Dari Rill untuk buku Sebuah Pohon', 20, '2026-04-10 08:18:37'),
(158, 1, 'Terima Request Peminjaman', 'Dari Rill untuk buku Sebuah Pohon', 20, '2026-04-10 08:18:38'),
(159, 1, 'Terima Request Peminjaman', 'Dari Rill untuk buku World Forest Day', 23, '2026-04-10 08:18:38'),
(160, 10, 'Login User', 'User login ke sistem', NULL, '2026-04-10 08:19:46'),
(161, 10, 'Request Peminjaman', 'Request pinjam 1 buku: Alam selama 3 hari', 21, '2026-04-10 08:20:14'),
(162, 1, 'Pengembalian Buku', 'Mengembalikan buku', 20, '2026-04-10 08:20:32'),
(163, 1, 'Pengembalian Buku', 'Mengembalikan buku', 23, '2026-04-10 08:20:34'),
(164, 1, 'Pengembalian Buku', 'Mengembalikan buku', 20, '2026-04-10 08:20:36'),
(165, 1, 'Pengembalian Buku', 'Mengembalikan buku', 23, '2026-04-10 08:20:37'),
(166, 1, 'Terima Request Peminjaman', 'Dari Restu untuk buku Alam', 21, '2026-04-10 08:20:41'),
(167, 10, 'Request Peminjaman', 'Request pinjam 1 buku: Alam selama 3 hari', 21, '2026-04-10 08:22:17'),
(168, 10, 'Request Peminjaman', 'Request pinjam 4 buku: Feel The Nature Origin selama 3 hari', 7, '2026-04-10 08:41:43'),
(169, 1, 'Terima Request Peminjaman', 'Dari Restu untuk buku Feel The Nature Origin (4 buku)', 7, '2026-04-10 08:41:53'),
(170, 1, 'Pengembalian Buku', 'Mengembalikan buku', 7, '2026-04-10 08:42:01'),
(171, 10, 'Request Peminjaman', 'Request pinjam 10 buku: Sebuah Pohon selama 3 hari', 20, '2026-04-10 08:42:18'),
(172, 1, 'Terima Request Peminjaman', 'Dari Restu untuk buku Sebuah Pohon (10 buku)', 20, '2026-04-10 08:42:31'),
(173, 1, 'Pengembalian Buku', 'Mengembalikan buku', 20, '2026-04-10 08:43:59'),
(174, 1, 'Edit Buku', 'Sebuah Pohon', 20, '2026-04-10 08:47:15'),
(175, 10, 'Request Peminjaman', 'Request pinjam 10 buku: Sebuah Pohon selama 3 hari', 20, '2026-04-10 08:48:33'),
(176, 1, 'Terima Request Peminjaman', 'Dari Restu untuk buku Sebuah Pohon (10 buku)', 20, '2026-04-10 08:48:44'),
(177, 1, 'Pengembalian Buku', 'Mengembalikan 10 buku', 20, '2026-04-10 08:48:57'),
(178, 1, 'Pengembalian Buku', 'Mengembalikan 0 buku', 21, '2026-04-10 08:50:45'),
(179, 1, 'Terima Request Peminjaman', 'Dari Restu untuk buku Alam (1 buku)', 21, '2026-04-10 08:51:02'),
(180, 1, 'Pengembalian Buku', 'Mengembalikan 1 buku', 21, '2026-04-10 08:51:06'),
(181, 1, 'Pengembalian Buku', 'Mengembalikan 0 buku', 24, '2026-04-10 08:51:08'),
(182, 10, 'Request Peminjaman', 'Request pinjam 1 buku: World Forest Day selama 3 hari', 23, '2026-04-10 09:28:04'),
(183, 1, 'Terima Request Peminjaman', 'Dari Restu untuk buku World Forest Day (1 buku)', 23, '2026-04-10 09:30:08'),
(184, 1, 'Pengembalian Buku', 'Mengembalikan 1 buku', 23, '2026-04-10 09:30:47'),
(185, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-11 05:24:19'),
(186, 1, 'Tambah Buku', 'Yuru Camp', NULL, '2026-04-11 05:25:26'),
(188, 1, 'Tambah Peminjaman', 'Membuat peminjaman baru', 20, '2026-04-11 05:38:26'),
(189, 1, 'Pengembalian Buku', 'Mengembalikan 0 buku', 20, '2026-04-11 05:38:37'),
(190, 1, 'Edit Buku', 'Sebuah Pohon', 20, '2026-04-11 05:42:53'),
(191, 1, 'Tambah Peminjaman', 'Membuat peminjaman baru sebanyak 45 buku', 20, '2026-04-11 05:51:01'),
(192, 1, 'Pengembalian Buku', 'Mengembalikan 45 buku', 20, '2026-04-11 05:51:09'),
(193, 1, 'Tambah Peminjaman', 'Membuat peminjaman baru sebanyak 10 buku', 20, '2026-04-11 05:55:31'),
(194, 1, 'Pengembalian Buku', 'Mengembalikan 10 buku', 20, '2026-04-11 05:55:41'),
(195, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-11 06:21:19'),
(196, 1, 'Tambah Peminjaman', 'Membuat peminjaman baru sebanyak 10 buku', 20, '2026-04-11 06:49:08'),
(197, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-11 07:11:32'),
(198, 2, 'Request Peminjaman', 'Request pinjam 1 buku: Alam selama 3 hari', 21, '2026-04-11 07:11:39'),
(199, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-11 07:11:52'),
(200, 1, 'Terima Request Peminjaman', 'Dari Rill untuk buku Alam (1 buku)', 21, '2026-04-11 07:11:57'),
(201, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-11 07:12:04'),
(202, 2, 'Request Pengembalian', 'Meminta pengembalian buku: Alam', 21, '2026-04-11 07:12:32'),
(203, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-11 07:13:03'),
(204, 1, 'Setujui Pengembalian', 'Buku: Alam dari Rill', 21, '2026-04-11 07:13:21'),
(205, 1, 'Pengembalian Buku', 'Mengembalikan 10 buku', 20, '2026-04-11 07:16:21'),
(206, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-11 07:17:02'),
(207, 2, 'Request Peminjaman', 'Request pinjam 1 buku: Sebuah Pohon selama 3 hari', 20, '2026-04-11 07:17:10'),
(208, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-11 07:17:16'),
(209, 1, 'Terima Request Peminjaman', 'Dari Rill untuk buku Sebuah Pohon (1 buku)', 20, '2026-04-11 07:17:38'),
(210, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-11 07:18:01'),
(211, 2, 'Request Pengembalian', 'Meminta pengembalian buku: Sebuah Pohon', 20, '2026-04-11 07:18:56'),
(212, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-11 07:19:04'),
(213, 1, 'Setujui Pengembalian', 'Buku: Sebuah Pohon dari Rill', 20, '2026-04-11 07:19:23'),
(214, 1, 'Tambah Peminjaman', 'Membuat peminjaman baru sebanyak 1 buku', 20, '2026-04-11 07:26:02'),
(215, 2, 'Login User', 'User login ke sistem', NULL, '2026-04-11 07:26:29'),
(216, 2, 'Request Pengembalian', 'Meminta pengembalian buku: Sebuah Pohon', 20, '2026-04-11 07:26:44'),
(217, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-11 07:26:52'),
(218, 1, 'Login Admin', 'Admin login ke sistem', NULL, '2026-04-11 07:40:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_user`, `tanggal_pinjam`, `tanggal_kembali`, `jumlah`) VALUES
(59, 2, '2026-04-11', '2026-04-13', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id_pengembalian` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `tanggal_dikembalikan` date NOT NULL,
  `terlambat` int(11) DEFAULT 0,
  `denda` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `request_peminjaman`
--

CREATE TABLE `request_peminjaman` (
  `id_request` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_buku` int(11) DEFAULT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `tanggal_request` datetime DEFAULT current_timestamp(),
  `status` enum('pending','diterima','ditolak') DEFAULT 'pending',
  `alasan_penolakan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `request_pengembalian`
--

CREATE TABLE `request_pengembalian` (
  `id_request_kembali` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `tanggal_request` datetime DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `tanggal_approved` datetime DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `request_pengembalian`
--

INSERT INTO `request_pengembalian` (`id_request_kembali`, `id_peminjaman`, `id_user`, `id_buku`, `tanggal_request`, `status`, `tanggal_approved`, `catatan_admin`) VALUES
(7, 59, 2, 20, '2026-04-11 07:26:44', 'pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Admin Perpus', 'admin', 'admin123', 'admin', '2026-01-21 08:24:52'),
(2, 'Rill', 'rill', 'rill123', 'user', '2026-01-21 08:24:52'),
(8, 'Edi', 'Edi', '123', 'user', '2026-04-01 07:42:00'),
(9, 'Fitri', 'Fitri', '123', 'user', '2026-04-01 07:53:35'),
(10, 'Restu', 'Restu123', '123', 'user', '2026-04-01 08:38:38');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indeks untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_detail_peminjaman` (`id_peminjaman`),
  ADD KEY `fk_detail_buku` (`id_buku`);

--
-- Indeks untuk tabel `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id_laporan`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `fk_peminjaman_user` (`id_user`);

--
-- Indeks untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`),
  ADD KEY `fk_pengembalian_peminjaman` (`id_peminjaman`);

--
-- Indeks untuk tabel `request_peminjaman`
--
ALTER TABLE `request_peminjaman`
  ADD PRIMARY KEY (`id_request`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indeks untuk tabel `request_pengembalian`
--
ALTER TABLE `request_pengembalian`
  ADD PRIMARY KEY (`id_request_kembali`),
  ADD KEY `id_peminjaman` (`id_peminjaman`),
  ADD KEY `id_buku` (`id_buku`),
  ADD KEY `idx_user` (`id_user`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_tanggal` (`tanggal_request`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT untuk tabel `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `request_peminjaman`
--
ALTER TABLE `request_peminjaman`
  MODIFY `id_request` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `request_pengembalian`
--
ALTER TABLE `request_pengembalian`
  MODIFY `id_request_kembali` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD CONSTRAINT `fk_detail_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `log_aktivitas_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `fk_pengembalian_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `request_peminjaman`
--
ALTER TABLE `request_peminjaman`
  ADD CONSTRAINT `request_peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_peminjaman_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `request_pengembalian`
--
ALTER TABLE `request_pengembalian`
  ADD CONSTRAINT `request_pengembalian_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_pengembalian_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_pengembalian_ibfk_3` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
