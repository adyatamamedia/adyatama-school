<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class ExportSettings extends BaseController
{
    public function index()
    {
        $settingModel = new SettingModel();
        $settings = $settingModel->findAll();
        
        $data = [
            'generated_at' => date('Y-m-d H:i:s'),
            'total_settings' => count($settings),
            'settings' => $settings
        ];
        
        $filename = 'adyatama_settings_' . date('Y-m-d_His') . '.json';
        
        return $this->response->download($filename, json_encode($data, JSON_PRETTY_PRINT));
    }
}
