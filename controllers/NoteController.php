<?php
require_once 'models/Note.php';

class NoteController {
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $noteModel = new Note();
            $data = [
                'company_id' => $_POST['company_id'] ?? null,
                'contact_id' => $_POST['contact_id'] ?? null,
                'deal_id' => $_POST['deal_id'] ?? null,
                'content' => $_POST['content']
            ];
            
            $noteModel->create($data);
            
            // Redirect back to the referring page
            $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
            header('Location: ' . $referer);
        }
    }
    
    public function delete($id) {
        $noteModel = new Note();
        $noteModel->delete($id);
        
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        header('Location: ' . $referer);
    }
}