<?php
require_once 'models/Company.php';
require_once 'models/Contact.php';
require_once 'models/Deal.php';
require_once 'models/Activity.php';

class DashboardController {
    
    public function index() {
        $companyModel = new Company();
        $contactModel = new Contact();
        $dealModel = new Deal();
        $activityModel = new Activity();
        
        $data = [
            'totalCompanies' => $companyModel->getCount(),
            'totalContacts' => $contactModel->getCount(),
            'totalDeals' => $dealModel->getCount(),
            'totalDealValue' => $dealModel->getTotalValue(),
            'upcomingActivities' => $activityModel->getUpcoming(),
            'recentDeals' => array_slice($dealModel->getAll(), 0, 5)
        ];
        
        require_once 'views/layout/header.php';
        require_once 'views/dashboard/index.php';
        require_once 'views/layout/footer.php';
    }
}