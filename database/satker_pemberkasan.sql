-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Jul 2026 pada 04.44
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kemenagtd_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `satker_pemberkasan`
--

CREATE TABLE `satker_pemberkasan` (
  `id` bigint(20) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `layanan_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `dept_id` bigint(20) NOT NULL,
  `waktu` date NOT NULL,
  `item_id` int(255) NOT NULL,
  `noreq` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `files` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`files`)),
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `requirements_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`requirements_snapshot`)),
  `status` varchar(255) NOT NULL DEFAULT 'DRAFT',
  `is_migrated` tinyint(1) NOT NULL DEFAULT 0,
  `migrated_at` timestamp NULL DEFAULT NULL,
  `verifikator_id` bigint(20) NOT NULL DEFAULT 999,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `satker_pemberkasan`
--
ALTER TABLE `satker_pemberkasan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `satker_pemberkasan`
--
ALTER TABLE `satker_pemberkasan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
