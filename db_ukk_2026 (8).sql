-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Nov 2026 pada 11.27
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
-- Database: `db_ukk_2026`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_guru`
--

CREATE TABLE `t_guru` (
  `id` int(11) NOT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_guru`
--

INSERT INTO `t_guru` (`id`, `nip`, `nama`, `email`, `status_aktif`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '19800101201001', 'Wawan Kurniawan, S.Pd.I', 'wawan@gmail.com', 1, 2, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(2, '19800101201002', 'Ai Nuryaidah, S.Pd', 'ai@gmail.com', 1, 3, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(3, '19800101201003', 'Maya Permata Endah, S.Pd', 'maya@gmail.com', 1, 4, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(4, '19800101201004', 'Dody Jaenudin, S.Pd.', 'dody@gmail.com', 1, 5, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(5, '19800101201005', 'Dedi Kardiman, S. Pd', 'dedi@gmail.com', 1, 6, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(6, '19800101201006', 'Uca Musa Yusenda, S.Pd', 'uca@gmail.com', 1, 7, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(7, '19800101201007', 'Drs. Ahmad Nur Mukhlis', 'ahmad@gmail.com', 1, 8, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(8, '19800101201008', 'Drs. Heri', 'heri@gmail.com', 1, 9, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(9, '19800101201009', 'Asep Bastaman', 'asep@gmail.com', 1, 10, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(10, '19800101201010', 'H. Encep Maemun, S.T., M.Pd', 'encep@gmail.com', 1, 11, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(11, '19800101201011', 'Opik', 'opik@gmail.com', 1, 12, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(12, '19800101201012', 'Ida Farida, S. Pd', 'ida@gmail.com', 1, 13, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(13, '19800101201013', 'Idah Mahmudah, S.Pd', 'idah@gmail.com', 1, 14, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(14, '19800101201014', 'Drs. Muhamad Ali', 'muhamad@gmail.com', 1, 15, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(15, '19800101201015', 'Helmi Ali Akbar, S.Pd.I', 'helmi@gmail.com', 1, 16, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(16, '19800101201016', 'Syeni Suswarini, S.Pd', 'syeni@gmail.com', 1, 17, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(17, '19800101201017', 'Wiwid Yulianti, S.Pd', 'wiwid@gmail.com', 1, 18, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(18, '19800101201018', 'Ansor Muhajir, S.T', 'ansor@gmail.com', 1, 19, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(19, '19800101201019', 'Ogi Gaosun Gias, S.Pd.I', 'ogi@gmail.com', 1, 20, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(20, '19800101201020', 'Sandi Mulyana, S.Pd', 'sandi@gmail.com', 1, 21, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(21, '19800101201021', 'Erna Suryani, S.Pd', 'erna@gmail.com', 1, 22, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(22, '19800101201022', 'Ai Wahidah, S.Ag', 'aiwahidah@gmail.com', 1, 23, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(23, '19800101201023', 'Chandra Aditya Yusup, S.Pd', 'chandra@gmail.com', 1, 24, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(24, '19800101201024', 'Arif Kusmawan, S.Pd', 'arif@gmail.com', 1, 25, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(25, '19800101201025', 'Asep Taufiq Hidayat, S.Kom', 'aseptaufiq@gmail.com', 1, 26, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(26, '19800101201026', 'Rendy Ichsan Kusumaḥ, S.T', 'rendy@gmail.com', 1, 27, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(27, '19800101201027', 'Feri Rahmawan, S.Pd', 'feri@gmail.com', 1, 28, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(28, '19800101201028', 'Ari Sandriana, S.Kom', 'arisandriana@gmail.com', 1, 29, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(29, '19800101201029', 'Yaneu Nurdian, S.Pd, M.Pd', 'yaneu@gmail.com', 1, 30, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(30, '19800101201030', 'Ucu Muhammad Khoer N, S.Pd', 'ucu@gmail.com', 1, 31, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(31, '19800101201031', 'Mira Dwi Rahmawati, S.Ag', 'mira@gmail.com', 1, 32, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(32, '19800101201032', 'Aldi Maulana, S.Pd', 'aldi@gmail.com', 1, 33, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(33, '19800101201033', 'Eli Rosliah, S.Pd', 'eli@gmail.com', 1, 34, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(34, '19800101201034', 'Isni Nur Fitriani, S.Pd', 'isni@gmail.com', 1, 35, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(35, '19800101201035', 'Aanisah Putri Nur Aini, S.Pd', 'aanisah@gmail.com', 1, 36, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(36, '19800101201036', 'Yusi Luthfiani, S.Sos.I', 'yusi@gmail.com', 1, 37, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(37, '19800101201037', 'Lilis Rustandy Mawardi, S.Pd., M.Pd', 'lilis@gmail.com', 1, 38, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(38, '19800101201038', 'Yusrin Noer Sabrina, S.Pd', 'yusrin@gmail.com', 1, 39, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(39, '19800101201039', 'Senny Indayani, S.Pd', 'senny@gmail.com', 1, 40, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(40, '19800101201040', 'Ilma Amalia, S.Pd', 'ilma@gmail.com', 1, 41, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(41, '19800101201041', 'Alvira Sofwatunnisa, S.Pd', 'alvira@gmail.com', 1, 42, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(42, '19800101201042', 'Risna Andriyani, S.Pd', 'risna@gmail.com', 1, 43, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(43, '19800101201043', 'Dra. Tintin Noerhayati', 'tintin@gmail.com', 1, 44, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(44, '19800101201044', 'Rizkia Fahira, S.Pd', 'rizkia@gmail.com', 1, 45, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(45, '19800101201045', 'Frasetio', 'frasetio@gmail.com', 1, 46, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(46, '19800101201046', 'Ratnaningsih Hatil Aeni, S.Pd.', 'ratnaningsih@gmail.com', 1, 47, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(47, '19800101201047', 'Dheny Darajatul Hasani, S.Pd.', 'dheny@gmail.com', 1, 48, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(48, '19800101201048', 'Fahrullah, S.Pd', 'fahrullah@gmail.com', 1, 49, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(49, '19800101201049', 'Aan Andriana, S.T', 'aan@gmail.com', 1, 50, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(50, '19800101201050', 'Fitri Fauziah, S.T', 'fitrifauziah@gmail.com', 1, 51, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(51, '19800101201051', 'Nida Aulia, S.Pd', 'nida@gmail.com', 1, 52, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(52, '19800101201052', 'Sri Nurrizki Putri Krisnadi, S.Pd', 'srinurrizki@gmail.com', 1, 53, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(53, '19800101201053', 'Riesy Putri Navia, S.Kom', 'riesy@gmail.com', 1, 54, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(54, '19800101201054', 'Rurry Febriani Maryadi, S.Pd', 'rurry@gmail.com', 1, 55, '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(55, '19800101201055', 'Irvan Rizal, S.Pd', 'irvan@gmail.com', 1, 56, '2026-11-03 04:12:14', '2026-11-03 04:12:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_kelas`
--

CREATE TABLE `t_kelas` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tingkat` varchar(20) DEFAULT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_kelas`
--

INSERT INTO `t_kelas` (`id`, `nama`, `tingkat`, `jurusan`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'X TKR 1', 'X', 'TKR', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(2, 'X TKR 2', 'X', 'TKR', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(3, 'X TKR 3', 'X', 'TKR', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(4, 'X TKR 4', 'X', 'TKR', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(5, 'X TKR 5', 'X', 'TKR', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(6, 'X TSM 1', 'X', 'TSM', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(7, 'X TSM 2', 'X', 'TSM', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(8, 'X TSM 3', 'X', 'TSM', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(9, 'X RPL 1', 'X', 'RPL', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(10, 'X RPL 2', 'X', 'RPL', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(11, 'X TKJ 1', 'X', 'TKJ', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(12, 'X TKJ 2', 'X', 'TKJ', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(13, 'X TKJ 3', 'X', 'TKJ', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(14, 'X BD', 'X', 'BD', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(15, 'XI TKR 1', 'XI', 'TKR', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(16, 'XI TKR 2', 'XI', 'TKR', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(17, 'XI TKR 3', 'XI', 'TKR', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(18, 'XI TSM 1', 'XI', 'TSM', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(19, 'XI TSM 2', 'XI', 'TSM', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(20, 'XI RPL', 'XI', 'RPL', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(21, 'XI TKJ 1', 'XI', 'TKJ', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(22, 'XI TKJ 2', 'XI', 'TKJ', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(23, 'XI BD', 'XI', 'BD', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(24, 'XII TKR 1', 'XII', 'TKR', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(25, 'XII TKR 2', 'XII', 'TKR', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(26, 'XII TKR 3', 'XII', 'TKR', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(27, 'XII TKR 4', 'XII', 'TKR', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(28, 'XII TKR 5', 'XII', 'TKR', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(29, 'XII TSM 1', 'XII', 'TSM', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(30, 'XII TSM 2', 'XII', 'TSM', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(31, 'XII TSM 3', 'XII', 'TSM', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(32, 'XII RPL 1', 'XII', 'RPL', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(33, 'XII RPL 2', 'XII', 'RPL', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(34, 'XII TKJ 1', 'XII', 'TKJ', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(35, 'XII TKJ 2', 'XII', 'TKJ', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(36, 'XII TKJ 3', 'XII', 'TKJ', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(37, 'XII TKJ 4', 'XII', 'TKJ', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03'),
(38, 'XII BD', 'XII', 'BD', 1, '2026-11-03 04:44:03', '2026-11-03 04:44:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_kelas_siswa`
--

CREATE TABLE `t_kelas_siswa` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_kelas_siswa`
--

INSERT INTO `t_kelas_siswa` (`id`, `siswa_id`, `tahun_ajaran_id`, `kelas_id`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(2, 2, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(3, 3, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(4, 4, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(5, 5, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(6, 6, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(7, 7, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(8, 8, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(9, 9, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(10, 10, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(11, 11, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(12, 12, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(13, 13, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(14, 14, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(15, 15, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(16, 16, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(17, 17, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(18, 18, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(19, 19, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(20, 20, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(21, 21, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(22, 22, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(23, 23, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(24, 24, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(25, 25, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(26, 26, 1, 33, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:43:24', '2026-11-04 02:43:24'),
(32, 55, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(33, 56, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(34, 57, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(35, 58, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(36, 59, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(37, 60, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(38, 61, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(39, 62, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(40, 63, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(41, 64, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(42, 65, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(43, 66, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(44, 67, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(45, 68, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(46, 69, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(47, 70, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(48, 71, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(49, 72, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(50, 73, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(51, 74, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(52, 75, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(53, 76, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(54, 77, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(55, 78, 1, 32, '2026-07-15', '2027-06-20', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran`
--

CREATE TABLE `t_pelanggaran` (
  `id` int(11) NOT NULL,
  `pelanggaran_kategori_id` int(11) NOT NULL,
  `kode` varchar(30) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `poin` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_pelanggaran`
--

INSERT INTO `t_pelanggaran` (`id`, `pelanggaran_kategori_id`, `kode`, `nama`, `poin`, `deskripsi`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, 'PLG-001', 'Terlambat hadir di sekolah', 10, 'Datang melewati batas waktu gerbang sekolah ditutup', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(2, 1, 'PLG-002', 'Keluar kelas tanpa izin saat jam pelajaran', 15, 'Meninggalkan ruang kelas tanpa izin guru yang mengajar', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(3, 1, 'PLG-003', 'Tidak mengikuti upacara bendera', 20, 'Tidak hadir atau sengaja menghindar dari kegiatan upacara sekolah', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(4, 2, 'PLG-004', 'Atribut seragam tidak lengkap', 10, 'Tidak memakai dasi, topi, lokasi, atau badge sesuai ketentuan hari tersebut', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(5, 2, 'PLG-005', 'Seragam tidak sesuai ketentuan / jadwal', 15, 'Memakai pakaian bebas atau seragam tidak sesuai jadwal harian', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(6, 2, 'PLG-006', 'Rambut panjang / dicat (khusus siswa laki-laki)', 15, 'Memelihara rambut panjang, tidak rapi, atau mewarnai rambut', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(7, 3, 'PLG-007', 'Membolos / cabut jam pelajaran', 25, 'Keluar lingkungan sekolah atau bersembunyi saat jam pelajaran berlangsung', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(8, 3, 'PLG-008', 'Alpha (Tidak masuk tanpa keterangan)', 20, 'Tidak hadir ke sekolah tanpa surat izin atau keterangan yang sah', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(9, 4, 'PLG-009', 'Membuat kegaduhan / keributan di kelas', 15, 'Membuat suasana kelas menjadi gaduh dan mengganggu proses KBM', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(10, 4, 'PLG-010', 'Tidak sopan / berkata kasar kepada guru / staf', 30, 'Menggunakan bahasa atau gestur yang tidak sopan kepada warga sekolah', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(11, 4, 'PLG-011', 'Mencoret-coret fasilitas sekolah (dinding/meja/kursi)', 25, 'Merusak estetika fasilitas kelas atau sekolah dengan coretan', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(12, 5, 'PLG-012', 'Membawa dan/atau merokok di lingkungan sekolah', 50, 'Kedapatan merokok atau membawa rokok di area sekolah', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(13, 5, 'PLG-013', 'Membawa HP / Gadget saat pelajaran tanpa izin', 25, 'Menggunakan handphone di kelas tanpa instruksi atau izin guru pengampu', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(14, 5, 'PLG-014', 'Terlibat perkelahian / tawuran antar pelajar', 100, 'Melakukan kontak fisik kekerasan atau tawuran di dalam maupun luar sekolah', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(15, 5, 'PLG-015', 'Membawa senjata tajam atau benda berbahaya', 50, 'Membawa senjata tajam, senjata api mainan berbahaya, atau alat pemukul', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(53, 5, 'PLG-016', 'Membawa senjata tajam atau benda berbahaya', 30, 'membawa minuman keras', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran_kategori`
--

CREATE TABLE `t_pelanggaran_kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_pelanggaran_kategori`
--

INSERT INTO `t_pelanggaran_kategori` (`id`, `nama`, `deskripsi`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'Kedisiplinan', 'Pelanggaran terkait aturan waktu dan tata tertib umum sekolah', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(2, 'Kerapihan Pakaian', 'Pelanggaran terkait kelengkapan atribut dan seragam sekolah', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(3, 'Kehadiran & Ketidakhadiran', 'Pelanggaran terkait absensi, membolos, dan jam pelajaran', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(4, 'Sikap & Perilaku', 'Pelanggaran terkait sopan santun, ketertiban kelas, dan etika', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26'),
(5, 'Barang Terlarang & Tindak Kriminal', 'Pelanggaran berat terkait kepemilikan barang terlarang atau tindakan kekerasan', 1, '2026-11-04 00:54:26', '2026-11-04 00:54:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran_siswa`
--

CREATE TABLE `t_pelanggaran_siswa` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `nama_siswa` varchar(150) DEFAULT NULL,
  `kelas_id` int(11) NOT NULL,
  `nama_kelas` varchar(100) DEFAULT NULL,
  `pelanggaran_id` int(11) NOT NULL,
  `nama_pelanggaran` varchar(150) DEFAULT NULL,
  `pelanggaran_kategori_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `nama_guru` varchar(150) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `poin` int(11) DEFAULT NULL,
  `tindakan` text DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_pelanggaran_siswa`
--

INSERT INTO `t_pelanggaran_siswa` (`id`, `tahun_ajaran_id`, `siswa_id`, `nama_siswa`, `kelas_id`, `nama_kelas`, `pelanggaran_id`, `nama_pelanggaran`, `pelanggaran_kategori_id`, `guru_id`, `nama_guru`, `tanggal`, `keterangan`, `poin`, `tindakan`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'JUNITA PUTRI NUR RAHMAN', 33, 'XII RPL 2', 1, 'Terlambat hadir di sekolah', 1, 32, 'Aldi Maulana, S.Pd', '2026-11-04', 'Terlambat masuk gerbang sekolah', 10, 'Peringatan lisan', 'Selesai', '2026-11-04 02:46:52', '2026-11-04 02:46:52'),
(2, 1, 55, 'ALFIN RAHADIAN', 32, 'XII RPL 1', 6, 'Rambut panjang / dicat (khusus siswa laki-laki)', 2, 1, 'Wawan Kurniawan, S.Pd.I', '2026-11-10', '', 15, 'Panggilan Orang Tua / SP 1', 'Selesai', '2026-11-10 10:06:13', NULL),
(3, 1, 3, 'ANISA AMIRULLA', 33, 'XII RPL 2', 11, 'Mencoret-coret fasilitas sekolah (dinding/meja/kursi)', 4, 1, 'Wawan Kurniawan, S.Pd.I', '2026-11-10', '', 25, 'Skorsing & Pembinaan Khusus', 'Selesai', '2026-11-10 10:11:13', NULL),
(4, 1, 69, 'MUHAMMAD ZIDANE MAULIDAN', 32, 'XII RPL 1', 3, 'Tidak mengikuti upacara bendera', 1, 32, 'Aldi Maulana, S.Pd', '2026-11-10', '', 20, 'Panggilan Orang Tua / SP 1', 'Selesai', '2026-11-10 10:24:03', NULL),
(5, 1, 78, 'TAZKIA ZAHRA', 32, 'XII RPL 1', 8, 'Alpha (Tidak masuk tanpa keterangan)', 3, 32, 'Aldi Maulana, S.Pd', '2026-11-10', '', 20, 'Panggilan Orang Tua / SP 1', 'Selesai', '2026-11-10 10:24:54', NULL),
(6, 1, 56, 'ALISA RAMADHANI', 32, 'XII RPL 1', 4, 'Atribut seragam tidak lengkap', 2, 32, 'Aldi Maulana, S.Pd', '2026-11-10', '', 10, 'Peringatan Lisan', 'Selesai', '2026-11-10 10:31:46', NULL),
(7, 1, 2, 'AGNIA ROSYA NOERILLAH', 33, 'XII RPL 2', 14, 'Terlibat perkelahian / tawuran antar pelajar', 5, 32, 'Aldi Maulana, S.Pd', '2026-11-10', '', 100, 'Dikembalikan kepada Orang Tua', 'Selesai', '2026-11-10 10:32:37', NULL),
(8, 1, 61, 'ERLANGGA SAPUTRA HIDAYAT', 32, 'XII RPL 1', 4, 'Atribut seragam tidak lengkap', 2, 32, 'Aldi Maulana, S.Pd', '2026-11-10', '', 10, 'Peringatan Lisan', 'Selesai', '2026-11-10 10:44:44', NULL),
(9, 1, 2, 'AGNIA ROSYA NOERILLAH', 33, 'XII RPL 2', 2, 'Keluar kelas tanpa izin saat jam pelajaran', 1, 28, 'Ari Sandriana, S.Kom', '2026-11-10', '', 15, 'Panggilan Orang Tua / SP 1', 'Selesai', '2026-11-10 11:24:22', NULL),
(10, 1, 64, 'JULIANDA DHARMA PANGESTU', 32, 'XII RPL 1', 13, 'Membawa HP / Gadget saat pelajaran tanpa izin', 5, 22, 'Ai Wahidah, S.Ag', '2026-11-11', '', 25, 'Skorsing & Pembinaan Khusus', 'Proses', '2026-11-11 00:17:17', NULL),
(11, 1, 78, 'TAZKIA ZAHRA', 32, 'XII RPL 1', 1, 'Terlambat hadir di sekolah', 1, 10, 'H. Encep Maemun, S.T., M.Pd', '2026-11-11', '', 10, 'Peringatan Lisan', 'Selesai', '2026-11-11 00:19:35', NULL),
(12, 1, 57, 'ALMA AURA LIVIA', 32, 'XII RPL 1', 2, 'Keluar kelas tanpa izin saat jam pelajaran', 1, 10, 'H. Encep Maemun, S.T., M.Pd', '2026-11-11', '', 15, 'Panggilan Orang Tua / SP 1', 'Selesai', '2026-11-11 00:23:01', NULL),
(14, 1, 26, 'TONI ANTONIO', 33, 'XII RPL 2', 13, 'Membawa HP / Gadget saat pelajaran tanpa izin', 5, 10, 'H. Encep Maemun, S.T., M.Pd', '2026-11-11', '', 25, 'Skorsing & Pembinaan Khusus', 'Selesai', '2026-11-11 00:24:01', NULL),
(15, 1, 66, 'MOZA DINA NABILLAH', 32, 'XII RPL 1', 4, 'Atribut seragam tidak lengkap', 2, 10, 'H. Encep Maemun, S.T., M.Pd', '2026-11-11', '', 10, 'Peringatan Lisan', 'Selesai', '2026-11-11 00:24:17', NULL),
(20, 1, 1, 'ADITTIA NUR LANTIP', 33, 'XII RPL 2', 3, 'Tidak mengikuti upacara bendera', 1, 28, 'Ari Sandriana, S.Kom', '2026-11-11', '', 20, 'Panggilan Orang Tua / SP 1', 'Selesai', '2026-11-11 02:13:01', NULL),
(21, 1, 56, 'ALISA RAMADHANI', 32, 'XII RPL 1', 3, 'Tidak mengikuti upacara bendera', 1, 28, 'Ari Sandriana, S.Kom', '2026-11-11', '', 20, 'Panggilan Orang Tua / SP 1', 'Selesai', '2026-11-11 02:13:35', NULL),
(22, 1, 56, 'ALISA RAMADHANI', 32, 'XII RPL 1', 5, 'Seragam tidak sesuai ketentuan / jadwal', 2, 28, 'Ari Sandriana, S.Kom', '2026-11-11', '', 15, 'Panggilan Orang Tua / SP 1', 'Selesai', '2026-11-11 02:25:40', NULL),
(23, 1, 55, 'ALFIN RAHADIAN', 32, 'XII RPL 1', 5, 'Seragam tidak sesuai ketentuan / jadwal', 2, 32, 'Aldi Maulana, S.Pd', '2026-11-11', '', 15, 'Panggilan Orang Tua / SP 1', 'Selesai', '2026-11-11 02:46:15', NULL),
(24, 1, 25, 'SURAIYA SALSABILA AZZAHRA', 33, 'XII RPL 2', 10, 'Tidak sopan / berkata kasar kepada guru / staf', 4, 32, 'Aldi Maulana, S.Pd', '2026-11-11', '', 30, 'baca quran sampa hatam', 'Selesai', '2026-11-11 05:40:53', NULL),
(25, 1, 56, 'ALISA RAMADHANI', 32, 'XII RPL 1', 4, 'Atribut seragam tidak lengkap', 2, 32, 'Aldi Maulana, S.Pd', '2026-11-11', '', 10, 'Peringatan Lisan', 'Pending', '2026-11-11 06:02:20', NULL),
(26, 1, 21, 'SENDI MARDIAN', 33, 'XII RPL 2', 5, 'Seragam tidak sesuai ketentuan / jadwal', 2, 32, 'Aldi Maulana, S.Pd', '2026-11-11', '', 15, 'kasih arahan', 'Selesai', '2026-11-11 06:09:17', NULL),
(27, 1, 56, 'ALISA RAMADHANI', 32, 'XII RPL 1', 9, 'Membuat kegaduhan / keributan di kelas', 4, 32, 'Aldi Maulana, S.Pd', '2026-11-11', 'panggil ke bk', 20, 'Panggilan Orang Tua / SP 1', 'Selesai', '2026-11-11 06:18:02', NULL),
(28, 1, 59, 'AZMIL AMIRA', 32, 'XII RPL 1', 2, 'Keluar kelas tanpa izin saat jam pelajaran', 1, 32, 'Aldi Maulana, S.Pd', '2026-11-11', '', 15, 'Panggilan Orang Tua / SP 1', 'Pending', '2026-11-11 06:21:54', NULL),
(29, 1, 16, 'RANI MEILANI', 33, 'XII RPL 2', 1, 'Terlambat hadir di sekolah', 1, 32, 'Aldi Maulana, S.Pd', '2026-11-11', 'Baca Surah', 10, 'Peringatan Lisan', 'Selesai', '2026-11-11 06:50:30', NULL),
(30, 1, 55, 'ALFIN RAHADIAN', 32, 'XII RPL 1', 3, 'Tidak mengikuti upacara bendera', 1, 10, 'H. Encep Maemun, S.T., M.Pd', '2026-11-11', 'Keliling Lapangan', 20, 'Panggilan Orang Tua / SP 1', 'Pending', '2026-11-11 07:51:31', NULL),
(31, 1, 14, 'PUSPITA DEWI', 33, 'XII RPL 2', 53, 'Membawa senjata tajam atau benda berbahaya', 5, 32, 'Aldi Maulana, S.Pd', '2026-11-11', 'Keluarkan', 30, 'Skorsing & Pembinaan Khusus', 'Selesai', '2026-11-11 08:19:55', NULL),
(32, 1, 16, 'RANI MEILANI', 33, 'XII RPL 2', 3, 'Tidak mengikuti upacara bendera', 1, 10, 'H. Encep Maemun, S.T., M.Pd', '2026-11-11', '', 20, 'dsasdas', 'Selesai', '2026-11-11 09:19:41', NULL),
(33, 1, 16, 'RANI MEILANI', 33, 'XII RPL 2', 4, 'Atribut seragam tidak lengkap', 2, 10, 'H. Encep Maemun, S.T., M.Pd', '2026-11-11', '', 10, 'Peringatan Lisan', 'Selesai', '2026-11-11 09:19:57', NULL),
(34, 1, 2, 'AGNIA ROSYA NOERILLAH', 33, 'XII RPL 2', 3, 'Tidak mengikuti upacara bendera', 1, 32, 'Aldi Maulana, S.Pd', '2026-11-11', 'tgdfsgdsfdsf', 20, 'Panggilan Orang Tua / SP 1', 'Pending', '2026-11-11 10:17:01', NULL),
(35, 1, 2, 'AGNIA ROSYA NOERILLAH', 33, 'XII RPL 2', 1, 'Terlambat hadir di sekolah', 1, 32, 'Aldi Maulana, S.Pd', '2026-11-11', 'AEWrdaesrerer', 10, 'Peringatan Lisan', 'Selesai', '2026-11-11 10:17:19', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_siswa`
--

CREATE TABLE `t_siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(30) NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `jenis_kelamin` char(1) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_siswa`
--

INSERT INTO `t_siswa` (`id`, `nis`, `nisn`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, '102410544', '0085000001', 'ADITTIA NUR LANTIP', 'L', '2008-01-10', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(2, '102410545', '0085000002', 'AGNIA ROSYA NOERILLAH', 'P', '2008-02-12', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(3, '102410546', '0085000003', 'ANISA AMIRULLA', 'P', '2008-03-15', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(4, '102410547', '0085000004', 'ARADEA KANDI', 'L', '2008-04-20', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(5, '102410548', '0085000005', 'ARFA DIESA OKMALELIZA', 'P', '2008-05-22', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(6, '102410549', '0085000006', 'FITRI NUR SYIAMI', 'P', '2008-06-05', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(7, '102410550', '0085000007', 'JUNITA PUTRI NUR RAHMAN', 'P', '2008-05-12', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(8, '102410551', '0085000008', 'MOHAMMAD ATALARIK ALI SYAHBANA', 'L', '2008-07-11', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(9, '102410552', '0085000009', 'MUHAMAD FAHRIL FAHRUROZI', 'L', '2008-08-14', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(10, '102410750', '0085000010', 'MUHAMMAD ADNAN SYAEFUL ISLAMI', 'L', '2008-09-03', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(11, '102410553', '0085000011', 'MUHAMMAD ZAHRU AQILA FIKRI', 'L', '2008-10-19', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(12, '102410554', '0085000012', 'NADIA ZULFA SORAYA', 'P', '2008-11-25', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(13, '102410555', '0085000013', 'NAZRIL ILHAM', 'L', '2008-12-01', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(14, '102410556', '0085000014', 'PUSPITA DEWI', 'P', '2008-01-18', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(15, '102410558', '0085000015', 'RAKHA MUHAMMAD HAYKAL', 'L', '2008-02-28', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(16, '102410559', '0085000016', 'RANI MEILANI', 'P', '2008-05-09', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(17, '102410560', '0085000017', 'REIHAN MUZAKI', 'L', '2008-04-12', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(18, '19800101201018', '0085000018', 'RESA JULIANSAH', 'L', '2008-07-29', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(19, '102410562', '0085000019', 'RIZKI PERMATA SUGIANTO', 'L', '2008-08-19', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(20, '102410563', '0085000020', 'SAHIRA FIRAJ', 'P', '2008-03-30', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(21, '102410564', '0085000021', 'SENDI MARDIAN', 'L', '2008-06-15', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(22, '102410755', '0085000022', 'SHAHAIDAN NIZZAR', 'L', '2008-09-10', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(23, '102410565', '0085000023', 'SHERLY SATRIANI', 'P', '2008-10-04', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(24, '102410566', '0085000024', 'SOFI NURJANAH', 'P', '2008-11-11', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(25, '102410567', '0085000025', 'SURAIYA SALSABILA AZZAHRA', 'P', '2008-12-20', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(26, '102410568', '0085000026', 'TONI ANTONIO', 'L', '2008-02-14', 'Tasikmalaya', 1, '2026-11-03 02:51:02', '2026-11-03 02:51:02'),
(55, '102410520', '0085000520', 'ALFIN RAHADIAN', 'L', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(56, '102410521', '0085000521', 'ALISA RAMADHANI', 'P', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(57, '102410522', '0085000522', 'ALMA AURA LIVIA', 'P', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(58, '102410523', '0085000523', 'ANIS KHOERUNNISA NUR RAMADANI', 'P', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(59, '102410524', '0085000524', 'AZMIL AMIRA', 'P', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(60, '102410525', '0085000525', 'AZRIL AKBAR FAWJI ABDILLAH', 'L', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(61, '102410527', '0085000527', 'ERLANGGA SAPUTRA HIDAYAT', 'L', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(62, '102410528', '0085000528', 'HILMA HUMMA TUNNAJAH', 'P', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(63, '102410529', '0085000529', 'ILMA NAJYA', 'P', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(64, '102410531', '0085000531', 'JULIANDA DHARMA PANGESTU', 'L', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(65, '102410532', '0085000532', 'KAYILA NUR FADILAH', 'P', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(66, '102410533', '0085000533', 'MOZA DINA NABILLAH', 'P', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(67, '102410534', '0085000534', 'MUHAMAD ALFARIZI', 'L', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(68, '102410535', '0085000535', 'MUHAMAD FAHRIN ADAM', 'L', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(69, '102410536', '0085000536', 'MUHAMMAD ZIDANE MAULIDAN', 'L', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(70, '102410537', '0085000537', 'MUHAMMAD ZULFAN NURALFANSYAH', 'L', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(71, '102410538', '0085000538', 'NATIYA NUR AZKIYA', 'P', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(72, '102410539', '0085000539', 'NOVITA PUSPITASARI', 'P', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(73, '102410540', '0085000540', 'NUCKY ANDREAS FEBRIAN', 'L', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(74, '102410743', '0085000743', 'RADITYA JETI PRASETYA', 'L', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(75, '102410541', '0085000541', 'RANGGA RAMADIYANTO NURHAKIM', 'L', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(76, '102410542', '0085000542', 'RISQI MUHAMAD IHWANI', 'L', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(77, '102410753', '0085000753', 'SALWA SABILATUL BARKAH', 'P', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54'),
(78, '102410543', '0085000543', 'TAZKIA ZAHRA', 'P', '2008-01-01', 'Tasikmalaya', 1, '2026-11-10 10:02:54', '2026-11-10 10:02:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_tahun_ajaran`
--

CREATE TABLE `t_tahun_ajaran` (
  `id` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_tahun_ajaran`
--

INSERT INTO `t_tahun_ajaran` (`id`, `nama`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, '2026/2027', '2026-07-15', '2027-06-20', 1, '2026-11-04 02:39:14', '2026-11-04 02:39:14'),
(2, '2027/2028', '2027-07-15', '2028-06-20', 0, '2026-11-04 02:39:14', '2026-11-04 02:39:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_users`
--

CREATE TABLE `t_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_users`
--

INSERT INTO `t_users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(2, 'Wawan Kurniawan, S.Pd.I', 'wawan@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(3, 'Ai Nuryaidah, S.Pd', 'ai@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(4, 'Maya Permata Endah, S.Pd', 'maya@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(5, 'Dody Jaenudin, S.Pd.', 'dody@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(6, 'Dedi Kardiman, S. Pd', 'dedi@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(7, 'Uca Musa Yusenda, S.Pd', 'uca@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(8, 'Drs. Ahmad Nur Mukhlis', 'ahmad@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(9, 'Drs. Heri', 'heri@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(10, 'Asep Bastaman', 'asep@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(11, 'H. Encep Maemun, S.T., M.Pd', 'encep@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(12, 'Opik', 'opik@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(13, 'Ida Farida, S. Pd', 'ida@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(14, 'Idah Mahmudah, S.Pd', 'idah@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(15, 'Drs. Muhamad Ali', 'muhamad@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(16, 'Helmi Ali Akbar, S.Pd.I', 'helmi@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(17, 'Syeni Suswarini, S.Pd', 'syeni@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(18, 'Wiwid Yulianti, S.Pd', 'wiwid@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(19, 'Ansor Muhajir, S.T', 'ansor@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(20, 'Ogi Gaosun Gias, S.Pd.I', 'ogi@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(21, 'Sandi Mulyana, S.Pd', 'sandi@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(22, 'Erna Suryani, S.Pd', 'erna@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(23, 'Ai Wahidah, S.Ag', 'aiwahidah@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(24, 'Chandra Aditya Yusup, S.Pd', 'chandra@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(25, 'Arif Kusmawan, S.Pd', 'arif@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(26, 'Asep Taufiq Hidayat, S.Kom', 'aseptaufiq@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(27, 'Rendy Ichsan Kusumaḥ, S.T', 'rendy@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(28, 'Feri Rahmawan, S.Pd', 'feri@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(29, 'Ari Sandriana, S.Kom', 'arisandriana@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(30, 'Yaneu Nurdian, S.Pd, M.Pd', 'yaneu@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(31, 'Ucu Muhammad Khoer N, S.Pd', 'ucu@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(32, 'Mira Dwi Rahmawati, S.Ag', 'mira@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(33, 'Aldi Maulana, S.Pd', 'aldi@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(34, 'Eli Rosliah, S.Pd', 'eli@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(35, 'Isni Nur Fitriani, S.Pd', 'isni@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(36, 'Aanisah Putri Nur Aini, S.Pd', 'aanisah@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(37, 'Yusi Luthfiani, S.Sos.I', 'yusi@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(38, 'Lilis Rustandy Mawardi, S.Pd., M.Pd', 'lilis@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(39, 'Yusrin Noer Sabrina, S.Pd', 'yusrin@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(40, 'Senny Indayani, S.Pd', 'senny@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(41, 'Ilma Amalia, S.Pd', 'ilma@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(42, 'Alvira Sofwatunnisa, S.Pd', 'alvira@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(43, 'Risna Andriyani, S.Pd', 'risna@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(44, 'Dra. Tintin Noerhayati', 'tintin@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(45, 'Rizkia Fahira, S.Pd', 'rizkia@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(46, 'Frasetio', 'frasetio@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(47, 'Ratnaningsih Hatil Aeni, S.Pd.', 'ratnaningsih@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(48, 'Dheny Darajatul Hasani, S.Pd.', 'dheny@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(49, 'Fahrullah, S.Pd', 'fahrullah@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(50, 'Aan Andriana, S.T', 'aan@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(51, 'Fitri Fauziah, S.T', 'fitrifauziah@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(52, 'Nida Aulia, S.Pd', 'nida@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(53, 'Sri Nurrizki Putri Krisnadi, S.Pd', 'srinurrizki@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(54, 'Riesy Putri Navia, S.Kom', 'riesy@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(55, 'Rurry Febriani Maryadi, S.Pd', 'rurry@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14'),
(56, 'Irvan Rizal, S.Pd', 'irvan@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-11-03 04:12:14', '2026-11-03 04:12:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_wali_kelas`
--

CREATE TABLE `t_wali_kelas` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_wali_kelas`
--

INSERT INTO `t_wali_kelas` (`id`, `tahun_ajaran_id`, `kelas_id`, `guru_id`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, 33, 32, '2026-07-15', '2027-06-20', 1, '2026-11-04 02:41:04', '2026-11-04 02:41:04'),
(2, 1, 32, 28, '2026-11-10', NULL, 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_guru_user` (`user_id`);

--
-- Indeks untuk tabel `t_kelas`
--
ALTER TABLE `t_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ks_siswa` (`siswa_id`),
  ADD KEY `fk_ks_tahun_ajaran` (`tahun_ajaran_id`),
  ADD KEY `fk_ks_kelas` (`kelas_id`);

--
-- Indeks untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pelanggaran_kategori` (`pelanggaran_kategori_id`);

--
-- Indeks untuk tabel `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ps_tahun_ajaran` (`tahun_ajaran_id`),
  ADD KEY `fk_ps_siswa` (`siswa_id`),
  ADD KEY `fk_ps_kelas` (`kelas_id`),
  ADD KEY `fk_ps_pelanggaran` (`pelanggaran_id`),
  ADD KEY `fk_ps_pelanggaran_kategori` (`pelanggaran_kategori_id`),
  ADD KEY `fk_ps_guru` (`guru_id`);

--
-- Indeks untuk tabel `t_siswa`
--
ALTER TABLE `t_siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_users`
--
ALTER TABLE `t_users`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wk_tahun_ajaran` (`tahun_ajaran_id`),
  ADD KEY `fk_wk_kelas` (`kelas_id`),
  ADD KEY `fk_wk_guru` (`guru_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT untuk tabel `t_kelas`
--
ALTER TABLE `t_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `t_siswa`
--
ALTER TABLE `t_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT untuk tabel `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  ADD CONSTRAINT `fk_guru_user` FOREIGN KEY (`user_id`) REFERENCES `t_users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD CONSTRAINT `fk_ks_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`),
  ADD CONSTRAINT `fk_ks_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`),
  ADD CONSTRAINT `fk_ks_tahun_ajaran` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`);

--
-- Ketidakleluasaan untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD CONSTRAINT `fk_pelanggaran_kategori` FOREIGN KEY (`pelanggaran_kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`);

--
-- Ketidakleluasaan untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD CONSTRAINT `fk_ps_guru` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`),
  ADD CONSTRAINT `fk_ps_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`),
  ADD CONSTRAINT `fk_ps_pelanggaran` FOREIGN KEY (`pelanggaran_id`) REFERENCES `t_pelanggaran` (`id`),
  ADD CONSTRAINT `fk_ps_pelanggaran_kategori` FOREIGN KEY (`pelanggaran_kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`),
  ADD CONSTRAINT `fk_ps_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`),
  ADD CONSTRAINT `fk_ps_tahun_ajaran` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`);

--
-- Ketidakleluasaan untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD CONSTRAINT `fk_wk_guru` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`),
  ADD CONSTRAINT `fk_wk_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`),
  ADD CONSTRAINT `fk_wk_tahun_ajaran` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
