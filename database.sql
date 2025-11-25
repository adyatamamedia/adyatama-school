-- ======================================================
-- SOLUSI AMAN: BUAT SEMUA TABEL DENGAN IF NOT EXISTS
-- ======================================================

-- Pastikan database ada
CREATE DATABASE IF NOT EXISTS `adyatama_sekolah` CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
USE `adyatama_sekolah`;

-- ---------------------------
-- 1. roles
-- ---------------------------
CREATE TABLE IF NOT EXISTS roles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE,
  description VARCHAR(150) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 2. users
-- ---------------------------
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(150) DEFAULT NULL,
  password_hash VARCHAR(255) NOT NULL,
  fullname VARCHAR(150) DEFAULT NULL,
  role_id INT UNSIGNED NOT NULL,
  status ENUM('active','inactive') DEFAULT 'active',
  photo VARCHAR(255) DEFAULT NULL,
  last_login DATETIME DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME DEFAULT NULL,
  INDEX idx_users_role (role_id),
  INDEX idx_users_status (status),
  CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 3. permissions
-- ---------------------------
CREATE TABLE IF NOT EXISTS permissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL UNIQUE,
  label VARCHAR(150) DEFAULT NULL,
  resource VARCHAR(100) DEFAULT NULL,
  action VARCHAR(50) DEFAULT NULL,
  description VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 4. role_permissions (pivot)
