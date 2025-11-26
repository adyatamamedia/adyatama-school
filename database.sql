-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2025 at 04:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adyatama_sekolah`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `subject_type` varchar(100) DEFAULT NULL,
  `subject_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `action`, `subject_type`, `subject_id`, `ip_address`, `user_agent`, `meta`, `created_at`) VALUES
(1, 2, 'logout', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 08:24:02'),
(2, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:32:01'),
(3, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:32:01'),
(4, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:32:02'),
(5, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:32:02'),
(6, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:32:03'),
(7, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:32:03'),
(8, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:32:04'),
(9, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:32:04'),
(10, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:32:05'),
(11, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:32:05'),
(12, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:32:06'),
(13, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:32:07'),
(14, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:32:24'),
(15, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:32:34'),
(16, 2, 'create_post', 'post', 0, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36  (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36', NULL, '2025-11-25 08:34:24'),
(17, 2, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 08:43:59'),
(18, 2, 'create_post', 'post', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 08:51:02'),
(19, 2, 'create_post', 'post', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 08:56:43'),
(20, 2, 'update_post', 'post', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 08:57:12'),
(21, 2, 'update_post', 'post', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 08:58:53'),
(22, 2, 'logout', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 09:20:28'),
(23, 3, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 09:21:40'),
(24, 3, 'logout', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 09:23:52'),
(25, 3, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 09:24:05'),
(26, 3, 'update_post', 'post', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 09:40:59'),
(27, 3, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 12:07:03'),
(28, 3, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 12:40:21'),
(29, 3, 'update_post', 'post', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 12:45:34'),
(30, NULL, 'student_application.created', 'App\\Models\\StudentApplication', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"nama_lengkap\":\"Dena Setya Utama\"}', '2025-11-25 13:30:50'),
(31, 3, 'update_post', 'post', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 13:52:37'),
(32, 3, 'update_post', 'post', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 13:59:14'),
(33, 3, 'update_post', 'post', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 14:16:21'),
(34, 3, 'update_post', 'post', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 14:17:24'),
(35, 3, 'logout', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 16:00:38'),
(36, 3, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-25 16:00:49'),
(37, 3, 'login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-26 13:04:20'),
(38, 3, 'update_post', 'post', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-26 13:21:38'),
(39, 3, 'update_post', 'post', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-26 13:21:57'),
(40, 3, 'update_post', 'post', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-26 13:48:00'),
(41, 3, 'update_post', 'post', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-26 13:49:01'),
(42, 3, 'update_post', 'post', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-26 13:50:01'),
(43, 3, 'update_post', 'post', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-26 14:20:51'),
(44, 3, 'update_post', 'post', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-26 14:43:14'),
(45, 3, 'update_post', 'post', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-26 14:43:27'),
(46, 3, 'create_post', 'post', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-26 15:55:58'),
(47, 3, 'update_post', 'post', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-26 15:56:09');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`) VALUES
(3, 'Pembelajaran', 'pembelajaran', '', '2025-11-25 08:48:24'),
(4, 'Kegiatan', 'kegiatan', '', '2025-11-25 08:48:39');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `gallery_id` int(11) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `author_name` varchar(150) DEFAULT NULL,
  `author_email` varchar(150) DEFAULT NULL,
  `content` text NOT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `is_spam` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `extracurriculars`
--

CREATE TABLE `extracurriculars` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `extracurriculars`
--

INSERT INTO `extracurriculars` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Pramuka', '', '2025-11-25 09:08:58'),
(2, 'Sepak Bola', '', '2025-11-25 09:09:20'),
(3, 'Voli', '', '2025-11-25 09:09:28'),
(4, 'Banjari', '', '2025-11-25 09:09:36'),
(5, 'Gerak Jalan', '', '2025-11-26 15:26:22');

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `featured_img` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `view_count` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `extracurricular_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'published',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `title`, `featured_img`, `slug`, `view_count`, `description`, `extracurricular_id`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Test Gallery Album', NULL, 'test-gallery-album', 13, 'This is a test gallery album for automation testing.', 1, 'published', '2025-11-25 08:33:34', '2025-11-26 16:21:22', '2025-11-26 16:21:22'),
(2, 'Tes Galeri', NULL, 'tes-galeri', 2, '<p>Tes Galeri</p>', 3, 'published', '2025-11-26 15:54:53', '2025-11-26 16:03:06', '2025-11-26 16:03:06'),
(3, 'Tes lagi galeri nya', NULL, 'tes-lagi-galeri-nya', 0, '<p>Tes lagi galeri nya</p>', 3, 'published', '2025-11-26 16:00:21', '2025-11-26 16:03:02', '2025-11-26 16:03:02'),
(4, 'Tes lagi galeri nya', NULL, 'tes-lagi-galeri-nya-1764172910', 1, '<p>Tes lagi galeri nya</p>', 3, 'published', '2025-11-26 16:01:50', '2025-11-26 16:21:22', '2025-11-26 16:21:22'),
(5, 'Tes Featured Image', NULL, 'tes-featured-image', 1, '<p>Tes Featured Image</p>', 5, 'published', '2025-11-26 16:05:12', '2025-11-26 16:21:22', '2025-11-26 16:21:22'),
(6, 'tes lagi', NULL, 'tes-lagi', 0, '<p>tes lagi</p>', 2, 'published', '2025-11-26 16:06:26', '2025-11-26 16:21:22', '2025-11-26 16:21:22'),
(7, 'HARUSNYA UDAH BISA', NULL, 'harusnya-udah-bisa', 0, '<p>HARUSNYA UDAH BISA</p>', 5, 'published', '2025-11-26 16:08:18', '2025-11-26 16:21:22', '2025-11-26 16:21:22'),
(8, 'TESS LAGI BRAYY', NULL, 'tess-lagi-brayy', 0, '<p>TESS LAGI BRAYY</p>', 3, 'published', '2025-11-26 16:10:43', '2025-11-26 16:21:22', '2025-11-26 16:21:22'),
(9, 'TESSS CUYY', NULL, 'tesss-cuyy', 0, '<p>TESSS CUYY</p>', 1, 'published', '2025-11-26 16:12:58', '2025-11-26 16:21:22', '2025-11-26 16:21:22'),
(10, 'TESS LAGI NIGH', NULL, 'tess-lagi-nigh', 1, '<p>TESS LAGI NIGH</p>', 2, 'published', '2025-11-26 16:14:00', '2025-11-26 16:21:22', '2025-11-26 16:21:22'),
(11, 'COBA SEEKk', NULL, 'coba-seekk', 0, '<p>COBA SEEKk</p>', 1, 'published', '2025-11-26 16:20:11', '2025-11-26 16:21:22', '2025-11-26 16:21:22'),
(12, 'TESS CUYYY', NULL, 'tess-cuyyy', 0, '<p>TESS CUYYY</p>', 2, 'published', '2025-11-26 16:21:58', '2025-11-26 16:21:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gallery_items`
--

CREATE TABLE `gallery_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `gallery_id` int(10) UNSIGNED NOT NULL,
  `media_id` int(10) UNSIGNED NOT NULL,
  `type` enum('image','video') DEFAULT 'image',
  `path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `order_num` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_items`
--

INSERT INTO `gallery_items` (`id`, `gallery_id`, `media_id`, `type`, `path`, `caption`, `order_num`, `created_at`, `deleted_at`) VALUES
(1, 1, 2, 'image', 'uploads/1764061084_d21e6cad083dbd339831.png', 'twibbon_konfercab-ansor-2025_1763959013508.png', 0, '2025-11-25 09:01:00', '2025-11-26 13:51:16'),
(2, 1, 4, 'image', 'uploads/1764061105_cef7c3f6c38d3c11d7cd.jpeg', 'WhatsApp Image 2025-11-23 at 22.03.26.jpeg', 0, '2025-11-25 09:01:03', '2025-11-26 13:51:19'),
(3, 1, 3, 'image', 'uploads/1764061097_43287c99965b22b1ce7b.png', 'twibbon_konfercabansor2025_1763388725535.png', 0, '2025-11-25 09:01:06', '2025-11-26 13:51:21'),
(4, 1, 7, 'image', 'uploads/1764165054_426c8cbf3c6e4ed6c391.jpg', 'LOGO KONFERENSI XIV MWC NU WAJAK.jpg', 0, '2025-11-26 13:51:25', NULL),
(5, 1, 5, 'image', 'uploads/1764164715_3d498dd6ba809d849a55.jpg', 'LOGO KONFERENSI XIV MWC NU WAJAK_2.jpg', 0, '2025-11-26 13:51:29', NULL),
(6, 4, 8, 'image', 'uploads/1764165743_2ba49490f696734e231c.png', 'Logo 93', 1, '2025-11-26 16:01:50', NULL),
(7, 4, 7, 'image', 'uploads/1764165054_426c8cbf3c6e4ed6c391.jpg', 'LOGO KONFERENSI XIV MWC NU WAJAK.jpg', 2, '2025-11-26 16:01:50', NULL),
(8, 4, 5, 'image', 'uploads/1764164715_3d498dd6ba809d849a55.jpg', 'LOGO KONFERENSI XIV MWC NU WAJAK_2.jpg', 3, '2025-11-26 16:01:50', NULL),
(9, 4, 2, 'image', 'uploads/1764061084_d21e6cad083dbd339831.png', 'Twibbon Smoothies', 4, '2025-11-26 16:01:50', NULL),
(10, 4, 10, 'image', 'uploads/1764166697_c0ae0db78033e857d970.png', 'LOGO MI MANARUL HUDA.png', 5, '2025-11-26 16:01:50', NULL),
(11, 5, 5, 'image', 'uploads/1764164715_3d498dd6ba809d849a55.jpg', 'LOGO KONFERENSI XIV MWC NU WAJAK_2.jpg', 1, '2025-11-26 16:05:12', NULL),
(12, 5, 7, 'image', 'uploads/1764165054_426c8cbf3c6e4ed6c391.jpg', 'LOGO KONFERENSI XIV MWC NU WAJAK.jpg', 2, '2025-11-26 16:05:12', NULL),
(13, 5, 8, 'image', 'uploads/1764165743_2ba49490f696734e231c.png', 'Logo 93', 3, '2025-11-26 16:05:12', NULL),
(14, 5, 10, 'image', 'uploads/1764166697_c0ae0db78033e857d970.png', 'LOGO MI MANARUL HUDA.png', 4, '2025-11-26 16:05:12', NULL),
(15, 5, 2, 'image', 'uploads/1764061084_d21e6cad083dbd339831.png', 'Twibbon Smoothies', 5, '2025-11-26 16:05:12', NULL),
(16, 6, 5, 'image', 'uploads/1764164715_3d498dd6ba809d849a55.jpg', 'LOGO KONFERENSI XIV MWC NU WAJAK_2.jpg', 1, '2025-11-26 16:06:26', NULL),
(17, 6, 7, 'image', 'uploads/1764165054_426c8cbf3c6e4ed6c391.jpg', 'LOGO KONFERENSI XIV MWC NU WAJAK.jpg', 2, '2025-11-26 16:06:26', NULL),
(18, 7, 10, 'image', 'uploads/1764166697_c0ae0db78033e857d970.png', 'LOGO MI MANARUL HUDA.png', 1, '2025-11-26 16:08:18', NULL),
(19, 7, 8, 'image', 'uploads/1764165743_2ba49490f696734e231c.png', 'Logo 93', 2, '2025-11-26 16:08:18', NULL),
(20, 7, 7, 'image', 'uploads/1764165054_426c8cbf3c6e4ed6c391.jpg', 'LOGO KONFERENSI XIV MWC NU WAJAK.jpg', 3, '2025-11-26 16:08:18', NULL),
(21, 8, 10, 'image', 'uploads/1764166697_c0ae0db78033e857d970.png', 'LOGO MI MANARUL HUDA.png', 1, '2025-11-26 16:10:43', NULL),
(22, 8, 8, 'image', 'uploads/1764165743_2ba49490f696734e231c.png', 'Logo 93', 2, '2025-11-26 16:10:43', NULL),
(23, 9, 8, 'image', 'uploads/1764165743_2ba49490f696734e231c.png', 'Logo 93', 1, '2025-11-26 16:12:58', NULL),
(24, 10, 2, 'image', 'uploads/1764061084_d21e6cad083dbd339831.png', 'Twibbon Smoothies', 1, '2025-11-26 16:14:00', NULL),
(25, 11, 10, 'image', 'uploads/1764166697_c0ae0db78033e857d970.png', 'LOGO MI MANARUL HUDA.png', 1, '2025-11-26 16:20:11', NULL),
(26, 12, 2, 'image', 'uploads/1764061084_d21e6cad083dbd339831.png', 'Twibbon Smoothies', 1, '2025-11-26 16:21:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `guru_staff`
--

CREATE TABLE `guru_staff` (
  `id` int(10) UNSIGNED NOT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `bidang` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('guru','staff') DEFAULT 'guru',
  `is_active` tinyint(1) DEFAULT 1,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru_staff`
--

INSERT INTO `guru_staff` (`id`, `nip`, `nama_lengkap`, `jabatan`, `bidang`, `bio`, `email`, `no_hp`, `foto`, `status`, `is_active`, `user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '763546825612', 'Dena Setya Utama', 'Kepala Sekolah', '', NULL, 'denasetyautama@gmail.com', '085645810516', 'uploads/1764165743_2ba49490f696734e231c.png', 'guru', 1, NULL, '2025-11-25 08:59:46', '2025-11-26 14:02:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(10) UNSIGNED NOT NULL,
  `origin_post_id` int(10) UNSIGNED DEFAULT NULL,
  `type` enum('image','video','file') DEFAULT 'image',
  `path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `order_num` int(11) DEFAULT 0,
  `filesize` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `origin_post_id`, `type`, `path`, `caption`, `meta`, `order_num`, `filesize`, `created_at`, `deleted_at`) VALUES
(1, NULL, 'image', 'uploads/1764060540_b3ddb2e28ff98f8a8e2d.png', 'twibbon_konfercab-ansor-2025_1763959716900.png', '{\"client_name\":\"twibbon_konfercab-ansor-2025_1763959716900.png\",\"mime_type\":\"image\\/png\"}', 0, 1259088, '2025-11-25 08:49:00', '2025-11-25 08:58:07'),
(2, NULL, 'image', 'uploads/1764061084_d21e6cad083dbd339831.png', 'Twibbon Smoothies', '{\"client_name\":\"twibbon_konfercab-ansor-2025_1763959013508.png\",\"mime_type\":\"image\\/png\"}', 0, 1256975, '2025-11-25 08:58:04', NULL),
(3, NULL, 'image', 'uploads/1764061097_43287c99965b22b1ce7b.png', 'Tes Edit Caption', '{\"client_name\":\"twibbon_konfercabansor2025_1763388725535.png\",\"mime_type\":\"image\\/png\"}', 0, 1497781, '2025-11-25 08:58:17', '2025-11-26 14:01:58'),
(4, NULL, 'image', 'uploads/1764061105_cef7c3f6c38d3c11d7cd.jpeg', 'Logo Koperasi Cahaya Pintu Nusantara', '{\"client_name\":\"WhatsApp Image 2025-11-23 at 22.03.26.jpeg\",\"mime_type\":\"image\\/jpeg\"}', 0, 50762, '2025-11-25 08:58:25', '2025-11-26 13:46:26'),
(5, NULL, 'image', 'uploads/1764164715_3d498dd6ba809d849a55.jpg', 'LOGO KONFERENSI XIV MWC NU WAJAK_2.jpg', '{\"client_name\":\"LOGO KONFERENSI XIV MWC NU WAJAK_2.jpg\",\"mime_type\":\"image\\/jpeg\"}', 0, 1693965, '2025-11-26 13:45:15', NULL),
(6, NULL, 'image', 'uploads/1764164775_c300edc72ec7605856b1.jpg', 'LOGO KONFERENSI XIV MWC NU WAJAK.jpg', '{\"client_name\":\"LOGO KONFERENSI XIV MWC NU WAJAK.jpg\",\"mime_type\":\"image\\/jpeg\"}', 0, 1452166, '2025-11-26 13:46:15', '2025-11-26 13:48:38'),
(7, NULL, 'image', 'uploads/1764165054_426c8cbf3c6e4ed6c391.jpg', 'LOGO KONFERENSI XIV MWC NU WAJAK.jpg', '{\"client_name\":\"LOGO KONFERENSI XIV MWC NU WAJAK.jpg\",\"mime_type\":\"image\\/jpeg\"}', 0, 1452166, '2025-11-26 13:50:54', NULL),
(8, NULL, 'image', 'uploads/1764165743_2ba49490f696734e231c.png', 'Logo 93', '{\"client_name\":\"marc-marquez-93-logo-png_seeklogo-259418.png\",\"mime_type\":\"image\\/png\"}', 0, 9489, '2025-11-26 14:02:24', NULL),
(9, NULL, 'image', 'uploads/1764166697_2a2547bcb26f9ef7162c.png', 'WhatsApp Image 2025-11-21 at 13.01.58_5cb5a67b-photoaidcom-2x-ai-zoom.jpg', '{\"client_name\":\"WhatsApp Image 2025-11-21 at 13.01.58_5cb5a67b-photoaidcom-2x-ai-zoom.jpg\",\"mime_type\":\"image\\/png\"}', 0, 1936655, '2025-11-26 14:18:17', '2025-11-26 14:24:05'),
(10, NULL, 'image', 'uploads/1764166697_c0ae0db78033e857d970.png', 'LOGO MI MANARUL HUDA.png', '{\"client_name\":\"LOGO MI MANARUL HUDA.png\",\"mime_type\":\"image\\/png\"}', 0, 283452, '2025-11-26 14:18:17', NULL),
(11, NULL, 'image', 'uploads/1764166697_4460461d98b7eb91609a.jpg', 'WhatsApp Image 2025-11-21 at 13.01.58_ba8f53e0.jpg', '{\"client_name\":\"WhatsApp Image 2025-11-21 at 13.01.58_ba8f53e0.jpg\",\"mime_type\":\"image\\/jpeg\"}', 0, 83455, '2025-11-26 14:18:17', '2025-11-26 14:24:05');

-- --------------------------------------------------------

--
-- Table structure for table `media_variants`
--

CREATE TABLE `media_variants` (
  `id` int(10) UNSIGNED NOT NULL,
  `media_id` int(10) UNSIGNED NOT NULL,
  `variant_key` varchar(50) NOT NULL,
  `path` varchar(255) NOT NULL,
  `width` int(10) UNSIGNED DEFAULT NULL,
  `height` int(10) UNSIGNED DEFAULT NULL,
  `filesize` bigint(20) UNSIGNED DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
(4, '2025_11_25_110108_create_school_core_tables', 1),
(5, '2025_11_25_112349_create_student_applications_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `migrations_legacy`
--

CREATE TABLE `migrations_legacy` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations_legacy`
--

INSERT INTO `migrations_legacy` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(3, '2025-11-25-120955', 'App\\Database\\Migrations\\AddNewSettings', 'default', 'App', 1764075578, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'published',
  `order_num` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `featured_image`, `status`, `order_num`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Test Static Page', 'test-static-page', 'This is a test static page content for SEO metadata testing.', '', 'draft', 0, '2025-11-25 08:34:15', '2025-11-26 13:29:38', '2025-11-26 13:29:38'),
(2, 'Kontak Kami', 'kontak-kami', '<h1>What is Lorem Ipsum?</h1><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. </p><h1>Why do we use it? </h1><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like). </p><h1>Where does it come from? </h1><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>', 'uploads/1764165054_426c8cbf3c6e4ed6c391.jpg', 'published', 0, '2025-11-25 09:00:38', '2025-11-26 14:42:38', NULL),
(3, 'Profil Sekolah', 'profil-sekolah', '<h1 style=\"border:0px solid;margin-right:0px;margin-bottom:0px;margin-left:0px;padding:0px;font-size:inherit;font-weight:inherit;font-family:\'Instrument Sans\', \'ui-sans-serif\', \'system-ui\', sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\', \'Noto Color Emoji\';\">What is Lorem Ipsum?</h1><p style=\"border:0px solid;margin-right:0px;margin-bottom:16px;margin-left:0px;padding:0px;line-height:1.625;font-family:\'Instrument Sans\', \'ui-sans-serif\', \'system-ui\', sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\', \'Noto Color Emoji\';\">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><h1 style=\"border:0px solid;margin-right:0px;margin-bottom:0px;margin-left:0px;padding:0px;font-size:inherit;font-weight:inherit;font-family:\'Instrument Sans\', \'ui-sans-serif\', \'system-ui\', sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\', \'Noto Color Emoji\';\">Why do we use it?</h1><p style=\"border:0px solid;margin-right:0px;margin-bottom:16px;margin-left:0px;padding:0px;line-height:1.625;font-family:\'Instrument Sans\', \'ui-sans-serif\', \'system-ui\', sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\', \'Noto Color Emoji\';\">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><h1 style=\"border:0px solid;margin-right:0px;margin-bottom:0px;margin-left:0px;padding:0px;font-size:inherit;font-weight:inherit;font-family:\'Instrument Sans\', \'ui-sans-serif\', \'system-ui\', sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\', \'Noto Color Emoji\';\">Where does it come from?</h1><p style=\"border:0px solid;margin-right:0px;margin-bottom:16px;margin-left:0px;padding:0px;line-height:1.625;font-family:\'Instrument Sans\', \'ui-sans-serif\', \'system-ui\', sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\', \'Noto Color Emoji\';\">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>', '', 'published', 0, '2025-11-26 14:00:56', '2025-11-26 14:42:27', NULL),
(4, 'tesss', 'tesss', '<p>resss</p>', 'uploads/1764166697_c0ae0db78033e857d970.png', 'published', 0, '2025-11-26 16:16:00', '2025-11-26 16:16:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `label` varchar(150) DEFAULT NULL,
  `resource` varchar(100) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `author_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `post_type` enum('article','announcement','video') DEFAULT 'article',
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `featured_media_id` int(10) UNSIGNED DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `view_count` int(10) UNSIGNED DEFAULT 0,
  `react_enabled` tinyint(1) DEFAULT 1,
  `comments_enabled` tinyint(1) DEFAULT 1,
  `status` enum('draft','published') DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `author_id`, `category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_media_id`, `video_url`, `view_count`, `react_enabled`, `comments_enabled`, `status`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 3, 'announcement', 'Why do we use it?', 'tes-upload', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 'Why do we use it?\r\nIt is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).\r\n\r\n\r\nWhere does it come from?\r\nContrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.\r\n\r\nThe standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.\r\n\r\nWhere can I get some?\r\nThere are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.', 3, NULL, 0, 1, 1, 'published', '2025-11-25 08:51:00', '2025-11-25 08:51:02', '2025-11-25 14:17:24', NULL),
(2, 2, 4, 'article', 'What is Lorem Ipsum Cuy!', 'what-is-lorem-ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '<h1>What is Lorem Ipsum?</h1><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p>\r\n<img src=\"http://localhost:8080/uploads/summernote/1764164985_f4b6e8037729bb37feff.jpg\" alt=\"1764164985_f4b6e8037729bb37feff.jpg\" style=\"width:100%;\" /></p><h1>\r\nWhy do we use it?\r\n</h1><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).\r\n</p><h1>\r\n\r\nWhere does it come from?\r\n</h1><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>', 5, NULL, 0, 1, 1, 'published', '2025-11-25 08:56:00', '2025-11-25 08:56:43', '2025-11-26 14:43:27', NULL),
(3, 3, 3, 'article', 'Tes tulis artikel lagi bro', 'tes-tulis-artikel-lagi-bro', 'Tes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi bro', '<p>Tes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi broTes tulis artikel lagi bro</p>', 8, NULL, 0, 1, 1, 'published', '2025-11-26 15:55:00', '2025-11-26 15:55:58', '2025-11-26 15:55:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post_reactions`
--

CREATE TABLE `post_reactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `reaction_type_id` tinyint(3) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_reaction_counts`
--

CREATE TABLE `post_reaction_counts` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `counts` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '{}' CHECK (json_valid(`counts`)),
  `total` int(10) UNSIGNED DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_views`
--

CREATE TABLE `post_views` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `viewed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reaction_types`
--

CREATE TABLE `reaction_types` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `code` varchar(30) NOT NULL,
  `emoji` varchar(10) NOT NULL,
  `label` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reaction_types`
--

INSERT INTO `reaction_types` (`id`, `code`, `emoji`, `label`, `created_at`) VALUES
(1, 'like', '👍', 'Like', '2025-11-25 15:04:34'),
(2, 'love', '❤️', 'Love', '2025-11-25 15:04:34'),
(3, 'wow', '😮', 'Wow', '2025-11-25 15:04:34'),
(4, 'sad', '😢', 'Sad', '2025-11-25 15:04:34'),
(5, 'clap', '👏', 'Clap', '2025-11-25 15:04:34');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(150) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'admin', 'Administrator with full access', '2025-11-25 14:23:07'),
(2, 'guru', 'Teacher with content management access', '2025-11-25 14:23:07'),
(3, 'staff', 'Staff with limited access', '2025-11-25 14:23:07');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_jobs`
--

CREATE TABLE `scheduled_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_key` varchar(150) NOT NULL,
  `job_type` varchar(100) NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `scheduled_at` datetime NOT NULL,
  `attempts` tinyint(3) UNSIGNED DEFAULT 0,
  `status` enum('pending','running','failed','done') DEFAULT 'pending',
  `last_error` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seo_overrides`
--

CREATE TABLE `seo_overrides` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject_type` varchar(50) NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `canonical` varchar(255) DEFAULT NULL,
  `robots` varchar(50) DEFAULT 'index,follow',
  `structured_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`structured_data`)),
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seo_overrides`
--

INSERT INTO `seo_overrides` (`id`, `subject_type`, `subject_id`, `meta_title`, `meta_description`, `meta_keywords`, `canonical`, `robots`, `structured_data`, `created_at`, `updated_at`) VALUES
(1, 'page', 1, 'Test Static Page SEO Title', 'This is a brief description for SEO testing of the static page.', 'test, static, page, seo', 'https://example.com/test-static-page', 'index,follow', NULL, '2025-11-25 08:34:15', '2025-11-25 08:34:15'),
(2, 'post', 0, '', '', '', '', 'index,follow', NULL, '2025-11-25 08:34:24', '2025-11-25 08:34:24'),
(6, 'post', 1, '', '', '', '', 'index,follow', NULL, '2025-11-25 08:51:02', '2025-11-25 14:17:24'),
(7, 'post', 2, '', '', '', '', 'index,follow', NULL, '2025-11-25 08:56:43', '2025-11-26 14:43:27'),
(8, 'page', 2, '', '', '', '', 'index,follow', NULL, '2025-11-25 09:00:38', '2025-11-26 14:00:22'),
(9, 'page', 3, '', '', '', '', 'index,follow', NULL, '2025-11-26 14:00:56', '2025-11-26 14:00:56'),
(10, 'post', 3, '', '', '', '', 'index,follow', NULL, '2025-11-26 15:55:58', '2025-11-26 15:56:09'),
(11, 'page', 4, '', '', '', '', 'index,follow', NULL, '2025-11-26 16:16:00', '2025-11-26 16:16:00');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `type` enum('text','textarea','number','boolean','image','json') DEFAULT 'text',
  `group_name` varchar(50) DEFAULT 'general',
  `description` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key_name`, `value`, `type`, `group_name`, `description`, `updated_at`, `created_at`) VALUES
(1, 'site_name', 'Adyatama Sekolah', 'text', 'general', 'Nama Website', '2025-11-26 16:56:01', '2025-11-25 08:13:28'),
(2, 'site_description', 'Website Sekolah Terbaik', 'textarea', 'general', 'Deskripsi Singkat', '2025-11-26 16:56:01', '2025-11-25 08:13:28'),
(3, 'school_address', 'Jl. Sukoanyar No. 2', 'textarea', 'contact', 'Alamat Sekolah', '2025-11-26 16:56:01', '2025-11-25 08:13:28'),
(4, 'school_phone', '(021) 7654321', 'text', 'contact', 'Nomor Telepon', '2025-11-26 16:56:01', '2025-11-25 08:13:28'),
(5, 'school_email', 'contact@adyatama.sch.id', 'text', 'contact', 'Email Sekolah', '2025-11-26 16:56:01', '2025-11-25 08:13:28'),
(6, 'meta_keywords', 'education, school, adyatama updated', 'textarea', 'seo', 'Default Meta Keywords', '2025-11-26 16:56:01', '2025-11-25 08:13:28'),
(7, 'facebook_url', '#', 'text', 'social', 'Facebook URL', '2025-11-26 16:56:01', '2025-11-25 08:13:28'),
(8, 'instagram_url', '#', 'text', 'social', 'Instagram URL', '2025-11-26 16:56:01', '2025-11-25 08:13:28'),
(26, 'hero_title', 'Wesbsite Sekolah Adyatama Terbaru!', 'text', 'hero', 'Hero Section Title', '2025-11-26 16:56:01', '2025-11-25 12:59:38'),
(27, 'hero_description', 'Ayo Sekolah Disini!', 'textarea', 'hero', 'Hero Section Description', '2025-11-26 16:56:01', '2025-11-25 12:59:38'),
(28, 'hero_btn_text', 'Learn More', 'text', 'hero', 'Hero Button Text', '2025-11-26 16:56:01', '2025-11-25 12:59:38'),
(29, 'hero_btn_url', '#', 'text', 'hero', 'Hero Button URL', '2025-11-26 16:56:01', '2025-11-25 12:59:38'),
(30, 'site_logo', 'uploads/settings/1764077873_2dc85ef0b22f88897d62.png', 'image', 'general', 'Website Logo', '2025-11-25 13:37:53', '2025-11-25 20:35:52'),
(31, 'seo_image', 'uploads/settings/1764078004_01666d36caced665e9ab.png', 'image', 'seo', 'Default SEO Image (OG Image)', '2025-11-25 13:40:04', '2025-11-25 20:35:52'),
(32, 'hero_bg_image', 'uploads/settings/1764078793_fd0f826199bab22c8c51.jpg', 'image', 'hero', 'Hero Section Background Image', '2025-11-25 13:53:13', '2025-11-25 20:35:52');

-- --------------------------------------------------------

--
-- Table structure for table `student_applications`
--

CREATE TABLE `student_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `nama_ortu` varchar(150) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `asal_sekolah` varchar(150) DEFAULT NULL,
  `dokumen_kk` varchar(255) DEFAULT NULL,
  `dokumen_akte` varchar(255) DEFAULT NULL,
  `status` enum('pending','review','accepted','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_applications`
--

INSERT INTO `student_applications` (`id`, `nama_lengkap`, `nisn`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `nama_ortu`, `no_hp`, `email`, `asal_sekolah`, `dokumen_kk`, `dokumen_akte`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Dena Setya Utama', NULL, 'L', 'Malang', '2025-11-05', 'Jl. Merdeka Garotan\r\nWajak', 'Ainun', '085645810516', 'denasetyautama@gmail.com', 'AL hasan', 'pendaftaran/tFRt7mPtFgvoUwbbVLTCB8Xn8m8h30jeCVTvbsKD.png', 'pendaftaran/ULoCl9OIofZN92l35r9Yi9H25BztUNOhws8hVlJW.jpg', 'pending', '2025-11-25 06:30:50', '2025-11-25 06:30:50');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(150) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `subscribed_at` datetime DEFAULT current_timestamp(),
  `unsubscribed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `post_id`, `name`, `slug`, `created_at`) VALUES
(13, 2, 'Lorem', 'lorem', '2025-11-26 14:43:27'),
(14, 2, 'Ipsum', 'ipsum', '2025-11-26 14:43:27'),
(15, 3, 'Tes Tulis', 'tes-tulis', '2025-11-26 15:56:09'),
(16, 3, 'Artikel', 'artikel', '2025-11-26 15:56:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `fullname` varchar(150) DEFAULT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `photo` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `fullname`, `role_id`, `status`, `photo`, `last_login`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'admin', 'admin@adyatama.school', '$2y$12$Qa.FH1aFKhFHHfyu4CgUJ.qT.gjXoEe/Wxsb12wEeO3kABnCXq.52', 'Super Admin', 1, 'inactive', NULL, '2025-11-25 08:43:59', '2025-11-25 08:11:38', '2025-11-25 09:23:47', NULL),
(3, 'denasetya', 'denasetyacom@gmail.com', '$2y$12$DlYetvU5DtAqHsRcLEfgseOeOGVxvdGg.vk/j51z2L/bPM4FZsJGK', 'Dena Setya Utama', 1, 'active', NULL, '2025-11-26 13:04:20', '2025-11-25 09:20:18', '2025-11-26 13:04:20', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_al_user` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_comments_post` (`post_id`),
  ADD KEY `idx_comments_parent` (`parent_id`),
  ADD KEY `fk_comments_user` (`user_id`);

--
-- Indexes for table `extracurriculars`
--
ALTER TABLE `extracurriculars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_galleries_extracurricular` (`extracurricular_id`);

--
-- Indexes for table `gallery_items`
--
ALTER TABLE `gallery_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_gallery_items_gallery` (`gallery_id`),
  ADD KEY `idx_gallery_items_media` (`media_id`);

--
-- Indexes for table `guru_staff`
--
ALTER TABLE `guru_staff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_gs_user` (`user_id`),
  ADD KEY `idx_gs_status` (`is_active`,`status`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_media_post` (`origin_post_id`);

--
-- Indexes for table `media_variants`
--
ALTER TABLE `media_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ux_media_variant` (`media_id`,`variant_key`),
  ADD KEY `idx_media_variants_media` (`media_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations_legacy`
--
ALTER TABLE `migrations_legacy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_posts_author` (`author_id`),
  ADD KEY `idx_posts_category` (`category_id`),
  ADD KEY `idx_posts_status_published` (`status`,`published_at`),
  ADD KEY `idx_posts_slug_status` (`slug`,`status`);

--
-- Indexes for table `post_reactions`
--
ALTER TABLE `post_reactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pr_post` (`post_id`),
  ADD KEY `idx_pr_user` (`user_id`),
  ADD KEY `idx_pr_session` (`session_id`),
  ADD KEY `fk_pr_rt` (`reaction_type_id`);

--
-- Indexes for table `post_reaction_counts`
--
ALTER TABLE `post_reaction_counts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `post_views`
--
ALTER TABLE `post_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pv_post` (`post_id`),
  ADD KEY `idx_pv_user` (`user_id`);

--
-- Indexes for table `reaction_types`
--
ALTER TABLE `reaction_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `idx_rp_perm` (`permission_id`);

--
-- Indexes for table `scheduled_jobs`
--
ALTER TABLE `scheduled_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sj_type` (`job_type`),
  ADD KEY `idx_sj_scheduled` (`scheduled_at`);

--
-- Indexes for table `seo_overrides`
--
ALTER TABLE `seo_overrides`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ux_seo_subject` (`subject_type`,`subject_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Indexes for table `student_applications`
--
ALTER TABLE `student_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tags_post` (`post_id`),
  ADD KEY `idx_tags_slug` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_users_role` (`role_id`),
  ADD KEY `idx_users_status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extracurriculars`
--
ALTER TABLE `extracurriculars`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `gallery_items`
--
ALTER TABLE `gallery_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `guru_staff`
--
ALTER TABLE `guru_staff`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `media_variants`
--
ALTER TABLE `media_variants`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations_legacy`
--
ALTER TABLE `migrations_legacy`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `post_reactions`
--
ALTER TABLE `post_reactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_views`
--
ALTER TABLE `post_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reaction_types`
--
ALTER TABLE `reaction_types`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `scheduled_jobs`
--
ALTER TABLE `scheduled_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seo_overrides`
--
ALTER TABLE `seo_overrides`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `student_applications`
--
ALTER TABLE `student_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `fk_al_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_parent` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `galleries`
--
ALTER TABLE `galleries`
  ADD CONSTRAINT `fk_galleries_extracurricular` FOREIGN KEY (`extracurricular_id`) REFERENCES `extracurriculars` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `gallery_items`
--
ALTER TABLE `gallery_items`
  ADD CONSTRAINT `fk_gallery_items_gallery` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_gallery_items_media` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `guru_staff`
--
ALTER TABLE `guru_staff`
  ADD CONSTRAINT `fk_gs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `fk_media_post` FOREIGN KEY (`origin_post_id`) REFERENCES `posts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `media_variants`
--
ALTER TABLE `media_variants`
  ADD CONSTRAINT `fk_media_variants_media` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_posts_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `post_reactions`
--
ALTER TABLE `post_reactions`
  ADD CONSTRAINT `fk_pr_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pr_rt` FOREIGN KEY (`reaction_type_id`) REFERENCES `reaction_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pr_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `post_reaction_counts`
--
ALTER TABLE `post_reaction_counts`
  ADD CONSTRAINT `fk_prc_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_views`
--
ALTER TABLE `post_views`
  ADD CONSTRAINT `fk_pv_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pv_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `fk_rp_perm` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rp_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `fk_tags_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
