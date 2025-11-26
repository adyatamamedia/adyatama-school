<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNewSettings extends Migration
{
    public function up()
    {
        $data = [
            // General
            [
                'key_name'    => 'site_logo',
                'value'       => '',
                'type'        => 'image',
                'group_name'  => 'general',
                'description' => 'Website Logo',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            // SEO
            [
                'key_name'    => 'seo_image',
                'value'       => '',
                'type'        => 'image',
                'group_name'  => 'seo',
                'description' => 'Default SEO Image (OG Image)',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            // Hero Section
            [
                'key_name'    => 'hero_bg_image',
                'value'       => '',
                'type'        => 'image',
                'group_name'  => 'hero',
                'description' => 'Hero Section Background Image',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'key_name'    => 'hero_title',
                'value'       => 'Welcome to Adyatama School',
                'type'        => 'text',
                'group_name'  => 'hero',
                'description' => 'Hero Section Title',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'key_name'    => 'hero_description',
                'value'       => 'Empowering the next generation of leaders.',
                'type'        => 'textarea',
                'group_name'  => 'hero',
                'description' => 'Hero Section Description',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'key_name'    => 'hero_btn_text',
                'value'       => 'Learn More',
                'type'        => 'text',
                'group_name'  => 'hero',
                'description' => 'Hero Button Text',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'key_name'    => 'hero_btn_url',
                'value'       => '#',
                'type'        => 'text',
                'group_name'  => 'hero',
                'description' => 'Hero Button URL',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('settings')->insertBatch($data);
    }

    public function down()
    {
        $keys = [
            'site_logo',
            'seo_image',
            'hero_bg_image',
            'hero_title',
            'hero_description',
            'hero_btn_text',
            'hero_btn_url'
        ];

        $this->db->table('settings')->whereIn('key_name', $keys)->delete();
    }
}
