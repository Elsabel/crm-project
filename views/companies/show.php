<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><?= htmlspecialchars($company['name']) ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=companies&action=index">Companies</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($company['name']) ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="index.php?page=companies&action=edit&id=<?= $company['id'] ?>" class="btn-modern btn-modern-outline">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <button onclick="deleteCompany('<?= $company['id'] ?>')" class="btn-modern btn-modern-danger">
                <i class="fas fa-trash me-1"></i> Delete
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="modern-card mb-4">
            <div class="modern-card-header">
                <h5 class="modern-card-title"><i class="fas fa-info-circle me-2"></i>Company Details</h5>
                <span class="badge-modern badge-<?= ($company['status'] ?? 'active') == 'active' ? 'success' : 'danger' ?>">
                    <?= ucfirst($company['status'] ?? 'active') ?>
                </span>
            </div>
            <div class="modern-card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Email</small>
                        <strong><?= htmlspecialchars($company['email'] ?? 'N/A') ?></strong>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Phone</small>
                        <strong><?= htmlspecialchars($company['phone'] ?? 'N/A') ?></strong>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Website</small>
                        <?php if ($company['website']): ?>
                            <a href="<?= htmlspecialchars($company['website']) ?>" target="_blank"><?= htmlspecialchars($company['website']) ?></a>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Industry</small>
                        <strong><?= htmlspecialchars($company['industry'] ?? 'N/A') ?></strong>
                    </div>
                    <div class="col-12 mb-3">
                        <small class="text-muted d-block">Address</small>
                        <p><?= nl2br(htmlspecialchars($company['address'] ?? 'N/A')) ?></p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Created</small>
                        <small><?= date('M d, Y', strtotime($company['created_at'])) ?></small>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Last Updated</small>
                        <small><?= date('M d, Y', strtotime($company['updated_at'])) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="modern-card mb-3">
            <div class="modern-card-header"><h5 class="modern-card-title">Quick Stats</h5></div>
            <div class="modern-card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Contacts</span>
                    <strong><?= count($contacts) ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Active Deals</span>
                    <strong><?= count($deals) ?></strong>
                </div>
            </div>
        </div>
        
        <div class="modern-card">
            <div class="modern-card-header"><h5 class="modern-card-title">Quick Actions</h5></div>
            <div class="modern-card-body">
                <a href="index.php?page=contacts&action=create&company_id=<?= $company['id'] ?>" class="btn-modern btn-modern-outline w-100 mb-2">
                    <i class="fas fa-user-plus me-1"></i> Add Contact
                </a>
                <a href="index.php?page=deals&action=create&company_id=<?= $company['id'] ?>" class="btn-modern btn-modern-outline w-100">
                    <i class="fas fa-handshake me-1"></i> New Deal
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function deleteCompany(id) {
    if (confirm('Are you sure you want to delete this company?')) {
        window.location.href = `index.php?page=companies&action=delete&id=${id}`;
    }
}
</script>