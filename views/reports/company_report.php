<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Company Analysis Report</h2>
        <p class="text-muted">Performance metrics by company</p>
    </div>
    <div class="d-flex gap-2">
        <a href="index.php?page=reports&action=company_report&download=csv" class="btn-modern btn-modern-success">
            <i class="fas fa-file-csv me-1"></i> Download CSV
        </a>
        <button onclick="window.print()" class="btn-modern btn-modern-outline">
            <i class="fas fa-print me-1"></i> Print
        </button>
    </div>
</div>

<?php if (empty($reportData)): ?>
    <div class="modern-card"><div class="modern-card-body text-center py-5">
        <i class="fas fa-building fa-3x text-muted mb-3"></i>
        <h4>No Company Data</h4>
        <p class="text-muted">Add companies to see the report</p>
    </div></div>
<?php else: ?>
    <!-- Stats -->
    <div class="stats-grid mb-4">
        <div class="stat-card primary"><div class="stat-value"><?= count($reportData) ?></div><div class="stat-label">Companies</div></div>
        <div class="stat-card success"><div class="stat-value"><?= array_sum(array_column($reportData, 'contact_count')) ?></div><div class="stat-label">Contacts</div></div>
        <div class="stat-card info"><div class="stat-value"><?= array_sum(array_column($reportData, 'deal_count')) ?></div><div class="stat-label">Deals</div></div>
        <div class="stat-card warning"><div class="stat-value">$<?= number_format(array_sum(array_column($reportData, 'total_value')), 0) ?></div><div class="stat-label">Pipeline</div></div>
    </div>

    <!-- Top Companies -->
    <div class="modern-card mb-4">
        <div class="modern-card-header"><h5 class="modern-card-title"><i class="fas fa-trophy text-warning me-2"></i>Top Performing Companies</h5></div>
        <div class="modern-card-body">
            <?php 
            $sorted = $reportData;
            usort($sorted, function($a, $b) { return $b['total_value'] - $a['total_value']; });
            $top = array_slice($sorted, 0, 5);
            $maxVal = max(array_column($top, 'total_value')) ?: 1;
            $rank = 1;
            foreach ($top as $row): 
                $pct = ($row['total_value'] / $maxVal) * 100;
            ?>
                <div class="d-flex align-items-center mb-3">
                    <span class="fw-bold me-3" style="width:30px;">#<?= $rank++ ?></span>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between mb-1">
                            <span><?= htmlspecialchars($row['company']['name']) ?></span>
                            <strong>$<?= number_format($row['total_value'], 0) ?></strong>
                        </div>
                        <div class="progress-modern" style="height:8px;">
                            <div class="progress-modern-bar bg-warning" style="width:<?= $pct ?>%"></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Companies Table -->
    <div class="modern-card">
        <div class="modern-card-body p-0">
            <table class="modern-table">
                <thead><tr><th>Company</th><th>Industry</th><th>Contacts</th><th>Deals</th><th>Pipeline Value</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($reportData as $row): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($row['company']['name']) ?></strong></td>
                            <td><?= htmlspecialchars($row['company']['industry'] ?? 'N/A') ?></td>
                            <td><span class="badge-modern badge-info"><?= $row['contact_count'] ?></span></td>
                            <td><span class="badge-modern badge-primary"><?= $row['deal_count'] ?></span></td>
                            <td><strong class="text-success">$<?= number_format($row['total_value'], 2) ?></strong></td>
                            <td><span class="badge-modern badge-<?= $row['company']['status'] == 'active' ? 'success' : 'danger' ?>"><?= ucfirst($row['company']['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>