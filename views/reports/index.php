<div class="mb-4">
    <h2>Reports & Analytics</h2>
    <p class="text-muted">View insights and download reports</p>
</div>

<!-- Quick Stats -->
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-building"></i></div>
        </div>
        <div class="stat-value"><?= $totalCompanies ?? 0 ?></div>
        <div class="stat-label">Total Companies</div>
    </div>
    <div class="stat-card success">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-handshake"></i></div>
        </div>
        <div class="stat-value"><?= $totalDeals ?? 0 ?></div>
        <div class="stat-label">Total Deals</div>
    </div>
    <div class="stat-card info">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-dollar-sign"></i></div>
        </div>
        <div class="stat-value">$<?= number_format($totalDealValue ?? 0, 0) ?></div>
        <div class="stat-label">Pipeline Value</div>
    </div>
</div>

<!-- Report Cards - Responsive Grid -->
<div class="row g-3">
    <!-- Sales Pipeline -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="modern-card h-100">
            <div class="modern-card-body text-center">
                <h4>Sales Pipeline</h4>
                <small class="text-muted d-block mb-3">Stage-by-stage funnel analysis</small>
                <div class="d-grid gap-1">
                    <a href="index.php?page=reports&action=sales_pipeline" class="btn-modern btn-modern-success btn-modern-sm w-100">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="index.php?page=reports&action=sales_pipeline&download=csv" class="btn-modern btn-modern-outline btn-modern-sm w-100">
                        <i class="fas fa-download"></i> CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Company Analysis -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="modern-card h-100">
            <div class="modern-card-body text-center">
                <h4>Company Analysis</h4>
                <small class="text-muted d-block mb-3">Performance by company</small>
                <div class="d-grid gap-1">
                    <a href="index.php?page=reports&action=company_report" class="btn-modern btn-modern-success btn-modern-sm w-100">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="index.php?page=reports&action=company_report&download=csv" class="btn-modern btn-modern-outline btn-modern-sm w-100">
                        <i class="fas fa-download"></i> CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Deal Analysis -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="modern-card h-100">
            <div class="modern-card-body text-center">
                <h4>Deal Analysis</h4>
                <small class="text-muted d-block mb-3">Win/loss metrics</small>
                <div class="d-grid gap-1">
                    <a href="index.php?page=reports&action=deal_report" class="btn-modern btn-modern-success btn-modern-sm w-100">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="index.php?page=reports&action=deal_report&download=csv" class="btn-modern btn-modern-outline btn-modern-sm w-100">
                        <i class="fas fa-download"></i> CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Activity Report -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="modern-card h-100">
            <div class="modern-card-body text-center">
                <h4>Activity Report</h4>
                <small class="text-muted d-block mb-3">Team performance</small>
                <div class="d-grid gap-1">
                    <a href="index.php?page=reports&action=activity_report" class="btn-modern btn-modern-success btn-modern-sm w-100">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="index.php?page=reports&action=activity_report&download=csv" class="btn-modern btn-modern-outline btn-modern-sm w-100">
                        <i class="fas fa-download"></i> CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Report Builder -->
<div class="modern-card mt-4">
    <div class="modern-card-header">
        <h5 class="modern-card-title"><i class="fas fa-magic me-2"></i>Custom Report Builder</h5>
    </div>
    <div class="modern-card-body">
        <form id="customReportForm" onsubmit="generateCustomReport(event)">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label">Report Type</label>
                    <select class="form-select" id="reportType" name="report_type">
                        <option value="">Select Report Type</option>
                        <option value="sales_pipeline">Sales Pipeline</option>
                        <option value="company_report">Company Analysis</option>
                        <option value="deal_report">Deal Analysis</option>
                        <option value="activity_report">Activity Report</option>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">Date Range</label>
                    <select class="form-select" id="dateRange" name="date_range">
                        <option value="">Select Date Range</option>
                        <option value="7">Last 7 Days</option>
                        <option value="30">Last 30 Days</option>
                        <option value="90">This Quarter</option>
                        <option value="365">This Year</option>
                        <option value="all">All Time</option>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">Export Format</label>
                    <select class="form-select" id="exportFormat" name="format">
                        <option value="csv">CSV (Excel)</option>
                        <option value="pdf">PDF (Print)</option>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn-modern btn-modern-primary" id="generateBtn">
                    <i class="fas fa-download me-1"></i> Generate Report
                </button>
                <button type="button" class="btn-modern btn-modern-outline" onclick="resetReportForm()">
                    <i class="fas fa-redo me-1"></i> Reset
                </button>
            </div>
        </form>
        <div id="reportMessage" class="mt-3" style="display:none;"></div>
    </div>
</div>

<script>
function generateCustomReport(event) {
    event.preventDefault();
    
    const reportType = document.getElementById('reportType').value;
    const dateRange = document.getElementById('dateRange').value;
    const format = document.getElementById('exportFormat').value;
    const btn = document.getElementById('generateBtn');
    const msg = document.getElementById('reportMessage');
    
    // Validation
    if (!reportType) {
        showMessage('Please select a report type', 'error');
        return;
    }
    
    if (!dateRange) {
        showMessage('Please select a date range', 'error');
        return;
    }
    
    // Show loading state
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Generating...';
    
    // Build download URL
    let url = `index.php?page=reports&action=${reportType}&download=${format}&date_range=${dateRange}`;
    
    // If PDF, open in new tab
    if (format === 'pdf') {
        window.open(url, '_blank');
        showMessage('Report opened in new tab!', 'success');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-download me-1"></i> Generate Report';
    } else {
        // For CSV, trigger download
        window.location.href = url;
        
        // Show success after short delay
        setTimeout(() => {
            showMessage('Report downloaded successfully!', 'success');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-download me-1"></i> Generate Report';
        }, 1000);
    }
}

function showMessage(text, type) {
    const msg = document.getElementById('reportMessage');
    msg.style.display = 'block';
    
    if (type === 'success') {
        msg.style.background = '#e0f7ef';
        msg.style.color = '#00854c';
        msg.innerHTML = `<i class="fas fa-check-circle me-2"></i>${text}`;
    } else {
        msg.style.background = '#ffe0e3';
        msg.style.color = '#c0392b';
        msg.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>${text}`;
    }
    
    msg.style.padding = '12px 16px';
    msg.style.borderRadius = '8px';
    
    // Auto hide
    setTimeout(() => { msg.style.display = 'none'; }, 3000);
}

function resetReportForm() {
    document.getElementById('reportType').value = '';
    document.getElementById('dateRange').value = '';
    document.getElementById('exportFormat').value = 'csv';
    document.getElementById('reportMessage').style.display = 'none';
}
</script>