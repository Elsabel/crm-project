<?php
class Note {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAll() {
        $result = $this->db->query('notes?select=*&order=created_at.desc');
        return $result['data'] ?? [];
    }
    
    public function getById($id) {
        $result = $this->db->query("notes?id=eq.{$id}&select=*");
        return $result['data'][0] ?? null;
    }
    
    public function getByCompany($companyId) {
        $result = $this->db->query("notes?company_id=eq.{$companyId}&select=*&order=created_at.desc");
        return $result['data'] ?? [];
    }
    
    public function getByContact($contactId) {
        $result = $this->db->query("notes?contact_id=eq.{$contactId}&select=*&order=created_at.desc");
        return $result['data'] ?? [];
    }
    
    public function getByDeal($dealId) {
        $result = $this->db->query("notes?deal_id=eq.{$dealId}&select=*&order=created_at.desc");
        return $result['data'] ?? [];
    }
    
    public function create($data) {
        $result = $this->db->query('notes', 'POST', $data);
        return $result['data'][0] ?? null;
    }
    
    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->db->query("notes?id=eq.{$id}", 'PATCH', $data);
        return $result['data'][0] ?? null;
    }
    
    public function delete($id) {
        $result = $this->db->query("notes?id=eq.{$id}", 'DELETE');
        return $result['status'] === 204;
    }
    
    public function getCount() {
        $result = $this->db->query('notes?select=count');
        return $result['data'][0]['count'] ?? 0;
    }
}