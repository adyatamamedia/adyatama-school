<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReactionTypeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['code' => 'like', 'emoji' => '👍', 'label' => 'Like'],
            ['code' => 'love', 'emoji' => '❤️', 'label' => 'Love'],
            ['code' => 'wow',  'emoji' => '😮', 'label' => 'Wow'],
            ['code' => 'sad',  'emoji' => '😢', 'label' => 'Sad'],
            ['code' => 'clap', 'emoji' => '👏', 'label' => 'Clap'],
        ];

        $db = \Config\Database::connect();
        $builder = $db->table('reaction_types');

        foreach ($data as $reaction) {
            if ($builder->where('code', $reaction['code'])->countAllResults() === 0) {
                $builder->insert($reaction);
            }
        }
    }
}
