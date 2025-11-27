<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class DebugGeneralSettings extends BaseController
{
    public function index()
    {
        $settingModel = new SettingModel();
        
        // Get all settings in 'general' group
        $generalSettings = $settingModel->where('group_name', 'general')->findAll();
        
        echo "<h2>Settings in 'general' group (" . count($generalSettings) . " total)</h2>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Key Name</th><th>Type</th><th>Description</th><th>Value</th></tr>";
        
        foreach ($generalSettings as $setting) {
            echo "<tr>";
            echo "<td>{$setting->id}</td>";
            echo "<td>{$setting->key_name}</td>";
            echo "<td>{$setting->type}</td>";
            echo "<td>{$setting->description}</td>";
            echo "<td>" . substr($setting->value, 0, 50) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Get all groups
        echo "<br><br><h2>All Groups</h2>";
        $allSettings = $settingModel->findAll();
        $groups = [];
        
        foreach ($allSettings as $setting) {
            if (!isset($groups[$setting->group_name])) {
                $groups[$setting->group_name] = 0;
            }
            $groups[$setting->group_name]++;
        }
        
        ksort($groups);
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Group Name</th><th>Count</th></tr>";
        foreach ($groups as $groupName => $count) {
            echo "<tr><td>{$groupName}</td><td>{$count}</td></tr>";
        }
        echo "</table>";
        
        echo "<br><br><h3>Total Settings: " . count($allSettings) . "</h3>";
    }
}
