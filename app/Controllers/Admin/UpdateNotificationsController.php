<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UpdateNotificationModel;

class UpdateNotificationsController extends BaseController
{
    protected $notificationModel;
    
    public function __construct()
    {
        $this->notificationModel = new UpdateNotificationModel();
    }
    
    /**
     * Display all notifications
     */
    public function index()
    {
        $notifications = $this->notificationModel->getAllNotifications();
        $unreadCount = $this->notificationModel->getUnreadCount();
        
        return $this->response->setJSON([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
    
    /**
     * Get unread count
     */
    public function getUnreadCount()
    {
        $count = $this->notificationModel->getUnreadCount();
        return $this->response->setJSON(['count' => $count]);
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($id = null)
    {
        if ($id) {
            $this->notificationModel->markAsRead($id);
            return $this->response->setJSON(['success' => true, 'message' => 'Notification marked as read']);
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid notification ID']);
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $this->notificationModel->markAllAsRead();
        return $this->response->setJSON(['success' => true, 'message' => 'All notifications marked as read']);
    }
    
    /**
     * Add new notification
     */
    public function addNotification()
    {
        $type = $this->request->getPost('type');
        $title = $this->request->getPost('title');
        $message = $this->request->getPost('message');
        $module = $this->request->getPost('module');
        $version = $this->request->getPost('version');
        
        if ($type && $title && $message) {
            $data = [
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'module' => $module,
                'version' => $version,
            ];
            
            $this->notificationModel->addNotification($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Notification added successfully']);
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'Missing required fields']);
    }
    
    /**
     * Delete notification
     */
    public function deleteNotification($id = null)
    {
        if ($id) {
            $this->notificationModel->deleteNotification($id);
            return $this->response->setJSON(['success' => true, 'message' => 'Notification deleted']);
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid notification ID']);
    }
}
