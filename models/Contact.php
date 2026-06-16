<?php
class Contact {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAll() {
        $result = $this->db->query('contacts?select=*,companies(name)&order=created_at.desc');
        return $result['data'] ?? [];
    }
    
    public function getById($id) {
        $result = $this->db->query("contacts?id=eq.{$id}&select=*,companies(name)");
        return $result['data'][0] ?? null;
    }
    
    public function getByCompany($companyId) {
        $result = $this->db->query("contacts?company_id=eq.{$companyId}&select=*&order=created_at.desc");
        return $result['data'] ?? [];
    }
    
    public function create($data) {
        $result = $this->db->query('contacts', 'POST', $data);
        return $result['data'][0] ?? null;
    }
    
    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->db->query("contacts?id=eq.{$id}", 'PATCH', $data);
        return $result['data'][0] ?? null;
    }
    
    public function delete($id) {
        $result = $this->db->query("contacts?id=eq.{$id}", 'DELETE');
        return $result['status'] === 204;
    }
    
    public function getCount() {
        $result = $this->db->query('contacts?select=count');
        return $result['data'][0]['count'] ?? 0;
    }
}