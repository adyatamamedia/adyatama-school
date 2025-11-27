<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class Settings extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        // Group settings by 'group_name'
        $settings = $this->settingModel->orderBy('group_name', 'ASC')->findAll();

        $groupedSettings = [];
        foreach ($settings as $s) {
            $groupedSettings[$s->group_name][] = $s;
        }

        $data = [
            'title' => 'System Settings',
            'groupedSettings' => $groupedSettings
        ];

        return view('admin/settings/index', $data);
    }

    public function update()
    {
        $postData = $this->request->getPost();

        // Remove CSRF token from processing
        unset($postData[csrf_token()]);

        // Handle Legal Documents Upload (PDF/DOC files)
        $legalKeys = ['sk_pendirian', 'izin_operasional', 'akreditasi', 'kurikulum', 'academic_calendar_url'];
        foreach ($legalKeys as $key) {
            $file = $this->request->getFile($key);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Validate file type and size
                $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    session()->setFlashdata('error', 'Invalid file type for ' . $key . '. Only PDF and DOC files are allowed.');
                    continue;
                }

                if ($file->getSize() > 5 * 1024 * 1024) { // 5MB
                    session()->setFlashdata('error', 'File size too large for ' . $key . '. Maximum size is 5MB.');
                    continue;
                }

                // Create directory if not exists
                if (!is_dir(FCPATH . 'uploads/legal-docs')) {
                    mkdir(FCPATH . 'uploads/legal-docs', 0755, true);
                }

                // Generate safe filename
                $newName = $key . '_' . time() . '.' . $file->getExtension();

                if ($file->move(FCPATH . 'uploads/legal-docs', $newName)) {
                    // Delete old file if exists
                    $setting = $this->settingModel->where('key_name', $key)->first();
                    if ($setting && !empty($setting->value) && file_exists(FCPATH . $setting->value)) {
                        unlink(FCPATH . $setting->value);
                    }

                    // Update database with new file path
                    $this->settingModel->updateOrCreate($key, 'uploads/legal-docs/' . $newName);
                }
            }
        }

        // Handle Image Uploads (favicon, logo, og_image, hero_bg_image)
        $imageKeys = ['site_favicon', 'site_logo', 'og_image', 'hero_bg_image'];
        foreach ($imageKeys as $key) {
            $file = $this->request->getFile($key);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Validate file type and size
                $allowedTypes = ['image/x-icon', 'image/vnd.microsoft.icon', 'image/ico', 'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    session()->setFlashdata('error', 'Invalid file type for ' . $key . '. Only image files are allowed.');
                    continue;
                }

                // Different size limits for different images
                $maxSize = ($key === 'site_favicon') ? 1 * 1024 * 1024 : 5 * 1024 * 1024; // 1MB for favicon, 5MB for others
                if ($file->getSize() > $maxSize) {
                    session()->setFlashdata('error', 'File size too large for ' . $key . '. Maximum size is ' . ($maxSize / 1024 / 1024) . 'MB.');
                    continue;
                }

                // Create directory if not exists
                if (!is_dir(FCPATH . 'uploads/settings')) {
                    mkdir(FCPATH . 'uploads/settings', 0755, true);
                }

                // Generate safe filename
                $newName = $key . '_' . time() . '.' . $file->getExtension();

                if ($file->move(FCPATH . 'uploads/settings', $newName)) {
                    // Delete old file if exists
                    $setting = $this->settingModel->where('key_name', $key)->first();
                    if ($setting && !empty($setting->value) && file_exists(FCPATH . $setting->value)) {
                        unlink(FCPATH . $setting->value);
                    }

                    // Update database with new file path
                    $this->settingModel->updateOrCreate($key, 'uploads/settings/' . $newName);
                }
            }
        }

        // Update text fields (excluding handled fields above)
        foreach ($postData as $key => $value) {
            if (!in_array($key, array_merge($legalKeys, $imageKeys))) {
                // Handle special cases for new settings
                if (in_array($key, ['hero_cta_url', 'maps_embed_url', 'website_url'])) {
                    $this->settingModel->updateOrCreate($key, $value, 'url', 'visual_identity');
                } elseif (in_array($key, ['email_from'])) {
                    $this->settingModel->updateOrCreate($key, $value, 'email', 'email_config');
                } elseif (in_array($key, ['min_age', 'max_age', 'smtp_port', 'data_retention_days', 'max_login_attempts', 'login_lockout_time', 'posts_per_page', 'galleries_per_page', 'search_results_per_page'])) {
                    $this->settingModel->updateOrCreate($key, $value, 'number', 'website_behavior');
                } elseif (in_array($key, ['admission_deadline'])) {
                    $this->settingModel->updateOrCreate($key, $value, 'date', 'admission_info');
                } elseif (in_array($key, ['hero_title', 'hero_cta_text', 'hero_description', 'school_motto', 'school_type', 'school_level', 'academic_year', 'accreditation', 'registration_period', 'registration_status', 'registration_fee', 'annual_fee', 'payment_method', 'bank_account_name', 'bank_account_number', 'bank_name', 'npsn', 'nss', 'latitude', 'longitude', 'office_hours', 'emergency_contact', 'school_motto'])) {
                    $this->settingModel->updateOrCreate($key, $value, 'text', 'visual_identity'); // Group with similar settings
                } elseif (in_array($key, ['curriculum', 'school_vision', 'school_mission', 'additional_documents', 'admission_procedure', 'holidays_schedule', 'exam_schedule', 'event_announcement', 'upcoming_events', 'privacy_policy', 'terms_of_service', 'robots_txt', 'maintenance_message', 'donation_message'])) {
                    $this->settingModel->updateOrCreate($key, $value, 'textarea', 'academic_info'); // Group with similar settings
                } elseif (in_array($key, ['show_branding', 'social_media_widget', 'sitemap_enabled', 'cdn_enabled', 'minify_html', 'guest_comment', 'auto_approve_comments', 'comment_captcha', 'enable_reactions', 'enable_sharing', 'enable_search', 'email_notifications', 'application_notification', 'comment_notification', 'recaptcha_enabled', 'gdpr_compliance', 'cookie_consent', 'maintenance_mode', 'payment_enabled', 'donation_enabled'])) {
                    $this->settingModel->updateOrCreate($key, $value, 'boolean', 'website_behavior');
                } elseif (in_array($key, ['primary_color', 'secondary_color', 'theme_color'])) {
                    $this->settingModel->updateOrCreate($key, $value, 'color', 'visual_identity');
                } else {
                    // Default update for existing settings
                    $this->settingModel->updateOrCreate($key, $value);
                }
            }
        }

        // Return appropriate message
        if (!empty(session()->getFlashdata('error'))) {
            return redirect()->to('/dashboard/settings')->with('error', session()->getFlashdata('error'));
        }

        return redirect()->to('/dashboard/settings')->with('message', 'Settings updated successfully.');
    }

    public function deleteImage($id)
    {
        $setting = $this->settingModel->find($id);

        if (!$setting || $setting->type !== 'image') {
            return redirect()->to('/dashboard/settings')->with('error', 'Invalid image setting.');
        }

        // Delete the physical file if it exists
        if (!empty($setting->value) && file_exists(FCPATH . $setting->value)) {
            unlink(FCPATH . $setting->value);
        }

        // Update database to clear the value
        $this->settingModel->update($id, ['value' => '']);

        return redirect()->to('/dashboard/settings')->with('message', 'Image deleted successfully.');
    }

    public function debugUpload()
    {
        return view('admin/settings/debug_upload');
    }

    public function seedDefaults()
    {
        // Check if enhanced settings already exist
        if ($this->settingModel->where('key_name', 'hero_title')->first()) {
            return redirect()->to('/dashboard/settings')->with('error', 'Enhanced settings already seeded.');
        }

        $defaults = [
            // Visual Identity
            ['key_name' => 'site_name', 'value' => 'Adyatama School', 'type' => 'text', 'group_name' => 'visual_identity', 'description' => 'Nama Sekolah'],
            ['key_name' => 'site_slogan', 'value' => 'Berkarakter, Berkualitas, Berprestasi', 'type' => 'text', 'group_name' => 'visual_identity', 'description' => 'Tagline/Motto Sekolah'],
            ['key_name' => 'site_logo', 'value' => '', 'type' => 'image', 'group_name' => 'visual_identity', 'description' => 'Logo Utama Website (disarankan PNG)'],
            ['key_name' => 'site_favicon', 'value' => '', 'type' => 'image', 'group_name' => 'visual_identity', 'description' => 'Favicon Website (ICO/PNG 32x32)'],
            ['key_name' => 'og_image', 'value' => '', 'type' => 'image', 'group_name' => 'visual_identity', 'description' => 'Default OG Image (1200x630px)'],
            ['key_name' => 'primary_color', 'value' => '#0ea5e9', 'type' => 'color', 'group_name' => 'visual_identity', 'description' => 'Warna Primer Website'],
            ['key_name' => 'secondary_color', 'value' => '#0284c7', 'type' => 'color', 'group_name' => 'visual_identity', 'description' => 'Warna Sekunder Website'],
            ['key_name' => 'theme_color', 'value' => '#0ea5e9', 'type' => 'color', 'group_name' => 'visual_identity', 'description' => 'Color scheme untuk mobile'],
            ['key_name' => 'show_branding', 'value' => '1', 'type' => 'boolean', 'group_name' => 'visual_identity', 'description' => 'Tampilkan logo/branding di website'],
            ['key_name' => 'hero_title', 'value' => 'Membentuk Generasi Qurani dan Berprestasi', 'type' => 'text', 'group_name' => 'visual_identity', 'description' => 'Judul Hero Section'],
            ['key_name' => 'hero_description', 'value' => 'Sekolah Islam terpadu dengan fokus pada karakter dan prestasi akademik.', 'type' => 'textarea', 'group_name' => 'visual_identity', 'description' => 'Deskripsi Hero Section'],
            ['key_name' => 'hero_bg_image', 'value' => '', 'type' => 'image', 'group_name' => 'visual_identity', 'description' => 'Background Image Hero Section'],
            ['key_name' => 'hero_cta_text', 'value' => 'Daftar Online MI', 'type' => 'text', 'group_name' => 'visual_identity', 'description' => 'Teks tombol Call-to-Action'],
            ['key_name' => 'hero_cta_url', 'value' => '/daftar-online', 'type' => 'url', 'group_name' => 'visual_identity', 'description' => 'Link Call-to-Action'],

            // Academic Information
            ['key_name' => 'school_type', 'value' => 'Sekolah Islam Terpadu', 'type' => 'text', 'group_name' => 'academic_info', 'description' => 'Jenjang sekolah (MI, SD, SMP, SMA)'],
            ['key_name' => 'school_level', 'value' => 'MI', 'type' => 'text', 'group_name' => 'academic_info', 'description' => 'Tingkat pendidikan'],
            ['key_name' => 'curriculum', 'value' => 'Kurikulum Merdeka + Integrasi Nilai Islam', 'type' => 'textarea', 'group_name' => 'academic_info', 'description' => 'Kurikulum yang digunakan'],
            ['key_name' => 'academic_year', 'value' => '2025/2026', 'type' => 'text', 'group_name' => 'academic_info', 'description' => 'Tahun Ajaran'],
            ['key_name' => 'accreditation', 'value' => 'A', 'type' => 'text', 'group_name' => 'academic_info', 'description' => 'Status Akreditasi'],
            ['key_name' => 'school_motto', 'value' => 'Ilmu, Iman dan Amal', 'type' => 'text', 'group_name' => 'academic_info', 'description' => 'Motto Sekolah'],
            ['key_name' => 'school_vision', 'value' => 'Menjadi sekolah unggul berbasis nilai-nilai Islam', 'type' => 'textarea', 'group_name' => 'academic_info', 'description' => 'Visi Sekolah'],
            ['key_name' => 'school_mission', 'value' => 'Mewujudkan siswa beriman, berilmu, dan beramal', 'type' => 'textarea', 'group_name' => 'academic_info', 'description' => 'Misi Sekolah'],

            // Contact Information
            ['key_name' => 'school_name', 'value' => 'Yayasan Adyatama', 'type' => 'text', 'group_name' => 'contact_info', 'description' => 'Nama Lembaga Resmi'],
            ['key_name' => 'school_email', 'value' => 'info@adyatama.sch.id', 'type' => 'email', 'group_name' => 'contact_info', 'description' => 'Email Umum'],
            ['key_name' => 'school_phone', 'value' => '(021) 123456789', 'type' => 'tel', 'group_name' => 'contact_info', 'description' => 'Telepon Kantor'],
            ['key_name' => 'school_address', 'value' => 'Jl. Pendidikan No. 123, Jakarta Selatan', 'type' => 'textarea', 'group_name' => 'contact_info', 'description' => 'Alamat Lengkap'],
            ['key_name' => 'whatsapp_number', 'value' => '+628123456789', 'type' => 'tel', 'group_name' => 'contact_info', 'description' => 'Nomor WhatsApp (format: +62xxx)'],
            ['key_name' => 'whatsapp_message', 'value' => 'Halo Admin Adyatama School, saya ingin bertanya tentang...', 'type' => 'textarea', 'group_name' => 'contact_info', 'description' => 'Pesan Default WhatsApp'],
            ['key_name' => 'emergency_contact', 'value' => '(021) 987654321', 'type' => 'tel', 'group_name' => 'contact_info', 'description' => 'Kontak Darurat'],
            ['key_name' => 'office_hours', 'value' => 'Senin-Jumat 07:00-15:00', 'type' => 'text', 'group_name' => 'contact_info', 'description' => 'Jam Operasional'],

            // Location & Maps
            ['key_name' => 'maps_embed_url', 'value' => '', 'type' => 'url', 'group_name' => 'contact_info', 'description' => 'Link Embed Google Maps (copy paste dari Google Maps)'],
            ['key_name' => 'maps_embed_iframe', 'value' => '', 'type' => 'textarea', 'group_name' => 'contact_info', 'description' => 'Embed Iframe Code Google Maps'],
            ['key_name' => 'latitude', 'value' => '-6.200000', 'type' => 'text', 'group_name' => 'contact_info', 'description' => 'Koordinat Latitude'],
            ['key_name' => 'longitude', 'value' => '106.816666', 'type' => 'text', 'group_name' => 'contact_info', 'description' => 'Koordinat Longitude'],

            // Admission & Registration
            ['key_name' => 'registration_period', 'value' => 'Januari - Juli 2025', 'type' => 'text', 'group_name' => 'admission_info', 'description' => 'Periode Pendaftaran'],
            ['key_name' => 'admission_deadline', 'value' => '31 July 2025', 'type' => 'date', 'group_name' => 'admission_info', 'description' => 'Deadline Pendaftaran'],
            ['key_name' => 'registration_status', 'value' => 'open', 'type' => 'text', 'group_name' => 'admission_info', 'description' => 'Status: open/closed'],
            ['key_name' => 'min_age', 'value' => '4', 'type' => 'number', 'group_name' => 'admission_info', 'description' => 'Minimal usia masuk'],
            ['key_name' => 'max_age', 'value' => '12', 'type' => 'number', 'group_name' => 'admission_info', 'description' => 'Maksimal usia masuk'],
            ['key_name' => 'registration_fee', 'value' => 'Rp 150.000', 'type' => 'text', 'group_name' => 'admission_info', 'description' => 'Biaya Pendaftaran'],
            ['key_name' => 'annual_fee', 'value' => 'Rp 1.200.000', 'type' => 'text', 'group_name' => 'admission_info', 'description' => 'Biaya Tahunan'],
            ['key_name' => 'additional_documents', 'value' => 'Surat keterangan sehat, Surat akte lahir', 'type' => 'textarea', 'group_name' => 'admission_info', 'description' => 'Dokumen tambahan yang dibutuhkan'],
            ['key_name' => 'admission_procedure', 'value' => '1. Isi formulir pendaftaran\n2. Upload dokumen\n3. Verifikasi admin\n4. Konfirmasi pendaftaran', 'type' => 'textarea', 'group_name' => 'admission_info', 'description' => 'Prosedur pendaftaran'],

            // Social Media
            ['key_name' => 'instagram_url', 'value' => 'https://instagram.com/adyatamaschool', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'Instagram URL'],
            ['key_name' => 'facebook_url', 'value' => 'https://facebook.com/adyatamaschool', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'Facebook Page URL'],
            ['key_name' => 'youtube_url', 'value' => 'https://youtube.com/@adyatamaschool', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'YouTube Channel URL'],
            ['key_name' => 'tiktok_url', 'value' => 'https://tiktok.com/@adyatamaschool', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'TikTok URL'],
            ['key_name' => 'twitter_url', 'value' => 'https://twitter.com/adyatamaschool', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'Twitter/X URL'],
            ['key_name' => 'linkedin_url', 'value' => '', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'LinkedIn URL'],
            ['key_name' => 'website_url', 'value' => 'https://adyatamaschool.sch.id', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'Website Utama'],
            ['key_name' => 'social_media_widget', 'value' => '1', 'type' => 'boolean', 'group_name' => 'social_media', 'description' => 'Tampilkan widget media sosial'],

            // Legal Documents
            ['key_name' => 'sk_pendirian', 'value' => '', 'type' => 'file', 'group_name' => 'legal_documents', 'description' => 'SK Pendirian Yayasan (PDF/DOC)'],
            ['key_name' => 'izin_operasional', 'value' => '', 'type' => 'file', 'group_name' => 'legal_documents', 'description' => 'Izin Operasional Sekolah (PDF/DOC)'],
            ['key_name' => 'akreditasi', 'value' => '', 'type' => 'file', 'group_name' => 'legal_documents', 'description' => 'Sertifikat Akreditasi (PDF/JPG)'],
            ['key_name' => 'kurikulum', 'value' => '', 'type' => 'file', 'group_name' => 'legal_documents', 'description' => 'Ijin Kurikulum (PDF/DOC)'],
            ['key_name' => 'legalitas_lain', 'value' => '', 'type' => 'textarea', 'group_name' => 'legal_documents', 'description' => 'Legalitas Lainnya (deskripsi saja)'],
            ['key_name' => 'npsn', 'value' => '123456789', 'type' => 'text', 'group_name' => 'legal_documents', 'description' => 'Nomor Pokok Sekolah Nasional'],
            ['key_name' => 'nss', 'value' => '123456789', 'type' => 'text', 'group_name' => 'legal_documents', 'description' => 'Nomor Statistik Sekolah'],

            // SEO Configuration
            ['key_name' => 'meta_description', 'value' => 'Sekolah Islam Terpadu Adyatama School - Berkarakter, Berkualitas, Berprestasi', 'type' => 'textarea', 'group_name' => 'seo_config', 'description' => 'Deskripsi Website (max 160 karakter)'],
            ['key_name' => 'meta_keywords', 'value' => 'sekolah, pendidikan, islam, adyatama, tk, sd, smp, sma', 'type' => 'textarea', 'group_name' => 'seo_config', 'description' => 'Keywords (dipisah dengan koma)'],
            ['key_name' => 'google_analytics', 'value' => '', 'type' => 'textarea', 'group_name' => 'seo_config', 'description' => 'Google Analytics Tracking Code'],
            ['key_name' => 'google_tag_manager', 'value' => '', 'type' => 'textarea', 'group_name' => 'seo_config', 'description' => 'Google Tag Manager Code'],
            ['key_name' => 'facebook_pixel', 'value' => '', 'type' => 'textarea', 'group_name' => 'seo_config', 'description' => 'Facebook Pixel ID'],
            ['key_name' => 'site_verification', 'value' => '', 'type' => 'text', 'group_name' => 'seo_config', 'description' => 'Google Site Verification Code'],
            ['key_name' => 'robots_txt', 'value' => 'User-agent: *\nAllow: /\nSitemap: /sitemap.xml', 'type' => 'textarea', 'group_name' => 'seo_config', 'description' => 'Custom robots.txt content'],
            ['key_name' => 'sitemap_enabled', 'value' => '1', 'type' => 'boolean', 'group_name' => 'seo_config', 'description' => 'Aktifkan sitemap.xml'],
            ['key_name' => 'cdn_enabled', 'value' => '0', 'type' => 'boolean', 'group_name' => 'seo_config', 'description' => 'Gunakan CDN untuk assets'],
            ['key_name' => 'minify_html', 'value' => '0', 'type' => 'boolean', 'group_name' => 'seo_config', 'description' => 'Minify HTML output'],

            // Email Configuration
            ['key_name' => 'email_from', 'value' => 'info@adyatama.sch.id', 'type' => 'email', 'group_name' => 'email_config', 'description' => 'Alamat email pengirim'],
            ['key_name' => 'email_from_name', 'value' => 'Adyatama School', 'type' => 'text', 'group_name' => 'email_config', 'description' => 'Nama pengirim email'],
            ['key_name' => 'smtp_host', 'value' => 'smtp.gmail.com', 'type' => 'text', 'group_name' => 'email_config', 'description' => 'SMTP Host'],
            ['key_name' => 'smtp_port', 'value' => '587', 'type' => 'number', 'group_name' => 'email_config', 'description' => 'SMTP Port'],
            ['key_name' => 'smtp_username', 'value' => '', 'type' => 'text', 'group_name' => 'email_config', 'description' => 'SMTP Username'],
            ['key_name' => 'smtp_password', 'value' => '', 'type' => 'text', 'group_name' => 'email_config', 'description' => 'SMTP Password'],
            ['key_name' => 'email_notifications', 'value' => '1', 'type' => 'boolean', 'group_name' => 'email_config', 'description' => 'Aktifkan notifikasi email'],
            ['key_name' => 'application_notification', 'value' => '1', 'type' => 'boolean', 'group_name' => 'email_config', 'description' => 'Notifikasi pendaftaran baru'],
            ['key_name' => 'comment_notification', 'value' => '1', 'type' => 'boolean', 'group_name' => 'email_config', 'description' => 'Notifikasi komentar baru'],

            // Security
            ['key_name' => 'recaptcha_enabled', 'value' => '0', 'type' => 'boolean', 'group_name' => 'security', 'description' => 'Aktifkan reCAPTCHA'],
            ['key_name' => 'recaptcha_site_key', 'value' => '', 'type' => 'text', 'group_name' => 'security', 'description' => 'reCAPTCHA Site Key'],
            ['key_name' => 'recaptcha_secret_key', 'value' => '', 'type' => 'text', 'group_name' => 'security', 'description' => 'reCAPTCHA Secret Key'],
            ['key_name' => 'gdpr_compliance', 'value' => '0', 'type' => 'boolean', 'group_name' => 'security', 'description' => 'Aktifkan GDPR Compliance'],
            ['key_name' => 'cookie_consent', 'value' => '1', 'type' => 'boolean', 'group_name' => 'security', 'description' => 'Tampilkan cookie consent'],
            ['key_name' => 'privacy_policy', 'value' => 'Kebijakan privasi sekolah...', 'type' => 'textarea', 'group_name' => 'security', 'description' => 'Kebijakan privasi'],
            ['key_name' => 'terms_of_service', 'value' => 'Syarat dan ketentuan penggunaan...', 'type' => 'textarea', 'group_name' => 'security', 'description' => 'Terms of Service'],
            ['key_name' => 'data_retention_days', 'value' => '365', 'type' => 'number', 'group_name' => 'security', 'description' => 'Hari retensi data'],
            ['key_name' => 'max_login_attempts', 'value' => '5', 'type' => 'number', 'group_name' => 'security', 'description' => 'Maksimal percobaan login'],
            ['key_name' => 'login_lockout_time', 'value' => '900', 'type' => 'number', 'group_name' => 'security', 'description' => 'Waktu lockout login (detik)'],

            // Academic Calendar
            ['key_name' => 'academic_calendar_url', 'value' => '/uploads/academic-calendar.pdf', 'type' => 'file', 'group_name' => 'academic_calendar', 'description' => 'Link Kalender Akademik'],
            ['key_name' => 'holidays_schedule', 'value' => '1 Jan: Tahun Baru\n25 Jan: Tahun Baru Imlek', 'type' => 'textarea', 'group_name' => 'academic_calendar', 'description' => 'Jadwal Libur'],
            ['key_name' => 'exam_schedule', 'value' => 'Ujian Tengah Semester: 15 Maret - 5 April 2025', 'type' => 'textarea', 'group_name' => 'academic_calendar', 'description' => 'Jadwal Ujian'],
            ['key_name' => 'event_announcement', 'value' => '', 'type' => 'textarea', 'group_name' => 'academic_calendar', 'description' => 'Pengumuman Event Terkini'],
            ['key_name' => 'upcoming_events', 'value' => '', 'type' => 'textarea', 'group_name' => 'academic_calendar', 'description' => 'Event Mendatang'],

            // Website Behavior
            ['key_name' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Mode maintenance'],
            ['key_name' => 'maintenance_message', 'value' => 'Website sedang dalam perawatan, akan kembali pada pukul 10:00 WIB', 'type' => 'textarea', 'group_name' => 'website_behavior', 'description' => 'Pesan maintenance mode'],
            ['key_name' => 'guest_comment', 'value' => '1', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Izinkan komentar guest'],
            ['key_name' => 'auto_approve_comments', 'value' => '0', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Auto approve komentar'],
            ['key_name' => 'comment_captcha', 'value' => '1', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Aktifkan captcha komentar'],
            ['key_name' => 'posts_per_page', 'value' => '9', 'type' => 'number', 'group_name' => 'website_behavior', 'description' => 'Jumlah post per halaman'],
            ['key_name' => 'galleries_per_page', 'value' => '12', 'type' => 'number', 'group_name' => 'website_behavior', 'description' => 'Jumlah galeri per halaman'],
            ['key_name' => 'enable_reactions', 'value' => '1', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Aktifkan sistem reaksi'],
            ['key_name' => 'enable_sharing', 'value' => '1', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Aktifkan share social media'],
            ['key_name' => 'enable_search', 'value' => '1', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Aktifkan fitur pencarian'],
            ['key_name' => 'search_results_per_page', 'value' => '10', 'type' => 'number', 'group_name' => 'website_behavior', 'description' => 'Hasil pencarian per halaman'],

            // Payment & Donation
            ['key_name' => 'payment_enabled', 'value' => '0', 'type' => 'boolean', 'group_name' => 'payment_config', 'description' => 'Aktifkan payment gateway'],
            ['key_name' => 'payment_method', 'value' => 'manual_transfer', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Metode pembayaran (manual_transfer, midtrans, etc)'],
            ['key_name' => 'bank_account_name', 'value' => 'Yayasan Adyatama School', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Nama rekening'],
            ['key_name' => 'bank_account_number', 'value' => '1234567890', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Nomor rekening'],
            ['key_name' => 'bank_name', 'value' => 'Bank BRI', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Nama bank'],
            ['key_name' => 'donation_enabled', 'value' => '0', 'type' => 'boolean', 'group_name' => 'payment_config', 'description' => 'Aktifkan sistem donasi'],
            ['key_name' => 'donation_message', 'value' => 'Bantu kami dalam mendidik generasi qurani...', 'type' => 'textarea', 'group_name' => 'payment_config', 'description' => 'Pesan donasi'],
        ];

        $this->settingModel->insertBatch($defaults);

        return redirect()->to('/dashboard/settings')->with('message', 'Enhanced default settings seeded successfully.');
    }
}