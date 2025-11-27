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

        // 1. Handle File Uploads (Images & Documents)
        $files = $this->request->getFiles();
        
        if ($files) {
            foreach ($files as $key => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Determine upload path and validation based on key or file type
                    $uploadPath = 'uploads/settings'; // Default path
                    $allowedTypes = [];
                    $maxSize = 2 * 1024 * 1024; // Default 2MB

                    // Check if it's a legal document
                    if (in_array($key, ['sk_pendirian', 'izin_operasional', 'akreditasi', 'kurikulum', 'legalitas_lain', 'academic_calendar_url'])) {
                        $uploadPath = 'uploads/legal-docs';
                        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                        $maxSize = 10 * 1024 * 1024; // 10MB for docs
                    } 
                    // Check if it's an image
                    elseif (in_array($key, ['site_logo', 'site_favicon', 'og_image', 'hero_bg_image'])) {
                        $uploadPath = 'uploads/settings';
                        $allowedTypes = ['image/x-icon', 'image/vnd.microsoft.icon', 'image/ico', 'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                        $maxSize = ($key === 'site_favicon') ? 1 * 1024 * 1024 : 5 * 1024 * 1024;
                    } else {
                        // Unknown file key, skip or handle generically
                        continue;
                    }

                    // Validate Mime Type
                    if (!empty($allowedTypes) && !in_array($file->getMimeType(), $allowedTypes)) {
                        session()->setFlashdata('error', "Invalid file type for $key.");
                        continue;
                    }

                    // Validate Size
                    if ($file->getSize() > $maxSize) {
                        session()->setFlashdata('error', "File size too large for $key.");
                        continue;
                    }

                    // Ensure directory exists
                    if (!is_dir(FCPATH . $uploadPath)) {
                        mkdir(FCPATH . $uploadPath, 0755, true);
                    }

                    // Generate filename
                    $newName = $key . '_' . time() . '.' . $file->getExtension();

                    // Move file
                    if ($file->move(FCPATH . $uploadPath, $newName)) {
                        // Delete old file
                        $existing = $this->settingModel->where('key_name', $key)->first();
                        if ($existing && !empty($existing->value) && file_exists(FCPATH . $existing->value)) {
                            unlink(FCPATH . $existing->value);
                        }

                        // Update database
                        if ($existing) {
                            $this->settingModel->update($existing->id, ['value' => $uploadPath . '/' . $newName]);
                        } else {
                            $this->settingModel->insert([
                                'key_name' => $key,
                                'value' => $uploadPath . '/' . $newName,
                                'type' => 'file',
                                'group_name' => 'general'
                            ]);
                        }
                    }
                }
            }
        }

        // 2. Handle Text/Boolean/Other Fields
        foreach ($postData as $key => $value) {
            $existing = $this->settingModel->where('key_name', $key)->first();

            if ($existing) {
                // Only update the value!
                $this->settingModel->update($existing->id, ['value' => $value]);
            } else {
                // If it's a new setting not in DB, insert it
                $this->settingModel->insert([
                    'key_name' => $key,
                    'value' => $value,
                    'type' => 'text',
                    'group_name' => 'general'
                ]);
            }
        }

        return redirect()->to('/dashboard/settings')->with('message', 'Settings updated successfully.');
    }
    
    // Initial Seeder for Settings (Optional helper to run once if needed via URL or logic)
    // For now, we assume database.sql didn't populate settings, so we might need to insert defaults if empty.
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
        $defaults = [
            // ===== GENERAL =====
            ['key_name' => 'site_title', 'value' => 'Adyatama School', 'type' => 'text', 'group_name' => 'general', 'description' => 'Judul Website'],
            ['key_name' => 'site_tagline', 'value' => 'Berkarakter, Berkualitas, Berprestasi', 'type' => 'text', 'group_name' => 'general', 'description' => 'Tagline Website'],
            ['key_name' => 'site_description', 'value' => 'Sekolah Islam Terpadu yang mengutamakan pendidikan karakter dan prestasi akademik', 'type' => 'textarea', 'group_name' => 'general', 'description' => 'Deskripsi Singkat Sekolah'],
            ['key_name' => 'timezone', 'value' => 'Asia/Jakarta', 'type' => 'text', 'group_name' => 'general', 'description' => 'Zona Waktu'],
            ['key_name' => 'language', 'value' => 'id', 'type' => 'text', 'group_name' => 'general', 'description' => 'Bahasa Default (id/en)'],
            ['key_name' => 'date_format', 'value' => 'd-m-Y', 'type' => 'text', 'group_name' => 'general', 'description' => 'Format Tanggal'],
            
            // ===== VISUAL IDENTITY =====
            ['key_name' => 'site_name', 'value' => 'Adyatama School', 'type' => 'text', 'group_name' => 'visual_identity', 'description' => 'Nama Sekolah'],
            ['key_name' => 'site_slogan', 'value' => 'Berkarakter, Berkualitas, Berprestasi', 'type' => 'text', 'group_name' => 'visual_identity', 'description' => 'Tagline/Motto Sekolah'],
            ['key_name' => 'site_logo', 'value' => '', 'type' => 'image', 'group_name' => 'visual_identity', 'description' => 'Logo Utama Website (PNG)'],
            ['key_name' => 'site_favicon', 'value' => '', 'type' => 'image', 'group_name' => 'visual_identity', 'description' => 'Favicon Website (ICO/PNG 32x32)'],
            ['key_name' => 'og_image', 'value' => '', 'type' => 'image', 'group_name' => 'visual_identity', 'description' => 'Default OG Image (1200x630px)'],
            ['key_name' => 'primary_color', 'value' => '#0ea5e9', 'type' => 'color', 'group_name' => 'visual_identity', 'description' => 'Warna Primer Website'],
            ['key_name' => 'hero_bg_image', 'value' => '', 'type' => 'image', 'group_name' => 'visual_identity', 'description' => 'Background Hero Section'],

            // ===== CONTACT INFO =====
            ['key_name' => 'school_name', 'value' => 'Yayasan Adyatama', 'type' => 'text', 'group_name' => 'contact_info', 'description' => 'Nama Lembaga Resmi'],
            ['key_name' => 'school_email', 'value' => 'info@adyatama.sch.id', 'type' => 'email', 'group_name' => 'contact_info', 'description' => 'Email Umum'],
            ['key_name' => 'school_phone', 'value' => '(021) 123456789', 'type' => 'tel', 'group_name' => 'contact_info', 'description' => 'Telepon Kantor'],
            ['key_name' => 'school_address', 'value' => 'Jl. Pendidikan No. 123, Jakarta Selatan', 'type' => 'textarea', 'group_name' => 'contact_info', 'description' => 'Alamat Lengkap'],
            ['key_name' => 'whatsapp_number', 'value' => '+628123456789', 'type' => 'tel', 'group_name' => 'contact_info', 'description' => 'Nomor WhatsApp (format: +62xxx)'],
            ['key_name' => 'whatsapp_message', 'value' => 'Halo Admin Adyatama School, saya ingin bertanya tentang...', 'type' => 'textarea', 'group_name' => 'contact_info', 'description' => 'Pesan Default WhatsApp'],
            ['key_name' => 'latitude', 'value' => '-6.2088', 'type' => 'text', 'group_name' => 'contact_info', 'description' => 'Latitude Lokasi'],
            ['key_name' => 'longitude', 'value' => '106.8456', 'type' => 'text', 'group_name' => 'contact_info', 'description' => 'Longitude Lokasi'],
            ['key_name' => 'maps_embed_url', 'value' => '', 'type' => 'url', 'group_name' => 'contact_info', 'description' => 'Link Google Maps'],

            // ===== ADMISSION INFO =====
            ['key_name' => 'registration_status', 'value' => 'open', 'type' => 'text', 'group_name' => 'admission_info', 'description' => 'Status Pendaftaran (open/closed)'],
            ['key_name' => 'registration_period', 'value' => 'Januari - Juli 2025', 'type' => 'text', 'group_name' => 'admission_info', 'description' => 'Periode Pendaftaran'],
            ['key_name' => 'admission_deadline', 'value' => '2025-07-31', 'type' => 'date', 'group_name' => 'admission_info', 'description' => 'Deadline Pendaftaran'],
            ['key_name' => 'registration_fee', 'value' => 'Rp 500.000', 'type' => 'text', 'group_name' => 'admission_info', 'description' => 'Biaya Pendaftaran'],
            ['key_name' => 'annual_fee', 'value' => 'Rp 12.000.000', 'type' => 'text', 'group_name' => 'admission_info', 'description' => 'Biaya Tahunan'],
            ['key_name' => 'min_age', 'value' => '5', 'type' => 'number', 'group_name' => 'admission_info', 'description' => 'Minimal Usia Pendaftaran'],
            ['key_name' => 'max_age', 'value' => '18', 'type' => 'number', 'group_name' => 'admission_info', 'description' => 'Maksimal Usia Pendaftaran'],
            ['key_name' => 'admission_procedure', 'value' => "1. Isi formulir online\n2. Upload dokumen persyaratan\n3. Bayar biaya pendaftaran\n4. Tes masuk\n5. Pengumuman", 'type' => 'textarea', 'group_name' => 'admission_info', 'description' => 'Prosedur Pendaftaran'],
            ['key_name' => 'additional_documents', 'value' => "- Fotocopy Akta Kelahiran\n- Fotocopy Kartu Keluarga\n- Pas Foto 3x4 (3 lembar)\n- Rapor terakhir", 'type' => 'textarea', 'group_name' => 'admission_info', 'description' => 'Dokumen yang Diperlukan'],

            // ===== SOCIAL MEDIA =====
            ['key_name' => 'instagram_url', 'value' => 'https://instagram.com/adyatamaschool', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'Instagram URL'],
            ['key_name' => 'facebook_url', 'value' => 'https://facebook.com/adyatamaschool', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'Facebook Page URL'],
            ['key_name' => 'youtube_url', 'value' => 'https://youtube.com/@adyatamaschool', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'YouTube Channel URL'],
            ['key_name' => 'tiktok_url', 'value' => 'https://tiktok.com/@adyatamaschool', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'TikTok URL'],
            ['key_name' => 'twitter_url', 'value' => '', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'Twitter/X URL'],
            ['key_name' => 'social_media_widget', 'value' => '1', 'type' => 'boolean', 'group_name' => 'social_media', 'description' => 'Tampilkan Widget Social Media'],

            // ===== LEGAL DOCUMENTS =====
            ['key_name' => 'sk_pendirian', 'value' => '', 'type' => 'file', 'group_name' => 'legal_documents', 'description' => 'SK Pendirian Yayasan (PDF/DOC)'],
            ['key_name' => 'izin_operasional', 'value' => '', 'type' => 'file', 'group_name' => 'legal_documents', 'description' => 'Izin Operasional Sekolah (PDF/DOC)'],
            ['key_name' => 'akreditasi', 'value' => '', 'type' => 'file', 'group_name' => 'legal_documents', 'description' => 'Sertifikat Akreditasi (PDF/JPG)'],
            ['key_name' => 'kurikulum', 'value' => '', 'type' => 'file', 'group_name' => 'legal_documents', 'description' => 'Ijin Kurikulum (PDF/DOC)'],
            ['key_name' => 'npsn', 'value' => '', 'type' => 'text', 'group_name' => 'legal_documents', 'description' => 'Nomor Pokok Sekolah Nasional'],
            ['key_name' => 'nss', 'value' => '', 'type' => 'text', 'group_name' => 'legal_documents', 'description' => 'Nomor Statistik Sekolah'],
            ['key_name' => 'privacy_policy', 'value' => '', 'type' => 'textarea', 'group_name' => 'legal_documents', 'description' => 'Kebijakan Privasi'],
            ['key_name' => 'terms_of_service', 'value' => '', 'type' => 'textarea', 'group_name' => 'legal_documents', 'description' => 'Syarat dan Ketentuan'],

            // ===== SEO CONFIG =====
            ['key_name' => 'meta_description', 'value' => 'Sekolah Islam Terpadu Adyatama School - Berkarakter, Berkualitas, Berprestasi', 'type' => 'textarea', 'group_name' => 'seo_config', 'description' => 'Meta Description (max 160 karakter)'],
            ['key_name' => 'meta_keywords', 'value' => 'sekolah, pendidikan, islam, adyatama, tk, sd, smp, sma', 'type' => 'textarea', 'group_name' => 'seo_config', 'description' => 'Meta Keywords (dipisah koma)'],
            ['key_name' => 'google_analytics', 'value' => '', 'type' => 'textarea', 'group_name' => 'seo_config', 'description' => 'Google Analytics Tracking Code'],
            ['key_name' => 'robots_txt', 'value' => "User-agent: *\nAllow: /", 'type' => 'textarea', 'group_name' => 'seo_config', 'description' => 'Konten robots.txt'],
            ['key_name' => 'sitemap_enabled', 'value' => '1', 'type' => 'boolean', 'group_name' => 'seo_config', 'description' => 'Aktifkan Sitemap XML'],

            // ===== EMAIL CONFIG =====
            ['key_name' => 'smtp_host', 'value' => 'smtp.gmail.com', 'type' => 'text', 'group_name' => 'email_config', 'description' => 'SMTP Host'],
            ['key_name' => 'smtp_port', 'value' => '587', 'type' => 'number', 'group_name' => 'email_config', 'description' => 'SMTP Port'],
            ['key_name' => 'smtp_username', 'value' => '', 'type' => 'text', 'group_name' => 'email_config', 'description' => 'SMTP Username'],
            ['key_name' => 'smtp_password', 'value' => '', 'type' => 'text', 'group_name' => 'email_config', 'description' => 'SMTP Password'],
            ['key_name' => 'smtp_encryption', 'value' => 'tls', 'type' => 'text', 'group_name' => 'email_config', 'description' => 'SMTP Encryption (tls/ssl)'],
            ['key_name' => 'email_from', 'value' => 'noreply@adyatama.sch.id', 'type' => 'email', 'group_name' => 'email_config', 'description' => 'Email Pengirim'],
            ['key_name' => 'email_from_name', 'value' => 'Adyatama School', 'type' => 'text', 'group_name' => 'email_config', 'description' => 'Nama Pengirim'],
            ['key_name' => 'email_notifications', 'value' => '1', 'type' => 'boolean', 'group_name' => 'email_config', 'description' => 'Aktifkan Notifikasi Email'],
            ['key_name' => 'application_notification', 'value' => '1', 'type' => 'boolean', 'group_name' => 'email_config', 'description' => 'Notifikasi Pendaftaran Baru'],
            ['key_name' => 'comment_notification', 'value' => '1', 'type' => 'boolean', 'group_name' => 'email_config', 'description' => 'Notifikasi Komentar Baru'],

            // ===== SECURITY =====
            ['key_name' => 'recaptcha_enabled', 'value' => '0', 'type' => 'boolean', 'group_name' => 'security', 'description' => 'Aktifkan reCAPTCHA'],
            ['key_name' => 'recaptcha_site_key', 'value' => '', 'type' => 'text', 'group_name' => 'security', 'description' => 'reCAPTCHA Site Key'],
            ['key_name' => 'recaptcha_secret_key', 'value' => '', 'type' => 'text', 'group_name' => 'security', 'description' => 'reCAPTCHA Secret Key'],
            ['key_name' => 'max_login_attempts', 'value' => '5', 'type' => 'number', 'group_name' => 'security', 'description' => 'Maksimal Percobaan Login'],
            ['key_name' => 'login_lockout_time', 'value' => '900', 'type' => 'number', 'group_name' => 'security', 'description' => 'Durasi Lockout (detik)'],
            ['key_name' => 'gdpr_compliance', 'value' => '1', 'type' => 'boolean', 'group_name' => 'security', 'description' => 'Aktifkan GDPR Compliance'],
            ['key_name' => 'cookie_consent', 'value' => '1', 'type' => 'boolean', 'group_name' => 'security', 'description' => 'Tampilkan Cookie Consent'],
            ['key_name' => 'data_retention_days', 'value' => '365', 'type' => 'number', 'group_name' => 'security', 'description' => 'Lama Retensi Data (hari)'],

            // ===== ACADEMIC CALENDAR =====
            ['key_name' => 'academic_year', 'value' => '2024/2025', 'type' => 'text', 'group_name' => 'academic_calendar', 'description' => 'Tahun Ajaran'],
            ['key_name' => 'semester', 'value' => 'Ganjil', 'type' => 'text', 'group_name' => 'academic_calendar', 'description' => 'Semester Aktif'],
            ['key_name' => 'academic_calendar_url', 'value' => '', 'type' => 'file', 'group_name' => 'academic_calendar', 'description' => 'File Kalender Akademik (PDF)'],
            ['key_name' => 'holidays_schedule', 'value' => "17-08-2025 Hari Kemerdekaan\n25-12-2025 Natal", 'type' => 'textarea', 'group_name' => 'academic_calendar', 'description' => 'Jadwal Libur'],
            ['key_name' => 'exam_schedule', 'value' => '', 'type' => 'textarea', 'group_name' => 'academic_calendar', 'description' => 'Jadwal Ujian'],
            ['key_name' => 'event_announcement', 'value' => '', 'type' => 'textarea', 'group_name' => 'academic_calendar', 'description' => 'Pengumuman Event'],
            ['key_name' => 'upcoming_events', 'value' => '', 'type' => 'textarea', 'group_name' => 'academic_calendar', 'description' => 'Event Mendatang'],

            // ===== WEBSITE BEHAVIOR =====
            ['key_name' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Mode Maintenance'],
            ['key_name' => 'maintenance_message', 'value' => 'Website sedang dalam perbaikan. Mohon kembali lagi nanti.', 'type' => 'textarea', 'group_name' => 'website_behavior', 'description' => 'Pesan Maintenance'],
            ['key_name' => 'posts_per_page', 'value' => '12', 'type' => 'number', 'group_name' => 'website_behavior', 'description' => 'Jumlah Post per Halaman'],
            ['key_name' => 'galleries_per_page', 'value' => '12', 'type' => 'number', 'group_name' => 'website_behavior', 'description' => 'Jumlah Galeri per Halaman'],
            ['key_name' => 'search_results_per_page', 'value' => '10', 'type' => 'number', 'group_name' => 'website_behavior', 'description' => 'Hasil Pencarian per Halaman'],
            ['key_name' => 'enable_search', 'value' => '1', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Aktifkan Fitur Pencarian'],
            ['key_name' => 'enable_sharing', 'value' => '1', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Aktifkan Tombol Share'],
            ['key_name' => 'enable_reactions', 'value' => '1', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Aktifkan Reaksi Emoji'],
            ['key_name' => 'guest_comment', 'value' => '0', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Izinkan Komentar Tamu'],
            ['key_name' => 'auto_approve_comments', 'value' => '0', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Auto Approve Komentar'],
            ['key_name' => 'comment_captcha', 'value' => '1', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Captcha untuk Komentar'],
            ['key_name' => 'cdn_enabled', 'value' => '0', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Gunakan CDN'],
            ['key_name' => 'minify_html', 'value' => '0', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Minify HTML Output'],

            // ===== PAYMENT CONFIG =====
            ['key_name' => 'payment_enabled', 'value' => '1', 'type' => 'boolean', 'group_name' => 'payment_config', 'description' => 'Aktifkan Payment Gateway'],
            ['key_name' => 'payment_method', 'value' => 'manual_transfer', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Metode Pembayaran Default'],
            ['key_name' => 'bank_name', 'value' => 'Bank Mandiri', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Nama Bank'],
            ['key_name' => 'bank_account_name', 'value' => 'Yayasan Adyatama', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Nama Pemilik Rekening'],
            ['key_name' => 'bank_account_number', 'value' => '1234567890', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Nomor Rekening'],
            ['key_name' => 'midtrans_server_key', 'value' => '', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Midtrans Server Key'],
            ['key_name' => 'midtrans_client_key', 'value' => '', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Midtrans Client Key'],
            ['key_name' => 'midtrans_is_production', 'value' => '0', 'type' => 'boolean', 'group_name' => 'payment_config', 'description' => 'Midtrans Production Mode'],
            ['key_name' => 'donation_enabled', 'value' => '1', 'type' => 'boolean', 'group_name' => 'payment_config', 'description' => 'Aktifkan Fitur Donasi'],
            ['key_name' => 'donation_message', 'value' => 'Dukung pendidikan berkualitas di Adyatama School', 'type' => 'textarea', 'group_name' => 'payment_config', 'description' => 'Pesan Donasi'],

            // ===== ACADEMIC INFO =====
            ['key_name' => 'school_vision', 'value' => 'Menjadi lembaga pendidikan Islam terpadu yang unggul dalam prestasi dan berakhlak mulia', 'type' => 'textarea', 'group_name' => 'academic_info', 'description' => 'Visi Sekolah'],
            ['key_name' => 'school_mission', 'value' => "1. Menyelenggarakan pendidikan berkualitas\n2. Membentuk karakter Islami\n3. Mengembangkan potensi siswa", 'type' => 'textarea', 'group_name' => 'academic_info', 'description' => 'Misi Sekolah'],
            ['key_name' => 'curriculum', 'value' => 'Kurikulum Merdeka dengan pendekatan Islam Terpadu', 'type' => 'textarea', 'group_name' => 'academic_info', 'description' => 'Kurikulum'],
            ['key_name' => 'accreditation', 'value' => 'A', 'type' => 'text', 'group_name' => 'academic_info', 'description' => 'Akreditasi'],
            ['key_name' => 'total_students', 'value' => '500', 'type' => 'number', 'group_name' => 'academic_info', 'description' => 'Jumlah Siswa'],
            ['key_name' => 'total_teachers', 'value' => '50', 'type' => 'number', 'group_name' => 'academic_info', 'description' => 'Jumlah Guru'],
            ['key_name' => 'total_classes', 'value' => '24', 'type' => 'number', 'group_name' => 'academic_info', 'description' => 'Jumlah Kelas'],
            ['key_name' => 'facilities', 'value' => "- Laboratorium Komputer\n- Perpustakaan\n- Masjid\n- Lapangan Olahraga\n- Kantin", 'type' => 'textarea', 'group_name' => 'academic_info', 'description' => 'Fasilitas Sekolah'],
        ];

        // Smart insertion - only insert settings that don't exist yet
        $inserted = 0;
        $skipped = 0;
        
        foreach ($defaults as $setting) {
            $existing = $this->settingModel->where('key_name', $setting['key_name'])->first();
            if (!$existing) {
                $this->settingModel->insert($setting);
                $inserted++;
            } else {
                $skipped++;
            }
        }

        if ($inserted > 0) {
            return redirect()->to('/dashboard/settings')->with('message', "Successfully added {$inserted} new settings. {$skipped} settings already exist.");
        } else {
            return redirect()->to('/dashboard/settings')->with('error', 'All settings already exist. No new settings added.');
        }
    }

    public function addMissingGroups()
    {
        // This method adds only the missing groups without touching existing data
        $allDefaults = [
            // ===== GENERAL =====
            ['key_name' => 'site_title', 'value' => 'Adyatama School', 'type' => 'text', 'group_name' => 'general', 'description' => 'Judul Website'],
            ['key_name' => 'site_tagline', 'value' => 'Berkarakter, Berkualitas, Berprestasi', 'type' => 'text', 'group_name' => 'general', 'description' => 'Tagline Website'],
            ['key_name' => 'site_description', 'value' => 'Sekolah Islam Terpadu yang mengutamakan pendidikan karakter dan prestasi akademik', 'type' => 'textarea', 'group_name' => 'general', 'description' => 'Deskripsi Singkat Sekolah'],
            ['key_name' => 'timezone', 'value' => 'Asia/Jakarta', 'type' => 'text', 'group_name' => 'general', 'description' => 'Zona Waktu'],
            ['key_name' => 'language', 'value' => 'id', 'type' => 'text', 'group_name' => 'general', 'description' => 'Bahasa Default (id/en)'],
            ['key_name' => 'date_format', 'value' => 'd-m-Y', 'type' => 'text', 'group_name' => 'general', 'description' => 'Format Tanggal'],
            
            // ===== ADMISSION INFO =====
            ['key_name' => 'registration_status', 'value' => 'open', 'type' => 'text', 'group_name' => 'admission_info', 'description' => 'Status Pendaftaran (open/closed)'],
            ['key_name' => 'registration_period', 'value' => 'Januari - Juli 2025', 'type' => 'text', 'group_name' => 'admission_info', 'description' => 'Periode Pendaftaran'],
            ['key_name' => 'admission_deadline', 'value' => '2025-07-31', 'type' => 'date', 'group_name' => 'admission_info', 'description' => 'Deadline Pendaftaran'],
            ['key_name' => 'registration_fee', 'value' => 'Rp 500.000', 'type' => 'text', 'group_name' => 'admission_info', 'description' => 'Biaya Pendaftaran'],
            ['key_name' => 'annual_fee', 'value' => 'Rp 12.000.000', 'type' => 'text', 'group_name' => 'admission_info', 'description' => 'Biaya Tahunan'],
            ['key_name' => 'min_age', 'value' => '5', 'type' => 'number', 'group_name' => 'admission_info', 'description' => 'Minimal Usia Pendaftaran'],
            ['key_name' => 'max_age', 'value' => '18', 'type' => 'number', 'group_name' => 'admission_info', 'description' => 'Maksimal Usia Pendaftaran'],
            ['key_name' => 'admission_procedure', 'value' => "1. Isi formulir online\n2. Upload dokumen persyaratan\n3. Bayar biaya pendaftaran\n4. Tes masuk\n5. Pengumuman", 'type' => 'textarea', 'group_name' => 'admission_info', 'description' => 'Prosedur Pendaftaran'],
            ['key_name' => 'additional_documents', 'value' => "- Fotocopy Akta Kelahiran\n- Fotocopy Kartu Keluarga\n- Pas Foto 3x4 (3 lembar)\n- Rapor terakhir", 'type' => 'textarea', 'group_name' => 'admission_info', 'description' => 'Dokumen yang Diperlukan'],

            // ===== EMAIL CONFIG =====
            ['key_name' => 'smtp_host', 'value' => 'smtp.gmail.com', 'type' => 'text', 'group_name' => 'email_config', 'description' => 'SMTP Host'],
            ['key_name' => 'smtp_port', 'value' => '587', 'type' => 'number', 'group_name' => 'email_config', 'description' => 'SMTP Port'],
            ['key_name' => 'smtp_username', 'value' => '', 'type' => 'text', 'group_name' => 'email_config', 'description' => 'SMTP Username'],
            ['key_name' => 'smtp_password', 'value' => '', 'type' => 'text', 'group_name' => 'email_config', 'description' => 'SMTP Password'],
            ['key_name' => 'smtp_encryption', 'value' => 'tls', 'type' => 'text', 'group_name' => 'email_config', 'description' => 'SMTP Encryption (tls/ssl)'],
            ['key_name' => 'email_from', 'value' => 'noreply@adyatama.sch.id', 'type' => 'email', 'group_name' => 'email_config', 'description' => 'Email Pengirim'],
            ['key_name' => 'email_from_name', 'value' => 'Adyatama School', 'type' => 'text', 'group_name' => 'email_config', 'description' => 'Nama Pengirim'],
            ['key_name' => 'email_notifications', 'value' => '1', 'type' => 'boolean', 'group_name' => 'email_config', 'description' => 'Aktifkan Notifikasi Email'],
            ['key_name' => 'application_notification', 'value' => '1', 'type' => 'boolean', 'group_name' => 'email_config', 'description' => 'Notifikasi Pendaftaran Baru'],
            ['key_name' => 'comment_notification', 'value' => '1', 'type' => 'boolean', 'group_name' => 'email_config', 'description' => 'Notifikasi Komentar Baru'],

            // ===== SECURITY =====
            ['key_name' => 'recaptcha_enabled', 'value' => '0', 'type' => 'boolean', 'group_name' => 'security', 'description' => 'Aktifkan reCAPTCHA'],
            ['key_name' => 'recaptcha_site_key', 'value' => '', 'type' => 'text', 'group_name' => 'security', 'description' => 'reCAPTCHA Site Key'],
            ['key_name' => 'recaptcha_secret_key', 'value' => '', 'type' => 'text', 'group_name' => 'security', 'description' => 'reCAPTCHA Secret Key'],
            ['key_name' => 'max_login_attempts', 'value' => '5', 'type' => 'number', 'group_name' => 'security', 'description' => 'Maksimal Percobaan Login'],
            ['key_name' => 'login_lockout_time', 'value' => '900', 'type' => 'number', 'group_name' => 'security', 'description' => 'Durasi Lockout (detik)'],
            ['key_name' => 'gdpr_compliance', 'value' => '1', 'type' => 'boolean', 'group_name' => 'security', 'description' => 'Aktifkan GDPR Compliance'],
            ['key_name' => 'cookie_consent', 'value' => '1', 'type' => 'boolean', 'group_name' => 'security', 'description' => 'Tampilkan Cookie Consent'],
            ['key_name' => 'data_retention_days', 'value' => '365', 'type' => 'number', 'group_name' => 'security', 'description' => 'Lama Retensi Data (hari)'],

            // ===== ACADEMIC CALENDAR =====
            ['key_name' => 'academic_year', 'value' => '2024/2025', 'type' => 'text', 'group_name' => 'academic_calendar', 'description' => 'Tahun Ajaran'],
            ['key_name' => 'semester', 'value' => 'Ganjil', 'type' => 'text', 'group_name' => 'academic_calendar', 'description' => 'Semester Aktif'],
            ['key_name' => 'academic_calendar_url', 'value' => '', 'type' => 'file', 'group_name' => 'academic_calendar', 'description' => 'File Kalender Akademik (PDF)'],
            ['key_name' => 'holidays_schedule', 'value' => "17-08-2025 Hari Kemerdekaan\n25-12-2025 Natal", 'type' => 'textarea', 'group_name' => 'academic_calendar', 'description' => 'Jadwal Libur'],
            ['key_name' => 'exam_schedule', 'value' => '', 'type' => 'textarea', 'group_name' => 'academic_calendar', 'description' => 'Jadwal Ujian'],
            ['key_name' => 'event_announcement', 'value' => '', 'type' => 'textarea', 'group_name' => 'academic_calendar', 'description' => 'Pengumuman Event'],
            ['key_name' => 'upcoming_events', 'value' => '', 'type' => 'textarea', 'group_name' => 'academic_calendar', 'description' => 'Event Mendatang'],

            // ===== WEBSITE BEHAVIOR =====
            ['key_name' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Mode Maintenance'],
            ['key_name' => 'maintenance_message', 'value' => 'Website sedang dalam perbaikan. Mohon kembali lagi nanti.', 'type' => 'textarea', 'group_name' => 'website_behavior', 'description' => 'Pesan Maintenance'],
            ['key_name' => 'posts_per_page', 'value' => '12', 'type' => 'number', 'group_name' => 'website_behavior', 'description' => 'Jumlah Post per Halaman'],
            ['key_name' => 'galleries_per_page', 'value' => '12', 'type' => 'number', 'group_name' => 'website_behavior', 'description' => 'Jumlah Galeri per Halaman'],
            ['key_name' => 'search_results_per_page', 'value' => '10', 'type' => 'number', 'group_name' => 'website_behavior', 'description' => 'Hasil Pencarian per Halaman'],
            ['key_name' => 'enable_search', 'value' => '1', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Aktifkan Fitur Pencarian'],
            ['key_name' => 'enable_sharing', 'value' => '1', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Aktifkan Tombol Share'],
            ['key_name' => 'enable_reactions', 'value' => '1', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Aktifkan Reaksi Emoji'],
            ['key_name' => 'guest_comment', 'value' => '0', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Izinkan Komentar Tamu'],
            ['key_name' => 'auto_approve_comments', 'value' => '0', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Auto Approve Komentar'],
            ['key_name' => 'comment_captcha', 'value' => '1', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Captcha untuk Komentar'],
            ['key_name' => 'cdn_enabled', 'value' => '0', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Gunakan CDN'],
            ['key_name' => 'minify_html', 'value' => '0', 'type' => 'boolean', 'group_name' => 'website_behavior', 'description' => 'Minify HTML Output'],

            // ===== PAYMENT CONFIG =====
            ['key_name' => 'payment_enabled', 'value' => '1', 'type' => 'boolean', 'group_name' => 'payment_config', 'description' => 'Aktifkan Payment Gateway'],
            ['key_name' => 'payment_method', 'value' => 'manual_transfer', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Metode Pembayaran Default'],
            ['key_name' => 'bank_name', 'value' => 'Bank Mandiri', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Nama Bank'],
            ['key_name' => 'bank_account_name', 'value' => 'Yayasan Adyatama', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Nama Pemilik Rekening'],
            ['key_name' => 'bank_account_number', 'value' => '1234567890', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Nomor Rekening'],
            ['key_name' => 'midtrans_server_key', 'value' => '', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Midtrans Server Key'],
            ['key_name' => 'midtrans_client_key', 'value' => '', 'type' => 'text', 'group_name' => 'payment_config', 'description' => 'Midtrans Client Key'],
            ['key_name' => 'midtrans_is_production', 'value' => '0', 'type' => 'boolean', 'group_name' => 'payment_config', 'description' => 'Midtrans Production Mode'],
            ['key_name' => 'donation_enabled', 'value' => '1', 'type' => 'boolean', 'group_name' => 'payment_config', 'description' => 'Aktifkan Fitur Donasi'],
            ['key_name' => 'donation_message', 'value' => 'Dukung pendidikan berkualitas di Adyatama School', 'type' => 'textarea', 'group_name' => 'payment_config', 'description' => 'Pesan Donasi'],

            // ===== ACADEMIC INFO =====
            ['key_name' => 'school_vision', 'value' => 'Menjadi lembaga pendidikan Islam terpadu yang unggul dalam prestasi dan berakhlak mulia', 'type' => 'textarea', 'group_name' => 'academic_info', 'description' => 'Visi Sekolah'],
            ['key_name' => 'school_mission', 'value' => "1. Menyelenggarakan pendidikan berkualitas\n2. Membentuk karakter Islami\n3. Mengembangkan potensi siswa", 'type' => 'textarea', 'group_name' => 'academic_info', 'description' => 'Misi Sekolah'],
            ['key_name' => 'curriculum', 'value' => 'Kurikulum Merdeka dengan pendekatan Islam Terpadu', 'type' => 'textarea', 'group_name' => 'academic_info', 'description' => 'Kurikulum'],
            ['key_name' => 'accreditation', 'value' => 'A', 'type' => 'text', 'group_name' => 'academic_info', 'description' => 'Akreditasi'],
            ['key_name' => 'total_students', 'value' => '500', 'type' => 'number', 'group_name' => 'academic_info', 'description' => 'Jumlah Siswa'],
            ['key_name' => 'total_teachers', 'value' => '50', 'type' => 'number', 'group_name' => 'academic_info', 'description' => 'Jumlah Guru'],
            ['key_name' => 'total_classes', 'value' => '24', 'type' => 'number', 'group_name' => 'academic_info', 'description' => 'Jumlah Kelas'],
            ['key_name' => 'facilities', 'value' => "- Laboratorium Komputer\n- Perpustakaan\n- Masjid\n- Lapangan Olahraga\n- Kantin", 'type' => 'textarea', 'group_name' => 'academic_info', 'description' => 'Fasilitas Sekolah'],
        ];

        $inserted = 0;
        foreach ($allDefaults as $setting) {
            $existing = $this->settingModel->where('key_name', $setting['key_name'])->first();
            if (!$existing) {
                $this->settingModel->insert($setting);
                $inserted++;
            }
        }

        return redirect()->to('/dashboard/settings')->with('message', "Successfully added {$inserted} missing settings from new groups.");
    }
}
