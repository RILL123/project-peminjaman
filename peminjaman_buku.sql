-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Apr 2026 pada 05.50
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
(7, 'Feel_The_Nature_Origin.jpg', 'Feel The Nature Origin', 'Non-Fiksi', 'Steven', '2020', 48, '2026-01-29 07:54:41'),
(8, 'Wild_Life.jpg', 'Wild Life', 'Ensiklopedia', 'Edi Kurniawan', '2026', 89, '2026-01-29 07:55:59'),
(9, 'Adventure_Hikes.jpg', 'Adventure Hikes', 'Non-Fiksi', 'Edi Kurniawan', '2022', 49, '2026-01-29 08:02:52'),
(10, 'The_Bike_Guy.jpg', 'The Bike Guy', 'Fiksi', 'Edi Kurniawan', '2024', 96, '2026-01-29 08:07:08'),
(11, 'The_World_Need_More_Love.jpg', 'The World Need More Love', 'Non-Fiksi', 'Steven', '2024', 67, '2026-01-29 08:12:25'),
(14, 'Wild_Adventure.jpg', 'Wild Adventure', 'Novel', 'Fira Feona', '2025', 76, '2026-01-29 09:37:45'),
(17, 'Balis_Day_of_Silence.jpg', 'Bali\'s Day of Silence', 'Novel', 'Fira Feona', '2025', 45, '2026-01-29 09:45:57'),
(18, 'Choir_Festival.jpg', 'Choir Festival', 'Novel', 'Fira Feona', '2026', 88, '2026-01-29 09:52:20'),
(19, 'Camp_V.jpg', 'Camp V', 'Novel', 'Fira Feona', '2022', 83, '2026-01-29 10:13:13'),
(20, 'Sebuah_Pohon.jpg', 'Sebuah Pohon', 'Novel', 'Fira Feona', '2026', 23, '2026-01-29 10:38:15'),
(21, 'Alam.jpg', 'Alam', 'Novel', 'Fira Feona', '2026', 32, '2026-01-29 10:40:50'),
(22, 'Celebrate_Forest.jpg', 'Celebrate Forest', 'Ensiklopedia', 'Steven', '2023', 40, '2026-01-29 10:44:01'),
(23, 'World_Forest_Day.jpg', 'World Forest Day', 'Non-Fiksi', 'Steven', '2016', 71, '2026-01-29 10:48:47'),
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
(6, 6, 24, 0),
(21, 21, 9, 0),
(33, 33, 14, 0),
(35, 35, 23, 0);

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
(70, 1, 'Pengembalian Buku', 'Mengembalikan buku', 10, '2026-04-06 05:34:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_user`, `tanggal_pinjam`, `tanggal_kembali`) VALUES
(6, 2, '2026-02-25', '2026-02-28'),
(20, 2, '2026-04-01', '2026-04-04'),
(21, 10, '2026-04-01', '2026-04-04'),
(33, 2, '2026-04-06', '2026-04-09'),
(35, 2, '2026-04-06', '2026-04-09');

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
  `tanggal_request` datetime DEFAULT current_timestamp(),
  `status` enum('pending','diterima','ditolak') DEFAULT 'pending',
  `alasan_penolakan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `request_peminjaman`
--

INSERT INTO `request_peminjaman` (`id_request`, `id_user`, `id_buku`, `tanggal_request`, `status`, `alasan_penolakan`) VALUES
(27, 2, 23, '2026-04-06 00:00:00', 'pending', NULL),
(28, 2, 23, '2026-04-06 00:00:00', 'pending', NULL),
(29, 2, 20, '2026-04-06 00:00:00', 'pending', NULL),
(30, 2, 20, '2026-04-06 00:00:00', 'pending', NULL);

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
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `request_peminjaman`
--
ALTER TABLE `request_peminjaman`
  MODIFY `id_request` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
