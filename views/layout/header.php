<?php
// Load shared functions FIRST
if (file_exists(__DIR__ . '/../../helpers/functions.php')) {
    require_once __DIR__ . '/../../helpers/functions.php';
}

require_once __DIR__ . '/modern-header.php';
require_once __DIR__ . '/modern-sidebar.php';
?>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    
    <!-- Top Header -->
    <header class="modern-header">
        <div class="header-left">
            <button class="header-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="breadcrumb-modern d-none d-md-flex">
                <a href="index.php?page=dashboard">Home</a>
                <span class="separator">/</span>
                <span class="current"><?= getPageTitle($currentPage ?? 'dashboard') ?></span>
            </div>
        </div>
        
        <div class="header-right">
            <div class="header-search d-none d-lg-block">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search anything..." id="globalSearch">
            </div>
            
            <div class="header-actions">
                <!-- Simple Notification Bell (No database dependency for now) -->
                <div class="dropdown-modern" id="notificationDropdown">
                    <button class="btn-modern btn-modern-ghost btn-modern-icon position-relative" 
                            onclick="toggleDropdown('notificationDropdown')">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                              style="font-size: 10px; display: none;" id="notifBadge">0</span>
                    </button>
                    
                    <div class="dropdown-menu-modern" style="width: 380px; max-height: 500px; overflow-y: auto;">
                        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                            <h6 class="mb-0 fw-bold">Notifications</h6>
                            <button class="btn-modern btn-modern-ghost btn-modern-sm" onclick="markAllRead()">
                                <i class="fas fa-check-double me-1"></i> Mark all read
                            </button>
                        </div>
                        
                        <div id="notificationsList">
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-bell fa-3x mb-3 d-block"></i>
                                <p>Loading notifications...</p>
                            </div>
                        </div>
                        
                        <div class="p-2 text-center border-top">
                            <a href="index.php?page=notifications&action=view_all" class="btn-modern btn-modern-ghost btn-modern-sm w-100">
                                <i class="fas fa-list me-1"></i> View All Notifications
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- User Dropdown -->
                <div class="dropdown-modern" id="userDropdown">
                    <button class="btn-modern btn-modern-ghost" onclick="toggleDropdown('userDropdown')">
                        <div class="user-avatar avatar-sm" style="background: <?= getAvatarColor($_SESSION['user_name'] ?? 'User') ?>; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 13px;">
                            <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 2)) ?>
                        </div>
                        <span class="d-none d-md-inline ms-2"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                        <i class="fas fa-chevron-down ms-2"></i>
                    </button>
                    
                    <div class="dropdown-menu-modern">
                        <a href="index.php?page=profile" class="dropdown-item">
                            <i class="fas fa-user me-2"></i> My Profile
                        </a>
                        <a href="index.php?page=reports" class="dropdown-item">
                            <i class="fas fa-chart-bar me-2"></i> Reports
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="index.php?page=auth&action=logout" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Flash Messages -->
    <div style="padding: 0 24px;">
        <?php if (isset($_SESSION['success'])): ?>
            <div style="border-radius: 8px; border: none; background: #e0f7ef; color: #00854c; padding: 12px 16px; margin-top: 8px; display: flex; align-items: center; justify-content: space-between;">
                <span><i class="fas fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; font-size:18px; color:#00854c;">&times;</button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div style="border-radius: 8px; border: none; background: #ffe0e3; color: #c0392b; padding: 12px 16px; margin-top: 8px; display: flex; align-items: center; justify-content: space-between;">
                <span><i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; font-size:18px; color:#c0392b;">&times;</button>
            </div>
        <?php endif; ?>
    </div>
    
    <div style="padding: 24px;">

<script>
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    dropdown.classList.toggle('active');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown-modern')) {
        document.querySelectorAll('.dropdown-modern').forEach(d => d.classList.remove('active'));
    }
});

function toggleSidebar() {
    document.getElementById('modernSidebar').classList.toggle('collapsed');
}

function toggleMobileSidebar() {
    document.getElementById('modernSidebar').classList.toggle('mobile-open');
    const overlay = document.getElementById('mobileOverlay');
    if (overlay) {
        overlay.style.display = document.getElementById('modernSidebar').classList.contains('mobile-open') ? 'block' : 'none';
    }
}

// Notification functions
function loadNotifications() {
    fetch('index.php?page=notifications&action=get')
        .then(response => response.json())
        .then(data => {
            updateBadge(data.unread_count);
            renderNotifications(data.notifications);
        })
        .catch(err => {
            document.getElementById('notificationsList').innerHTML = 
                '<div class="text-center py-5 text-muted"><i class="fas fa-bell-slash fa-3x mb-3 d-block"></i><p>No notifications</p></div>';
        });
}

function updateBadge(count) {
    const badge = document.getElementById('notifBadge');
    if (count > 0) {
        badge.style.display = '';
        badge.textContent = count > 99 ? '99+' : count;
    } else {
        badge.style.display = 'none';
    }
}

function renderNotifications(notifications) {
    const container = document.getElementById('notificationsList');
    
    if (!notifications || notifications.length === 0) {
        container.innerHTML = '<div class="text-center py-5 text-muted"><i class="fas fa-bell-slash fa-3x mb-3 d-block"></i><p>No notifications yet</p><small>You\'re all caught up!</small></div>';
        return;
    }
    
    container.innerHTML = notifications.map(n => `
        <a href="${n.link || '#'}" class="dropdown-item" style="display:flex; align-items:flex-start; gap:12px; padding:12px 16px; ${!n.is_read ? 'background:#f8f9fa;' : ''}">
            <div style="width:40px; height:40px; border-radius:50%; background:#e8e8ff; color:#6161ff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="fas fa-bell"></i>
            </div>
            <div style="flex:1; min-width:0;">
                <div style="display:flex; justify-content:space-between;">
                    <strong style="font-size:13px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${escapeHtml(n.title)}</strong>
                    ${!n.is_read ? '<span style="width:8px; height:8px; background:#6161ff; border-radius:50%; flex-shrink:0; margin-top:6px;"></span>' : ''}
                </div>
                <small style="color:#666; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${escapeHtml(n.message)}</small>
                <small style="color:#999; font-size:11px;">${n.created_at || ''}</small>
            </div>
        </a>
    `).join('');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text || '';
    return div.innerHTML;
}

function markAllRead() {
    fetch('index.php?page=notifications&action=mark_all_read')
        .then(response => response.json())
        .then(data => {
            if (data.success) loadNotifications();
        });
}

// Load notifications on page load
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
});

// Refresh notifications every 30 seconds
setInterval(loadNotifications, 30000);
</script>