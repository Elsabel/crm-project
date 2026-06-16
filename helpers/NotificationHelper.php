<?php
class NotificationHelper {
    
    public static function createNotification($userId, $type, $title, $message, $link = null, $relatedType = null, $relatedId = null) {
        $notificationModel = new Notification();
        
        $data = [
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
            'is_read' => false
        ];
        
        return $notificationModel->create($data);
    }
    
    public static function notifyTicketCreated($ticket) {
        // Notify admins
        $userModel = new User();
        $admins = $userModel->getAll();
        
        foreach ($admins as $admin) {
            if (in_array($admin['role'], ['superadmin', 'admin'])) {
                self::createNotification(
                    $admin['id'],
                    'ticket_created',
                    'New Support Ticket',
                    "Ticket #{$ticket['ticket_number']}: {$ticket['subject']}",
                    "index.php?page=tickets&action=show&id={$ticket['id']}",
                    'ticket',
                    $ticket['id']
                );
            }
        }
        
        // Notify assigned user
        if (!empty($ticket['assigned_to'])) {
            self::createNotification(
                $ticket['assigned_to'],
                'ticket_assigned',
                'Ticket Assigned to You',
                "Ticket #{$ticket['ticket_number']}: {$ticket['subject']}",
                "index.php?page=tickets&action=show&id={$ticket['id']}",
                'ticket',
                $ticket['id']
            );
        }
    }
    
    public static function notifyTicketReplied($ticket, $reply) {
        // Notify ticket creator
        if (!empty($ticket['user_id'])) {
            self::createNotification(
                $ticket['user_id'],
                'ticket_replied',
                'New Reply on Your Ticket',
                "Reply on ticket #{$ticket['ticket_number']}",
                "index.php?page=tickets&action=show&id={$ticket['id']}",
                'ticket',
                $ticket['id']
            );
        }
    }
    
    public static function notifyCompanyCreated($company, $userId) {
        $userModel = new User();
        $admins = $userModel->getAll();
        
        foreach ($admins as $admin) {
            if (in_array($admin['role'], ['superadmin', 'admin'])) {
                self::createNotification(
                    $admin['id'],
                    'company_added',
                    'New Company Added',
                    "{$company['name']} has been added to the CRM",
                    "index.php?page=companies&action=show&id={$company['id']}",
                    'company',
                    $company['id']
                );
            }
        }
    }
    
    public static function notifyDealStageChanged($deal, $oldStage, $newStage) {
        $userModel = new User();
        $admins = $userModel->getAll();
        
        foreach ($admins as $admin) {
            if (in_array($admin['role'], ['superadmin', 'admin'])) {
                self::createNotification(
                    $admin['id'],
                    'deal_updated',
                    'Deal Stage Changed',
                    "Deal '{$deal['title']}' moved from {$oldStage} to {$newStage}",
                    "index.php?page=deals&action=show&id={$deal['id']}",
                    'deal',
                    $deal['id']
                );
            }
        }
    }
}