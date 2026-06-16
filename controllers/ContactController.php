<?php
require_once 'models/Contact.php';
require_once 'models/Company.php';

class ContactController {
    
    public function index() {
        $contactModel = new Contact();
        $contacts = $contactModel->getAll();
        
        require_once 'views/layout/header.php';
        require_once 'views/contacts/index.php';
        require_once 'views/layout/footer.php';
    }
    
    public function create() {
        $companyModel = new Company();
        $companies = $companyModel->getAll();
        
        require_once 'views/layout/header.php';
        require_once 'views/contacts/create.php';
        require_once 'views/layout/footer.php';
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactModel = new Contact();
            $data = [
                'company_id' => $_POST['company_id'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'position' => $_POST['position'],
                'department' => $_POST['department'],
                'status' => $_POST['status'] ?? 'active',
                'notes' => $_POST['notes'] ?? ''
            ];
            
            $contactModel->create($data);
            header('Location: index.php?page=contacts&action=index');
        }
    }
    
    public function edit($id) {
        $contactModel = new Contact();
        $companyModel = new Company();
        
        $contact = $contactModel->getById($id);
        $companies = $companyModel->getAll();
        
        require_once 'views/layout/header.php';
        require_once 'views/contacts/edit.php';
        require_once 'views/layout/footer.php';
    }
    
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactModel = new Contact();
            $data = [
                'company_id' => $_POST['company_id'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'position' => $_POST['position'],
                'department' => $_POST['department'],
                'status' => $_POST['status'],
                'notes' => $_POST['notes']
            ];
            
            $contactModel->update($id, $data);
            header('Location: index.php?page=contacts&action=index');
        }
    }
    
    public function delete($id) {
        $contactModel = new Contact();
        $contactModel->delete($id);
        header('Location: index.php?page=contacts&action=index');
    }
    
    public function show($id) {
    $contactModel = new Contact();
    $contact = $contactModel->getById($id);
    
    if (!$contact) {
        $_SESSION['error'] = 'Contact not found';
        header('Location: index.php?page=contacts&action=index');
        exit;
    }
    
    require_once 'views/layout/header.php';
    require_once 'views/contacts/show.php';
    require_once 'views/layout/footer.php';
}
}