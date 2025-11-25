<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

class ActivityLogs extends BaseController
{
    protected $logModel;

    public function __construct()
    {
        $this->logModel = new ActivityLogModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Activity Logs',
            'logs' => $this->logModel
                ->select('activity_log.*, users.username')
                ->join('users', 'users.id = activity_log.user_id', 'left')
                ->orderBy('created_at', 'DESC')
                ->findAll(100) // Limit to last 100 for now
        ];

        return view('admin/activity_logs/index', $data);
    }
}
