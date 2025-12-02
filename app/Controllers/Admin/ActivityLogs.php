<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;
use App\Models\UserModel;

class ActivityLogs extends BaseController
{
    protected $logModel;

    public function __construct()
    {
        $this->logModel = new ActivityLogModel();
    }

    public function index()
    {
        $builder = $this->logModel
            ->select('activity_log.*, users.username, users.fullname, users.photo')
            ->join('users', 'users.id = activity_log.user_id', 'left')
            ->orderBy('activity_log.created_at', 'DESC');

        // Filters
        $filterUser = $this->request->getGet('user');
        $filterAction = $this->request->getGet('action');
        $filterDate = $this->request->getGet('date');
        $search = $this->request->getGet('search');

        if ($filterUser) {
            $builder->where('activity_log.user_id', $filterUser);
        }

        if ($filterAction) {
            $builder->where('activity_log.action', $filterAction);
        }

        if ($filterDate) {
            $builder->where('DATE(activity_log.created_at)', $filterDate);
        }

        if ($search) {
            $builder->groupStart()
                ->like('activity_log.action', $search)
                ->orLike('activity_log.subject_type', $search)
                ->orLike('users.username', $search)
                ->orLike('activity_log.ip_address', $search)
                ->groupEnd();
        }

        // Pagination
        $perPage = 50;
        $logs = $builder->paginate($perPage, 'default');
        $pager = $builder->pager;

        // Get unique actions and users for filters
        $db = \Config\Database::connect();
        $actionsQuery = $db->table('activity_log')->select('action')->distinct()->get();
        $actions = $actionsQuery->getResult();
        
        $userModel = new UserModel();
        $users = $userModel->select('id, username, fullname')->where('deleted_at', null)->findAll();

        $data = [
            'title' => 'Activity Log',
            'logs' => $logs,
            'pager' => $pager,
            'actions' => $actions,
            'users' => $users,
            'filterUser' => $filterUser,
            'filterAction' => $filterAction,
            'filterDate' => $filterDate,
            'search' => $search
        ];

        return view('admin/activity_logs/index', $data);
    }
}
