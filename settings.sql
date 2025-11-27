-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2025 at 07:22 AM
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
(125, 'site_name', 'ADYATAMA SCHOOL', 'text', 'general', 'Nama website sekolah', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(126, 'site_slogan', 'Berkarakter, Berkualitas, Berprestasi', 'text', 'general', 'Slogan utama sekolah', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(127, 'site_logo', 'uploads/settings/site_logo_1764200011.png', 'image', 'visual_identity', 'Logo utama sekolah', '2025-11-27 14:19:00', '2025-11-27 06:32:06'),
(128, 'site_favicon', 'uploads/settings/site_favicon_1764200011.png', 'image', 'visual_identity', 'Favicon website', '2025-11-27 14:19:07', '2025-11-27 06:32:06'),
(129, 'og_image', 'uploads/settings/og_image_1764200011.png', 'image', 'visual_identity', 'Gambar default untuk social media sharing', '2025-11-27 14:19:22', '2025-11-27 06:32:06'),
(130, 'primary_color', '#0ea5e9', 'text', 'visual_identity', 'Warna Primer Website', '2025-11-27 14:19:34', '2025-11-27 06:32:06'),
(131, 'secondary_color', '#0284c7', 'text', 'visual_identity', 'Warna Sekunder Website', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(132, 'theme_color', '#0ea5e9', 'text', 'visual_identity', 'Warna tema browser/mobile', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(133, 'show_branding', '1', 'boolean', 'general', 'Tampilkan branding sekolah', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(134, 'hero_title', 'Membentuk Generasi Qurani dan Berprestasi', 'text', 'homepage', 'Judul utama di hero section', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(135, 'hero_description', 'Sekolah Islam terpadu dengan fokus pada karakter dan prestasi akademik.', 'textarea', 'homepage', 'Deskripsi di hero section', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(136, 'hero_bg_image', '/uploads/hero-bg.jpg', 'image', 'homepage', 'Background Image Hero Section', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(137, 'hero_cta_text', 'Daftar Online MI', 'text', 'homepage', 'Teks tombol call-to-action', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(138, 'hero_cta_url', '/daftar-online', 'text', 'homepage', 'URL tombol call-to-action', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(139, 'school_type', 'Sekolah Islam Terpadu', 'text', 'school_info', 'Jenis sekolah', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(140, 'school_level', 'MI', 'text', 'school_info', 'Tingkat pendidikan', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(141, 'curriculum', 'Kurikulum Merdeka + Integrasi Nilai Islam', 'text', 'school_info', 'Kurikulum yang digunakan', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(142, 'academic_year', '2025/2026', 'text', 'school_info', 'Tahun ajaran berjalan', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(143, 'accreditation', 'A', 'text', 'school_info', 'Akreditasi sekolah', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(144, 'school_motto', 'Ilmu, Iman dan Amal', 'text', 'school_info', 'Motto sekolah', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(145, 'school_vision', 'Menjadi sekolah unggul berbasis nilai-nilai Islam', 'textarea', 'school_info', 'Visi sekolah', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(146, 'school_mission', 'Mewujudkan siswa beriman, berilmu, dan beramal', 'textarea', 'school_info', 'Misi sekolah', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(147, 'school_name', 'Yayasan Adyatama School', 'text', 'school_info', 'Nama lengkap yayasan/sekolah', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(148, 'school_email', 'info@adyatama.sch.id', 'text', 'contact_info', 'Email resmi sekolah', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(149, 'school_phone', '(021) 123456789', 'text', 'contact_info', 'Telepon sekolah', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(150, 'school_address', 'Jl. Pendidikan No. 123, Jakarta Selatan', 'textarea', 'contact_info', 'Alamat Lengkap Sekolah', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(151, 'whatsapp_number', '+628123456789', 'text', 'contact_info', 'Nomor WhatsApp (format: +62xxx)', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(152, 'whatsapp_message', 'Halo Admin Adyatama School, saya ingin bertanya tentang...', 'textarea', 'contact_info', 'Pesan Default WhatsApp', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(153, 'emergency_contact', '(021) 987654321', 'text', 'contact_info', 'Kontak darurat', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(154, 'office_hours', 'Senin-Jumat 07:00-15:00', 'text', 'contact_info', 'Jam operasional kantor', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(155, 'maps_embed_url', 'https://maps.google.com/?q=Jl.+Pendidikan+No.+123,+Jakarta+Selatan', 'text', 'contact_info', 'Link Embed Google Maps', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(156, 'maps_embed_iframe', '<iframe src=\"https://maps.google.com/maps?q=Jl.+Pendidikan+No.+123,+Jakarta+Selatan&output=embed\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\"></iframe>', 'textarea', 'contact_info', 'Kode Iframe Google Maps', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(157, 'latitude', '-6.200000', 'text', 'contact_info', 'Koordinat latitude', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(158, 'longitude', '106.816666', 'text', 'contact_info', 'Koordinat longitude', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(159, 'registration_period', 'Januari - Juli 2025', 'text', 'admission', 'Periode pendaftaran', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(160, 'admission_deadline', '31 July 2025', 'text', 'admission', 'Batas akhir pendaftaran', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(161, 'registration_status', 'open', 'text', 'admission', 'Status pendaftaran (open/closed)', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(162, 'min_age', '4', 'number', 'admission', 'Usia minimum pendaftaran', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(163, 'max_age', '12', 'number', 'admission', 'Usia maksimum pendaftaran', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(164, 'registration_fee', '150000', 'number', 'admission', 'Biaya pendaftaran (dalam angka)', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(165, 'annual_fee', '1200000', 'number', 'admission', 'Biaya tahunan (dalam angka)', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(166, 'additional_documents', 'Surat keterangan sehat, Surat akte lahir', 'textarea', 'admission', 'Dokumen tambahan yang diperlukan', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(167, 'admission_procedure', '1. Isi formulir pendaftaran\n2. Upload dokumen\n3. Verifikasi admin\n4. Konfirmasi pendaftaran', 'textarea', 'admission', 'Prosedur pendaftaran', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(168, 'instagram_url', 'https://instagram.com/adyatamaschool', 'text', 'social_media', 'Instagram URL', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(169, 'facebook_url', 'https://facebook.com/adyatamaschool', 'text', 'social_media', 'Facebook Page URL', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(170, 'youtube_url', 'https://youtube.com/@adyatamaschool', 'text', 'social_media', 'YouTube Channel URL', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(171, 'tiktok_url', 'https://tiktok.com/@adyatamaschool', 'text', 'social_media', 'TikTok URL', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(172, 'twitter_url', 'https://twitter.com/adyatamaschool', 'text', 'social_media', 'Twitter/X URL', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(173, 'linkedin_url', 'https://linkedin.com/company/adyatamaschool', 'text', 'social_media', 'LinkedIn Company URL', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(174, 'website_url', 'https://adyatamaschool.sch.id', 'text', 'social_media', 'Website URL', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(175, 'social_media_widget', '1', 'boolean', 'social_media', 'Tampilkan widget media sosial', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(176, 'sk_pendirian', 'uploads/legal/sk_pendirian.pdf', 'text', 'legal_documents', 'SK Pendirian Yayasan', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(177, 'izin_operasional', 'uploads/legal/izin_operasional.pdf', 'text', 'legal_documents', 'Izin Operasional Sekolah', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(178, 'akreditasi', 'uploads/legal/sertifikat_akreditasi.pdf', 'text', 'legal_documents', 'Sertifikat Akreditasi', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(179, 'kurikulum', 'uploads/legal/ijin_kurikulum.pdf', 'text', 'legal_documents', 'Ijin Kurikulum', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(180, 'legalitas_lain', 'uploads/legal/dokumen_lainnya.pdf', 'text', 'legal_documents', 'Dokumen legalitas lainnya', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(181, 'npsn', '123456789', 'text', 'school_info', 'Nomor Pokok Sekolah Nasional', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(182, 'nss', '123456789', 'text', 'school_info', 'Nomor Statistik Sekolah', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(183, 'meta_description', 'Sekolah Islam Terpadu Adyatama School - Berkarakter, Berkualitas, Berprestasi. Pendidikan berbasis nilai Islam dengan kurikulum merdeka.', 'textarea', 'seo_config', 'Deskripsi Website (max 160 karakter)', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(184, 'meta_keywords', 'sekolah islam, pendidikan, adyatama school, tk, sd, smp, sma, sekolah terpadu, kurikulum merdeka', 'textarea', 'seo_config', 'Keywords (dipisah dengan koma)', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(185, 'google_analytics', 'G-XXXXXXXXXX', 'textarea', 'seo_config', 'Google Analytics Tracking Code', '2025-11-27 06:32:06', '2025-11-27 06:32:06'),
(186, 'google_tag_manager', 'GTM-XXXXXXX', 'text', 'seo_config', 'Google Tag Manager ID', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(187, 'facebook_pixel', 'XXXXXXXXXXXXXXX', 'text', 'seo_config', 'Facebook Pixel ID', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(188, 'site_verification', 'google-site-verification=XXXXXXXXXX', 'text', 'seo_config', 'Kode verifikasi search engine', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(189, 'robots_txt', 'User-agent: *\nAllow: /\nSitemap: https://adyatamaschool.sch.id/sitemap.xml', 'textarea', 'seo_config', 'Konten robots.txt', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(190, 'sitemap_enabled', '1', 'boolean', 'seo_config', 'Aktifkan sitemap otomatis', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(191, 'cdn_enabled', '0', 'boolean', 'performance', 'Aktifkan CDN untuk assets', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(192, 'minify_html', '1', 'boolean', 'performance', 'Minifikasi HTML output', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(193, 'email_from', 'info@adyatama.sch.id', 'text', 'email_config', 'Alamat email pengirim', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(194, 'email_from_name', 'Adyatama School', 'text', 'email_config', 'Nama pengirim email', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(195, 'smtp_host', 'smtp.gmail.com', 'text', 'email_config', 'SMTP Host', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(196, 'smtp_port', '587', 'number', 'email_config', 'SMTP Port', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(197, 'smtp_username', 'noreply@adyatama.sch.id', 'text', 'email_config', 'SMTP Username', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(198, 'smtp_password', 'encrypted_password_here', 'text', 'email_config', 'SMTP Password (encrypted)', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(199, 'email_notifications', '1', 'boolean', 'email_config', 'Aktifkan notifikasi email', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(200, 'application_notification', '1', 'boolean', 'email_config', 'Notifikasi pendaftaran baru', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(201, 'comment_notification', '1', 'boolean', 'email_config', 'Notifikasi komentar baru', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(202, 'recaptcha_enabled', '1', 'boolean', 'security', 'Aktifkan reCAPTCHA', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(203, 'recaptcha_site_key', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', 'text', 'security', 'reCAPTCHA Site Key', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(204, 'recaptcha_secret_key', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe', 'text', 'security', 'reCAPTCHA Secret Key', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(205, 'gdpr_compliance', '1', 'boolean', 'security', 'Aktifkan GDPR Compliance', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(206, 'cookie_consent', '1', 'boolean', 'security', 'Tampilkan cookie consent banner', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(207, 'privacy_policy', 'Kebijakan privasi sekolah mengatur bagaimana data pribadi dikumpulkan, digunakan, dan dilindungi.', 'textarea', 'security', 'Kebijakan Privasi', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(208, 'terms_of_service', 'Syarat dan ketentuan penggunaan website dan layanan Adyatama School.', 'textarea', 'security', 'Syarat dan Ketentuan Layanan', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(209, 'data_retention_days', '365', 'number', 'security', 'Masa penyimpanan data (hari)', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(210, 'max_login_attempts', '5', 'number', 'security', 'Maksimal percobaan login', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(211, 'login_lockout_time', '900', 'number', 'security', 'Waktu lockout setelah gagal login (detik)', '2025-11-27 07:20:35', '2025-11-27 06:32:06'),
(212, 'academic_calendar_url', '/uploads/academic-calendar-2025-2026.pdf', 'text', 'academic', 'URL kalender akademik', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(213, 'holidays_schedule', '1 Jan: Tahun Baru\n25 Jan: Tahun Baru Imlek\n12 Mar: Isra Miraj\n29 Mar: Hari Raya Nyepi\n10-11 Apr: Idul Fitri 1446 H\n1 May: Hari Buruh\n14 May: Kenaikan Isa Almasih\n1 Jun: Hari Lahir Pancasila\n17 Jun: Idul Adha 1446 H\n7 Jul: Tahun Baru Islam 1447 H\n17 Aug: Hari Kemerdekaan RI\n25 Dec: Hari Raya Natal', 'textarea', 'academic', 'Jadwal hari libur', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(214, 'exam_schedule', 'Ujian Tengah Semester: 15 Maret - 5 April 2025\nUjian Akhir Semester: 15 Juni - 5 Juli 2025', 'textarea', 'academic', 'Jadwal ujian', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(215, 'event_announcement', 'Pendaftaran Tahun Ajaran 2025/2026 telah dibuka!', 'text', 'academic', 'Pengumuman event terkini', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(216, 'upcoming_events', 'Open House: 15 Januari 2025\nPorseni: 20-25 Februari 2025', 'textarea', 'academic', 'Event yang akan datang', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(217, 'maintenance_mode', '0', 'boolean', 'system', 'Mode maintenance website', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(218, 'maintenance_message', 'Website sedang dalam perawatan, akan kembali pada pukul 10:00 WIB', 'textarea', 'system', 'Pesan maintenance mode', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(219, 'guest_comment', '1', 'boolean', 'content', 'Izinkan komentar dari tamu', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(220, 'auto_approve_comments', '0', 'boolean', 'content', 'Auto-approve komentar', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(221, 'comment_captcha', '1', 'boolean', 'content', 'CAPTCHA untuk komentar', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(222, 'posts_per_page', '9', 'number', 'content', 'Jumlah post per halaman', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(223, 'galleries_per_page', '12', 'number', 'content', 'Jumlah gallery per halaman', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(224, 'enable_reactions', '1', 'boolean', 'content', 'Aktifkan reaksi untuk post', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(225, 'enable_sharing', '1', 'boolean', 'content', 'Aktifkan sharing ke media sosial', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(226, 'enable_search', '1', 'boolean', 'content', 'Aktifkan fitur pencarian', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(227, 'search_results_per_page', '10', 'number', 'content', 'Jumlah hasil pencarian per halaman', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(228, 'payment_enabled', '1', 'boolean', 'payment', 'Aktifkan pembayaran online', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(229, 'payment_method', 'midtrans', 'text', 'payment', 'Metode pembayaran (midtrans/manual)', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(230, 'bank_account_name', 'Yayasan Adyatama School', 'text', 'payment', 'Nama pemilik rekening bank', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(231, 'bank_account_number', '1234567890', 'text', 'payment', 'Nomor rekening bank', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(232, 'bank_name', 'Bank BRI', 'text', 'payment', 'Nama bank', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(233, 'donation_enabled', '1', 'boolean', 'payment', 'Aktifkan fitur donasi', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(234, 'donation_message', 'Bantu kami dalam mendidik generasi qurani melalui donasi Anda. Setiap kontribusi sangat berarti untuk perkembangan pendidikan Islam.', 'textarea', 'payment', 'Pesan donasi', '2025-11-27 07:20:35', '2025-11-27 06:32:07'),
(238, 'timezone', 'Asia/Jakarta', 'text', 'system', 'Zona waktu sistem', '2025-11-27 07:20:35', '2025-11-27 07:08:56'),
(239, 'language', 'id', 'text', 'system', 'Bahasa default website', '2025-11-27 07:20:35', '2025-11-27 07:08:56'),
(240, 'date_format', 'd-m-Y', 'text', 'system', 'Format tanggal', '2025-11-27 07:20:35', '2025-11-27 07:08:56'),
(241, 'smtp_encryption', 'tls', 'text', 'email_config', 'Enkripsi SMTP', '2025-11-27 07:20:35', '2025-11-27 07:08:56'),
(242, 'semester', 'Ganjil', 'text', 'academic', 'Semester berjalan', '2025-11-27 07:20:35', '2025-11-27 07:08:56'),
(243, 'midtrans_server_key', 'SB-Mid-server-XXXXXXXXXXXX', 'text', 'payment', 'Midtrans Server Key', '2025-11-27 07:20:35', '2025-11-27 07:08:56'),
(244, 'midtrans_client_key', 'SB-Mid-client-XXXXXXXXXXXX', 'text', 'payment', 'Midtrans Client Key', '2025-11-27 07:20:35', '2025-11-27 07:08:56'),
(245, 'midtrans_is_production', '0', 'boolean', 'payment', 'Mode production Midtrans', '2025-11-27 07:20:35', '2025-11-27 07:08:56'),
(246, 'total_students', '500', 'number', 'school_info', 'Total jumlah siswa', '2025-11-27 07:20:35', '2025-11-27 07:08:56'),
(247, 'total_teachers', '50', 'number', 'school_info', 'Total jumlah guru', '2025-11-27 07:20:35', '2025-11-27 07:08:56'),
(248, 'total_classes', '24', 'number', 'school_info', 'Total jumlah kelas', '2025-11-27 07:20:35', '2025-11-27 07:08:56'),
(249, 'facilities', '- Laboratorium Komputer\n- Perpustakaan Digital\n- Masjid Sekolah\n- Lapangan Olahraga\n- Kantin Sehat\n- Ruang Musik\n- Laboratorium Sains\n- Ruang Multimedia', 'textarea', 'school_info', 'Fasilitas sekolah', '2025-11-27 07:20:35', '2025-11-27 07:08:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;