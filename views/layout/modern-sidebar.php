<?php
// Load shared functions
require_once __DIR__ . '/../../helpers/functions.php';

$auth = new Auth();
$userRole = $_SESSION['user_role'] ?? 'guest';
$userName = $_SESSION['user_name'] ?? 'User';
$currentPage = $_GET['page'] ?? 'dashboard';
?>

<aside class="modern-sidebar" id="modernSidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <a href="index.php?page=dashboard" class="sidebar-logo">
            <i class="fas fa-viacoin"></i>
            <span>YesBoss CRM</span>
        </a>
    </div>
    
    <!-- User Info -->
    <div class="sidebar-user">
        <div class="user-avatar" style="background: <?= getAvatarColor($userName) ?>">
            <?= strtoupper(substr($userName, 0, 2)) ?>
        </div>
        <div class="user-info">
            <div class="user-name"><?= htmlspecialchars($userName) ?></div>
            <div class="user-role"><?= ucfirst($userRole) ?></div>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="sidebar-nav">
        <!-- Main Menu -->
        <div class="nav-section">
            <div class="nav-section-title">Main Menu</div>
            
            <a href="index.php?page=dashboard" 
               class="nav-item <?= $currentPage == 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            
            <?php if ($auth->isAdmin()): ?>
            <a href="index.php?page=companies&action=index" 
               class="nav-item <?= $currentPage == 'companies' ? 'active' : '' ?>">
                <i class="fas fa-building"></i>
                <span>Companies</span>
            </a>
            <?php endif; ?>
            
            <a href="index.php?page=contacts&action=index" 
               class="nav-item <?= $currentPage == 'contacts' ? 'active' : '' ?>">
                <i class="fas fa-address-book"></i>
                <span>Contacts</span>
            </a>
            
            <a href="index.php?page=deals&action=index" 
               class="nav-item <?= $currentPage == 'deals' ? 'active' : '' ?>">
                <i class="fas fa-handshake"></i>
                <span>Deals</span>
            </a>
            
            <a href="index.php?page=activities&action=index" 
               class="nav-item <?= $currentPage == 'activities' ? 'active' : '' ?>">
                <i class="fas fa-tasks"></i>
                <span>Activities</span>
            </a>
        </div>
        
        <!-- Support -->
        <div class="nav-section">
            <div class="nav-section-title">Support</div>
            
            <a href="index.php?page=tickets&action=index" 
               class="nav-item <?= $currentPage == 'tickets' ? 'active' : '' ?>">
                <i class="fas fa-ticket-alt"></i>
                <span>Tickets</span>
            </a>
        </div>
        
        <!-- Analytics -->
        <div class="nav-section">
            <div class="nav-section-title">Analytics</div>
            
            <a href="index.php?page=reports" 
               class="nav-item <?= $currentPage == 'reports' ? 'active' : '' ?>">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
        </div>
        
        <!-- Administration (Super Admin Only) -->
        <?php if ($auth->isSuperAdmin()): ?>
        <div class="nav-section">
            <div class="nav-section-title">Administration</div>
            
            <a href="index.php?page=users&action=index" 
               class="nav-item <?= $currentPage == 'users' ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            
            <a href="index.php?page=settings" 
               class="nav-item <?= $currentPage == 'settings' ? 'active' : '' ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </div>
        <?php endif; ?>
    </nav>
    
    <!-- Sidebar Footer -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 16px; border-top: 1px solid rgba(255,255,255,0.1);">
        <a href="index.php?page=auth&action=logout" class="nav-item" style="color: rgba(255,255,255,0.7);">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>