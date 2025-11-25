<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard Overview',
            'stats' => [
                'posts' => 0, // Placeholder
                'users' => 0, // Placeholder
                'views' => 0, // Placeholder
            ]
        ];

        // Future: Inject real stats from Models here
        // $postModel = new \App\Models\PostModel();
        // $data['stats']['posts'] = $postModel->countAll();

        return view('admin/dashboard', $data);
    }
}
