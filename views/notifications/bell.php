<?php
// Load shared functions if not already loaded
if (!function_exists('timeAgo')) {
    require_once __DIR__ . '/../../helpers/functions.php';
}

$notificationModel = new Notification();
$userId = $_SESSION['user_id'];
$unreadCount = $notificationModel->getUnreadCount($userId);
$notifications = $notificationModel->getUserNotifications($userId, 5);
?>

<div class="dropdown-modern" id="notificationDropdown">
    <button class="btn-modern btn-modern-ghost btn-modern-icon position-relative" 
            onclick="toggleDropdown('notificationDropdown'); loadNotifications();">
        <i class="fas fa-bell"></i>
        <?php if ($unreadCount > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                  style="font-size: 10px;" id="notificationBadge">
                <?= $unreadCount > 99 ? '99+' : $unreadCount ?>
            </span>
        <?php endif; ?>
    </button>
    
    <div class="dropdown-menu-modern" style="width: 380px; max-height: 500px; overflow-y: auto;">
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <h6 class="mb-0 fw-bold">Notifications</h6>
            <?php if ($unreadCount > 0): ?>
                <button class="btn-modern btn-modern-ghost btn-modern-sm" onclick="markAllNotificationsRead()">
                    <i class="fas fa-check-double me-1"></i> Mark all read
                </button>
            <?php endif; ?>
        </div>
        
        <div id="notificationsList">
            <?php if (empty($notifications)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-bell-slash fa-3x mb-3 d-block"></i>
                    <p>No notifications yet</p>
                    <small>You're all caught up!</small>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notification): ?>
                    <a href="<?= $notification['link'] ?? '#' ?>" 
                       class="dropdown-item notification-item <?= !$notification['is_read'] ? 'bg-light' : '' ?>"
                       onclick="markNotificationRead('<?= $notification['id'] ?>')"
                       style="display: flex; align-items: flex-start; gap: 12px; padding: 12px 16px;">
                        
                        <!-- Icon based on type -->
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width: 40px; height: 40px; background: <?= getNotificationBg($notification['type']) ?>; color: <?= getNotificationColor($notification['type']) ?>;">
                            <i class="fas <?= getNotificationIcon($notification['type']) ?>"></i>
                        </div>
                        
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <strong class="text-truncate" style="font-size: 13px;">
                                    <?= htmlspecialchars($notification['title']) ?>
                                </strong>
                                <?php if (!$notification['is_read']): ?>
                                    <span class="badge bg-primary rounded-pill flex-shrink-0" 
                                          style="width: 8px; height: 8px; padding: 0; margin-top: 6px;"></span>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted d-block text-truncate" style="font-size: 12px;">
                                <?= htmlspecialchars($notification['message']) ?>
                            </small>
                            <small class="text-muted" style="font-size: 11px;">
                                <?= timeAgo($notification['created_at']) ?>
                            </small>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="p-2 text-center border-top">
            <a href="index.php?page=notifications&action=view_all" class="btn-modern btn-modern-ghost btn-modern-sm w-100">
                <i class="fas fa-list me-1"></i> View All Notifications
            </a>
        </div>
    </div>
</div>

<?php
// Keep these helper functions as they are specific to notifications
if (!function_exists('getNotificationIcon')) {
    function getNotificationIcon($type) {
        switch($type) {
            case 'ticket_created':
            case 'ticket_replied':
            case 'ticket_assigned':
                return 'fa-ticket-alt';
            case 'company_added':
                return 'fa-building';
            case 'deal_updated':
                return 'fa-handshake';
            case 'activity_reminder':
                return 'fa-clock';
            default:
                return 'fa-bell';
        }
    }
}

if (!function_exists('getNotificationBg')) {
    function getNotificationBg($type) {
        switch($type) {
            case 'ticket_created':
                return '#e0f7ef';
            case 'ticket_replied':
                return '#e0ecff';
            case 'ticket_assigned':
                return '#fff8e0';
            case 'company_added':
                return '#e8e8ff';
            case 'deal_updated':
                return '#e0f7ef';
            default:
                return '#f5f5f5';
        }
    }
}

if (!function_exists('getNotificationColor')) {
    function getNotificationColor($type) {
        switch($type) {
            case 'ticket_created':
                return '#00c875';
            case 'ticket_replied':
                return '#579bfc';
            case 'ticket_assigned':
                return '#ffcb00';
            case 'company_added':
                return '#6161ff';
            case 'deal_updated':
                return '#00c875';
            default:
                return '#676879';
        }
    }
}
?>

<script>
function loadNotifications() {
    fetch('index.php?page=notifications&action=get')
        .then(response => response.json())
        .then(data => {
            updateNotificationBadge(data.unread_count);
            renderNotifications(data.notifications);
        });
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationBadge');
    if (count > 0) {
        if (badge) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = '';
        } else {
            // Create badge if it doesn't exist
            const btn = document.querySelector('#notificationDropdown .btn-modern-icon');
            if (btn) {
                const span = document.createElement('span');
                span.id = 'notificationBadge';
                span.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                span.style.fontSize = '10px';
                span.textContent = count > 99 ? '99+' : count;
                btn.appendChild(span);
            }
        }
    } else {
        if (badge) badge.style.display = 'none';
    }
}

function renderNotifications(notifications) {
    const container = document.getElementById('notificationsList');
    
    if (!notifications || notifications.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5 text-muted">
                <i class="fas fa-bell-slash fa-3x mb-3 d-block"></i>
                <p>No notifications yet</p>
                <small>You're all caught up!</small>
            </div>`;
        return;
    }
    
    container.innerHTML = notifications.map(n => `
        <a href="${n.link || '#'}" 
           class="dropdown-item notification-item ${!n.is_read ? 'bg-light' : ''}"
           onclick="markNotificationRead('${n.id}')"
           style="display: flex; align-items: flex-start; gap: 12px; padding: 12px 16px;">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width: 40px; height: 40px; background: ${getNotificationBgJS(n.type)}; color: ${getNotificationColorJS(n.type)};">
                <i class="fas ${getNotificationIconJS(n.type)}"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="d-flex justify-content-between align-items-start">
                    <strong class="text-truncate" style="font-size: 13px;">${escapeHtml(n.title)}</strong>
                    ${!n.is_read ? '<span class="badge bg-primary rounded-pill flex-shrink-0" style="width: 8px; height: 8px; padding: 0; margin-top: 6px;"></span>' : ''}
                </div>
                <small class="text-muted d-block text-truncate" style="font-size: 12px;">${escapeHtml(n.message)}</small>
                <small class="text-muted" style="font-size: 11px;">${n.created_at}</small>
            </div>
        </a>
    `).join('');
}

// JS versions of notification helper functions
function getNotificationIconJS(type) {
    const icons = {
        'ticket_created': 'fa-ticket-alt',
        'ticket_replied': 'fa-ticket-alt',
        'ticket_assigned': 'fa-ticket-alt',
        'company_added': 'fa-building',
        'deal_updated': 'fa-handshake',
        'activity_reminder': 'fa-clock'
    };
    return icons[type] || 'fa-bell';
}

function getNotificationBgJS(type) {
    const bgs = {
        'ticket_created': '#e0f7ef',
        'ticket_replied': '#e0ecff',
        'ticket_assigned': '#fff8e0',
        'company_added': '#e8e8ff',
        'deal_updated': '#e0f7ef'
    };
    return bgs[type] || '#f5f5f5';
}

function getNotificationColorJS(type) {
    const colors = {
        'ticket_created': '#00c875',
        'ticket_replied': '#579bfc',
        'ticket_assigned': '#ffcb00',
        'company_added': '#6161ff',
        'deal_updated': '#00c875'
    };
    return colors[type] || '#676879';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function markNotificationRead(id) {
    fetch('index.php?page=notifications&action=mark_read&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationCount();
            }
        });
}

function markAllNotificationsRead() {
    fetch('index.php?page=notifications&action=mark_all_read')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
            }
        });
}

function updateNotificationCount() {
    fetch('index.php?page=notifications&action=get')
        .then(response => response.json())
        .then(data => {
            updateNotificationBadge(data.unread_count);
        });
}

// Poll for new notifications every 30 seconds
setInterval(updateNotificationCount, 30000);
</script>