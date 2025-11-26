<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubscriberModel;

class Subscribers extends BaseController
{
    protected $subscriberModel;

    public function __construct()
    {
        $this->subscriberModel = new SubscriberModel();
    }

    public function index()
    {
        // Get query parameters
        $perPage = $this->request->getGet('per_page') ?? 25;
        $sortBy = $this->request->getGet('sort') ?? 'newest';
        $search = $this->request->getGet('search') ?? '';
        
        // Build query
        $builder = $this->subscriberModel;
        
        // Apply search
        if ($search) {
            $builder->like('email', $search);
        }
        
        // Apply sorting
        switch ($sortBy) {
            case 'oldest':
                $builder->orderBy('subscribed_at', 'ASC');
                break;
            case 'email_asc':
                $builder->orderBy('email', 'ASC');
                break;
            case 'email_desc':
                $builder->orderBy('email', 'DESC');
                break;
            case 'newest':
            default:
                $builder->orderBy('subscribed_at', 'DESC');
                break;
        }
        
        $data = [
            'title' => 'Subscribers',
            'subscribers' => $builder->paginate($perPage, 'default'),
            'pager' => $builder->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'search' => $search,
            'sortOptions' => [
                'newest' => 'Terbaru',
                'oldest' => 'Terlama',
                'email_asc' => 'Email A-Z',
                'email_desc' => 'Email Z-A'
            ],
            'enableBulkActions' => true,
            'bulkActions' => [
                ['action' => 'delete', 'label' => 'Hapus', 'icon' => 'trash', 'variant' => 'danger', 'confirm' => 'Hapus subscriber terpilih?']
            ],
            'createButton' => false // Subscribers are added via frontend
        ];

        return view('admin/subscribers/index', $data);
    }

    public function delete($id)
    {
        $this->subscriberModel->delete($id);
        return redirect()->to('/dashboard/subscribers')->with('message', 'Subscriber deleted.');
    }
    
    // Optional: Toggle Status
    public function toggle($id)
    {
        $sub = $this->subscriberModel->find($id);
        if ($sub) {
            $newStatus = $sub->is_active ? 0 : 1;
            $this->subscriberModel->update($id, ['is_active' => $newStatus]);
        }
        return redirect()->back()->with('message', 'Status updated.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No subscribers selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($this->subscriberModel->delete($id)) {
                $count++;
            }
        }

        return redirect()->to('/dashboard/subscribers')->with('message', "$count subscriber(s) deleted successfully.");
    }
}
