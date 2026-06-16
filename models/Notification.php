<?php
class Notification {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function create($data) {
        $result = $this->db->query('notifications', 'POST', $data);
        return $result['data'][0] ?? null;
    }
    
    public function getUserNotifications($userId, $limit = 20) {
        $result = $this->db->query("notifications?user_id=eq.{$userId}&order=created_at.desc&limit={$limit}");
        return $result['data'] ?? [];
    }
    
    public function getUnreadCount($userId) {
        $result = $this->db->query("notifications?user_id=eq.{$userId}&is_read=eq.false&select=count");
        return $result['data'][0]['count'] ?? 0;
    }
    
    public function markAsRead($id) {
        return $this->db->query("notifications?id=eq.{$id}", 'PATCH', ['is_read' => true]);
    }
    
    public function markAllAsRead($userId) {
        return $this->db->query("notifications?user_id=eq.{$userId}&is_read=eq.false", 'PATCH', ['is_read' => true]);
    }
    
    public function delete($id) {
        return $this->db->query("notifications?id=eq.{$id}", 'DELETE');
    }
}