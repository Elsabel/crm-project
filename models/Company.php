<?php
class Company {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAll() {
        $result = $this->db->query('companies?select=*&order=created_at.desc');
        return $result['data'] ?? [];
    }
    
    public function getById($id) {
        $result = $this->db->query("companies?id=eq.{$id}&select=*");
        return $result['data'][0] ?? null;
    }
    
    public function create($data) {
        $result = $this->db->query('companies', 'POST', $data);
        return $result['data'][0] ?? null;
    }
    
    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->db->query("companies?id=eq.{$id}", 'PATCH', $data);
        return $result['data'][0] ?? null;
    }
    
    public function delete($id) {
        $result = $this->db->query("companies?id=eq.{$id}", 'DELETE');
        return $result['status'] === 204;
    }
    
    public function getCount() {
        $result = $this->db->query('companies?select=count');
        return $result['data'][0]['count'] ?? 0;
    }
}