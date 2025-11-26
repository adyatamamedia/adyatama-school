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
        $legalKeys = ['sk_pendirian', 'izin_operasional', 'akreditasi', 'kurikulum'];
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

        // Handle Image Uploads (favicon, logo, og_image)
        $imageKeys = ['site_favicon', 'site_logo', 'og_image'];
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
                $maxSize = ($key === 'site_favicon') ? 1 * 1024 * 1024 : 2 * 1024 * 1024; // 1MB for favicon, 2MB for others
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
            if (!in_array($key, array_merge($legalKeys, $imageKeys, ['whatsapp_number', 'whatsapp_message', 'school_address', 'meta_description', 'meta_keywords', 'google_analytics', 'primary_color', 'maps_embed_url', 'maps_embed_iframe', 'instagram_url', 'facebook_url', 'youtube_url', 'tiktok_url']))) {
                $this->settingModel->updateOrCreate($key, $value);
            }
        }

        // Return appropriate message
        if (!empty(session()->getFlashdata('error'))) {
            return redirect()->to('/dashboard/settings')->with('error', session()->getFlashdata('error'));
        }

        return redirect()->to('/dashboard/settings')->with('message', 'Settings updated successfully.');

        // Update text fields (including new enhanced fields)
        foreach ($postData as $key => $value) {
            if (!in_array($key, array_merge($legalKeys, $imageKeys))) {
                // Handle new field types
                if ($key === 'primary_color') {
                    $this->settingModel->updateOrCreate($key, $value, 'color', 'visual_identity');
                } elseif ($key === 'maps_embed_url') {
                    $this->settingModel->updateOrCreate($key, $value, 'url', 'location_config');
                } elseif ($key === 'maps_embed_iframe') {
                    $this->settingModel->updateOrCreate($key, $value, 'textarea', 'location_config');
                } elseif (in_array($key, ['whatsapp_number', 'whatsapp_message'])) {
                    $this->settingModel->updateOrCreate($key, $value, 'tel', 'contact_info');
                } elseif ($key === 'school_address') {
                    $this->settingModel->updateOrCreate($key, $value, 'textarea', 'contact_info');
                } elseif ($key === 'meta_description') {
                    $this->settingModel->updateOrCreate($key, $value, 'textarea', 'seo_config');
                } elseif ($key === 'meta_keywords') {
                    $this->settingModel->updateOrCreate($key, $value, 'textarea', 'seo_config');
                } elseif ($key === 'google_analytics') {
                    $this->settingModel->updateOrCreate($key, $value, 'textarea', 'seo_config');
                } elseif (in_array($key, ['instagram_url', 'facebook_url', 'youtube_url', 'tiktok_url'])) {
                    $this->settingModel->updateOrCreate($key, $value, 'url', 'social_media');
                } else {
                    $this->settingModel->updateOrCreate($key, $value);
                }
            }
        }

        $errors = [];
        $successCount = 0;

        foreach ($imageSettings as $setting) {
            $file = $this->request->getFile($setting->key_name);

            // Check if file was uploaded and is valid
            if ($file && $file->isValid() && !$file->hasMoved() && $file->getSize() > 0) {
                // Check file size (max 5MB)
                if ($file->getSize() > 5 * 1024 * 1024) {
                    $errors[] = 'File size too large for ' . $setting->key_name . '. Maximum size is 5MB.';
                    continue;
                }

                // Check file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    $errors[] = 'Invalid file type for ' . $setting->key_name . '. Only JPG, PNG, GIF, and WebP are allowed.';
                    continue;
                }

                // Delete old file if it exists
                if (!empty($setting->value) && file_exists(FCPATH . $setting->value)) {
                    unlink(FCPATH . $setting->value);
                }

                // Create directory if not exists
                if (!is_dir(FCPATH . 'uploads/settings')) {
                    mkdir(FCPATH . 'uploads/settings', 0755, true);
                }

                $newName = $file->getRandomName();

                if ($file->move(FCPATH . 'uploads/settings', $newName)) {
                    // Update the database with new file path
                    $this->settingModel->update($setting->id, ['value' => 'uploads/settings/' . $newName]);
                    $successCount++;
                } else {
                    $errors[] = 'Failed to upload ' . $setting->key_name . '. Please try again.';
                }
            } elseif ($file && !$file->isValid()) {
                // Log upload errors for debugging
                $errorMessage = $file->getErrorString();
                log_message('error', "File upload error for {$setting->key_name}: " . $errorMessage);
                $errors[] = "Upload error for {$setting->key_name}: " . $errorMessage;
            }
        }

        // Return appropriate message
        if (!empty($errors)) {
            if ($successCount > 0) {
                $message = "Successfully uploaded {$successCount} file(s), but some errors occurred: " . implode('; ', $errors);
                return redirect()->to('/dashboard/settings')->with('warning', $message);
            } else {
                return redirect()->to('/dashboard/settings')->with('error', implode('; ', $errors));
            }
        } elseif ($successCount > 0) {
            return redirect()->to('/dashboard/settings')->with('message', "Successfully uploaded {$successCount} file(s).");
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
        // Check if enhanced settings already exist
        if ($this->settingModel->where('key_name', 'site_slogan')->first()) {
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

            // Contact Information
            ['key_name' => 'school_name', 'value' => 'Yayasan Adyatama', 'type' => 'text', 'group_name' => 'contact_info', 'description' => 'Nama Lembaga Resmi'],
            ['key_name' => 'school_email', 'value' => 'info@adyatama.sch.id', 'type' => 'email', 'group_name' => 'contact_info', 'description' => 'Email Umum'],
            ['key_name' => 'school_phone', 'value' => '(021) 123456789', 'type' => 'tel', 'group_name' => 'contact_info', 'description' => 'Telepon Kantor'],
            ['key_name' => 'school_address', 'value' => 'Jl. Pendidikan No. 123, Jakarta Selatan', 'type' => 'textarea', 'group_name' => 'contact_info', 'description' => 'Alamat Lengkap'],
            ['key_name' => 'whatsapp_number', 'value' => '+628123456789', 'type' => 'tel', 'group_name' => 'contact_info', 'description' => 'Nomor WhatsApp (format: +62xxx)'],
            ['key_name' => 'whatsapp_message', 'value' => 'Halo Admin Adyatama School, saya ingin bertanya tentang...', 'type' => 'textarea', 'group_name' => 'contact_info', 'description' => 'Pesan Default WhatsApp'],

            // Location & Maps
            ['key_name' => 'maps_embed_url', 'value' => '', 'type' => 'url', 'group_name' => 'location_config', 'description' => 'Link Embed Google Maps (copy paste dari Google Maps)'],
            ['key_name' => 'maps_embed_iframe', 'value' => '', 'type' => 'textarea', 'group_name' => 'location_config', 'description' => 'Embed Iframe Code Google Maps'],

            // Legal Documents
            ['key_name' => 'sk_pendirian', 'value' => '', 'type' => 'file', 'group_name' => 'legal_documents', 'description' => 'SK Pendirian Yayasan (PDF/DOC)'],
            ['key_name' => 'izin_operasional', 'value' => '', 'type' => 'file', 'group_name' => 'legal_documents', 'description' => 'Izin Operasional Sekolah (PDF/DOC)'],
            ['key_name' => 'akreditasi', 'value' => '', 'type' => 'file', 'group_name' => 'legal_documents', 'description' => 'Sertifikat Akreditasi (PDF/JPG)'],
            ['key_name' => 'kurikulum', 'value' => '', 'type' => 'file', 'group_name' => 'legal_documents', 'description' => 'Ijin Kurikulum (PDF/DOC)'],
            ['key_name' => 'legalitas_lain', 'value' => '', 'type' => 'textarea', 'group_name' => 'legal_documents', 'description' => 'Legalitas Lainnya (deskripsi saja)'],

            // Social Media
            ['key_name' => 'instagram_url', 'value' => 'https://instagram.com/adyatamaschool', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'Instagram URL'],
            ['key_name' => 'facebook_url', 'value' => 'https://facebook.com/adyatamaschool', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'Facebook Page URL'],
            ['key_name' => 'youtube_url', 'value' => 'https://youtube.com/@adyatamaschool', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'YouTube Channel URL'],
            ['key_name' => 'tiktok_url', 'value' => 'https://tiktok.com/@adyatamaschool', 'type' => 'url', 'group_name' => 'social_media', 'description' => 'TikTok URL'],

            // SEO Configuration
            ['key_name' => 'meta_description', 'value' => 'Sekolah Islam Terpadu Adyatama School - Berkarakter, Berkualitas, Berprestasi', 'type' => 'textarea', 'group_name' => 'seo_config', 'description' => 'Deskripsi Website (max 160 karakter)'],
            ['key_name' => 'meta_keywords', 'value' => 'sekolah, pendidikan, islam, adyatama, tk, sd, smp, sma', 'type' => 'textarea', 'group_name' => 'seo_config', 'description' => 'Keywords (dipisah dengan koma)'],
            ['key_name' => 'google_analytics', 'value' => '', 'type' => 'textarea', 'group_name' => 'seo_config', 'description' => 'Google Analytics Tracking Code'],
        ];

        $this->settingModel->insertBatch($defaults);

        return redirect()->to('/dashboard/settings')->with('message', 'Enhanced default settings seeded successfully.');
    }
}
