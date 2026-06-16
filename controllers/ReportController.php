<?php

if (file_exists('models/Company.php')) {
    require_once 'models/Company.php';
}
if (file_exists('models/Contact.php')) {
    require_once 'models/Contact.php';
}
if (file_exists('models/Deal.php')) {
    require_once 'models/Deal.php';
}
if (file_exists('models/Activity.php')) {
    require_once 'models/Activity.php';
}

class ReportController {
    
    public function index() {
        $totalCompanies = 0;
        $totalDeals = 0;
        $totalDealValue = 0;
        
        try {
            if (class_exists('Company')) {
                $companyModel = new Company();
                $totalCompanies = $companyModel->getCount();
            }
            if (class_exists('Deal')) {
                $dealModel = new Deal();
                $totalDeals = $dealModel->getCount();
                $totalDealValue = $dealModel->getTotalValue();
            }
        } catch (Exception $e) {
            // Use default values
        }
        
        require_once 'views/layout/header.php';
        require_once 'views/reports/index.php';
        require_once 'views/layout/footer.php';
    }
    
    public function salesPipeline() {
        $pipelineData = [];
        
        try {
            if (class_exists('Deal')) {
                $dealModel = new Deal();
                $deals = $dealModel->getAll();
                
                $stages = ['lead', 'qualified', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];
                
                foreach ($stages as $stage) {
                    $stageDeals = array_filter($deals, function($deal) use ($stage) {
                        return ($deal['stage'] ?? '') == $stage;
                    });
                    
                    $totalValue = array_sum(array_column($stageDeals, 'value'));
                    
                    $pipelineData[$stage] = [
                        'count' => count($stageDeals),
                        'value' => $totalValue,
                        'deals' => $stageDeals
                    ];
                }
            }
        } catch (Exception $e) {
            $pipelineData = [];
        }
        
        // Handle download
        if (isset($_GET['download'])) {
            $this->downloadCSV($pipelineData, 'sales_pipeline');
            return;
        }
        
        require_once 'views/layout/header.php';
        require_once 'views/reports/sales_pipeline.php';
        require_once 'views/layout/footer.php';
    }
    
    public function companyReport() {
        $reportData = [];
        
        try {
            if (class_exists('Company')) {
                $companyModel = new Company();
                $companies = $companyModel->getAll();
                
                foreach ($companies as $company) {
                    $reportData[] = [
                        'company' => $company,
                        'contact_count' => 0,
                        'deal_count' => 0,
                        'total_value' => 0
                    ];
                }
            }
        } catch (Exception $e) {
            $reportData = [];
        }
        
        if (isset($_GET['download'])) {
            $this->downloadCSV($reportData, 'company_report');
            return;
        }
        
        require_once 'views/layout/header.php';
        require_once 'views/reports/company_report.php';
        require_once 'views/layout/footer.php';
    }
    
    public function dealReport() {
        $deals = [];
        $stats = [
            'total_deals' => 0,
            'total_value' => 0,
            'won_value' => 0,
            'lost_value' => 0,
            'open_value' => 0,
            'win_rate' => 0
        ];
        
        try {
            if (class_exists('Deal')) {
                $dealModel = new Deal();
                $deals = $dealModel->getAll();
                
                $stats['total_deals'] = count($deals);
                
                foreach ($deals as $deal) {
                    $value = $deal['value'] ?? 0;
                    $stats['total_value'] += $value;
                    
                    if (($deal['status'] ?? '') == 'won') {
                        $stats['won_value'] += $value;
                    } elseif (($deal['status'] ?? '') == 'lost') {
                        $stats['lost_value'] += $value;
                    } else {
                        $stats['open_value'] += $value;
                    }
                }
                
                $wonDeals = count(array_filter($deals, function($d) { 
                    return ($d['status'] ?? '') == 'won'; 
                }));
                $stats['win_rate'] = $stats['total_deals'] > 0 ? 
                    round(($wonDeals / $stats['total_deals']) * 100, 2) : 0;
            }
        } catch (Exception $e) {
            // Use defaults
        }
        
        if (isset($_GET['download'])) {
            $this->downloadCSV($deals, 'deal_report');
            return;
        }
        
        require_once 'views/layout/header.php';
        require_once 'views/reports/deal_report.php';
        require_once 'views/layout/footer.php';
    }
    
    public function activityReport() {
        $activities = [];
        $byType = [];
        $byStatus = [];
        
        try {
            if (class_exists('Activity')) {
                $activityModel = new Activity();
                $activities = $activityModel->getAll();
                
                foreach ($activities as $activity) {
                    $type = $activity['type'] ?? 'unknown';
                    $status = $activity['status'] ?? 'unknown';
                    
                    if (!isset($byType[$type])) {
                        $byType[$type] = 0;
                    }
                    $byType[$type]++;
                    
                    if (!isset($byStatus[$status])) {
                        $byStatus[$status] = 0;
                    }
                    $byStatus[$status]++;
                }
            }
        } catch (Exception $e) {
            // Use defaults
        }
        
        if (isset($_GET['download'])) {
            $this->downloadCSV($activities, 'activity_report');
            return;
        }
        
        require_once 'views/layout/header.php';
        require_once 'views/reports/activity_report.php';
        require_once 'views/layout/footer.php';
    }
    
    private function downloadCSV($data, $filename) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for Excel UTF-8 compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        if (!empty($data)) {
            if (isset($data[0]) && is_array($data[0])) {
                // Array of arrays
                $headers = array_keys($data[0]);
                fputcsv($output, $headers);
                
                foreach ($data as $row) {
                    $flatRow = [];
                    foreach ($row as $value) {
                        if (is_array($value)) {
                            $flatRow[] = json_encode($value);
                        } else {
                            $flatRow[] = $value;
                        }
                    }
                    fputcsv($output, $flatRow);
                }
            } else {
                // Associative array
                foreach ($data as $key => $value) {
                    if (is_array($value)) {
                        fputcsv($output, [$key, 'Count:', count($value)]);
                    } else {
                        fputcsv($output, [$key, $value]);
                    }
                }
            }
        } else {
            fputcsv($output, ['No data available']);
        }
        
        fclose($output);
        exit;
    }

    // Replace the generatePDF method with this:
    private function generatePDF($html, $filename, $orientation = 'Portrait') {
    // Create simple PDF
    $pdf = new SimplePDF($orientation == 'Landscape' ? 'L' : 'P');
    $pdf->writeHTML($html);
    $pdf->Output($filename . '_' . date('Y-m-d') . '.pdf', 'D');
    exit;
}
    
}