<?php
require_once 'models/User.php';
require_once 'models/Company.php';
require_once 'helpers/Auth.php';

class UserController {
    
    public function __construct() {
        $auth = new Auth();
        if (!$auth->isAdmin()) {
            $_SESSION['error'] = 'Access denied';
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
    
    public function index() {
        $userModel = new User();
        $users = $userModel->getAll();
        
        require_once 'views/layout/header.php';
        require_once 'views/users/index.php';
        require_once 'views/layout/footer.php';
    }
    
    public function create() {
        $companyModel = new Company();
        $companies = $companyModel->getAll();
        
        require_once 'views/layout/header.php';
        require_once 'views/users/create.php';
        require_once 'views/layout/footer.php';
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            
            $data = [
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'role' => $_POST['role'],
                'company_id' => $_POST['company_id'] ?? null,
                'status' => $_POST['status'] ?? 'active'
            ];
            
            if ($data['role'] === 'company' && empty($data['company_id'])) {
                $_SESSION['error'] = 'Company users must be assigned to a company';
                header('Location: index.php?page=users&action=create');
                exit;
            }
            
            $userModel->create($data);
            $_SESSION['success'] = 'User created successfully';
            header('Location: index.php?page=users&action=index');
        }
    }
    
    public function edit($id) {
        $userModel = new User();
        $companyModel = new Company();
        
        $user = $userModel->getById($id);
        $companies = $companyModel->getAll();
        
        require_once 'views/layout/header.php';
        require_once 'views/users/edit.php';
        require_once 'views/layout/footer.php';
    }
    
    public function update($id) {
    // Handle quick status toggle via GET
    if (isset($_GET['quick_status'])) {
        $userModel = new User();
        $data = ['status' => $_GET['quick_status']];
        $userModel->update($id, $data);
        $_SESSION['success'] = 'User status updated successfully';
        header('Location: index.php?page=users&action=index');
        exit;
    }
    
    // Handle form submission via POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userModel = new User();
        
        $data = [
            'email' => $_POST['email'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'role' => $_POST['role'],
            'company_id' => !empty($_POST['company_id']) ? $_POST['company_id'] : null,
            'status' => $_POST['status'] ?? 'active'
        ];
        
        // Update password only if provided
        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }
        
        if ($data['role'] === 'company' && empty($data['company_id'])) {
            $_SESSION['error'] = 'Company users must be assigned to a company';
            header('Location: index.php?page=users&action=edit&id=' . $id);
            exit;
        }
        
        $result = $userModel->update($id, $data);
        
        if ($result) {
            $_SESSION['success'] = 'User updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update user';
        }
        
        header('Location: index.php?page=users&action=index');
        exit;
    }
}
    
    public function delete($id) {
        $userModel = new User();
        
        if ($userModel->delete($id)) {
            $_SESSION['success'] = 'User deleted successfully';
        } else {
            $_SESSION['error'] = 'Cannot delete user';
        }
        
        header('Location: index.php?page=users&action=index');
    }
}