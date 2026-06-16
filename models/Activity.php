<?php
class Activity {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAll() {
        $result = $this->db->query('activities?select=*,companies(name),contacts(first_name,last_name)&order=due_date.desc');
        return $result['data'] ?? [];
    }
    
    public function getById($id) {
        $result = $this->db->query("activities?id=eq.{$id}&select=*,companies(name),contacts(first_name,last_name)");
        return $result['data'][0] ?? null;
    }
    
    public function getByCompany($companyId) {
        $result = $this->db->query("activities?company_id=eq.{$companyId}&select=*&order=due_date.desc");
        return $result['data'] ?? [];
    }
    
    public function getByContact($contactId) {
        $result = $this->db->query("activities?contact_id=eq.{$contactId}&select=*&order=due_date.desc");
        return $result['data'] ?? [];
    }
    
    public function getByDeal($dealId) {
        $result = $this->db->query("activities?deal_id=eq.{$dealId}&select=*&order=due_date.desc");
        return $result['data'] ?? [];
    }
    
    public function create($data) {
        $result = $this->db->query('activities', 'POST', $data);
        return $result['data'][0] ?? null;
    }
    
    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->db->query("activities?id=eq.{$id}", 'PATCH', $data);
        return $result['data'][0] ?? null;
    }
    
    public function delete($id) {
        $result = $this->db->query("activities?id=eq.{$id}", 'DELETE');
        return $result['status'] === 204;
    }
    
    public function getCount() {
        $result = $this->db->query('activities?select=count');
        return $result['data'][0]['count'] ?? 0;
    }
    
    public function getUpcoming($limit = 5) {
        $result = $this->db->query("activities?due_date=gte." . date('Y-m-d') . "&order=due_date.asc&limit={$limit}");
        return $result['data'] ?? [];
    }
}