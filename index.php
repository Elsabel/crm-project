<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Include configuration
if (file_exists('config/database.php')) {
    require_once 'config/database.php';
} else {
    die('Error: config/database.php not found. Please check your installation.');
}

// Include Auth helper
if (file_exists('helpers/Auth.php')) {
    require_once 'helpers/Auth.php';
} else {
    die('Error: helpers/Auth.php not found. Please check your installation.');
}

// Include shared functions
if (file_exists('helpers/functions.php')) {
    require_once 'helpers/functions.php';
}

// Initialize Auth
$auth = new Auth();

// Get requested page and action
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Public pages that don't require login
$publicPages = ['login', 'auth'];

// AJAX/API pages
$apiPages = ['notifications'];

// Redirect to login if not authenticated (skip for public and API pages)
if (!$auth->check() && !in_array($page, $publicPages) && !in_array($page, $apiPages)) {
    $_SESSION['error'] = 'Please login to access the CRM';
    header('Location: index.php?page=login');
    exit;
}

// Check permissions for the requested page
if ($auth->check() && !in_array($page, $publicPages) && !in_array($page, $apiPages)) {
    if (!$auth->canAccess($page)) {
        $_SESSION['error'] = 'You do not have permission to access this page';
        header('Location: index.php?page=dashboard');
        exit;
    }
}

