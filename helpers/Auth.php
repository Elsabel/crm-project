<?php
class Auth {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function login($email, $password) {
        $result = $this->db->query("users?email=eq.{$email}&select=*&status=eq.active");
        
        if (empty($result['data'])) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }
        
        $user = $result['data'][0];
        
        if (password_verify($password, $user['password'])) {
            $this->db->query("users?id=eq.{$user['id']}", 'PATCH', [
                'last_login' => date('Y-m-d H:i:s')
            ]);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['company_id'] = $user['company_id'];
            
            return ['success' => true, 'user' => $user];
        }
        
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    public function logout() {
        $_SESSION = array();
        session_destroy();
        return true;
    }
    
    public function check() {
        return isset($_SESSION['user_id']);
    }
    
    public function user() {
        if (!$this->check()) {
            return null;
        }
        
        $result = $this->db->query("users?id=eq.{$_SESSION['user_id']}&select=*");
        return $result['data'][0] ?? null;
    }
    
    public function hasRole($roles) {
        if (!$this->check()) {
            return false;
        }
        
        if (is_string($roles)) {
            return $_SESSION['user_role'] === $roles;
        }
        
        return in_array($_SESSION['user_role'], $roles);
    }
    
    public function isSuperAdmin() {
        return $this->hasRole('superadmin');
    }
    
    public function isAdmin() {
        return $this->hasRole(['superadmin', 'admin']);
    }
    
    public function isCompanyUser() {
        return $this->hasRole('company');
    }
    
    public function canAccess($page) {
        $role = $_SESSION['user_role'] ?? 'guest';
        
        // Define permissions for each role
       $permissions = [
    'superadmin' => [
        'dashboard', 'companies', 'contacts', 'deals', 
        'activities', 'tickets', 'reports', 'notifications',
        'users', 'profile', 'settings'
    ],
    'admin' => [
        'dashboard', 'companies', 'contacts', 'deals', 
        'activities', 'tickets', 'reports', 'notifications',
        'profile'
    ],
    'company' => [
        'dashboard', 'contacts', 'deals', 
        'activities', 'tickets', 'reports', 'notifications',
        'profile'
    ]
];
        // Public pages accessible without login
        $publicPages = ['login', 'auth'];
        
        if (in_array($page, $publicPages)) {
            return true;
        }
        
        // Check if role has permission
        return in_array($page, $permissions[$role] ?? []);
    }
    
    public function getCompanyId() {
        return $_SESSION['company_id'] ?? null;
    }
}