<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Sales Pipeline Report</h2>
        <p class="text-muted">Stage-by-stage breakdown of your sales funnel</p>
    </div>
    <div class="d-flex gap-2">
        <a href="index.php?page=reports&action=sales_pipeline&download=csv" class="btn-modern btn-modern-success">
            <i class="fas fa-file-csv me-1"></i> Download CSV
        </a>
        <button onclick="window.print()" class="btn-modern btn-modern-outline">
            <i class="fas fa-print me-1"></i> Print
        </button>
    </div>
</div>

<?php if (empty($pipelineData)): ?>
    <div class="modern-card">
        <div class="modern-card-body text-center py-5">
            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
            <h4>No Pipeline Data</h4>
            <p class="text-muted">Create deals to see your pipeline report</p>
            <a href="index.php?page=deals&action=create" class="btn-modern btn-modern-primary">Create Deal</a>
        </div>
    </div>
<?php else: ?>
    <!-- Pipeline Funnel Visualization -->
    <div class="modern-card mb-4">
        <div class="modern-card-header"><h5 class="modern-card-title"><i class="fas fa-filter me-2"></i>Pipeline Funnel</h5></div>
        <div class="modern-card-body">
            <?php 
            $stages = ['lead', 'qualified', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];
            $maxCount = max(array_column($pipelineData, 'count')) ?: 1;
            $colors = ['secondary', 'info', 'warning', 'primary', 'success', 'danger'];
            $i = 0;
            
            foreach ($stages as $stage):
                $data = $pipelineData[$stage] ?? ['count' => 0, 'value' => 0];
                $width = ($data['count'] / $maxCount) * 100;
                $color = $colors[$i++];
            ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-semibold"><?= ucfirst(str_replace('_', ' ', $stage)) ?></span>
                        <span><?= $data['count'] ?> deals · $<?= number_format($data['value'], 0) ?></span>
                    </div>
                    <div class="progress-modern" style="height: 32px;">
                        <div class="progress-modern-bar bg-<?= $color ?> d-flex align-items-center px-2" 
                             style="width: <?= max($width, 5) ?>%; min-width: 80px;">
                            <small class="text-white fw-bold"><?= $data['count'] ?> (<?= round($width) ?>%)</small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Pipeline Table -->
    <div class="modern-card">
        <div class="modern-card-header"><h5 class="modern-card-title"><i class="fas fa-table me-2"></i>Pipeline Details</h5></div>
        <div class="modern-card-body p-0">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Stage</th>
                        <th>Deals Count</th>
                        <th>Total Value</th>
                        <th>Avg Deal Size</th>
                        <th>% of Pipeline</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalDeals = array_sum(array_column($pipelineData, 'count'));
                    $totalValue = array_sum(array_column($pipelineData, 'value'));
                    $stageIndex = 0;
                    foreach ($stages as $stage): 
                        $data = $pipelineData[$stage] ?? ['count' => 0, 'value' => 0];
                        $avgDeal = $data['count'] > 0 ? $data['value'] / $data['count'] : 0;
                        $percent = $totalValue > 0 ? ($data['value'] / $totalValue) * 100 : 0;
                        $color = $colors[$stageIndex++];
                    ?>
                        <tr>
                            <td><span class="badge-modern badge-<?= $color ?>"><?= ucfirst(str_replace('_', ' ', $stage)) ?></span></td>
                            <td><strong><?= $data['count'] ?></strong></td>
                            <td><strong class="text-success">$<?= number_format($data['value'], 2) ?></strong></td>
                            <td>$<?= number_format($avgDeal, 2) ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress-modern flex-grow-1" style="height:6px;">
                                        <div class="progress-modern-bar bg-<?= $color ?>" style="width:<?= $percent ?>%"></div>
                                    </div>
                                    <small><?= round($percent, 1) ?>%</small>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tfoot>
                        <tr class="bg-light fw-bold">
                            <td>Total</td>
                            <td><?= $totalDeals ?></td>
                            <td class="text-success">$<?= number_format($totalValue, 2) ?></td>
                            <td>$<?= number_format($totalDeals > 0 ? $totalValue / $totalDeals : 0, 2) ?></td>
                            <td>100%</td>
                        </tr>
                    </tfoot>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>