<?php
class Ticket {
    private $db;
    private $lastError = null;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAll() {
        $result = $this->db->query('tickets?select=*,companies(name),contacts(first_name,last_name),users!tickets_assigned_to_fkey(first_name,last_name)&order=created_at.desc');
        return $result['data'] ?? [];
    }
    
    public function getById($id) {
        $result = $this->db->query("tickets?id=eq.{$id}&select=*,companies(name),contacts(first_name,last_name,email),users!tickets_assigned_to_fkey(first_name,last_name)");
        return $result['data'][0] ?? null;
    }
    
    public function getByCompany($companyId) {
        $result = $this->db->query("tickets?company_id=eq.{$companyId}&select=*,companies(name),contacts(first_name,last_name)&order=created_at.desc");
        return $result['data'] ?? [];
    }
    
    public function getByUser($userId) {
        $result = $this->db->query("tickets?user_id=eq.{$userId}&select=*,companies(name),contacts(first_name,last_name)&order=created_at.desc");
        return $result['data'] ?? [];
    }
    
    public function create($data) {
        $this->lastError = null;

        // Remove any null values that might cause issues
        $cleanData = [];
        foreach ($data as $key => $value) {
            if ($value !== null && $value !== '') {
                $cleanData[$key] = $value;
            }
        }
        
        // Make sure required fields exist
        if (empty($cleanData['subject']) || empty($cleanData['company_id'])) {
            $this->lastError = 'Ticket creation failed: missing required fields';
            error_log($this->lastError);
            return null;
        }
        
        $result = $this->db->query('tickets', 'POST', $cleanData, [], true);
        
        // Log the result for debugging
        error_log("Ticket creation result: " . json_encode($result));

        if (($result['status'] ?? 0) < 200 || ($result['status'] ?? 0) >= 300) {
            $message = $result['data']['message'] ?? $result['error'] ?? 'Unknown database error';
            $this->lastError = $message;
            return null;
        }
        
        return $result['data'][0] ?? null;
    }
    
    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->db->query("tickets?id=eq.{$id}", 'PATCH', $data, [], true);
        return $result['data'][0] ?? null;
    }
    
    public function delete($id) {
        $result = $this->db->query("tickets?id=eq.{$id}", 'DELETE', null, [], true);
        return $result['status'] === 204;
    }
    
    public function getCount() {
        $result = $this->db->query('tickets?select=count');
        return $result['data'][0]['count'] ?? 0;
    }
    
    public function getByStatus($status) {
        $result = $this->db->query("tickets?status=eq.{$status}&select=count");
        return $result['data'][0]['count'] ?? 0;
    }
    
    public function getReplies($ticketId) {
        $result = $this->db->query("ticket_replies?ticket_id=eq.{$ticketId}&select=*,users(first_name,last_name)&order=created_at.asc");
        return $result['data'] ?? [];
    }
    
    public function addReply($data) {
        $result = $this->db->query('ticket_replies', 'POST', $data, [], true);
        return $result['data'][0] ?? null;
    }

    public function getLastError() {
        return $this->lastError;
    }
}
