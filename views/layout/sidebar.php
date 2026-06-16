<?php
// DEFINE FUNCTIONS FIRST - Before using them!
if (!function_exists('getRoleBadgeColor')) {
    function getRoleBadgeColor($role) {
        switch($role) {
            case 'superadmin': return 'danger';
            case 'admin': return 'warning';
            case 'company': return 'info';
            default: return 'secondary';
        }
    }
}

// Now get auth and session data
$auth = new Auth();
$userRole = $_SESSION['user_role'] ?? 'guest';
$userName = $_SESSION['user_name'] ?? 'User';
$currentPage = $_GET['page'] ?? 'dashboard';
?>
<nav id="sidebar" class="bg-dark text-white">
    <div class="sidebar-header p-3">
        <h3>CRM System</h3>
        <small class="text-muted"><?= htmlspecialchars($userName) ?></small>
        <br>
        <span class="badge bg-<?= getRoleBadgeColor($userRole) ?>">
            <?= ucfirst($userRole) ?>
        </span>
    </div>
    
    <ul class="list-unstyled components p-2">
        <!-- Dashboard - All roles -->
        <li class="<?= $currentPage == 'dashboard' ? 'active' : '' ?>">
            <a href="index.php?page=dashboard" class="text-white text-decoration-none d-block p-2">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>
        
        <!-- Companies - Super Admin & Admin only -->
        <?php if ($auth->isAdmin()): ?>
        <li class="<?= $currentPage == 'companies' ? 'active' : '' ?>">
            <a href="index.php?page=companies&action=index" class="text-white text-decoration-none d-block p-2">
                <i class="fas fa-building me-2"></i> Companies
            </a>
        </li>
        <?php endif; ?>
        
        <!-- Contacts - All roles -->
        <li class="<?= $currentPage == 'contacts' ? 'active' : '' ?>">
            <a href="index.php?page=contacts&action=index" class="text-white text-decoration-none d-block p-2">
                <i class="fas fa-address-book me-2"></i> Contacts
            </a>
        </li>
        
        <!-- Deals - All roles -->
        <li class="<?= $currentPage == 'deals' ? 'active' : '' ?>">
            <a href="index.php?page=deals&action=index" class="text-white text-decoration-none d-block p-2">
                <i class="fas fa-handshake me-2"></i> Deals
            </a>
        </li>
        
        <!-- Activities - All roles -->
        <li class="<?= $currentPage == 'activities' ? 'active' : '' ?>">
            <a href="index.php?page=activities&action=index" class="text-white text-decoration-none d-block p-2">
                <i class="fas fa-tasks me-2"></i> Activities
            </a>
        </li>
        
        <!-- Reports - All roles -->
        <li class="<?= $currentPage == 'reports' ? 'active' : '' ?>">
            <a href="index.php?page=reports" class="text-white text-decoration-none d-block p-2">
                <i class="fas fa-chart-bar me-2"></i> Reports
            </a>
        </li>
        
        <!-- User Management - Super Admin only -->
        <?php if ($auth->isSuperAdmin()): ?>
        <li class="<?= $currentPage == 'users' ? 'active' : '' ?>">
            <a href="index.php?page=users&action=index" class="text-white text-decoration-none d-block p-2">
                <i class="fas fa-users me-2"></i> User Management
            </a>
        </li>
        <?php endif; ?>
        
        <!-- Settings - Super Admin only -->
        <?php if ($auth->isSuperAdmin()): ?>
        <li class="<?= $currentPage == 'settings' ? 'active' : '' ?>">
            <a href="index.php?page=settings" class="text-white text-decoration-none d-block p-2">
                <i class="fas fa-cog me-2"></i> Settings
            </a>
        </li>
        <?php endif; ?>
    </ul>
    
    <div class="sidebar-footer p-3 mt-auto">
        <div class="d-grid gap-2">
            <a href="index.php?page=profile" class="btn btn-outline-light btn-sm">
                <i class="fas fa-user-circle me-2"></i> Profile
            </a>
            <a href="index.php?page=auth&action=logout" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
        <small class="text-muted d-block mt-2">© 2024 CRM System v1.0</small>
    </div>
</nav>