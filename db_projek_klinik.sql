-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Des 2025 pada 17.33
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
-- Database: `db_projek_klinik`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `appointment`
--

CREATE TABLE `appointment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text NOT NULL,
  `keluhan` text NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `status` enum('Menunggu','Disetujui','Selesai','Dibatalkan') NOT NULL,
  `admin_notes` text DEFAULT NULL,
  `confirmed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `appointment`
--

INSERT INTO `appointment` (`id`, `user_id`, `nama`, `no_hp`, `tanggal_lahir`, `alamat`, `keluhan`, `tanggal`, `jam`, `status`, `admin_notes`, `confirmed_by`, `confirmed_at`, `created_at`, `updated_at`) VALUES
(8, 5, 'Riko', '0876453', '1990-12-20', 'Jl. Melati', 'Konsultasi luka', '2025-11-11', '11:00:00', 'Dibatalkan', 'tolong jadwalin dihari selanjutnya ya dikarenakan klinik sedang libur', NULL, NULL, '2025-11-04 17:58:55', '2025-11-05 00:15:43'),
(9, 5, 'Riko', '085353', '2025-11-05', 'Jalan jambi - palembang km 17 desa muaro sebapo kecamtana mestong', 'sakit bagian punggung', '2025-11-12', '11:00:00', 'Disetujui', NULL, NULL, NULL, '2025-11-05 00:05:11', '2025-11-05 00:05:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_sessions`
--

CREATE TABLE `chat_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `message_count` int(11) NOT NULL DEFAULT 0,
  `is_premium` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `started_at` timestamp NULL DEFAULT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `chat_sessions`
--

INSERT INTO `chat_sessions` (`id`, `patient_id`, `doctor_id`, `message_count`, `is_premium`, `is_active`, `started_at`, `ended_at`, `created_at`, `updated_at`) VALUES
(1, 5, 7, 5, 1, 0, '2025-12-06 09:15:14', '2025-12-10 04:43:12', '2025-12-06 09:15:14', '2025-12-10 04:43:12'),
(2, 5, 19, 2, 1, 1, '2025-12-10 03:53:32', NULL, '2025-12-10 03:53:32', '2025-12-10 04:41:16'),
(3, 5, 7, 3, 1, 0, '2025-12-10 04:43:21', '2025-12-10 05:00:38', '2025-12-10 04:43:21', '2025-12-10 05:00:38'),
(4, 5, 7, 0, 1, 1, '2025-12-10 05:00:48', NULL, '2025-12-10 05:00:48', '2025-12-10 05:00:48'),
(5, 5, 17, 0, 1, 1, '2025-12-10 05:01:01', NULL, '2025-12-10 05:01:01', '2025-12-10 05:01:01'),
(6, 6, 7, 3, 1, 0, '2025-12-10 05:17:23', '2025-12-10 05:46:59', '2025-12-10 05:17:23', '2025-12-10 05:46:59'),
(7, 6, 7, 0, 1, 1, '2025-12-10 05:48:24', NULL, '2025-12-10 05:48:24', '2025-12-10 05:48:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ch_favorites`
--

CREATE TABLE `ch_favorites` (
  `id` char(36) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `favorite_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ch_messages`
--

CREATE TABLE `ch_messages` (
  `id` char(36) NOT NULL,
  `from_id` bigint(20) NOT NULL,
  `to_id` bigint(20) NOT NULL,
  `body` varchar(5000) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ch_messages`
--

INSERT INTO `ch_messages` (`id`, `from_id`, `to_id`, `body`, `attachment`, `seen`, `created_at`, `updated_at`) VALUES
('0357e3de-3988-46c5-be97-06b3949b5eea', 5, 7, 'tidak ada dok makasih banyak ya', NULL, 1, '2025-12-09 18:56:00', '2025-12-09 18:56:02'),
('0c2f9f2b-d7da-4b59-8371-bcd1b1a8597a', 5, 7, 'haidok', NULL, 1, '2025-12-01 04:14:09', '2025-12-01 04:14:37'),
('0ddb49d1-b0ab-4d6c-b7d1-63deddb2488a', 7, 5, 'ini untuk batuknya yaa diminum sehabis makan sama kayak tadi', '{\"new_name\":\"0dff2134-b04d-4679-b5d7-ef4a95c63ab7.jpg\",\"old_name\":\"viks.jpg\"}', 1, '2025-12-09 18:55:34', '2025-12-09 18:55:36'),
('150317d6-7b33-42db-aa1b-c86eb90aed70', 6, 7, 'fdfgd', NULL, 0, '2025-12-10 06:10:11', '2025-12-10 06:10:11'),
('19e8996a-8239-4cc1-99c9-4ff99f577b1b', 7, 5, 'jh,,jhg', NULL, 1, '2025-12-10 05:00:34', '2025-12-10 05:00:36'),
('1a7725ee-bf1c-47a6-b6a1-175c265fb994', 5, 7, 'saya merasa sakit tenggorokan dua hari ini', NULL, 1, '2025-12-01 04:15:46', '2025-12-01 04:15:52'),
('1b3d7120-5572-4fe8-88d7-b77859e4d877', 7, 5, 'halo ada keluhan', NULL, 1, '2025-11-18 13:05:19', '2025-11-18 13:05:22'),
('1d324a31-b3cb-49e1-865d-345d45656117', 7, 5, 'dfksodfkso', NULL, 1, '2025-12-10 03:09:34', '2025-12-10 03:09:35'),
('1dc6cecf-4e7f-4101-88fb-a9e687c2293b', 6, 7, 'gf', NULL, 0, '2025-12-10 06:10:19', '2025-12-10 06:10:19'),
('2a6161c8-a311-46c5-b401-a7ae04b287f5', 6, 7, 'kkkk', NULL, 1, '2025-12-10 05:18:02', '2025-12-10 05:31:25'),
('2c9cf56c-7146-4933-ab83-34b4c7e8e4c1', 5, 7, 'iya dok', NULL, 1, '2025-12-10 03:09:21', '2025-12-10 03:09:24'),
('2d584a90-e0e3-4a3d-b2ec-f1a4555d1216', 7, 5, 'sebelumnya kamu ada alergi obat gak? usia mu berapa dan apa akhir akhir ini kamu sering kelelahan', NULL, 1, '2025-12-01 06:02:05', '2025-12-01 06:02:12'),
('4431267d-a33c-4b6a-8e4e-876054ba0cd3', 6, 7, 'haiiii', NULL, 1, '2025-12-10 05:17:24', '2025-12-10 05:31:25'),
('46f8333c-04b2-47be-a978-6e7046ce7482', 6, 7, 'mnb', NULL, 1, '2025-12-10 05:48:24', '2025-12-10 05:49:15'),
('497f7b72-cbde-4068-8db6-d1e6c829135a', 7, 6, 'halo', NULL, 1, '2025-12-10 05:41:57', '2025-12-10 05:47:53'),
('4d4058be-a1c5-4cbf-9638-a885fd2d5092', 5, 19, 'k', NULL, 0, '2025-12-10 04:40:31', '2025-12-10 04:40:31'),
('59d0d15e-7b3d-490b-b9d8-342161d982b4', 5, 7, 'hallo doc', NULL, 1, '2025-11-18 13:03:55', '2025-11-18 13:05:10'),
('6043e3b9-cb26-43c3-81e3-84deebe20fae', 5, 7, 'cngfc vjvhygyuk\\', NULL, 1, '2025-12-10 05:00:57', '2025-12-10 05:01:09'),
('619d6702-a179-4da3-ab5e-935e8d5085c7', 5, 7, 'tidak ada dok', NULL, 1, '2025-12-01 04:27:54', '2025-12-01 05:34:06'),
('635d9007-0b0f-41d5-be72-81291f3eb24c', 7, 5, 'gfjh', NULL, 1, '2025-12-10 05:00:28', '2025-12-10 05:00:29'),
('6f996288-fd25-43dc-9be3-23057eaf553b', 7, 5, 'sama sama cepat sehat yaaa', NULL, 1, '2025-12-09 18:56:23', '2025-12-09 18:56:24'),
('76a41594-69b3-4eb8-a2f4-50f5b0f6937d', 5, 7, 'kjhkfyjfjy', NULL, 1, '2025-12-10 05:00:12', '2025-12-10 05:00:21'),
('8321546c-7835-4330-88ab-5318f4331c01', 5, 7, 'tidak ada alergi dok, usia saya 20 tahun sih', NULL, 1, '2025-12-09 18:50:39', '2025-12-09 18:50:41'),
('86c8d928-2f7a-4b4e-b4e8-0623e5b98fd9', 5, 7, 'oh iya ada demam juga', NULL, 1, '2025-12-01 05:35:30', '2025-12-01 05:35:36'),
('87246cbf-cd3a-44df-889e-a593cdc43a4d', 5, 7, 'dggff', NULL, 1, '2025-12-10 05:00:17', '2025-12-10 05:00:21'),
('99977595-9e2f-4e3c-abe2-f17ea22b65b0', 7, 5, 'hindari makanan pedas dulu yaa yang santan juga, makan yang ringan ringan aja dulu biar kondisi panas dalamnya sembuh', NULL, 1, '2025-12-09 18:52:23', '2025-12-09 18:52:25'),
('99c7aca6-8ce7-44ad-a853-8a2674010ceb', 7, 5, 'beberapa hari terakhir kamu ada mengkonsumsi sesuatu tidak?', NULL, 1, '2025-12-01 04:16:20', '2025-12-01 04:19:15'),
('a0cbc00a-2dec-46c9-8988-4bd5935c381f', 7, 5, 'kalau muncul meriang minum paracetamol ya kak', '{\"new_name\":\"d32867d5-5d27-43b2-a696-4a6392756bd1.jpg\",\"old_name\":\"paracetamol.jpeg\"}', 1, '2025-12-09 18:53:10', '2025-12-09 18:53:12'),
('ab1f8f3b-fdd8-4d80-8f42-df44fd2f9fc9', 7, 5, 'selain sakit tenggorokan ada keluhan lain seperti pilek', NULL, 1, '2025-12-01 05:34:22', '2025-12-01 05:35:00'),
('b49a64e1-308a-43f9-9267-dc05c3428380', 7, 5, '3 kali sehari ya', NULL, 1, '2025-12-09 18:55:40', '2025-12-09 18:55:42'),
('bb9558b6-e054-4a17-88dd-a9169ea4c090', 5, 7, 'p', NULL, 1, '2025-12-10 04:43:42', '2025-12-10 04:44:55'),
('bd053720-494e-4198-aa7e-c1e227137bfa', 5, 7, 'gyyvtfyftb', NULL, 1, '2025-12-10 04:42:54', '2025-12-10 04:43:09'),
('bf5292e2-9365-4ed1-9cef-3c52124226db', 5, 7, 'jhktft', NULL, 1, '2025-12-10 04:43:22', '2025-12-10 04:43:29'),
('c13fcf02-5969-497a-9648-f64560c07713', 5, 7, 'p', NULL, 1, '2025-12-10 04:43:46', '2025-12-10 04:44:55'),
('d1a4c95f-0c8c-49e1-9374-14823a0fac01', 5, 19, 'lkmlmk', NULL, 0, '2025-12-10 03:55:09', '2025-12-10 03:55:09'),
('d268659e-cb24-4e7c-b179-4985d8875455', 7, 5, 'kamu harus banyakistirahat yaaa itu ada tanda kamu kelelahan dan indikasi panas dalam kamu kurang banyakminum, perbanyak istirahat minum air putih', NULL, 1, '2025-12-09 18:51:42', '2025-12-09 18:51:44'),
('d2a2cc0a-2115-471b-9199-3d3f38967dae', 7, 5, 'halo', NULL, 1, '2025-12-01 04:14:41', '2025-12-01 04:14:45'),
('e2341e60-d2aa-48c4-a313-cf9ccd04d695', 5, 7, 'sama kadang meriang sih dok', NULL, 1, '2025-12-01 06:01:26', '2025-12-01 06:01:36'),
('e5a6ff2e-26ef-4e80-931d-b89cee64aa19', 6, 7, 'dok saya punya keluhan', NULL, 1, '2025-12-10 05:17:53', '2025-12-10 05:31:25'),
('eb17241a-9ead-4175-9a83-5cdf4e98c95d', 7, 5, 'ada pertanyaan lagi tidak', NULL, 1, '2025-12-09 18:55:50', '2025-12-09 18:55:52'),
('f6c1e3c0-2426-44f2-a6c9-b3959f53b487', 5, 7, 'vjvgvjg', NULL, 1, '2025-12-10 04:43:02', '2025-12-10 04:43:09'),
('f948ca08-3f11-4378-a98f-e6f9140383e7', 5, 7, 'jjhkiuhug', NULL, 1, '2025-12-10 05:00:51', '2025-12-10 05:01:09'),
('fae2396e-35f1-444a-ae82-08c42fb6921c', 5, 7, 'dsdcjdk', NULL, 1, '2025-12-10 05:00:06', '2025-12-10 05:00:21'),
('fd11a337-a590-46ef-945f-afa11cb62f4c', 6, 7, 'nbm', NULL, 1, '2025-12-10 05:48:29', '2025-12-10 05:49:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `jobs`
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
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_25_091920_create_sessions_table', 1),
(5, '2025_08_29_111833_add_role_to_users_table', 1),
(6, '2025_09_04_073051_create_tenaga_kesehatan_table', 2),
(7, '2025_09_04_100936_add_alamat_to_tenaga_kesehatan_table', 3),
(8, '2025_09_04_101510_remove_alamat_from_tenaga_kesehatan_table', 4),
(9, '2025_09_06_171705_create_obat_table', 5),
(10, '2025_09_06_193208_add_kategori_to_obat_table', 6),
(11, '2025_09_08_101623_remove_kode_from_obat_table', 7),
(12, '2025_10_04_045421_add_dosis_to_obat_table', 8),
(13, '2025_10_14_085159_create_appointment_table', 9),
(14, '2025_10_23_050915_add_user_id_to_appointment_table', 10),
(15, '2025_11_12_073530_create_profil__pasien_table', 11),
(16, '2025_11_14_181144_rename_profil__pasien_table', 12),
(17, '2025_11_18_999999_add_active_status_to_users', 13),
(18, '2025_11_18_999999_add_avatar_to_users', 13),
(19, '2025_11_18_999999_add_dark_mode_to_users', 13),
(20, '2025_11_18_999999_add_messenger_color_to_users', 13),
(21, '2025_11_18_999999_create_chatify_favorites_table', 13),
(22, '2025_11_18_999999_create_chatify_messages_table', 13),
(23, '2025_11_24_094117_update_tenaga_kesehatan_table_structure', 14),
(24, '2025_11_24_094124_update_tenaga_kesehatan_table_structure', 14),
(25, '2025_11_24_100224_update_tenaga_kesehatan_remove_profesi_change_shift', 15),
(26, '2025_11_24_105142_remove_pengalaman_from_tenaga_kesehatan', 16),
(28, '2024_12_07_000001_create_chat_sessions_table', 17),
(29, '2025_12_10_005811_create_transactions_table', 18),
(30, '2025_12_10_013458_create_subscriptions_table', 19);

-- --------------------------------------------------------

--
-- Struktur dari tabel `obat`
--

CREATE TABLE `obat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `foto` varchar(255) NOT NULL,
  `nama_obat` varchar(255) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `bentuk` varchar(255) NOT NULL,
  `klasifikasi` varchar(255) NOT NULL,
  `deskripsi` longtext NOT NULL,
  `dosis` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `obat`
--

INSERT INTO `obat` (`id`, `foto`, `nama_obat`, `kategori`, `bentuk`, `klasifikasi`, `deskripsi`, `dosis`, `created_at`, `updated_at`) VALUES
(1, 'obat/vxayvbuiakw884OeQ4QHer56nvWgLdDcmU7O6hkB.jpg', 'Minyak Kayu Putih', 'Obat Herbal', 'Cair', 'Aromatherapy', 'Minyak kayu putih yang dapat memberikan rasa hangat, menjaga tubuh tetap hangat dan nyaman terutama pada saat cuaca dingin. Membantu meringankan sakit perut, perut kembung, rasa mual, dan gatal-gatal akibat gigitan serangga /nyamuk.', NULL, '2025-09-06 11:26:13', '2025-10-03 09:59:09'),
(3, 'obat/HRk1a8qTLThBQjoLCobesAFKLu093T5KmlfUZ9oH.jpg', 'Paracetamol', 'Obat Bebas', 'Tablet', 'Analgesik dan Antipiretik', 'Paracetamol adalah obat yang digunakan untuk meredakan rasa sakit ringan hingga sedang (seperti sakit kepala, nyeri otot, atau sakit gigi) dan untuk menurunkan demam.', NULL, '2025-09-24 11:04:32', '2025-09-24 11:04:32'),
(4, 'obat/A6FZfT2fUTviS4lFF8nEU4EMWsY9w1UceacHDYVj.jpg', 'Anakonidin', 'Obat Bebas Terbatas', 'Cair', 'Sistem Pernapasan', 'ANAKONIDIN SIRUP mengandung zat aktif Dextromethorphan HBr, Guaifenesin, Pseudoephedrine HCl, dan Chlorpheniramine Maleat. Obat ini digunakan untuk mengatasi gejala pilek seperti bersin-bersin dan hidung tersumbat yang disertai batuk pada anak. Obat ini menyebabkan kantuk.', NULL, '2025-10-03 07:29:46', '2025-10-03 10:02:36'),
(5, 'obat/yt1GMVFZju5AdFutvze5SJ5dQY6Zqh298abv5XEe.png', 'Diapet', 'Jamu', 'Kapsul', 'Sistem Gastrointestinal & Hepatobilier', 'Untuk mengobati diare dan mencret, memadatkan kembali feses yang cair serta mengatasi rasa mulas. Hati-hati penggunaan pada anak-anak dan pasien lajut usia, hentikan konsumsi diapet jika timbul gejala alergi obat.', NULL, '2025-10-03 07:32:35', '2025-10-03 07:32:35'),
(6, 'obat/n2scaVT8s8KOQNrqGV6J3F4OPurOj8uS8sEMGRlR.png', 'Salbutamol', 'Obat keras', 'Tablet', 'Sistem Pernapasan', 'Salbutamol 2 mg 10 Tablet mengandung Salbutamol Sulfate. Salbutamol Sulfate adalah senyawa yang secara selektif merangsang reseptor beta-adrenergik pada otot polos bronkus, sehingga meningkatkan produksi AMP siklik dan mengaktifkan enzim adenil siklase yang menyebabkan relaksasi otot bronkus serta memperlebar saluran pernapasan. Obat ini digunakan untuk membantu meredakan kejang bronkus pada kondisi seperti asma bronkial, bronkitis kronis, dan emfisema dengan tujuan memperbaiki fungsi pernapasan melalui bronkodilatasi. Dalam penggunaan obat ini HARUS SESUAI PETUNJUK DOKTER.', NULL, '2025-10-03 10:20:52', '2025-10-03 10:20:52'),
(7, 'obat/79L2dW7B6YpeBNhVSvcFVKqMw35voyhbWTa2JfqV.jpg', 'BlackMores Vit D3 1000 IU', 'Obat Bebas', 'Kapsul', 'Vitamin & Suplemen', 'Blackmores Bio D3 1000 IU 60 Kapsul merupakan suplemen kesehatan yang diformulasikan untuk membantu memenuhi kebutuhan vitamin D secara cepat, khususnya pada kondisi tertentu seperti lanjut usia, ibu hamil dan menyusui, individu dengan risiko tinggi, atau penderita penyakit infeksi dan autoimun. Informasi Umum Terkait Kandungan*: *Informasi ini bersifat edukatif dan tidak dimaksudkan sebagai klaim manfaat langsung dari produk. Produk ini mengandung vitamin D3 (cholecalciferol) 1000 mg, yaitu bentuk vitamin D yang sama dengan yang secara alami diproduksi oleh tubuh saat terpapar sinar matahari. Vitamin D sendiri memiliki peran penting dalam berbagai fungsi tubuh. Beberapa faktor, seperti paparan sinar matahari yang terbatas, usia lanjut, atau kondisi kulit tertentu, dapat memengaruhi kadar vitamin D dalam tubuh, sehingga suplementasi mungkin diperlukan. Untuk penggunaannya, Blackmores Bio D3 1000 IU 60 Kapsul umumnya diberikan diberikan 1 kali sehari atau sesuai dosis yang dianjurkan. Penting untuk diingat bahwa produk ini adalah suplemen kesehatan dan tidak dimaksudkan sebagai pengganti pola makan seimbang atau gaya hidup sehat.', 'Sebaiknya diminum sesudah makan', '2025-10-03 10:22:58', '2025-10-03 22:15:24'),
(8, 'obat/OBwLrTXu118NSNX5cW4FAZKe4h38oyTKnI9hMxi1.jpg', 'Sidomuncul Vit C 1000mg', 'Obat Bebas', 'Tablet', 'Vitamin & Suplemen', 'Vitamin C atau asam askorbat berperan sebagai antioksidan yang dapat menguatkan sistem imun dan menjaga tubuh dari efek radikal bebas. Vitamin ini juga dibutuhkan tubuh untuk membentuk kolagen, mencegah anemia, dan mempercepat penyembuhan luka. Tidak hanya menguatkan daya tahan tubuh, minum suplemen vitamin C 1000 mg ini dapat menjaga kesehatan tulang, sendi, saraf, dan jantung. Jika Anda sedang menyusui, sebaiknya jangan minum suplemen ini tanpa berkonsultasi ke dokter terlebih dulu.', NULL, '2025-10-03 10:24:04', '2025-10-03 10:24:04'),
(9, 'obat/QQoMVZ8F4hStS8aWMqgFKGR4vrsq8MdoaYzBg8No.jpg', 'Ventolin Inhaler 100 Mcg 200 Dosis', 'Obat keras', 'Inhalasi', 'Sistem Pernapasan', 'VENTOLIN INHALER merupakan obat dengan kandungan Salbutamol yang digunakan untuk mengobati penyakit pada saluran pernafasan seperti asma dan penyakit paru obstruktif kronik (PPOK). Obat ini bekerja dengan cara merangsang secara selektif reseptor beta-2 adrenergik terutama pada otot bronkus. hal ini menyebabkan terjadinya bronkodilatasi karena otot bronkus mengalami relaksasi. Dalam penggunaan obat ini harus SESUAI DENGAN PETUNJUK DOKTER. Tata Cara : Duduk atau berdiri tegak saat menggunakan inhaler. Kocok inhaler dengan baik sebelum menghirupsnya. Langsung tarik napas perlahan begitu Anda menekan inhaler. Tahan napas selama minimal 10 detik setelah menghirupnya. Tarik dan buang napas perlahan diantara setiap isapan.', 'PENGGUNAAN OBAT INI HARUS SESUAI DENGAN PETUNJUK DOKTER. Dewasa : - Menghilangkan bronkospam akut : 100 atau 200 mcg - Pencegahan alergen atau bronkospasme akibat olahraga : 200 mcg - Terapi kronis : 200 mcg, 4 kali/hari Anak-anak : - Menghilangkan bronkospam akut : 100 mcg - Pencegahan alergen atau bronkospame akibat olahraga : 100 mcg - Terapi kronis : 200 mcg, 4 kali/hari\r\n\r\nAturan Pakai\r\nDuduk atau berdiri tegak saat menggunakan inhaler. Kocok inhaler dengan baik sebelum menghirupsnya. Langsung tarik napas perlahan begitu Anda menekan inhaler. Tahan napas selama minimal 10 detik setelah menghirupnya. Tarik dan buang napas perlahan diantara setiap isapan.', '2025-10-03 10:24:42', '2025-10-03 22:22:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil_pasien`
--

CREATE TABLE `profil_pasien` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `golongan_darah` enum('A','B','AB','O') DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `berat_badan` decimal(5,2) DEFAULT NULL,
  `tinggi_badan` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `profil_pasien`
--

INSERT INTO `profil_pasien` (`id`, `user_id`, `foto`, `no_hp`, `tanggal_lahir`, `alamat`, `golongan_darah`, `jenis_kelamin`, `berat_badan`, `tinggi_badan`, `created_at`, `updated_at`) VALUES
(1, 5, 'patient-photos/zMQDMXMd6Itk0SAfvrwHuFvYvDp3CzFSBWZMeUUP.jpg', '0876453', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-14 10:04:40', '2025-11-18 09:16:14'),
(2, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-14 11:42:34', '2025-11-14 11:42:34'),
(3, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-10 07:14:00', '2025-12-10 07:14:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reset_password`
--

CREATE TABLE `reset_password` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reset_email` varchar(255) NOT NULL,
  `reset_otp` varchar(255) NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `reset_password`
--

INSERT INTO `reset_password` (`id`, `reset_email`, `reset_otp`, `expires_at`, `created_at`, `updated_at`) VALUES
(2, 'zahra.f.andiani@gmail.com', '896312', '2025-12-10 07:20:05', '2025-12-10 07:05:05', '2025-12-10 07:05:05'),
(3, 'zahra.f.andiani@gmail.com', '984506', '2025-12-10 07:20:45', '2025-12-10 07:05:45', '2025-12-10 07:05:45'),
(4, 'zahra.f.andiani@gmail.com', '672531', '2025-12-10 07:21:14', '2025-12-10 07:06:14', '2025-12-10 07:06:14'),
(5, 'zahra.f.andiani@gmail.com', '665526', '2025-12-10 07:22:20', '2025-12-10 07:07:20', '2025-12-10 07:07:20'),
(7, 'zahra.f.andiani@gmail.com', '439918', '2025-12-10 07:27:23', '2025-12-10 07:12:23', '2025-12-10 07:12:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('eIHtXg2GgHa1NxEc7rNnhxnBxKfUHyAW3QBZL1Wy', 21, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidno5OWVQMXhGWUhtandPTVJXRUNRWXRnalREUWk0YzNHZzF1akZ5aCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9maWwiO31zOjExOiJyZXNldF9lbWFpbCI7czoyNToiemFocmEuZi5hbmRpYW5pQGdtYWlsLmNvbSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjE7fQ==', 1765376041),
('ETqtZXeD7JwGp3YlW40qBJifer06O6Pa1z5JZ1DR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTUlDYVBJTm14RUlsMWRqTDlXOU9ZbTB6OHMwcFhJNk5CRUQxb1dWRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1765370854),
('q0WtX9038Ky9Y6Oc69qoTmMz0Mcw1Sd51L02K8Th', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZGJZa0FYc25HbDRKVHpxSzNpVVJSWXpLWjRUWVZRWjBXTDVBNzRnRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jaGF0aWZ5LzciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=', 1765378388),
('wT6LQcC4TJIBkTjJu046vEHAMZeDBtZk7frN3W13', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMXJkcUEzODZOZGJ0ZVBNUkE1ZmxBTGJUNkRZMlFsUmVYN2RvY2piSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jaGF0aWZ5LzUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo3O30=', 1765382050);

-- --------------------------------------------------------

--
-- Struktur dari tabel `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `plan_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `starts_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `plan_name`, `price`, `status`, `starts_at`, `expires_at`, `transaction_id`, `created_at`, `updated_at`) VALUES
(1, 5, 'monthly', 50000.00, 'active', '2025-12-10 04:41:16', '2026-01-10 04:41:16', 'ORDER-1765366842-kmIpH6', '2025-12-10 04:41:16', '2025-12-10 04:41:16'),
(2, 6, 'monthly', 50000.00, 'active', '2025-12-10 05:29:25', '2026-01-10 05:29:25', 'ORDER-1765369550-s4JWkf', '2025-12-10 05:29:25', '2025-12-10 05:29:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tenaga_kesehatan`
--

CREATE TABLE `tenaga_kesehatan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `foto_path` varchar(255) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hp` varchar(25) DEFAULT NULL,
  `str` varchar(255) DEFAULT NULL,
  `sip` varchar(255) DEFAULT NULL,
  `tahun_mulai` year(4) DEFAULT NULL,
  `jadwal_shift` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`jadwal_shift`)),
  `role` enum('dokter_umum','admin','superadmin') NOT NULL DEFAULT 'dokter_umum',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tenaga_kesehatan`
--

INSERT INTO `tenaga_kesehatan` (`id`, `user_id`, `foto_path`, `nama`, `email`, `hp`, `str`, `sip`, `tahun_mulai`, `jadwal_shift`, `role`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 7, 'tenaga_kesehatan/mJB1aawEEyhuDD01SYbXxmunLuQcX4nBwT6QsV0i.jpg', 'dr. Oh Sehun, Sp.PD', 'drsehun@gmail.com', '1234567890', 'MH2318401741', '503/SIP/2022/3', '2013', '[{\"hari\":\"Rabu\",\"jam_mulai\":\"08:00\",\"jam_selesai\":\"14:00\"}]', 'dokter_umum', '2025-09-04 03:05:41', '2025-11-25 14:56:39', NULL),
(10, 16, 'tenaga_kesehatan/VMcodgbVKyPaVENcNnrnMQMod4JUIDw7FctpYSXv.jpg', 'dr. Dinda Nurul Isnaini', 'dindanurulisnaini13@gmail.com', '1234567890', 'PJ00000073605100', 'MR15712503006074', '2024', '[{\"hari\":\"Selasa\",\"jam_mulai\":\"14:00\",\"jam_selesai\":\"21:00\"},{\"hari\":\"Kamis\",\"jam_mulai\":\"08:00\",\"jam_selesai\":\"14:00\"}]', 'dokter_umum', '2025-11-25 02:17:55', '2025-11-25 14:58:28', NULL),
(11, 17, 'tenaga_kesehatan/1FpSWhNN38VnFCzXZ6vC0hyyrVvJ0wuc01UfSU3F.jpg', 'dr Rifda Revonika', 'Rifdarevonika@gmail.com', '1234567890', 'KG00001651386009', 'MR15712505000383', '2024', '[{\"hari\":\"Senin\",\"jam_mulai\":\"08:00\",\"jam_selesai\":\"14:00\"},{\"hari\":\"Minggu\",\"jam_mulai\":\"14:00\",\"jam_selesai\":\"21:00\"}]', 'dokter_umum', '2025-11-25 02:29:03', '2025-11-25 14:59:06', NULL),
(12, NULL, NULL, 'Dr. Ahmad Fauzi', 'ahmad.fauzi@klinik.com', '081234567890', '123456789012345', '503/SIP/2020/001', '2014', '[{\"hari\":\"Senin\",\"jam_mulai\":\"14:00\",\"jam_selesai\":\"21:00\"},{\"hari\":\"Jumat\",\"jam_mulai\":\"08:00\",\"jam_selesai\":\"14:00\"}]', 'dokter_umum', '2025-11-25 02:40:23', '2025-11-25 15:04:09', NULL),
(13, NULL, NULL, 'Dr. Siti Nurhaliza', 'siti.nurhaliza@klinik.com', '081234567891', '123456789012346', '503/SIP/2021/002', '2017', NULL, 'admin', '2025-11-25 02:40:23', '2025-11-25 02:40:23', NULL),
(15, 18, 'tenaga_kesehatan/Ks6dCLJCU5F2X1AazSfjjnFNrLwxfvzVnSe6iWaw.jpg', 'dr Sindi Nabila', 'sindi.nabilla11@gmail.com', '081234567890', 'HP00001893479131', NULL, '2025', '[{\"hari\":\"Rabu\",\"jam_mulai\":\"08:00\",\"jam_selesai\":\"14:00\"}]', 'dokter_umum', '2025-11-25 14:52:05', '2025-11-25 14:52:25', NULL),
(16, 19, 'tenaga_kesehatan/QsSM1zOs1phhypFT1sCqkV8QAwiBYENzIJaAwtro.jpg', 'dr Bella Meita Mayasari', 'mayasaribella458@gmail.com', '081234567891', 'FA00001152487763', 'MR15712507000831', '2025', '[{\"hari\":\"Sabtu\",\"jam_mulai\":\"14:00\",\"jam_selesai\":\"21:00\"},{\"hari\":\"Jumat\",\"jam_mulai\":\"08:00\",\"jam_selesai\":\"14:00\"}]', 'dokter_umum', '2025-11-25 14:54:24', '2025-11-25 14:54:24', NULL),
(17, 20, 'tenaga_kesehatan/TJTqIyvD4dDytbaXf7uoyhgnz7TJK37kbYN3vokk.jpg', 'dr. Marisa Prafita Isman', 'rafitamarisa@gmail.com', '081234567890', 'BJ00001120314194', '446-DGT-1701178889-15.71.10.1002-DPMPTSP-SIPD-2023', '2024', '[{\"hari\":\"Senin\",\"jam_mulai\":\"09:00\",\"jam_selesai\":\"14:00\"}]', 'dokter_umum', '2025-12-09 04:11:17', '2025-12-09 04:11:17', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `gross_amount` decimal(10,2) NOT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `transaction_status` varchar(255) NOT NULL DEFAULT 'pending',
  `fraud_status` varchar(255) DEFAULT NULL,
  `midtrans_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`midtrans_response`)),
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `user_id`, `transaction_id`, `gross_amount`, `payment_type`, `transaction_status`, `fraud_status`, `midtrans_response`, `description`, `created_at`, `updated_at`) VALUES
(1, 'ORDER-1765363825-lIPydM', 5, NULL, 50000.00, NULL, 'pending', NULL, NULL, 'Subscription Paket Bulanan', '2025-12-10 03:50:25', '2025-12-10 03:50:25'),
(2, 'ORDER-1765364020-NUrLCI', 5, NULL, 50000.00, NULL, 'pending', NULL, NULL, 'Subscription Paket Bulanan', '2025-12-10 03:53:40', '2025-12-10 03:53:40'),
(3, 'ORDER-1765364135-jqeZoR', 5, NULL, 50000.00, NULL, 'pending', NULL, NULL, 'Subscription Paket Bulanan', '2025-12-10 03:55:35', '2025-12-10 03:55:35'),
(4, 'ORDER-1765364656-V3cx4P', 5, NULL, 50000.00, NULL, 'pending', NULL, NULL, 'Subscription Paket Bulanan', '2025-12-10 04:04:16', '2025-12-10 04:04:16'),
(5, 'ORDER-1765364806-nAcVbj', 5, NULL, 50000.00, NULL, 'pending', NULL, NULL, 'Subscription Paket Bulanan', '2025-12-10 04:06:46', '2025-12-10 04:06:46'),
(6, 'ORDER-1765364854-2hIZB0', 5, NULL, 50000.00, NULL, 'pending', NULL, NULL, 'Subscription Paket Bulanan', '2025-12-10 04:07:34', '2025-12-10 04:07:34'),
(7, 'ORDER-1765366842-kmIpH6', 5, 'efaa695c-32f4-4ae0-86b5-06e58d858d92', 50000.00, 'qris', 'settlement', 'accept', '{\"transaction_type\":\"on-us\",\"transaction_time\":\"2025-12-10 18:40:59\",\"transaction_status\":\"settlement\",\"transaction_id\":\"efaa695c-32f4-4ae0-86b5-06e58d858d92\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"200\",\"signature_key\":\"f2bd3818f28d4b4345987ad65f3b2afc6184e62e71eb8653e31725a2e4fe7f9da574956e4c4e1b40fccac024682315b8eac5c8ef4560cb33ad843c7b1913723a\",\"settlement_time\":\"2025-12-10 18:41:13\",\"pop_id\":\"30b2a4fd-643e-4f77-8701-7ccca952ef3a\",\"payment_type\":\"qris\",\"order_id\":\"ORDER-1765366842-kmIpH6\",\"merchant_id\":\"G526787538\",\"merchant_cross_reference_id\":\"bb548aaf-8048-470b-abb0-0a10a2d88335\",\"issuer\":\"gopay\",\"gross_amount\":\"50000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2025-12-10 18:55:59\",\"customer_details\":{\"full_name\":\"Riko Alfian\",\"email\":\"riko@gmail.com\"},\"currency\":\"IDR\",\"acquirer\":\"gopay\"}', 'Subscription Paket Bulanan', '2025-12-10 04:40:42', '2025-12-10 04:41:16'),
(8, 'ORDER-1765369550-s4JWkf', 6, '441dda7b-2898-456f-b905-bd27f35409aa', 50000.00, 'qris', 'settlement', 'accept', '{\"transaction_type\":\"on-us\",\"transaction_time\":\"2025-12-10 19:27:36\",\"transaction_status\":\"settlement\",\"transaction_id\":\"441dda7b-2898-456f-b905-bd27f35409aa\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"200\",\"signature_key\":\"02a8310e1d1ddc7cc82f53697781b698b4d064176e5502fbe8fa3adfdf09ced146b304895743e7bdef4b173a864ad27808b066fd0eaad60473d85ff0af64b9be\",\"settlement_time\":\"2025-12-10 19:29:23\",\"pop_id\":\"30b2a4fd-643e-4f77-8701-7ccca952ef3a\",\"payment_type\":\"qris\",\"order_id\":\"ORDER-1765369550-s4JWkf\",\"merchant_id\":\"G526787538\",\"merchant_cross_reference_id\":\"bb548aaf-8048-470b-abb0-0a10a2d88335\",\"issuer\":\"gopay\",\"gross_amount\":\"50000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2025-12-10 19:42:36\",\"customer_details\":{\"full_name\":\"Rae\",\"email\":\"rae@gmail.com\"},\"currency\":\"IDR\",\"acquirer\":\"gopay\"}', 'Subscription Paket Bulanan', '2025-12-10 05:25:50', '2025-12-10 05:29:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','perawat','bidan','dokter','pasien') NOT NULL DEFAULT 'pasien',
  `active_status` tinyint(1) NOT NULL DEFAULT 0,
  `avatar` varchar(255) NOT NULL DEFAULT 'avatar.png',
  `dark_mode` tinyint(1) NOT NULL DEFAULT 0,
  `messenger_color` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `active_status`, `avatar`, `dark_mode`, `messenger_color`) VALUES
(1, 'Perawat', 'perawat@gmail.com', NULL, '$2y$12$AwzLJSBh5toN5Cph7gNnuOl7UjPXDuTa82UUa3R053e4038vO.Ccm', NULL, '2025-08-29 11:10:13', '2025-08-29 11:10:13', 'perawat', 0, 'avatar.png', 0, NULL),
(2, 'Admin Klinik', 'admin@gmail.com', NULL, '$2y$12$K7LU5jq./iX/tXtbWGZz1O1oyIMLphE6ZF6EqxqF6zhpmyCFCZDdm', NULL, NULL, '2025-08-29 12:13:36', 'admin', 0, 'avatar.png', 0, NULL),
(5, 'Riko Alfian', 'riko@gmail.com', NULL, '$2y$12$SRFNotu3j9JMiCevjR147u3UoYuD4DPb3qqwvTfMmPOz/OaHDjRju', NULL, '2025-08-30 10:43:24', '2025-12-10 05:48:19', 'pasien', 1, 'avatar.png', 0, '#2196F3'),
(6, 'Rae', 'rae@gmail.com', NULL, '$2y$12$wR79S2J4jhG55mwzsuLeneyxyvYJ9ECVjRKkkpCpiqOeX6nQBXerO', NULL, '2025-08-30 10:47:53', '2025-12-10 06:12:54', 'pasien', 0, 'avatar.png', 0, NULL),
(7, 'dr. Oh Sehun, Sp.PD', 'drsehun@gmail.com', NULL, '$2y$12$832oy6U5iRO40KxRZxhrsOxc4xMAouzArFSDjhAxjmw6cIQ8KEZL6', NULL, '2025-09-04 03:05:42', '2025-12-10 08:54:10', 'dokter', 0, 'avatar.png', 0, NULL),
(16, 'dr. Dinda Nurul Isnaini', 'dindanurulisnaini13@gmail.com', NULL, '$2y$12$uM9KhkdZHOQr2NWTEt.7huwCjF1rBLwHa4qdTub61vcUasdotsIdS', NULL, '2025-11-25 02:17:56', '2025-11-25 14:58:28', 'dokter', 0, 'avatar.png', 0, NULL),
(17, 'dr Rifda Revonika', 'Rifdarevonika@gmail.com', NULL, '$2y$12$bQ3Z4l4AOE2/xpN/2S3gcOUxsYkp8yDzqa2zdTCk9OI.6SbLDOOGm', NULL, '2025-11-25 02:29:04', '2025-11-25 14:59:07', 'dokter', 0, 'avatar.png', 0, NULL),
(18, 'dr Sindi Nabila', 'sindi.nabilla11@gmail.com', NULL, '$2y$12$9ujgM803W7WgnChh40pbZewiXYDlG9rIMaL0T1/UyzSu0Lv/0f8J.', NULL, '2025-11-25 14:52:06', '2025-11-25 14:52:25', 'dokter', 0, 'avatar.png', 0, NULL),
(19, 'dr Bella Meita Mayasari', 'mayasaribella458@gmail.com', NULL, '$2y$12$IOPAE3uZrPJ.ry0WbVUVsO65dy72UAU4TciNDB7wTAK0/3TASfI5q', NULL, '2025-11-25 14:54:24', '2025-11-25 14:54:24', 'dokter', 0, 'avatar.png', 0, NULL),
(20, 'dr. Marisa Prafita Isman', 'rafitamarisa@gmail.com', NULL, '$2y$12$uRSAvI/d3Uc9whEWeasQSeqVHUVhjgacW0kB4JtTczRuEWBc2HS7.', NULL, '2025-12-09 04:11:17', '2025-12-09 04:11:17', 'dokter', 0, 'avatar.png', 0, NULL),
(21, 'zaa', 'zahra.f.andiani@gmail.com', NULL, '$2y$12$oBXkYQ0jnrWZsAiQrOOURe6F5s5P6n3s/bru8Ed08z1N02qFCrOVu', NULL, '2025-12-10 06:12:11', '2025-12-10 07:11:40', 'pasien', 0, 'avatar.png', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_confirmed_by_foreign` (`confirmed_by`),
  ADD KEY `appointment_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `chat_sessions`
--
ALTER TABLE `chat_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_sessions_doctor_id_foreign` (`doctor_id`),
  ADD KEY `chat_sessions_patient_id_doctor_id_is_active_index` (`patient_id`,`doctor_id`,`is_active`);

--
-- Indeks untuk tabel `ch_favorites`
--
ALTER TABLE `ch_favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ch_messages`
--
ALTER TABLE `ch_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `profil_pasien`
--
ALTER TABLE `profil_pasien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil__pasien_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `reset_password`
--
ALTER TABLE `reset_password`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_user_id_status_expires_at_index` (`user_id`,`status`,`expires_at`);

--
-- Indeks untuk tabel `tenaga_kesehatan`
--
ALTER TABLE `tenaga_kesehatan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenaga_kesehatan_email_unique` (`email`),
  ADD KEY `tenaga_kesehatan_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_order_id_unique` (`order_id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_order_id_transaction_status_index` (`order_id`,`transaction_status`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `chat_sessions`
--
ALTER TABLE `chat_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `obat`
--
ALTER TABLE `obat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `profil_pasien`
--
ALTER TABLE `profil_pasien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tenaga_kesehatan`
--
ALTER TABLE `tenaga_kesehatan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_confirmed_by_foreign` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `appointment_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `chat_sessions`
--
ALTER TABLE `chat_sessions`
  ADD CONSTRAINT `chat_sessions_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_sessions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `profil_pasien`
--
ALTER TABLE `profil_pasien`
  ADD CONSTRAINT `profil__pasien_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tenaga_kesehatan`
--
ALTER TABLE `tenaga_kesehatan`
  ADD CONSTRAINT `tenaga_kesehatan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
