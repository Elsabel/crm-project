<?php
// Supabase Configuration
define('SUPABASE_URL', 'https://wntpbzkafrxnczeoekms.supabase.co');
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6IndudHBiemthZnJ4bmN6ZW9la21zIiwicm9sZSI6ImFub24iLCJpYXQiOjE3ODE1Mjc0MDcsImV4cCI6MjA5NzEwMzQwN30.Pj4t9BMwygDw-cG4ggwPJYgArqwz-xE9u4KsJhc7lt4');
define('SUPABASE_SERVICE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6IndudHBiemthZnJ4bmN6ZW9la21zIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc4MTUyNzQwNywiZXhwIjoyMDk3MTAzNDA3fQ.1o18LSZRpTUsUZFxoQFJa9u1oCPIG80rCAd5zMk8wpM'); // Add this!

class Database {
    private $url;
    private $headers;
    private $serviceHeaders;
    
    public function __construct() {
        $this->url = SUPABASE_URL;
        
        // Anon key headers (for read operations)
        $this->headers = [
            'apikey: ' . SUPABASE_KEY,
            'Authorization: Bearer ' . SUPABASE_KEY,
            'Content-Type: application/json',
            'Prefer: return=representation'
        ];
        
        // Service role headers (for write operations - bypasses RLS)
        $this->serviceHeaders = [
            'apikey: ' . SUPABASE_KEY,
            'Authorization: Bearer ' . SUPABASE_SERVICE_KEY,
            'Content-Type: application/json',
            'Prefer: return=representation'
        ];
    }
    
    public function query($endpoint, $method = 'GET', $data = null, $params = []) {
        $url = $this->url . '/rest/v1/' . $endpoint;
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);
        
        // Use service role for write operations (POST, PUT, PATCH, DELETE)
        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->serviceHeaders);
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        }
        
        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        
        curl_close($ch);
        
        if ($curlError) {
            error_log("CURL Error: {$curlError} - URL: {$url}");
            return [
                'data' => null,
                'status' => 0,
                'error' => $curlError
            ];
        }
        
        return [
            'data' => json_decode($response, true),
            'status' => $httpCode,
            'raw' => $response
        ];
    }
}