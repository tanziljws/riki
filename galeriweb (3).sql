-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Nov 2025 pada 04.11
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
-- Database: `galeriweb`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `actor_user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('like','comment') NOT NULL,
  `gallery_id` bigint(20) UNSIGNED NOT NULL,
  `comment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `activities`
--

INSERT INTO `activities` (`id`, `actor_user_id`, `type`, `gallery_id`, `comment_id`, `meta`, `created_at`, `updated_at`) VALUES
(1, 7, 'like', 11, NULL, NULL, '2025-11-02 14:27:57', '2025-11-02 14:27:57'),
(2, 7, 'comment', 11, 1, NULL, '2025-11-02 14:28:04', '2025-11-02 14:28:04'),
(9, 7, 'like', 24, NULL, NULL, '2025-11-06 05:41:30', '2025-11-06 05:41:30'),
(11, 10, 'like', 24, NULL, NULL, '2025-11-06 05:51:44', '2025-11-06 05:51:44'),
(12, 11, 'comment', 24, 7, NULL, '2025-11-06 11:36:04', '2025-11-06 11:36:04'),
(13, 7, 'comment', 11, 8, NULL, '2025-11-06 12:05:52', '2025-11-06 12:05:52'),
(14, 7, 'like', 24, NULL, NULL, '2025-11-11 12:07:14', '2025-11-11 12:07:14'),
(15, 7, 'comment', 24, 9, NULL, '2025-11-11 12:07:23', '2025-11-11 12:07:23'),
(16, 7, 'comment', 24, 10, NULL, '2025-11-14 01:23:43', '2025-11-14 01:23:43');

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
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Kepala Sekolah', '2025-10-26 03:26:11', '2025-10-26 03:26:11'),
(2, 'Guru', '2025-10-26 03:26:11', '2025-10-26 03:26:11'),
(3, 'Jurusan', '2025-10-26 03:26:11', '2025-10-26 03:26:11'),
(4, 'Kegiatan', '2025-10-26 03:26:11', '2025-10-26 03:26:11'),
(5, 'Ekstrakurikuler', '2025-10-26 03:26:11', '2025-10-26 03:26:11'),
(6, 'Home', '2025-11-05 04:50:31', '2025-11-05 04:50:31'),
(7, 'Lainnya', '2025-11-05 04:50:31', '2025-11-05 04:50:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `gallery_id` bigint(20) UNSIGNED NOT NULL,
  `text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `gallery_id`, `text`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 7, 11, 'sehat selalu', '2025-11-02 14:28:04', '2025-11-02 15:05:07', '2025-11-02 15:05:07'),
(2, 7, 11, 'haloo', '2025-11-02 14:47:41', '2025-11-02 15:08:36', '2025-11-02 15:08:36'),
(5, 10, 24, 'SEMANGATTT BAPAKKK', '2025-11-06 05:14:37', '2025-11-10 14:02:11', '2025-11-10 14:02:11'),
(6, 7, 24, 'haloo bapakk', '2025-11-06 05:41:35', '2025-11-10 14:02:26', '2025-11-10 14:02:26'),
(7, 11, 24, 'haiiiii', '2025-11-06 11:36:04', '2025-11-06 11:36:04', NULL),
(8, 7, 11, 'Haii', '2025-11-06 12:05:51', '2025-11-06 12:05:51', NULL),
(9, 7, 24, 'haiii', '2025-11-11 12:07:23', '2025-11-11 12:07:23', NULL),
(10, 7, 24, 'haiii', '2025-11-14 01:23:42', '2025-11-14 01:23:42', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `eskuls`
--

CREATE TABLE `eskuls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `eskuls`
--

INSERT INTO `eskuls` (`id`, `nama`, `deskripsi`, `foto`, `created_at`, `updated_at`) VALUES
(2, 'PENCAK SILAT', 'Kegiatan Pencak Silat di SMK Negeri 4 Bogor umumnya adalah kegiatan ekstrakurikuler yang bertujuan melestarikan seni bela diri tradisional, meliputi pelatihan teknik dasar, pembentukan karakter seperti disiplin dan keberanian, serta persiapan untuk mengikuti kompetisi.', 'eskul/qLKcHDGqhh1ORCYNQa2nre47n84vo31u4fUQaZoa.png', '2025-09-09 01:04:35', '2025-09-09 01:04:35');

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
-- Struktur dari tabel `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `galleries`
--

INSERT INTO `galleries` (`id`, `title`, `description`, `image`, `category_id`, `created_at`, `updated_at`) VALUES
(11, 'Kepala Sekolah', 'Drs Mulya Murprihartono', 'gallery/5ir4EMF56Bglhrciut3HAdxobRjQtLCnbq3POqTx.jpg', 1, '2025-10-26 03:48:52', '2025-11-15 12:30:23'),
(20, 'Home Slide', NULL, 'gallery/cpthgm3b9awf7eXOh0EoOmmkh5t1jyihAsxeZlsw.jpg', 6, '2025-11-05 05:17:24', '2025-11-05 05:17:24'),
(21, 'Home Slide', NULL, 'gallery/NTzKujaAkjqzNH5RiIr910ulEMLVbGQM5DlRaF9b.jpg', 6, '2025-11-05 05:17:54', '2025-11-05 05:17:54'),
(22, 'Home Slide', NULL, 'gallery/6S9CUeJU78K6UwkYg1eYNwf4wEH2fqSwuJIXyi6o.jpg', 6, '2025-11-05 05:17:54', '2025-11-05 05:17:54'),
(23, 'Home Slide', NULL, 'gallery/SaNITqrkaSjjn90dAT2fd3kYEg5hFlpuaIv0rcaU.jpg', 6, '2025-11-05 05:17:54', '2025-11-05 05:17:54'),
(24, 'Home Slide', NULL, 'gallery/ufIFOxorkaqsgYiffuGKv7ht9cbSdTuTyrysKoyE.jpg', 6, '2025-11-05 05:17:54', '2025-11-05 05:17:54'),
(25, 'Musadarma S.Kom', 'guru Kejuruan', 'gallery/Z8FCoqXXG1D0ZIXRozYeAwxGjVnzfMe0wTsOVdrF.jpg', 2, '2025-11-18 01:03:29', '2025-11-18 01:03:29'),
(26, 'Dra.Erni Riana Syari', 'BK', 'gallery/tqKdHQY8ZGg6EvTj3K2AqAmsOfA9NSA9oW1OAFFf.jpg', 2, '2025-11-18 01:04:40', '2025-11-18 01:04:40'),
(27, 'Sunggono, S.Pt', 'kejuruan', 'gallery/wTKzUtUERSZkYbwVRad2df3pzL4NYmw1bI91EXSN.jpg', 2, '2025-11-18 01:05:40', '2025-11-18 01:05:40'),
(28, 'Atit Hartati S.Pd', 'Bahasa Indonesia', 'gallery/GNNiaq47YnXOUJb03gABtNV25Jvp54OEt6cH6Vpy.jpg', 2, '2025-11-18 01:07:02', '2025-11-18 01:07:02'),
(29, 'Tresna Amalia Septiarti', 'Bahasa Inggris', 'gallery/rQBC9FH4LSEX86scw004Hi0SJYZcPATkJcIqqIaJ.jpg', 2, '2025-11-18 01:07:50', '2025-11-18 01:07:50'),
(30, 'Sri Haryani', 'Matematika', 'gallery/EJupfgafzVQJoId3hdmM0ZvlPRNMknOfEcwLwT44.jpg', 2, '2025-11-18 01:08:33', '2025-11-18 01:08:33'),
(31, 'PPLG', 'Pengembangan Perangkat Lunak Dan Gim', 'gallery/AqxSvkAfxeIuXczxTF6LMOizbuhFmH5zcPhBkqb0.png', 3, '2025-11-18 01:09:34', '2025-11-18 01:09:34'),
(32, 'TJKT', 'Teknik Jaringan Komputer Dan Telekomunikasi', 'gallery/DtmSZfc061YrcKRUHcbRhpx5JjPzN0mK57peVTuD.jpg', 3, '2025-11-18 01:10:13', '2025-11-18 01:10:13'),
(33, 'TPFL', 'Teknik pabrikasi Logam', 'gallery/atlUd8G44HFmDbd5x4hEuI5lGFD5egYdiq97uTd9.png', 3, '2025-11-18 02:03:52', '2025-11-18 02:03:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gtk`
--

CREATE TABLE `gtk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `gtk`
--

INSERT INTO `gtk` (`id`, `nama`, `jabatan`, `foto`, `created_at`, `updated_at`) VALUES
(4, 'Atit Hartati S.pd', 'Bahasa Indonesia', 'gtks/3mPILzCRMhSklLYqvcCxs8R7d46iNSzAvnHfdD6P.jpg', '2025-08-28 02:38:12', '2025-08-29 00:26:38'),
(6, 'Tresna Septiarti Amalia M.Pd', 'Bahasa Inggris', 'gtks/t22SHFfHLgjpZtKFgUWwxgC6v6LwI8CuZa4skj60.jpg', '2025-08-28 02:50:12', '2025-08-28 02:50:12'),
(7, 'Drs.Lorensia Purwanti', 'Kewirausahaan', 'gtks/NMxfGH1pkC9Cnwq2QuRB62EdLvpzwf1pU8gT60Vu.jpg', '2025-08-28 02:55:54', '2025-08-28 03:23:26'),
(8, 'Sri Haryani S.Pd', 'Matematika', 'gtks/3P2kwz4lL04xXJfZjct7QcVp1AuHNu9vu632zn6I.jpg', '2025-08-28 03:02:25', '2025-08-28 03:24:40'),
(9, 'Dra.Erni Riana Syari', 'Bimbingan Konseling', 'gtks/uRpVW9IGDDGQNpbJF4ICTKRLVczg5EsFrhGOueql.jpg', '2025-08-28 03:06:43', '2025-08-28 03:07:43'),
(10, 'Musadarma, S.kom', 'Pemrograman Web', 'gtks/smLHCSlf7lhxh16yEwAjd7pSvCpwommOBCBk5KNa.jpg', '2025-08-28 03:13:07', '2025-08-28 03:15:44'),
(11, 'Sunggono, S.Pt', 'Pemrograman Perangkat B', 'gtks/T5Na6wXhg2PqRAepiWEZketle70y8ValcQxzuKq2.jpg', '2025-08-28 03:19:30', '2025-08-28 03:28:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gurus`
--

CREATE TABLE `gurus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `gurus`
--

INSERT INTO `gurus` (`id`, `nama`, `foto`, `created_at`, `updated_at`, `jabatan`) VALUES
(19, 'Dra.Erni Riana Syari', 'guru/qf3R3NfORv6ZM9xbMOGEzFHs2JwsZv0kLn1frLTt.jpg', '2025-09-10 06:50:40', '2025-09-10 06:50:40', 'Bimbingan Konseling'),
(20, 'Musadarma, S.kom', 'guru/H2WWVCmxVfDy7GldOmkwLZz4D1iH8lgW9QuFZTcK.jpg', '2025-09-10 06:52:36', '2025-09-10 06:52:36', 'Pemrograman Web'),
(21, 'Sunggono, S.Pt', 'guru/Q2WIwZQYaXXxh0evYMcHoao2JXOh89HEHT1eI5tf.jpg', '2025-09-10 06:53:47', '2025-09-10 06:53:57', 'Pemrograman Perangkat Bergerak'),
(23, 'Hsjhs', NULL, '2025-11-18 02:01:25', '2025-11-18 02:01:25', 'Hshs'),
(24, 'Hsusnsbs', NULL, '2025-11-18 02:01:43', '2025-11-18 02:01:43', 'Haushbus'),
(25, 'ku3grku3gukjw3gkwukhr', NULL, '2025-11-18 02:24:23', '2025-11-18 02:24:23', 'kjqwhgugwgqig'),
(26, 'wqgugiur4uiryrui3h', NULL, '2025-11-18 02:24:36', '2025-11-18 02:24:36', 'i3grighirugiurg4t3itihtu4ihti34'),
(27, 'ihikuhquirgihrkjwehi', NULL, '2025-11-18 02:24:48', '2025-11-18 02:24:48', 'ueugiufgwurgigrgruwi'),
(28, 'hgvkjwbrkjkrk4hkurhugrwerj3', NULL, '2025-11-18 02:25:05', '2025-11-18 02:25:05', 'hweergyg3yugryugrygygrfyuj34'),
(29, 'iheikehikhie', NULL, '2025-11-18 02:25:33', '2025-11-18 02:25:33', 'hueweirwoihow4ie'),
(30, 'jwghdukghwuhduhwdh', NULL, '2025-11-18 02:25:50', '2025-11-18 02:25:50', 'kehfkehfhehifwhawfih'),
(31, 'ihyruihewiuhiweh', NULL, '2025-11-18 02:26:06', '2025-11-18 02:26:06', 'kuwehyiuwhuiw'),
(32, 'juge3gg3wkur', NULL, '2025-11-18 02:26:22', '2025-11-18 02:26:22', 'hrkughkrwhk'),
(33, 'jbugeukfgeukgf', NULL, '2025-11-18 02:26:43', '2025-11-18 02:26:43', 'iuwshukdgkuwgdk'),
(34, 'Yayayy', NULL, '2025-11-18 02:29:14', '2025-11-18 02:29:14', 'Yuyuyu');

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
-- Struktur dari tabel `jurusans`
--

CREATE TABLE `jurusans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jurusans`
--

INSERT INTO `jurusans` (`id`, `nama`, `logo`, `created_at`, `updated_at`, `deskripsi`, `foto`) VALUES
(11, 'PPLG', NULL, '2025-09-08 06:29:26', '2025-09-08 06:56:57', 'Jurusan Pengembangan Perangkat Lunak dan Gim (PPLG) mempelajari pembuatan aplikasi, website, dan game. Siswa dipersiapkan untuk bekerja di industri teknologi atau membangun usaha rintisan (startup).', 'jurusan/9Tv7UrzQIu0NSdm83UdLou7dX9KRqqndJmfwLE1z.png'),
(12, 'TJKT', NULL, '2025-09-08 06:32:31', '2025-09-08 06:55:17', 'Jurusan Teknik Komputer dan Jaringan (TJKT) mempelajari instalasi, konfigurasi, dan perawatan perangkat jaringan, server, serta sistem operasi dan infrastruktur jaringan secara umum.', 'jurusan/3KkiyJnrPyvAJFMU7IaMMV0Qaeupvmwl4eZBH644.jpg'),
(13, 'TO', NULL, '2025-09-08 06:34:31', '2025-09-08 07:03:23', 'Jurusan Teknik Otomotif (TO) berfokus pada keterampilan perawatan dan perbaikan kendaraan ringan, khususnya mobil. Siswa juga dibekali pemahaman tentang sistem dan komponen kendaraan', 'jurusan/9PMpBE7fZJe6wWWRdfJ6ApXXBfiTsKWckH4tspRY.png'),
(14, 'TPFL', NULL, '2025-09-08 06:42:39', '2025-09-08 07:08:38', 'Jurusan Teknik Pengelasan dan Fabrikasi Logam (TPFL) membekali siswa dengan keterampilan merancang, menyambung, dan membentuk logam. Siswa juga dipersiapkan alat alat untuk praktik', 'jurusan/aP37DCR6eWnSpcXB2asv9L4h1Bp83JtbBpSJ8uym.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `likes`
--

CREATE TABLE `likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `gallery_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `gallery_id`, `created_at`, `updated_at`) VALUES
(5, 10, 24, '2025-11-06 05:51:44', '2025-11-06 05:51:44'),
(6, 7, 24, '2025-11-11 12:07:14', '2025-11-11 12:07:14');

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
(4, '2025_08_19_103233_create_personal_access_tokens_table', 1),
(5, '2025_08_19_104903_add_role_to_users_table', 1),
(6, '2025_08_19_123320_create_categories_table', 1),
(7, '2025_08_19_135142_create_galleries_table', 1),
(8, '2025_08_20_140055_create_photos_table', 1),
(9, '2025_08_25_062505_add_last_login_at_to_users_table', 1),
(10, '2025_08_25_144007_create_gurus_table', 1),
(11, '2025_08_26_095603_create_gtks_table', 1),
(12, '2025_08_26_125535_create_eskuls_table', 1),
(13, '2025_08_29_084238_create_jurusans_table', 2),
(14, '2025_09_08_083142_remove_mapel_from_gurus_table', 3),
(15, '2025_09_08_094217_add_jabatan_to_gurus_table', 4),
(16, '2025_09_08_110951_add_deskripsi_to_jurusans_table', 5),
(17, '2025_09_08_111151_make_logo_nullable_in_jurusans_table', 6),
(18, '2025_09_08_124315_add_foto_to_jurusans_table', 7),
(19, '2025_10_24_091639_add_avatar_to_users_table', 8),
(20, '2025_10_28_153000_add_is_active_to_users_table', 9),
(21, '2025_10_28_160500_add_soft_deletes_to_users_table', 10),
(22, '2025_11_02_210500_create_likes_table', 11),
(23, '2025_11_02_210510_create_comments_table', 11),
(24, '2025_11_02_210520_create_activities_table', 11);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `photos`
--

CREATE TABLE `photos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `avatar` varchar(255) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `is_active`, `avatar`, `last_login_at`, `deleted_at`) VALUES
(1, 'Admin', 'admin@example.com', NULL, '$2y$12$MsRukFlk28CgcDKpZsJaUe4Qb8qu5A1lg3E8tiudTR6LE4IgzEGK6', NULL, '2025-08-26 06:14:32', '2025-10-28 09:09:47', 'user', 0, NULL, NULL, '2025-10-28 09:09:47'),
(2, 'zidan', 'zidantv79@gmail.com', NULL, '$2y$12$uimd4CSzWfk.UYSVZruriubxdB8rTYdQuOHeQ2WiuAycxk.4b7GQe', 'spNerrksSJr8lY5HJxDEKrW7QDbLcSJWv58QwU5g4LkqJSOPG6ND1XBcdsA6', '2025-08-26 06:15:56', '2025-10-28 09:09:33', 'user', 0, NULL, '2025-10-26 03:26:31', '2025-10-28 09:09:33'),
(3, 'rikimaulana', 'rikimaulanaa04@gmail.com', NULL, '$2y$12$wJCFEQVjxlbbX.ERRGYjvuljAGNgimcPqDFQ.JtR9AhLDcF4sO68u', 'Vo1VMZOiSX76lkpApU8S9zq5sTAJmC17qdtujS4Jpm9dS0qkEelw7bAvCYUX', '2025-10-23 00:46:11', '2025-10-28 09:09:40', 'user', 0, 'avatars/wiRgYajTBlCSQUmCsNZ3LwaNJocv0uCj3iD7gnbl.jpg', NULL, '2025-10-28 09:09:40'),
(4, 'Riki', 'kyyykyyy31@gmail.com', NULL, '$2y$12$A1Etaj/.OxG1RqbnvLWn.elfa/a4mnNDtK7aG1kpIeueIK6/cvIei', 'KFnvWY5EbebQnKB6JQCHdF3qttAzV5LxwfJi096nFRn9OMfK5Pxkm2nzjOhT', '2025-10-24 02:44:59', '2025-10-28 09:09:45', 'user', 0, 'avatars/9oW3z6gfZ6KFWGJrFXjdG3B5q5jEJxr1HNTWCgFD.jpg', NULL, '2025-10-28 09:09:45'),
(5, 'syauqi', 'syauqi@gmail.com', NULL, '$2y$12$0GW9uneqUHMmz9is482yK.O1PAznSXOv.KRqB7qlirA590rFT.szK', NULL, '2025-10-26 01:26:36', '2025-10-28 09:09:37', 'user', 0, NULL, NULL, '2025-10-28 09:09:37'),
(6, 'maulana', 'maulana@gmail.com', NULL, '$2y$12$wkLZt4MYFKDp2A4aDFNUE.05VAsOvscs998KRFLPsZ6lN.4ZY0mMe', 'OKo8RymJNxqxRg8eqBptirbL5snEYMpoTnywgccDOOibTuzpJMhd5anz4WZ1', '2025-10-26 04:08:25', '2025-11-18 03:02:19', 'user', 1, NULL, '2025-11-18 03:02:19', NULL),
(7, 'Riki', 'rikim1221@gmail.com', NULL, '$2y$12$akQT0ugQett/5oqyLMt75.seCvse..iXy1y68WBunNPFX0Q/VYplu', NULL, '2025-10-26 04:37:35', '2025-11-14 01:22:27', 'user', 1, 'avatars/C2oWAtq52nsuW00ObMGaSpJCkrrDg1eZHe3JpgN1.jpg', NULL, NULL),
(8, 'rangga', 'rangga@gmail.com', NULL, '$2y$12$zS5TsZ5KRn6cjAwLjiq4de6QzA9LhxmVHVYLwgrc47RkJrb8BmyMe', NULL, '2025-11-02 15:10:26', '2025-11-06 04:18:59', 'user', 0, NULL, NULL, NULL),
(9, 'Admin', 'admin@gmail.com', NULL, '$2y$12$qudzCO3uzXYVXRqw92gZxuRNsnu7lpx53QmbU9OIX9Ur.hNYzESOa', NULL, '2025-11-05 05:33:07', '2025-11-05 06:22:34', 'admin', 0, NULL, '2025-11-05 06:21:54', '2025-11-05 06:22:34'),
(10, 'Safira Aulia', 'SafiraAulia@gmail.com', NULL, '$2y$12$xvuGu4NF5MMx69y2297QJuNaqkryblUtQYZYPZuQnF.5U6sjwp5EO', NULL, '2025-11-06 04:55:47', '2025-11-06 13:40:46', 'user', 0, 'avatars/YYBK2yo61wbxzg8TPPb0ixwQSxFtS99Yb0ZwXzqi.png', NULL, NULL),
(11, 'reza andriana', 'rezaandriana@gmail.com', NULL, '$2y$12$yHrRURML55Q0Dni.awC/F.HxOOFep6S7A3NvSkdjg8Rwaj80mWqVq', NULL, '2025-11-06 11:34:36', '2025-11-06 11:36:37', 'user', 1, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_actor_user_id_foreign` (`actor_user_id`),
  ADD KEY `activities_gallery_id_foreign` (`gallery_id`),
  ADD KEY `activities_comment_id_foreign` (`comment_id`),
  ADD KEY `activities_type_created_at_index` (`type`,`created_at`);

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
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `comments_gallery_id_created_at_index` (`gallery_id`,`created_at`);

--
-- Indeks untuk tabel `eskuls`
--
ALTER TABLE `eskuls`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galleries_category_id_foreign` (`category_id`);

--
-- Indeks untuk tabel `gtk`
--
ALTER TABLE `gtk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `gurus`
--
ALTER TABLE `gurus`
  ADD PRIMARY KEY (`id`);

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
-- Indeks untuk tabel `jurusans`
--
ALTER TABLE `jurusans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `likes_user_id_gallery_id_unique` (`user_id`,`gallery_id`),
  ADD KEY `likes_gallery_id_foreign` (`gallery_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indeks untuk tabel `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT untuk tabel `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `eskuls`
--
ALTER TABLE `eskuls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `gtk`
--
ALTER TABLE `gtk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `gurus`
--
ALTER TABLE `gurus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jurusans`
--
ALTER TABLE `jurusans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `photos`
--
ALTER TABLE `photos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_actor_user_id_foreign` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activities_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activities_gallery_id_foreign` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_gallery_id_foreign` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `galleries`
--
ALTER TABLE `galleries`
  ADD CONSTRAINT `galleries_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_gallery_id_foreign` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
