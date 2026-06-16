<?php
require_once 'helpers/Auth.php';

class AuthController {
    
    public function loginForm() {
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?page=dashboard');
            exit;
        }
        
        require_once 'views/auth/login.php';
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Please enter email and password';
                header('Location: index.php?page=login');
                exit;
            }
            
            $auth = new Auth();
            $result = $auth->login($email, $password);
            
            if ($result['success']) {
                $_SESSION['success'] = 'Welcome back, ' . $result['user']['first_name'] . '!';
                header('Location: index.php?page=dashboard');
            } else {
                $_SESSION['error'] = $result['message'];
                header('Location: index.php?page=login');
            }
        }
    }
    
    public function logout() {
        $auth = new Auth();
        $auth->logout();
        header('Location: index.php?page=login');
    }
    
    public function profile() {
        $auth = new Auth();
        $user = $auth->user();
        
        if (!$user) {
            header('Location: index.php?page=login');
            exit;
        }
        
        require_once 'views/layout/header.php';
        require_once 'views/auth/profile.php';
        require_once 'views/layout/footer.php';
    }
    
    public function updateProfile() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=profile');
        exit;
    }
    
    $userModel = new User();
    
    // Sanitize input
    $data = [
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'email' => trim($_POST['email'] ?? '')
    ];
    
    // Validate
    if (empty($data['first_name'])) {
        $_SESSION['error'] = 'First name is required';
        header('Location: index.php?page=profile');
        exit;
    }
    
    if (empty($data['last_name'])) {
        $_SESSION['error'] = 'Last name is required';
        header('Location: index.php?page=profile');
        exit;
    }
    
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Valid email is required';
        header('Location: index.php?page=profile');
        exit;
    }
    
    // Handle password change
    if (!empty($_POST['new_password'])) {
        if ($_POST['new_password'] !== ($_POST['confirm_password'] ?? '')) {
            $_SESSION['error'] = 'Passwords do not match';
            header('Location: index.php?page=profile');
            exit;
        }
        
        if (strlen($_POST['new_password']) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters';
            header('Location: index.php?page=profile');
            exit;
        }
        
        // Add password to update data (hashing done in model)
        $data['password'] = $_POST['new_password'];
    }
    
    // Update user in database
    $userId = $_SESSION['user_id'];
    $result = $userModel->update($userId, $data);
    
    if ($result) {
        // Update session
        $_SESSION['user_name'] = $data['first_name'] . ' ' . $data['last_name'];
        $_SESSION['user_email'] = $data['email'];
        
        $_SESSION['success'] = 'Profile updated successfully!';
    } else {
        $_SESSION['error'] = 'Failed to update profile. Please try again.';
    }
    
    header('Location: index.php?page=profile');
    exit;
}
}