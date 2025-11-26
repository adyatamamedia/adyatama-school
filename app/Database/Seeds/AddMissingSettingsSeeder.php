<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddMissingSettingsSeeder extends Seeder
{
    public function run()
    {
        $missingSettings = [
            'hero_bg_image' => [
                'value' => '',
                'type' => 'image',
                'group_name' => 'hero',
                'description' => 'Hero Section Background Image'
            ],
            'hero_title' => [
                'value' => 'Welcome to Adyatama School',
                'type' => 'text',
                'group_name' => 'hero',
                'description' => 'Hero Section Title'
            ],
            'hero_description' => [
                'value' => 'Empowering the next generation of leaders.',
                'type' => 'textarea',
                'group_name' => 'hero',
                'description' => 'Hero Section Description'
            ],
            'hero_btn_text' => [
                'value' => 'Learn More',
                'type' => 'text',
                'group_name' => 'hero',
                'description' => 'Hero Button Text'
            ],
            'hero_btn_url' => [
                'value' => '#',
                'type' => 'text',
                'group_name' => 'hero',
                'description' => 'Hero Button URL'
            ]
        ];

        foreach ($missingSettings as $keyName => $data) {
            // Check if setting already exists
            $existing = $this->db->table('settings')->where('key_name', $keyName)->get()->getRow();

            if (!$existing) {
                $this->db->table('settings')->insert([
                    'key_name' => $keyName,
                    'value' => $data['value'],
                    'type' => $data['type'],
                    'group_name' => $data['group_name'],
                    'description' => $data['description'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                echo "Added: {$keyName}\n";
            } else {
                echo "Already exists: {$keyName}\n";
            }
        }
    }
}
