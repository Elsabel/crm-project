<?php
require_once 'models/Activity.php';
require_once 'models/Company.php';
require_once 'models/Contact.php';
require_once 'models/Deal.php';

class ActivityController {
    
    public function index() {
        $activityModel = new Activity();
        $activities = $activityModel->getAll();
        
        require_once 'views/layout/header.php';
        require_once 'views/activities/index.php';
        require_once 'views/layout/footer.php';
    }
    
    public function create() {
        $companyModel = new Company();
        $contactModel = new Contact();
        $dealModel = new Deal();
        
        $companies = $companyModel->getAll();
        $contacts = $contactModel->getAll();
        $deals = $dealModel->getAll();
        
        require_once 'views/layout/header.php';
        require_once 'views/activities/create.php';
        require_once 'views/layout/footer.php';
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $activityModel = new Activity();
            $data = [
                'company_id' => $_POST['company_id'],
                'contact_id' => $_POST['contact_id'] ?? null,
                'deal_id' => $_POST['deal_id'] ?? null,
                'type' => $_POST['type'],
                'subject' => $_POST['subject'],
                'description' => $_POST['description'] ?? '',
                'due_date' => $_POST['due_date'],
                'status' => $_POST['status'] ?? 'pending'
            ];
            
            $activityModel->create($data);
            header('Location: index.php?page=activities&action=index');
        }
    }
    
    public function edit($id) {
        $activityModel = new Activity();
        $companyModel = new Company();
        $contactModel = new Contact();
        $dealModel = new Deal();
        
        $activity = $activityModel->getById($id);
        $companies = $companyModel->getAll();
        $contacts = $contactModel->getAll();
        $deals = $dealModel->getAll();
        
        require_once 'views/layout/header.php';
        require_once 'views/activities/edit.php';
        require_once 'views/layout/footer.php';
    }
    
    public function update($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $activityModel = new Activity();
        
        $data = [
            'company_id' => $_POST['company_id'],
            'contact_id' => !empty($_POST['contact_id']) ? $_POST['contact_id'] : null,
            'deal_id' => !empty($_POST['deal_id']) ? $_POST['deal_id'] : null,
            'type' => $_POST['type'],
            'subject' => $_POST['subject'],
            'description' => $_POST['description'] ?? '',
            'due_date' => $_POST['due_date'],
            'status' => $_POST['status']
        ];
        
        $result = $activityModel->update($id, $data);
        
        if ($result) {
            $_SESSION['success'] = 'Activity updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update activity';
        }
        
        header('Location: index.php?page=activities&action=index');
        exit;
    }
    
    // If not POST, show edit form
    $this->edit($id);
}
    
    public function delete($id) {
        $activityModel = new Activity();
        $activityModel->delete($id);
        header('Location: index.php?page=activities&action=index');
    }
}
