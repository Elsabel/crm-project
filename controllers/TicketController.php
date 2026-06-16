<?php
require_once 'models/Ticket.php';
require_once 'models/Company.php';
require_once 'models/Contact.php';
require_once 'models/User.php';

class TicketController {
    
    public function index() {
        $ticketModel = new Ticket();
        $auth = new Auth();
        
        if ($auth->isCompanyUser()) {
            $tickets = $ticketModel->getByCompany($auth->getCompanyId());
        } else {
            $tickets = $ticketModel->getAll();
        }
        
        $openCount = $ticketModel->getByStatus('open');
        $inProgressCount = $ticketModel->getByStatus('in_progress');
        $resolvedCount = $ticketModel->getByStatus('resolved');
        
        require_once 'views/layout/header.php';
        require_once 'views/tickets/index.php';
        require_once 'views/layout/footer.php';
    }
    
    public function create() {
        $companyModel = new Company();
        $contactModel = new Contact();
        $userModel = new User();
        
        $companies = $companyModel->getAll();
        $contacts = $contactModel->getAll();
        $users = $userModel->getAll();
        
        require_once 'views/layout/header.php';
        require_once 'views/tickets/create.php';
        require_once 'views/layout/footer.php';
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=tickets&action=create');
            exit;
        }
        
        // Debug: Print what we received
        error_log("POST data: " . print_r($_POST, true));
        
        $ticketModel = new Ticket();
        
        // Build data array
        $data = [
            'subject' => trim($_POST['subject'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'company_id' => $_POST['company_id'] ?? null,
            'priority' => $_POST['priority'] ?? 'medium',
            'category' => $_POST['category'] ?? 'general',
            'status' => 'open'
        ];
        
        // Optional fields
        if (!empty($_POST['contact_id'])) {
            $data['contact_id'] = $_POST['contact_id'];
        }
        if (!empty($_POST['assigned_to'])) {
            $data['assigned_to'] = $_POST['assigned_to'];
        }
        
        // Add user_id from session
        $data['user_id'] = $_SESSION['user_id'] ?? null;
        
        // Validate required fields
        if (empty($data['subject'])) {
            $_SESSION['error'] = 'Subject is required';
            header('Location: index.php?page=tickets&action=create');
            exit;
        }
        
        if (empty($data['company_id'])) {
            $_SESSION['error'] = 'Company is required';
            header('Location: index.php?page=tickets&action=create');
            exit;
        }
        
        // Try to create ticket
        $ticket = $ticketModel->create($data);
        
        if ($ticket && !empty($ticket['id'])) {
            $_SESSION['success'] = 'Ticket created successfully!';
            header('Location: index.php?page=tickets&action=show&id=' . $ticket['id']);
            exit;
        } else {
            $error = $ticketModel->getLastError() ?: 'Please check all fields and try again.';
            if (stripos($error, 'row-level security') !== false) {
                $error .= ' Configure SUPABASE_SERVICE_KEY with your Supabase service_role key, or add an insert policy for the tickets table.';
            }
            $_SESSION['error'] = 'Failed to create ticket. ' . $error;
            header('Location: index.php?page=tickets&action=create');
            exit;
        }
    }
    
    public function show($id) {
        $ticketModel = new Ticket();
        $ticket = $ticketModel->getById($id);
        
        if (!$ticket) {
            $_SESSION['error'] = 'Ticket not found';
            header('Location: index.php?page=tickets&action=index');
            exit;
        }
        
        $replies = $ticketModel->getReplies($id);
        
        require_once 'views/layout/header.php';
        require_once 'views/tickets/show.php';
        require_once 'views/layout/footer.php';
    }
    
    public function reply($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticketModel = new Ticket();
            
            $replyData = [
                'ticket_id' => $id,
                'user_id' => $_SESSION['user_id'],
                'message' => $_POST['message'] ?? '',
                'is_internal' => isset($_POST['is_internal']) ? true : false
            ];
            
            if (!empty(trim($replyData['message']))) {
                $ticketModel->addReply($replyData);
                
                // Update status if changed
                if (!empty($_POST['status'])) {
                    $ticketModel->update($id, ['status' => $_POST['status']]);
                }
                
                $_SESSION['success'] = 'Reply added successfully';
            } else {
                $_SESSION['error'] = 'Reply message cannot be empty';
            }
            
            header('Location: index.php?page=tickets&action=show&id=' . $id);
            exit;
        }
    }
    
    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticketModel = new Ticket();
            
            $data = ['status' => $_POST['status'] ?? 'open'];
            
            if (in_array($_POST['status'], ['resolved', 'closed'])) {
                $data['resolved_at'] = date('Y-m-d H:i:s');
            }
            
            $ticketModel->update($id, $data);
            $_SESSION['success'] = 'Ticket status updated';
            header('Location: index.php?page=tickets&action=show&id=' . $id);
            exit;
        }
    }
}
