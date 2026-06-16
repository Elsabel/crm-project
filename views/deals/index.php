<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Deals Pipeline</h2>
        <p class="text-muted">Track your sales pipeline</p>
    </div>
    <a href="index.php?page=deals&action=create" class="btn-modern btn-modern-primary">
        <i class="fas fa-plus"></i> New Deal
    </a>
</div>

<!-- Pipeline Stats -->
<div class="stats-grid mb-4">
    <?php
    $stages = ['lead', 'qualified', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];
    $stageData = [];
    $totalValue = 0;
    
    foreach ($stages as $stage) {
        $stageDeals = array_filter($deals, function($d) use ($stage) {
            return ($d['stage'] ?? '') == $stage;
        });
        $stageValue = array_sum(array_column($stageDeals, 'value'));
        $totalValue += $stageValue;
        $stageData[$stage] = ['count' => count($stageDeals), 'value' => $stageValue];
    }
    ?>
    
    <div class="stat-card info">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-handshake"></i></div>
        </div>
        <div class="stat-value"><?= count($deals) ?></div>
        <div class="stat-label">Total Deals</div>
    </div>
    
    <div class="stat-card success">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-dollar-sign"></i></div>
        </div>
        <div class="stat-value">$<?= number_format($totalValue, 0) ?></div>
        <div class="stat-label">Pipeline Value</div>
    </div>
    
    <div class="stat-card warning">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-trophy"></i></div>
        </div>
        <div class="stat-value"><?= $stageData['closed_won']['count'] ?></div>
        <div class="stat-label">Won Deals</div>
    </div>
</div>

<!-- Kanban Pipeline Board -->
<div class="pipeline-board">
    <?php foreach ($stages as $stage): 
        $stageDeals = array_filter($deals, function($d) use ($stage) {
            return ($d['stage'] ?? '') == $stage;
        });
        $color = getStageColor($stage);
    ?>
        <div class="pipeline-column">
            <div class="pipeline-column-header">
                <div class="pipeline-column-title">
                    <span class="column-dot" style="background: var(--<?= $color ?>)"></span>
                    <?= ucfirst(str_replace('_', ' ', $stage)) ?>
                </div>
                <span class="column-count"><?= count($stageDeals) ?></span>
            </div>
            
            <?php foreach ($stageDeals as $deal): ?>
                <div class="pipeline-card" onclick="window.location='index.php?page=deals&action=show&id=<?= $deal['id'] ?>'">
                    <div class="pipeline-card-title">
                        <?= htmlspecialchars($deal['title']) ?>
                    </div>
                    <div class="pipeline-card-meta">
                        <small><?= htmlspecialchars($deal['companies']['name'] ?? 'N/A') ?></small>
                        <span class="pipeline-card-value">$<?= number_format($deal['value'] ?? 0, 0) ?></span>
                    </div>
                    <?php if ($deal['probability'] ?? false): ?>
                    <div class="progress-modern mt-2" style="height:4px;">
                        <div class="progress-modern-bar bg-<?= $color ?>" style="width: <?= $deal['probability'] ?>%"></div>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<!-- Deals Table (Alternative View) -->
<div class="modern-card mt-4">
    <div class="modern-card-header">
        <h5 class="modern-card-title">All Deals</h5>
        <div class="modern-tabs">
            <button class="modern-tab active">List View</button>
            <button class="modern-tab">Kanban</button>
        </div>
    </div>
    <div class="modern-card-body p-0">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Deal</th>
                    <th>Company</th>
                    <th>Contact</th>
                    <th>Value</th>
                    <th>Stage</th>
                    <th>Probability</th>
                    <th>Expected Close</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($deals)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted">No deals yet</td></tr>
                <?php else: ?>
                    <?php foreach ($deals as $deal): ?>
                        <tr>
                            <td><a href="index.php?page=deals&action=show&id=<?= $deal['id'] ?>"><?= htmlspecialchars($deal['title']) ?></a></td>
                            <td><?= htmlspecialchars($deal['companies']['name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars(($deal['contacts']['first_name'] ?? '') . ' ' . ($deal['contacts']['last_name'] ?? '')) ?: 'N/A' ?></td>
                            <td><strong>$<?= number_format($deal['value'] ?? 0, 2) ?></strong></td>
                            <td><span class="badge-modern badge-<?= getStageColor($deal['stage'] ?? 'lead') ?>"><?= ucfirst(str_replace('_', ' ', $deal['stage'] ?? 'lead')) ?></span></td>
                            <td><?= $deal['probability'] ?? 0 ?>%</td>
                            <td><?= isset($deal['expected_close_date']) ? date('M d', strtotime($deal['expected_close_date'])) : 'N/A' ?></td>
                            <td>
                                <a href="index.php?page=deals&action=edit&id=<?= $deal['id'] ?>" class="btn-modern btn-modern-ghost btn-modern-sm"><i class="fas fa-edit"></i></a>
                                <button onclick="deleteDeal('<?= $deal['id'] ?>')" class="btn-modern btn-modern-ghost btn-modern-sm text-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function deleteDeal(id) {
    if (confirm('Are you sure you want to delete this deal?')) {
        window.location.href = `index.php?page=deals&action=delete&id=${id}`;
    }
}
</script>