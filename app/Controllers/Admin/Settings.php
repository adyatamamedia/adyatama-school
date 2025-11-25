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

        foreach ($postData as $key => $value) {
            // Update by key_name
            $this->settingModel->where('key_name', $key)
                               ->set(['value' => $value])
                               ->update();
        }

        return redirect()->to('/dashboard/settings')->with('message', 'Settings updated successfully.');
    }
    
    // Initial Seeder for Settings (Optional helper to run once if needed via URL or logic)
    // For now, we assume database.sql didn't populate settings, so we might need to insert defaults if empty.
    public function seedDefaults()
    {
        if ($this->settingModel->countAll() > 0) {
            return redirect()->to('/dashboard/settings')->with('error', 'Settings already seeded.');
        }

        $defaults = [
            // General
            ['key_name' => 'site_name', 'value' => 'Adyatama School', 'type' => 'text', 'group_name' => 'general', 'description' => 'Nama Website'],
            ['key_name' => 'site_description', 'value' => 'School CMS', 'type' => 'textarea', 'group_name' => 'general', 'description' => 'Deskripsi Singkat'],
            ['key_name' => 'school_address', 'value' => 'Jl. Pendidikan No. 1', 'type' => 'textarea', 'group_name' => 'contact', 'description' => 'Alamat Sekolah'],
            ['key_name' => 'school_phone', 'value' => '(021) 1234567', 'type' => 'text', 'group_name' => 'contact', 'description' => 'Nomor Telepon'],
            ['key_name' => 'school_email', 'value' => 'info@adyatama.sch.id', 'type' => 'text', 'group_name' => 'contact', 'description' => 'Email Sekolah'],
            // SEO
            ['key_name' => 'meta_keywords', 'value' => 'sekolah, pendidikan, adyatama', 'type' => 'textarea', 'group_name' => 'seo', 'description' => 'Default Meta Keywords'],
            // Social
            ['key_name' => 'facebook_url', 'value' => '#', 'type' => 'text', 'group_name' => 'social', 'description' => 'Facebook URL'],
            ['key_name' => 'instagram_url', 'value' => '#', 'type' => 'text', 'group_name' => 'social', 'description' => 'Instagram URL'],
        ];

        $this->settingModel->insertBatch($defaults);
        
        return redirect()->to('/dashboard/settings')->with('message', 'Default settings seeded.');
    }
}
