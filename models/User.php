<?php
class User {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAll() {
        $result = $this->db->query('users?select=*,companies(name)&order=created_at.desc');
        return $result['data'] ?? [];
    }
    
    public function getById($id) {
        $result = $this->db->query("users?id=eq.{$id}&select=*,companies(name)");
        return $result['data'][0] ?? null;
    }
    
    public function getByCompany($companyId) {
        $result = $this->db->query("users?company_id=eq.{$companyId}&select=*");
        return $result['data'] ?? [];
    }
    
    public function create($data) {
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $result = $this->db->query('users', 'POST', $data);
        return $result['data'][0] ?? null;
    }
    
    public function update($id, $data) {
    // Handle password - only hash if provided and not already hashed
    if (isset($data['password']) && !empty($data['password'])) {
        // Check if password is already hashed (bcrypt starts with $2y$)
        if (strlen($data['password']) < 60 || strpos($data['password'], '$2y$') !== 0) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
    } else {
        // Don't update password if not provided
        unset($data['password']);
    }
    
    // Add timestamp
    $data['updated_at'] = date('Y-m-d H:i:s');
    
    // Perform update
    $result = $this->db->query("users?id=eq.{$id}", 'PATCH', $data);
    
    // Return updated user or null
    if ($result['status'] >= 200 && $result['status'] < 300) {
        return $result['data'][0] ?? true;
    }
    
    return null;
}
    
    public function delete($id) {
        // Prevent deleting self
        if ($id == $_SESSION['user_id']) {
            return false;
        }
        
        $result = $this->db->query("users?id=eq.{$id}", 'DELETE');
        return $result['status'] === 204;
    }
    
    public function getCount() {
        $result = $this->db->query('users?select=count');
        return $result['data'][0]['count'] ?? 0;
    }
}