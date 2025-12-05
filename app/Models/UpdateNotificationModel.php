<?php

namespace App\Models;

use CodeIgniter\Model;

class UpdateNotificationModel extends Model
{
    protected $table = 'update_notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'type', 'title', 'message', 'module', 'version', 'is_read', 'created_at'
    ];
    protected $useTimestamps = false;
    
    /**
     * Get all notifications
     */
    public function getAllNotifications($limit = 10)
    {
        return $this->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
    
    /**
     * Get unread notifications
     */
    public function getUnreadNotifications($limit = 5)
    {
        return $this->where('is_read', false)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
    
    /**
     * Get unread count
     */
    public function getUnreadCount()
    {
        return $this->where('is_read', false)->countAllResults();
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        return $this->update($id, ['is_read' => true]);
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        return $this->set('is_read', true)->update();
    }
    
    /**
     * Add new notification
     */
    public function addNotification($data)
    {
        return $this->insert($data);
    }
    
    /**
     * Delete notification
     */
    public function deleteNotification($id)
    {
        return $this->delete($id);
    }
}