-- ---------------------------
CREATE TABLE IF NOT EXISTS role_permissions (
  role_id INT UNSIGNED NOT NULL,
  permission_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (role_id, permission_id),
  INDEX idx_rp_perm (permission_id),
  CONSTRAINT fk_rp_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_rp_perm FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 5. categories
-- ---------------------------
CREATE TABLE IF NOT EXISTS categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(120) NOT NULL UNIQUE,
  description TEXT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 6. posts
-- ---------------------------
CREATE TABLE IF NOT EXISTS posts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  author_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NULL,
  post_type ENUM('article','announcement','video') DEFAULT 'article',
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  excerpt TEXT NULL,
  content LONGTEXT NULL,
  featured_media_id INT UNSIGNED NULL,
  video_url VARCHAR(255) NULL,
  view_count INT UNSIGNED DEFAULT 0,
  react_enabled TINYINT(1) DEFAULT 1,
  comments_enabled TINYINT(1) DEFAULT 1,
  status ENUM('draft','published') DEFAULT 'draft',
  published_at DATETIME NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME DEFAULT NULL,
  INDEX idx_posts_author (author_id),
  INDEX idx_posts_category (category_id),
  INDEX idx_posts_status_published (status, published_at),
  INDEX idx_posts_slug_status (slug, status),
  CONSTRAINT fk_posts_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_posts_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 7. tags (per-post)
-- ---------------------------
CREATE TABLE IF NOT EXISTS tags (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_id INT UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(120) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_tags_post (post_id),
  INDEX idx_tags_slug (slug),
  CONSTRAINT fk_tags_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 8. media
-- ---------------------------
CREATE TABLE IF NOT EXISTS media (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  origin_post_id INT UNSIGNED NULL,
  type ENUM('image','video','file') DEFAULT 'image',
  path VARCHAR(255) NOT NULL,
  caption VARCHAR(255) DEFAULT NULL,
  meta JSON NULL,
  order_num INT DEFAULT 0,
  filesize BIGINT UNSIGNED NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  INDEX idx_media_post (origin_post_id),
  CONSTRAINT fk_media_post FOREIGN KEY (origin_post_id) REFERENCES posts(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 9. media_variants
-- ---------------------------
CREATE TABLE IF NOT EXISTS media_variants (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  media_id INT UNSIGNED NOT NULL,
  variant_key VARCHAR(50) NOT NULL,
  path VARCHAR(255) NOT NULL,
  width INT UNSIGNED NULL,
  height INT UNSIGNED NULL,
  filesize BIGINT UNSIGNED NULL,
  meta JSON NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY ux_media_variant (media_id, variant_key),
  INDEX idx_media_variants_media (media_id),
  CONSTRAINT fk_media_variants_media FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 10. extracurriculars
-- ---------------------------
CREATE TABLE IF NOT EXISTS extracurriculars (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE,
  description TEXT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 11. galleries
-- ---------------------------
CREATE TABLE IF NOT EXISTS galleries (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description TEXT NULL,
  extracurricular_id INT UNSIGNED NULL,
  status ENUM('draft','published') DEFAULT 'published',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  INDEX idx_galleries_extracurricular (extracurricular_id),
  CONSTRAINT fk_galleries_extracurricular FOREIGN KEY (extracurricular_id) REFERENCES extracurriculars(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 12. gallery_items
-- ---------------------------
CREATE TABLE IF NOT EXISTS gallery_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  gallery_id INT UNSIGNED NOT NULL,
  media_id INT UNSIGNED NOT NULL,
  type ENUM('image','video') DEFAULT 'image',
  path VARCHAR(255) NOT NULL,
  caption VARCHAR(255) DEFAULT NULL,
  order_num INT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  INDEX idx_gallery_items_gallery (gallery_id),
  INDEX idx_gallery_items_media (media_id),
  CONSTRAINT fk_gallery_items_gallery FOREIGN KEY (gallery_id) REFERENCES galleries(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_gallery_items_media FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 13. guru_staff
-- ---------------------------
CREATE TABLE IF NOT EXISTS guru_staff (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nip VARCHAR(50) DEFAULT NULL,
  nama_lengkap VARCHAR(150) NOT NULL,
  jabatan VARCHAR(100) DEFAULT NULL,
  bidang VARCHAR(100) DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  no_hp VARCHAR(20) DEFAULT NULL,
  foto VARCHAR(255) DEFAULT NULL,
  status ENUM('guru','staff') DEFAULT 'guru',
  is_active TINYINT(1) DEFAULT 1,
  user_id INT UNSIGNED NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  INDEX idx_gs_user (user_id),
  INDEX idx_gs_status (is_active, status),
  CONSTRAINT fk_gs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 14. pages
-- ---------------------------
CREATE TABLE IF NOT EXISTS pages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  content LONGTEXT NULL,
  featured_image VARCHAR(255) DEFAULT NULL,
  status ENUM('draft','published') DEFAULT 'published',
  order_num INT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 15. settings
-- ---------------------------
CREATE TABLE IF NOT EXISTS settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  key_name VARCHAR(100) NOT NULL UNIQUE,
  value TEXT NULL,
  type ENUM('text','textarea','number','boolean','image','json') DEFAULT 'text',
  group_name VARCHAR(50) DEFAULT 'general',
  description VARCHAR(255) DEFAULT NULL,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 16. seo_overrides
-- ---------------------------
CREATE TABLE IF NOT EXISTS seo_overrides (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  subject_type VARCHAR(50) NOT NULL,
  subject_id INT UNSIGNED NOT NULL,
  meta_title VARCHAR(255) NULL,
  meta_description VARCHAR(500) NULL,
  meta_keywords VARCHAR(500) NULL,
  canonical VARCHAR(255) NULL,
  robots VARCHAR(50) DEFAULT 'index,follow',
  structured_data JSON NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY ux_seo_subject (subject_type, subject_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 17. scheduled_jobs
-- ---------------------------
CREATE TABLE IF NOT EXISTS scheduled_jobs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  job_key VARCHAR(150) NOT NULL,
  job_type VARCHAR(100) NOT NULL,
  payload JSON NULL,
  scheduled_at DATETIME NOT NULL,
  attempts TINYINT UNSIGNED DEFAULT 0,
  status ENUM('pending','running','failed','done') DEFAULT 'pending',
  last_error TEXT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_sj_type (job_type),
  INDEX idx_sj_scheduled (scheduled_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 18. post_views
-- ---------------------------
CREATE TABLE IF NOT EXISTS post_views (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NULL,
  ip_address VARCHAR(45) NULL,
  user_agent VARCHAR(255) NULL,
  viewed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_pv_post (post_id),
  INDEX idx_pv_user (user_id),
  CONSTRAINT fk_pv_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_pv_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 19. comments
-- ---------------------------
CREATE TABLE IF NOT EXISTS comments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_id INT UNSIGNED NOT NULL,
  parent_id BIGINT UNSIGNED NULL,
  user_id INT UNSIGNED NULL,
  author_name VARCHAR(150) DEFAULT NULL,
  author_email VARCHAR(150) DEFAULT NULL,
  content TEXT NOT NULL,
  is_approved TINYINT(1) DEFAULT 0,
  is_spam TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_comments_post (post_id),
  INDEX idx_comments_parent (parent_id),
  CONSTRAINT fk_comments_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_comments_parent FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT fk_comments_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 20. reaction_types
-- ---------------------------
CREATE TABLE IF NOT EXISTS reaction_types (
  id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(30) NOT NULL UNIQUE,
  emoji VARCHAR(10) NOT NULL,
  label VARCHAR(50) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 21. post_reactions
-- ---------------------------
CREATE TABLE IF NOT EXISTS post_reactions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_id INT UNSIGNED NOT NULL,
  reaction_type_id TINYINT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NULL,
  session_id VARCHAR(128) NULL,
  ip_address VARCHAR(45) NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_pr_post (post_id),
  INDEX idx_pr_user (user_id),
  INDEX idx_pr_session (session_id),
  CONSTRAINT fk_pr_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_pr_rt FOREIGN KEY (reaction_type_id) REFERENCES reaction_types(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_pr_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 22. post_reaction_counts
-- ---------------------------
CREATE TABLE IF NOT EXISTS post_reaction_counts (
  post_id INT UNSIGNED PRIMARY KEY,
  counts JSON NOT NULL DEFAULT ('{}'),
  total INT UNSIGNED DEFAULT 0,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_prc_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 23. activity_log
-- ---------------------------
CREATE TABLE IF NOT EXISTS activity_log (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  action VARCHAR(100) NOT NULL,
  subject_type VARCHAR(100) NULL,
  subject_id INT UNSIGNED NULL,
  ip_address VARCHAR(45) NULL,
  user_agent VARCHAR(255) NULL,
  meta JSON NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_al_user (user_id),
  CONSTRAINT fk_al_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------
-- 24. subscribers
-- ---------------------------
CREATE TABLE IF NOT EXISTS subscribers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(150) NOT NULL UNIQUE,
  name VARCHAR(100) NULL,
  is_active TINYINT(1) DEFAULT 1,
  subscribed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  unsubscribed_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SELECT 'Semua tabel berhasil dibuat/dicek!' as status;