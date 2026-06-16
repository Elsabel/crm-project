<?php
require_once 'models/Company.php';
require_once 'models/Contact.php';
require_once 'models/Deal.php';

class CompanyController {
    
    public function index() {
        $companyModel = new Company();
        $companies = $companyModel->getAll();
        
        require_once 'views/layout/header.php';
        require_once 'views/companies/index.php';
        require_once 'views/layout/footer.php';
    }
    
    public function create() {
        require_once 'views/layout/header.php';
        require_once 'views/companies/create.php';
        require_once 'views/layout/footer.php';
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $companyModel = new Company();
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'website' => $_POST['website'],
                'industry' => $_POST['industry'],
                'status' => $_POST['status'] ?? 'active'
            ];
            
            $companyModel->create($data);
            header('Location: index.php?page=companies&action=index');
        }
    }
    
    public function edit($id) {
        $companyModel = new Company();
        $company = $companyModel->getById($id);
        
        require_once 'views/layout/header.php';
        require_once 'views/companies/edit.php';
        require_once 'views/layout/footer.php';
    }
    
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $companyModel = new Company();
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'website' => $_POST['website'],
                'industry' => $_POST['industry'],
                'status' => $_POST['status']
            ];
            
            $companyModel->update($id, $data);
            header('Location: index.php?page=companies&action=index');
        }
    }
    
    public function delete($id) {
        $companyModel = new Company();
        $companyModel->delete($id);
        header('Location: index.php?page=companies&action=index');
    }
    
    public function show($id) {
        $companyModel = new Company();
        $contactModel = new Contact();
        $dealModel = new Deal();
        
        $company = $companyModel->getById($id);
        $contacts = $contactModel->getByCompany($id);
        $deals = $dealModel->getAll(); // Filter by company in production
        
        require_once 'views/layout/header.php';
        require_once 'views/companies/show.php';
        require_once 'views/layout/footer.php';
    }
}