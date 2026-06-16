<?php
require_once 'models/Deal.php';
require_once 'models/Company.php';
require_once 'models/Contact.php';

class DealController {
    
    public function index() {
        $dealModel = new Deal();
        $deals = $dealModel->getAll();
        
        require_once 'views/layout/header.php';
        require_once 'views/deals/index.php';
        require_once 'views/layout/footer.php';
    }
    
    public function create() {
        $companyModel = new Company();
        $contactModel = new Contact();
        
        $companies = $companyModel->getAll();
        $contacts = $contactModel->getAll();
        
        require_once 'views/layout/header.php';
        require_once 'views/deals/create.php';
        require_once 'views/layout/footer.php';
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dealModel = new Deal();
            $data = [
                'company_id' => $_POST['company_id'],
                'contact_id' => $_POST['contact_id'] ?? null,
                'title' => $_POST['title'],
                'value' => $_POST['value'],
                'stage' => $_POST['stage'],
                'probability' => $_POST['probability'],
                'expected_close_date' => $_POST['expected_close_date'],
                'status' => $_POST['status'] ?? 'open',
                'notes' => $_POST['notes'] ?? ''
            ];
            
            $dealModel->create($data);
            header('Location: index.php?page=deals&action=index');
        }
    }
    
    public function edit($id) {
        $dealModel = new Deal();
        $companyModel = new Company();
        $contactModel = new Contact();
        
        $deal = $dealModel->getById($id);
        $companies = $companyModel->getAll();
        $contacts = $contactModel->getAll();
        
        require_once 'views/layout/header.php';
        require_once 'views/deals/edit.php';
        require_once 'views/layout/footer.php';
    }
    
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dealModel = new Deal();
            $data = [
                'company_id' => $_POST['company_id'],
                'contact_id' => $_POST['contact_id'],
                'title' => $_POST['title'],
                'value' => $_POST['value'],
                'stage' => $_POST['stage'],
                'probability' => $_POST['probability'],
                'expected_close_date' => $_POST['expected_close_date'],
                'status' => $_POST['status'],
                'notes' => $_POST['notes']
            ];
            
            $dealModel->update($id, $data);
            header('Location: index.php?page=deals&action=index');
        }
    }
    
    public function delete($id) {
        $dealModel = new Deal();
        $dealModel->delete($id);
        header('Location: index.php?page=deals&action=index');
    }
    
    public function show($id) {
        $dealModel = new Deal();
        $deal = $dealModel->getById($id);
        
        require_once 'views/layout/header.php';
        require_once 'views/deals/show.php';
        require_once 'views/layout/footer.php';
    }
}