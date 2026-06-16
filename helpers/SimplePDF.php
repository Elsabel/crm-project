<?php
/**
 * Simple PDF Generator - No external libraries needed
 * Generates basic PDF files directly
 */
class SimplePDF {
    
    private $pdf = '';
    private $pageWidth = 595.28; // A4 width in points
    private $pageHeight = 841.89; // A4 height in points
    private $margin = 40;
    private $currentY = 0;
    private $currentPage = 1;
    private $pages = [];
    private $fonts = [];
    
    public function __construct($orientation = 'P') {
        if ($orientation == 'L') {
            $this->pageWidth = 841.89;
            $this->pageHeight = 595.28;
        }
        $this->currentY = $this->margin;
        $this->initDocument();
    }
    
    private function initDocument() {
        $this->pdf = "%PDF-1.4\n";
        $this->objects = [];
        $this->currentObj = 1;
        
        // Catalog
        $this->objects[1] = "1 0 obj\n<</Type /Catalog /Pages 2 0 R>>\nendobj\n";
        
        // Pages
        $this->objects[2] = "2 0 obj\n<</Type /Pages /Kids [3 0 R] /Count 1>>\nendobj\n";
        $this->currentObj = 4;
    }
    
    private function addPage() {
        $contents = $this->currentObj;
        $this->objects[$contents] = "{$contents} 0 obj\n<</Length " . ($contents + 1) . " 0 R>>\nstream\n";
        
        $pageContent = "BT\n/F1 10 Tf\n";
        $pageContent .= "1 0 0 1 {$this->margin} {$this->currentY} Tm\n";
        $pageContent .= "ET\n";
        
        $this->objects[$contents] .= $pageContent . "endstream\nendobj\n";
        
        $streamLength = strlen($pageContent);
        $this->objects[$contents + 1] = ($contents + 1) . " 0 obj\n{$streamLength}\nendobj\n";
        
        $this->currentObj = $contents + 2;
        
        // Page object
        $pageObj = $this->currentObj;
        $this->objects[$pageObj] = "{$pageObj} 0 obj\n<</Type /Page /Parent 2 0 R /MediaBox [0 0 {$this->pageWidth} {$this->pageHeight}] /Contents {$contents} 0 R /Resources <</Font <</F1 " . ($pageObj + 1) . " 0 R>>>>>>\nendobj\n";
        
        // Font
        $this->objects[$pageObj + 1] = ($pageObj + 1) . " 0 obj\n<</Type /Font /Subtype /Type1 /BaseFont /Helvetica>>\nendobj\n";
        
        $this->currentObj = $pageObj + 2;
        $this->currentY = $this->pageHeight - $this->margin;
    }
    
    public function SetFont($family, $style = '', $size = 12) {
        $this->fontSize = $size;
        $this->fontFamily = $family;
    }
    
    public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = 'L', $fill = false) {
        $x = $this->margin;
        if ($align == 'C') {
            $x = $this->pageWidth / 2 - ($w / 2);
        } elseif ($align == 'R') {
            $x = $this->pageWidth - $this->margin - $w;
        }
        
        // Simplified - just track position
        $this->currentY -= $h ?: 15;
        $this->currentX = $x;
    }
    
    public function MultiCell($w, $h, $txt, $border = 0, $align = 'L') {
        $lines = explode("\n", wordwrap($txt, floor($w / 6)));
        foreach ($lines as $line) {
            $this->currentY -= $h;
        }
    }
    
    public function Ln($h = 0) {
        $this->currentY -= $h ?: 10;
    }
    
    public function SetY($y) {
        $this->currentY = $y;
    }
    
    public function GetY() {
        return $this->currentY;
    }
    
    
    
    public function Output($name = 'document.pdf', $dest = 'D') {
        // Build a minimal valid PDF
        $pdf = "%PDF-1.4\n";
        
        // Generate a simple valid PDF with text content
        $content = $this->generateMinimalPDF();
        
        if ($dest == 'D') {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $name . '"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');
            header('Expires: 0');
            header('Content-Length: ' . strlen($content));
        }
        
        echo $content;
    }
    
    private function generateMinimalPDF() {
        // Create a completely valid minimal PDF
        $objects = [];
        $offset = 0;
        
        // Object 1: Catalog
        $objects[] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        
        // Object 2: Pages
        $objects[] = "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 /MediaBox [0 0 612 792] >>\nendobj\n";
        
        // Object 4: Font
        $objects[] = "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";
        
        // Object 5: Page Content Stream
        $text = $this->formatTextForPDF($this->pdfContent);
        $stream = "BT\n/F1 12 Tf\n40 740 Td\n{$text}\nET";
        
        $objects[] = "5 0 obj\n<< /Length " . (strlen($stream) + 1) . " >>\nstream\n{$stream}\nendstream\nendobj\n";
        
        // Object 3: Page
        $objects[] = "3 0 obj\n<< /Type /Page /Parent 2 0 R /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>\nendobj\n";
        
        // Build cross-reference table
        $xref = "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
        $positions = [];
        $currentPos = 0;
        
        $pdfBody = '';
        foreach ($objects as $i => $obj) {
            $positions[$i + 1] = $currentPos;
            $pdfBody .= $obj;
            $currentPos += strlen($obj);
        }
        
        for ($i = 1; $i <= count($objects); $i++) {
            $xref .= str_pad($positions[$i], 10, '0', STR_PAD_LEFT) . " 00000 n \n";
        }
        
        $trailer = "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n" . $currentPos . "\n%%EOF";
        
        return "%PDF-1.4\n" . $pdfBody . $xref . $trailer;
    }
    
    private function formatTextForPDF($text) {
        // Escape special characters for PDF string
        $text = str_replace('\\', '\\\\', $text);
        $text = str_replace('(', '\\(', $text);
        $text = str_replace(')', '\\)', $text);
        $text = str_replace("\r", '', $text);
        return $text;
    }
    
    public function setContent($html) {
        // Strip HTML tags and convert to plain text
        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $this->pdfContent = $text;
    }
    
    public function writeHTML($html) {
        $this->setContent($html);
    }
}