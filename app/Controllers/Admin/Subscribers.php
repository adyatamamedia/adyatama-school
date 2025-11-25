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
        $data = [
            'title' => 'Subscribers',
            'subscribers' => $this->subscriberModel->orderBy('subscribed_at', 'DESC')->findAll()
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
}
