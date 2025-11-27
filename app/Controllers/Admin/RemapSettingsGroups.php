<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class RemapSettingsGroups extends BaseController
{
    public function index()
    {
        $settingModel = new SettingModel();
        
        // Mapping dari grup lama ke grup baru
        $groupMapping = [
            // Grup yang perlu di-remap
            'homepage' => 'general',
            'school_info' => 'academic_info',
            'admission' => 'admission_info',
            'academic' => 'academic_calendar',
            'system' => 'website_behavior',
            'content' => 'website_behavior',
            'payment' => 'payment_config',
            'performance' => 'website_behavior',
        ];
        
        $updated = 0;
        
        foreach ($groupMapping as $oldGroup => $newGroup) {
            $settings = $settingModel->where('group_name', $oldGroup)->findAll();
            
            foreach ($settings as $setting) {
                $settingModel->update($setting->id, ['group_name' => $newGroup]);
                $updated++;
            }
        }
        
        // Pindahkan beberapa setting spesifik ke general
        $moveToGeneral = ['timezone', 'language', 'date_format', 'show_branding'];
        foreach ($moveToGeneral as $key) {
            $setting = $settingModel->where('key_name', $key)->first();
            if ($setting && $setting->group_name !== 'general') {
                $settingModel->update($setting->id, ['group_name' => 'general']);
                $updated++;
            }
        }
        
        return redirect()->to('/dashboard/settings')->with('message', "Successfully remapped {$updated} settings to new group structure.");
    }
}
