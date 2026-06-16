<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Deal Analysis Report</h2>
        <p class="text-muted">Win/loss analysis and deal performance metrics</p>
    </div>
    <div class="d-flex gap-2">
        <a href="index.php?page=reports&action=deal_report&download=csv" class="btn-modern btn-modern-success">
            <i class="fas fa-file-csv me-1"></i> Download CSV
        </a>
        <button onclick="window.print()" class="btn-modern btn-modern-outline">
            <i class="fas fa-print me-1"></i> Print
        </button>
    </div>
</div>

<!-- Key Metrics -->
<div class="stats-grid mb-4">
    <div class="stat-card primary">
        <div class="stat-card-header"><div class="stat-card-icon"><i class="fas fa-chart-bar"></i></div></div>
        <div class="stat-value"><?= $stats['total_deals'] ?? 0 ?></div>
        <div class="stat-label">Total Deals</div>
    </div>
    <div class="stat-card success">
        <div class="stat-card-header"><div class="stat-card-icon"><i class="fas fa-dollar-sign"></i></div></div>
        <div class="stat-value">$<?= number_format($stats['total_value'] ?? 0, 0) ?></div>
        <div class="stat-label">Total Value</div>
    </div>
    <div class="stat-card info">
        <div class="stat-card-header"><div class="stat-card-icon"><i class="fas fa-trophy"></i></div></div>
        <div class="stat-value"><?= $stats['win_rate'] ?? 0 ?>%</div>
        <div class="stat-label">Win Rate</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-card-header"><div class="stat-card-icon"><i class="fas fa-check-circle"></i></div></div>
        <div class="stat-value">$<?= number_format($stats['won_value'] ?? 0, 0) ?></div>
        <div class="stat-label">Won Value</div>
    </div>
</div>

<!-- Win/Loss Chart -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="modern-card h-100">
            <div class="modern-card-header"><h5 class="modern-card-title">Deal Status Distribution</h5></div>
            <div class="modern-card-body">
                <?php 
                $wonPercent = $stats['total_value'] > 0 ? (($stats['won_value'] ?? 0) / $stats['total_value']) * 100 : 0;
                $lostPercent = $stats['total_value'] > 0 ? (($stats['lost_value'] ?? 0) / $stats['total_value']) * 100 : 0;
                $openPercent = 100 - $wonPercent - $lostPercent;
                ?>
                <div class="progress-modern mb-3" style="height:40px;">
                    <div class="progress-modern-bar bg-success d-flex align-items-center px-2" style="width:<?= $wonPercent ?>%">
                        <small class="text-white fw-bold">Won $<?= number_format($stats['won_value'] ?? 0, 0) ?></small>
                    </div>
                    <div class="progress-modern-bar bg-danger d-flex align-items-center px-2" style="width:<?= $lostPercent ?>%">
                        <small class="text-white fw-bold">Lost $<?= number_format($stats['lost_value'] ?? 0, 0) ?></small>
                    </div>
                    <div class="progress-modern-bar bg-warning d-flex align-items-center px-2" style="width:<?= $openPercent ?>%">
                        <small class="text-dark fw-bold">Open $<?= number_format($stats['open_value'] ?? 0, 0) ?></small>
                    </div>
                </div>
                <div class="d-flex justify-content-around text-center">
                    <div><span class="dot bg-success d-inline-block" style="width:12px;height:12px;border-radius:50%;"></span> Won (<?= round($wonPercent) ?>%)</div>
                    <div><span class="dot bg-danger d-inline-block" style="width:12px;height:12px;border-radius:50%;"></span> Lost (<?= round($lostPercent) ?>%)</div>
                    <div><span class="dot bg-warning d-inline-block" style="width:12px;height:12px;border-radius:50%;"></span> Open (<?= round($openPercent) ?>%)</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-card h-100">
            <div class="modern-card-header"><h5 class="modern-card-title">Quick Stats</h5></div>
            <div class="modern-card-body">
                <div class="mb-3"><small class="text-muted">Win Rate</small><h4 class="text-success"><?= $stats['win_rate'] ?? 0 ?>%</h4></div>
                <div class="mb-3"><small class="text-muted">Avg Deal Size</small><h4>$<?= number_format(($stats['total_deals'] ?? 0) > 0 ? ($stats['total_value'] ?? 0) / ($stats['total_deals'] ?? 1) : 0, 0) ?></h4></div>
                <div><small class="text-muted">Total Deals</small><h4><?= $stats['total_deals'] ?? 0 ?></h4></div>
            </div>
        </div>
    </div>
</div>

<!-- Deals Table -->
<div class="modern-card">
    <div class="modern-card-header"><h5 class="modern-card-title">All Deals</h5></div>
    <div class="modern-card-body p-0">
        <table class="modern-table">
            <thead>
                <tr><th>Title</th><th>Company</th><th>Value</th><th>Stage</th><th>Status</th><th>Expected Close</th></tr>
            </thead>
            <tbody>
                <?php if (empty($deals)): ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">No deals found</td></tr>
                <?php else: ?>
                    <?php foreach ($deals as $deal): ?>
                        <tr>
                            <td><a href="index.php?page=deals&action=show&id=<?= $deal['id'] ?>"><?= htmlspecialchars($deal['title'] ?? 'N/A') ?></a></td>
                            <td><?= htmlspecialchars($deal['companies']['name'] ?? 'N/A') ?></td>
                            <td><strong>$<?= number_format($deal['value'] ?? 0, 2) ?></strong></td>
                            <td><span class="badge-modern badge-<?= getStageColor($deal['stage'] ?? 'lead') ?>"><?= ucfirst(str_replace('_', ' ', $deal['stage'] ?? 'lead')) ?></span></td>
                            <td><span class="badge-modern badge-<?= ($deal['status'] ?? '') == 'won' ? 'success' : (($deal['status'] ?? '') == 'lost' ? 'danger' : 'warning') ?>"><?= ucfirst($deal['status'] ?? 'open') ?></span></td>
                            <td><?= isset($deal['expected_close_date']) ? date('M d, Y', strtotime($deal['expected_close_date'])) : 'N/A' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>