<?php
$auth = new Auth();
$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'] ?? 'User';
?>

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2>Notifications</h2>
            <p class="text-muted">Stay updated with your latest activities</p>
        </div>
        <div class="d-flex gap-2">
            <?php if ($unreadCount > 0): ?>
                <button class="btn-modern btn-modern-outline" onclick="markAllRead()">
                    <i class="fas fa-check-double me-1"></i> Mark All as Read
                </button>
            <?php endif; ?>
            <a href="index.php?page=dashboard" class="btn-modern btn-modern-ghost">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid mb-4">
    <div class="stat-card info">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-bell"></i></div>
        </div>
        <div class="stat-value"><?= count($notifications) ?></div>
        <div class="stat-label">Total Notifications</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-envelope"></i></div>
        </div>
        <div class="stat-value"><?= $unreadCount ?? 0 ?></div>
        <div class="stat-label">Unread</div>
    </div>
</div>

<!-- Notifications List -->
<div class="modern-card">
    <div class="modern-card-header">
        <h5 class="modern-card-title"><i class="fas fa-list me-2"></i>All Notifications</h5>
    </div>
    <div class="modern-card-body p-0">
        <?php if (empty($notifications)): ?>
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-4x text-muted mb-3 d-block opacity-50"></i>
                <h4>No Notifications</h4>
                <p class="text-muted">You're all caught up! New notifications will appear here.</p>
            </div>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($notifications as $notification): ?>
                    <div class="list-group-item border-bottom p-3 <?= !$notification['is_read'] ? 'bg-light' : '' ?>" 
                         style="border-left: 3px solid <?= !$notification['is_read'] ? '#6161ff' : 'transparent' ?>;">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width:44px; height:44px; background: <?= getNotificationBg($notification['type']) ?>; color: <?= getNotificationColor($notification['type']) ?>;">
                                    <i class="fas <?= getNotificationIcon($notification['type']) ?>"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong><?= htmlspecialchars($notification['title']) ?></strong>
                                        <?php if (!$notification['is_read']): ?>
                                            <span class="badge bg-primary rounded-pill ms-2" style="font-size:10px;">NEW</span>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted ms-2"><?= timeAgo($notification['created_at']) ?></small>
                                </div>
                                <p class="mb-1 text-muted"><?= htmlspecialchars($notification['message']) ?></p>
                                <div class="d-flex gap-2 mt-1">
                                    <?php if ($notification['link']): ?>
                                        <a href="<?= $notification['link'] ?>" class="btn-modern btn-modern-ghost btn-modern-sm">
                                            <i class="fas fa-external-link-alt me-1"></i> View
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!$notification['is_read']): ?>
                                        <button class="btn-modern btn-modern-ghost btn-modern-sm" 
                                                onclick="markRead('<?= $notification['id'] ?>')">
                                            <i class="fas fa-check me-1"></i> Mark Read
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Notification helper functions (local to this view)
if (!function_exists('getNotificationIcon')) {
    function getNotificationIcon($type) {
        switch($type) {
            case 'ticket_created': case 'ticket_replied': case 'ticket_assigned': return 'fa-ticket-alt';
            case 'company_added': return 'fa-building';
            case 'deal_updated': return 'fa-handshake';
            case 'activity_reminder': return 'fa-clock';
            default: return 'fa-bell';
        }
    }
}

if (!function_exists('getNotificationBg')) {
    function getNotificationBg($type) {
        switch($type) {
            case 'ticket_created': case 'deal_updated': return '#e0f7ef';
            case 'ticket_replied': case 'activity_reminder': return '#e0ecff';
            case 'ticket_assigned': return '#fff8e0';
            case 'company_added': return '#e8e8ff';
            default: return '#f5f5f5';
        }
    }
}

if (!function_exists('getNotificationColor')) {
    function getNotificationColor($type) {
        switch($type) {
            case 'ticket_created': case 'deal_updated': return '#00c875';
            case 'ticket_replied': case 'activity_reminder': return '#579bfc';
            case 'ticket_assigned': return '#ffcb00';
            case 'company_added': return '#6161ff';
            default: return '#676879';
        }
    }
}
?>

<script>
function markRead(id) {
    fetch('index.php?page=notifications&action=mark_read&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
}

function markAllRead() {
    if (confirm('Mark all notifications as read?')) {
        fetch('index.php?page=notifications&action=mark_all_read')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
    }
}
</script>