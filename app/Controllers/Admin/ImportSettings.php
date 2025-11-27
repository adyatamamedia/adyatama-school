<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class ImportSettings extends BaseController
{
    public function index()
    {
        $file = $this->request->getFile('json_file');

        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File upload failed.');
        }

        if ($file->getExtension() !== 'json') {
            return redirect()->back()->with('error', 'Invalid file format. Please upload a JSON file.');
        }

        $jsonContent = file_get_contents($file->getTempName());
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', 'Invalid JSON content.');
        }

        if (!isset($data['settings']) || !is_array($data['settings'])) {
            return redirect()->back()->with('error', 'Invalid settings format in JSON file.');
        }

        $settingModel = new SettingModel();
        $updatedCount = 0;
        $insertedCount = 0;

        foreach ($data['settings'] as $item) {
            // Check if setting exists by key_name
            $existing = $settingModel->where('key_name', $item['key_name'])->first();

            if ($existing) {
                // Update existing setting
                $settingModel->update($existing->id, [
                    'value' => $item['value'],
                    // Optionally update other fields if needed, but usually value is what we want to import
                    // 'group_name' => $item['group_name'], 
                    // 'type' => $item['type'],
                    // 'description' => $item['description']
                ]);
                $updatedCount++;
            } else {
                // Insert new setting if it doesn't exist (optional, depending on requirement)
                // For safety, let's only insert if it matches our known structure or just insert it.
                // Let's insert it to be flexible.
                $settingModel->insert([
                    'key_name' => $item['key_name'],
                    'value' => $item['value'],
                    'type' => $item['type'] ?? 'text',
                    'group_name' => $item['group_name'] ?? 'general',
                    'description' => $item['description'] ?? ''
                ]);
                $insertedCount++;
            }
        }

        return redirect()->to('/dashboard/settings')->with('message', "Import successful! Updated {$updatedCount} settings and added {$insertedCount} new settings.");
    }
}
