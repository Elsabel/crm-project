<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Activity Report</h2>
        <p class="text-muted">Team activities and performance</p>
    </div>
    <div class="d-flex gap-2">
        <a href="index.php?page=reports&action=activity_report&download=csv" class="btn-modern btn-modern-success">
            <i class="fas fa-file-csv me-1"></i> Download CSV
        </a>
        <button onclick="window.print()" class="btn-modern btn-modern-outline">
            <i class="fas fa-print me-1"></i> Print
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="modern-card h-100">
            <div class="modern-card-header"><h5 class="modern-card-title">By Type</h5></div>
            <div class="modern-card-body">
                <?php if (empty($byType)): ?>
                    <p class="text-muted text-center">No data</p>
                <?php else: 
                    $total = array_sum($byType);
                    foreach ($byType as $type => $count): 
                        $pct = $total > 0 ? ($count / $total) * 100 : 0;
                ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="fas fa-<?= getActivityIcon($type) ?> me-2"></i><?= ucfirst($type) ?></span>
                            <span><?= $count ?> (<?= round($pct) ?>%)</span>
                        </div>
                        <div class="progress-modern" style="height:8px;">
                            <div class="progress-modern-bar bg-info" style="width:<?= $pct ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="modern-card h-100">
            <div class="modern-card-header"><h5 class="modern-card-title">By Status</h5></div>
            <div class="modern-card-body">
                <?php if (empty($byStatus)): ?>
                    <p class="text-muted text-center">No data</p>
                <?php else: 
                    $total = array_sum($byStatus);
                    foreach ($byStatus as $status => $count): 
                        $pct = $total > 0 ? ($count / $total) * 100 : 0;
                        $color = $status == 'completed' ? 'success' : ($status == 'cancelled' ? 'danger' : 'warning');
                ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span><?= ucfirst($status) ?></span>
                            <span><?= $count ?> (<?= round($pct) ?>%)</span>
                        </div>
                        <div class="progress-modern" style="height:8px;">
                            <div class="progress-modern-bar bg-<?= $color ?>" style="width:<?= $pct ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities Table -->
<div class="modern-card">
    <div class="modern-card-header"><h5 class="modern-card-title">Recent Activities</h5></div>
    <div class="modern-card-body p-0">
        <table class="modern-table">
            <thead><tr><th>Subject</th><th>Type</th><th>Company</th><th>Due Date</th><th>Status</th></tr></thead>
            <tbody>
                <?php if (empty($activities)): ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">No activities</td></tr>
                <?php else: ?>
                    <?php foreach (array_slice($activities, 0, 20) as $activity): ?>
                        <tr>
                            <td><?= htmlspecialchars($activity['subject'] ?? 'N/A') ?></td>
                            <td><span class="badge-modern badge-info"><i class="fas fa-<?= getActivityIcon($activity['type'] ?? 'task') ?> me-1"></i><?= ucfirst($activity['type'] ?? 'N/A') ?></span></td>
                            <td><?= htmlspecialchars($activity['companies']['name'] ?? 'N/A') ?></td>
                            <td><?= date('M d, Y', strtotime($activity['due_date'] ?? 'now')) ?></td>
                            <td><span class="badge-modern badge-<?= ($activity['status'] ?? '') == 'completed' ? 'success' : 'warning' ?>"><?= ucfirst($activity['status'] ?? 'pending') ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>