// Route to appropriate controller
try {
    switch($page) {
        // ==================== AUTHENTICATION ====================
        case 'login':
            require_once 'controllers/AuthController.php';
            $controller = new AuthController();
            $controller->loginForm();
            break;
            
        case 'auth':
            require_once 'controllers/AuthController.php';
            $controller = new AuthController();
            if ($action == 'login') {
                $controller->login();
            } elseif ($action == 'logout') {
                $controller->logout();
            }
            break;
            
        case 'profile':
            require_once 'controllers/AuthController.php';
            $controller = new AuthController();
            if ($action == 'update') {
                $controller->updateProfile();
            } else {
                $controller->profile();
            }
            break;

        // ==================== DASHBOARD ====================
        case 'dashboard':
            require_once 'controllers/DashboardController.php';
            $controller = new DashboardController();
            $controller->index();
            break;

        // ==================== COMPANIES ====================
        case 'companies':
            require_once 'controllers/CompanyController.php';
            $controller = new CompanyController();
            routeAction($controller, $action, $id);
            break;

        // ==================== CONTACTS ====================
        case 'contacts':
            require_once 'controllers/ContactController.php';
            $controller = new ContactController();
            routeAction($controller, $action, $id);
            break;

        // ==================== DEALS ====================
        case 'deals':
            require_once 'controllers/DealController.php';
            $controller = new DealController();
            routeAction($controller, $action, $id);
            break;

        // ==================== ACTIVITIES ====================
        case 'activities':
            require_once 'controllers/ActivityController.php';
            $controller = new ActivityController();
            routeAction($controller, $action, $id);
            break;

        // ==================== TICKETS ====================
        case 'tickets':
            require_once 'models/Ticket.php';
            require_once 'models/Company.php';
            require_once 'models/Contact.php';
            require_once 'models/User.php';
            require_once 'helpers/NotificationHelper.php';
            require_once 'controllers/TicketController.php';
            $controller = new TicketController();
            
            switch($action) {
                case 'index':
                    $controller->index();
                    break;
                case 'create':
                    $controller->create();
                    break;
                case 'store':
                    $controller->store();
                    break;
                case 'show':
                    $controller->show($id);
                    break;
                case 'reply':
                    $controller->reply($id);
                    break;
                case 'update_status':
                    $controller->updateStatus($id);
                    break;
                default:
                    $controller->index();
            }
            break;

        // ==================== REPORTS ====================
        case 'reports':
            // Load required models
            if (file_exists('models/Company.php')) require_once 'models/Company.php';
            if (file_exists('models/Contact.php')) require_once 'models/Contact.php';
            if (file_exists('models/Deal.php')) require_once 'models/Deal.php';
            if (file_exists('models/Activity.php')) require_once 'models/Activity.php';
            
            require_once 'controllers/ReportController.php';
            $controller = new ReportController();
            
            switch($action) {
                case 'sales_pipeline':
                    $controller->salesPipeline();
                    break;
                case 'company_report':
                    $controller->companyReport();
                    break;
                case 'deal_report':
                    $controller->dealReport();
                    break;
                case 'activity_report':
                    $controller->activityReport();
                    break;
                default:
                    $controller->index();
            }
            break;

        // ==================== NOTIFICATIONS (AJAX/API) ====================
        case 'notifications':
            require_once 'models/Notification.php';
            require_once 'controllers/NotificationController.php';
            $controller = new NotificationController();
            
            switch($action) {
                case 'get':
                    $controller->getNotifications();
                    break;
                case 'mark_read':
                    $controller->markAsRead($id);
                    break;
                case 'mark_all_read':
                    $controller->markAllAsRead();
                    break;
                case 'view_all':
                    $controller->viewAll();
                    break;
                default:
                    $controller->viewAll();
            }
            break;

        // ==================== USER MANAGEMENT ====================
        case 'users':
            require_once 'controllers/UserController.php';
            $controller = new UserController();
            routeAction($controller, $action, $id);
            break;

        // ==================== SETTINGS ====================
        case 'settings':
            require_once 'views/layout/header.php';
            echo '<div class="modern-card"><div class="modern-card-body text-center py-5">';
            echo '<i class="fas fa-cog fa-3x text-muted mb-3"></i>';
            echo '<h3>Settings</h3>';
            echo '<p class="text-muted">Settings page coming soon.</p>';
            echo '</div></div>';
            require_once 'views/layout/footer.php';
            break;

        // ==================== DEFAULT - DASHBOARD ====================
        default:
            require_once 'controllers/DashboardController.php';
            $controller = new DashboardController();
            $controller->index();
    }
} catch (Exception $e) {
    // Display error in development
    echo '<div style="padding: 40px; text-align: center;">';
    echo '<h2 style="color: #ff4757;">⚠️ Application Error</h2>';
    echo '<p style="color: #676879;">' . $e->getMessage() . '</p>';
    echo '<p style="color: #999; font-size: 12px;">File: ' . $e->getFile() . ' on line ' . $e->getLine() . '</p>';
    echo '<details style="margin-top: 20px; text-align: left;">';
    echo '<summary>Stack Trace</summary>';
    echo '<pre style="font-size: 11px; background: #f5f5f5; padding: 15px; border-radius: 5px;">' . $e->getTraceAsString() . '</pre>';
    echo '</details>';
    echo '<a href="index.php?page=dashboard" class="btn-modern btn-modern-primary" style="margin-top: 20px; display: inline-block; text-decoration: none;">Go to Dashboard</a>';
    echo '</div>';
} catch (Error $e) {
    // Display fatal errors
    echo '<div style="padding: 40px; text-align: center;">';
    echo '<h2 style="color: #ff4757;">⚠️ System Error</h2>';
    echo '<p style="color: #676879;">' . $e->getMessage() . '</p>';
    echo '<p style="color: #999; font-size: 12px;">File: ' . $e->getFile() . ' on line ' . $e->getLine() . '</p>';
    echo '<details style="margin-top: 20px; text-align: left;">';
    echo '<summary>Stack Trace</summary>';
    echo '<pre style="font-size: 11px; background: #f5f5f5; padding: 15px; border-radius: 5px;">' . $e->getTraceAsString() . '</pre>';
    echo '</details>';
    echo '<a href="index.php?page=dashboard" class="btn-modern btn-modern-primary" style="margin-top: 20px; display: inline-block; text-decoration: none;">Go to Dashboard</a>';
    echo '</div>';
}

/**
 * Helper function for standard CRUD routing
 */
function routeAction($controller, $action, $id) {
    switch($action) {
        case 'index':
            $controller->index();
            break;
        case 'create':
            $controller->create();
            break;
        case 'store':
            $controller->store();
            break;
        case 'edit':
            $controller->edit($id);
            break;
        case 'update':
            $controller->update($id);
            break;
        case 'delete':
            $controller->delete($id);
            break;
        case 'show':
            $controller->show($id);
            break;
        default:
            $controller->index();
    }
}