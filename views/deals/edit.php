<div class="mb-4">
    <h2>Edit Deal</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=deals&action=index">Deals</a></li>
            <li class="breadcrumb-item active">Edit: <?= htmlspecialchars($deal['title']) ?></li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="modern-card-title"><i class="fas fa-edit me-2"></i>Edit Deal</h5>
                <div>
                    <span class="badge-modern badge-<?= getStageColor($deal['stage']) ?> me-2"><?= ucfirst(str_replace('_', ' ', $deal['stage'])) ?></span>
                    <span class="badge-modern badge-<?= ($deal['status'] ?? 'open') == 'won' ? 'success' : (($deal['status'] ?? '') == 'lost' ? 'danger' : 'warning') ?>"><?= ucfirst($deal['status'] ?? 'open') ?></span>
                </div>
            </div>
            <div class="modern-card-body">
                <form action="index.php?page=deals&action=update&id=<?= $deal['id'] ?>" method="POST">
                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="form-label">Deal Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-input" name="title" value="<?= htmlspecialchars($deal['title']) ?>" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Company <span class="text-danger">*</span></label>
                            <select class="form-select" name="company_id" required>
                                <?php foreach ($companies as $comp): 
                                    $sel = ($deal['company_id'] ?? '') == $comp['id'] ? 'selected' : '';
                                ?>
                                    <option value="<?= $comp['id'] ?>" <?= $sel ?>><?= htmlspecialchars($comp['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Contact</label>
                            <select class="form-select" name="contact_id">
                                <option value="">Select Contact</option>
                                <?php foreach ($contacts as $c): 
                                    $sel = ($deal['contact_id'] ?? '') == $c['id'] ? 'selected' : '';
                                ?>
                                    <option value="<?= $c['id'] ?>" <?= $sel ?>><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label">Value ($)</label>
                            <input type="number" class="form-input" name="value" step="0.01" value="<?= $deal['value'] ?? 0 ?>" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label">Stage</label>
                            <select class="form-select" name="stage">
                                <?php $stages = ['lead','qualified','proposal','negotiation','closed_won','closed_lost'];
                                foreach ($stages as $s): 
                                    $sel = ($deal['stage'] ?? '') == $s ? 'selected' : '';
                                ?>
                                    <option value="<?= $s ?>" <?= $sel ?>><?= ucfirst(str_replace('_', ' ', $s)) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label">Probability (%)</label>
                            <input type="number" class="form-input" name="probability" min="0" max="100" value="<?= $deal['probability'] ?? 0 ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Expected Close Date</label>
                            <input type="date" class="form-input" name="expected_close_date" value="<?= $deal['expected_close_date'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="open" <?= ($deal['status'] ?? '') == 'open' ? 'selected' : '' ?>>Open</option>
                                <option value="won" <?= ($deal['status'] ?? '') == 'won' ? 'selected' : '' ?>>Won</option>
                                <option value="lost" <?= ($deal['status'] ?? '') == 'lost' ? 'selected' : '' ?>>Lost</option>
                                <option value="on_hold" <?= ($deal['status'] ?? '') == 'on_hold' ? 'selected' : '' ?>>On Hold</option>
                            </select>
                        </div>
                        <div class="col-12 form-group">
                            <label class="form-label">Notes</label>
                            <textarea class="form-input" name="notes" rows="3"><?= htmlspecialchars($deal['notes'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="fas fa-save me-1"></i> Update Deal
                        </button>
                        <a href="index.php?page=deals&action=index" class="btn-modern btn-modern-outline">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="button" onclick="deleteDeal('<?= $deal['id'] ?>')" class="btn-modern btn-modern-danger ms-auto">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="modern-card">
            <div class="modern-card-header"><h5 class="modern-card-title">Deal Progress</h5></div>
            <div class="modern-card-body">
                <div class="progress-modern mb-3" style="height:12px;">
                    <div class="progress-modern-bar bg-<?= getStageColor($deal['stage']) ?> progress-bar-striped" 
                         style="width: <?= $deal['probability'] ?? 0 ?>%"></div>
                </div>
                <p class="text-center fw-bold"><?= $deal['probability'] ?? 0 ?>% Complete</p>
                <p><strong>Created:</strong> <?= date('M d, Y', strtotime($deal['created_at'])) ?></p>
                <p><strong>Updated:</strong> <?= date('M d, Y', strtotime($deal['updated_at'])) ?></p>
            </div>
        </div>
    </div>
</div>

<script>
function deleteDeal(id) {
    if (confirm('Are you sure you want to delete this deal?')) {
        window.location.href = `index.php?page=deals&action=delete&id=${id}`;
    }
}
</script>