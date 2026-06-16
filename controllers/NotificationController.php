<?php
require_once 'models/Notification.php';

class NotificationController {
    
    public function getNotifications() {
        $notificationModel = new Notification();
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            header('Content-Type: application/json');
            echo json_encode(['notifications' => [], 'unread_count' => 0]);
            return;
        }
        
        $notifications = $notificationModel->getUserNotifications($userId);
        $unreadCount = $notificationModel->getUnreadCount($userId);
        
        header('Content-Type: application/json');
        echo json_encode([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
    
    public function markAsRead($id) {
        $notificationModel = new Notification();
        $notificationModel->markAsRead($id);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }
    
    public function markAllAsRead() {
        $notificationModel = new Notification();
        $userId = $_SESSION['user_id'] ?? null;
        
        if ($userId) {
            $notificationModel->markAllAsRead($userId);
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }
    
    public function viewAll() {
        $notificationModel = new Notification();
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            header('Location: index.php?page=login');
            exit;
        }
        
        $notifications = $notificationModel->getUserNotifications($userId, 50);
        $unreadCount = $notificationModel->getUnreadCount($userId);
        
        // Check if view file exists
        if (file_exists('views/notifications/index.php')) {
            require_once 'views/layout/header.php';
            require_once 'views/notifications/index.php';
            require_once 'views/layout/footer.php';
        } else {
            // Fallback if view doesn't exist
            require_once 'views/layout/header.php';
            echo '<div class="modern-card"><div class="modern-card-body text-center py-5">';
            echo '<i class="fas fa-bell fa-3x text-muted mb-3"></i>';
            echo '<h3>Notifications</h3>';
            echo '<p class="text-muted">You have ' . $unreadCount . ' unread notifications.</p>';
            
            if (!empty($notifications)) {
                echo '<div class="text-start mt-3">';
                foreach ($notifications as $n) {
                    echo '<div class="p-3 mb-2 rounded ' . (!$n['is_read'] ? 'bg-light' : '') . '">';
                    echo '<strong>' . htmlspecialchars($n['title']) . '</strong><br>';
                    echo '<small class="text-muted">' . htmlspecialchars($n['message']) . '</small>';
                    echo '</div>';
                }
                echo '</div>';
            }
            
            echo '</div></div>';
            require_once 'views/layout/footer.php';
        }
    }
}