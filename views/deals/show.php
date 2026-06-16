<div class="row mb-4">
    <div class="col-md-8">
        <h2><?= htmlspecialchars($deal['title']) ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=deals&action=index">Deals</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($deal['title']) ?></li>
            </ol>
        </nav>
    </div>
    <div class="col-md-4 text-end">
        <a href="index.php?page=deals&action=edit&id=<?= $deal['id'] ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <button onclick="deleteDeal('<?= $deal['id'] ?>')" class="btn btn-danger">
            <i class="fas fa-trash"></i> Delete
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Deal Details -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Deal Information</h5>
                <span class="badge bg-<?= getStageColor($deal['stage']) ?>">
                    <?= ucfirst(str_replace('_', ' ', $deal['stage'])) ?>
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Company:</strong>
                        <p>
                            <?php if ($deal['companies']['name'] ?? false): ?>
                                <a href="index.php?page=companies&action=show&id=<?= $deal['company_id'] ?>">
                                    <?= htmlspecialchars($deal['companies']['name']) ?>
                                </a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Contact:</strong>
                        <p>
                            <?php if ($deal['contacts']['first_name'] ?? false): ?>
                                <a href="index.php?page=contacts&action=show&id=<?= $deal['contact_id'] ?>">
                                    <?= htmlspecialchars($deal['contacts']['first_name'] . ' ' . $deal['contacts']['last_name']) ?>
                                </a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Value:</strong>
                        <p class="h4 text-success">$<?= number_format($deal['value'] ?? 0, 2) ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Probability:</strong>
                        <p><?= $deal['probability'] ?? 0 ?>%</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Expected Close:</strong>
                        <p><?= $deal['expected_close_date'] ? date('M d, Y', strtotime($deal['expected_close_date'])) : 'N/A' ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <p>
                            <span class="badge bg-<?= $deal['status'] == 'open' ? 'primary' : ($deal['status'] == 'won' ? 'success' : 'danger') ?>">
                                <?= ucfirst($deal['status']) ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Created:</strong>
                        <p><?= date('M d, Y', strtotime($deal['created_at'])) ?></p>
                    </div>
                </div>
                
                <?php if ($deal['notes']): ?>
                    <hr>
                    <strong>Notes:</strong>
                    <p><?= nl2br(htmlspecialchars($deal['notes'])) ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Deal Progress</h5>
            </div>
            <div class="card-body">
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar bg-<?= getStageColor($deal['stage']) ?> progress-bar-striped progress-bar-animated" 
                         role="progressbar" 
                         style="width: <?= $deal['probability'] ?? 0 ?>%;" 
                         aria-valuenow="<?= $deal['probability'] ?? 0 ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        <?= $deal['probability'] ?? 0 ?>% Complete
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Deal Value Card -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <h6 class="text-muted">DEAL VALUE</h6>
                <h2 class="text-success">$<?= number_format($deal['value'] ?? 0, 2) ?></h2>
                <small class="text-muted">
                    Expected: <?= $deal['expected_close_date'] ? date('M d, Y', strtotime($deal['expected_close_date'])) : 'N/A' ?>
                </small>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Days in Pipeline:</span>
                    <strong>
                        <?php 
                        $created = strtotime($deal['created_at']);
                        $now = time();
                        $days = floor(($now - $created) / (60 * 60 * 24));
                        echo $days;
                        ?>
                    </strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Last Updated:</span>
                    <strong><?= date('M d', strtotime($deal['updated_at'])) ?></strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Probability:</span>
                    <strong><?= $deal['probability'] ?? 0 ?>%</strong>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="index.php?page=activities&action=create&deal_id=<?= $deal['id'] ?>" 
                   class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-plus"></i> Add Activity
                </a>
                <button class="btn btn-outline-success w-100 mb-2" onclick="updateStage('<?= $deal['id'] ?>', 'closed_won')">
                    <i class="fas fa-check"></i> Mark as Won
                </button>
                <button class="btn btn-outline-danger w-100" onclick="updateStage('<?= $deal['id'] ?>', 'closed_lost')">
                    <i class="fas fa-times"></i> Mark as Lost
                </button>
            </div>
        </div>
    </div>
</div>

<?php
function getStageColor($stage) {
    switch($stage) {
        case 'lead': return 'secondary';
        case 'qualified': return 'info';
        case 'proposal': return 'warning';
        case 'negotiation': return 'primary';
        case 'closed_won': return 'success';
        case 'closed_lost': return 'danger';
        default: return 'secondary';
    }
}
?>

<script>
function deleteDeal(id) {
    if (confirm('Are you sure you want to delete this deal?')) {
        window.location.href = `index.php?page=deals&action=delete&id=${id}`;
    }
}

function updateStage(dealId, stage) {
    if (confirm('Are you sure you want to change this deal to ' + stage.replace('_', ' ') + '?')) {
        window.location.href = `index.php?page=deals&action=update&id=${dealId}&stage=${stage}`;
    }
}
</script>