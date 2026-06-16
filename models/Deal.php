<?php
class Deal {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAll() {
        $result = $this->db->query('deals?select=*,companies(name),contacts(first_name,last_name)&order=created_at.desc');
        return $result['data'] ?? [];
    }
    
    public function getById($id) {
        $result = $this->db->query("deals?id=eq.{$id}&select=*,companies(name),contacts(first_name,last_name)");
        return $result['data'][0] ?? null;
    }
    
    public function getByCompany($companyId) {
        $result = $this->db->query("deals?company_id=eq.{$companyId}&select=*,companies(name),contacts(first_name,last_name)&order=created_at.desc");
        return $result['data'] ?? [];
    }
    
    public function create($data) {
        $result = $this->db->query('deals', 'POST', $data);
        return $result['data'][0] ?? null;
    }
    
    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->db->query("deals?id=eq.{$id}", 'PATCH', $data);
        return $result['data'][0] ?? null;
    }
    
    public function delete($id) {
        $result = $this->db->query("deals?id=eq.{$id}", 'DELETE');
        return $result['status'] === 204;
    }
    
    public function getCount() {
        $result = $this->db->query('deals?select=count');
        return $result['data'][0]['count'] ?? 0;
    }
    
    public function getTotalValue() {
        $result = $this->db->query('deals?select=value.sum()');
        return $result['data'][0]['sum'] ?? 0;
    }
}