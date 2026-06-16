<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Companies</h2>
        <p class="text-muted">Manage your company relationships</p>
    </div>
    <a href="index.php?page=companies&action=create" class="btn-modern btn-modern-primary">
        <i class="fas fa-plus"></i> Add Company
    </a>
</div>

<!-- Search & Filter -->
<div class="modern-card mb-4">
    <div class="modern-card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-input" id="searchCompany" placeholder="Search companies..." onkeyup="filterCompanies()">
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterIndustry" onchange="filterCompanies()">
                    <option value="">All Industries</option>
                    <option value="Technology">Technology</option>
                    <option value="Healthcare">Healthcare</option>
                    <option value="Finance">Finance</option>
                    <option value="Education">Education</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterStatus" onchange="filterCompanies()">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Companies Grid -->
<div class="row" id="companiesGrid">
    <?php if (empty($companies)): ?>
        <div class="col-12">
            <div class="modern-card">
                <div class="modern-card-body text-center py-5">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <h4>No Companies Yet</h4>
                    <p class="text-muted">Start by adding your first company</p>
                    <a href="index.php?page=companies&action=create" class="btn-modern btn-modern-primary">
                        <i class="fas fa-plus"></i> Add Company
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($companies as $company): ?>
            <div class="col-md-6 col-lg-4 mb-4 company-card" 
                 data-name="<?= strtolower(htmlspecialchars($company['name'])) ?>"
                 data-industry="<?= strtolower(htmlspecialchars($company['industry'] ?? '')) ?>"
                 data-status="<?= $company['status'] ?>">
                <div class="modern-card h-100" style="cursor: pointer;" onclick="window.location='index.php?page=companies&action=show&id=<?= $company['id'] ?>'">
                    <div class="modern-card-body">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-modern avatar-lg me-3" style="background: <?= getAvatarColor($company['name']) ?>">
                                    <?= strtoupper(substr($company['name'], 0, 2)) ?>
                                </div>
                                <div>
                                    <h5 class="mb-1"><?= htmlspecialchars($company['name']) ?></h5>
                                    <small class="text-muted">
                                        <i class="fas fa-industry me-1"></i>
                                        <?= htmlspecialchars($company['industry'] ?? 'N/A') ?>
                                    </small>
                                </div>
                            </div>
                            <span class="badge-modern badge-<?= $company['status'] == 'active' ? 'success' : 'danger' ?>">
                                <?= ucfirst($company['status']) ?>
                            </span>
                        </div>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Email</small>
                                <small><?= htmlspecialchars($company['email'] ?? 'N/A') ?></small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Phone</small>
                                <small><?= htmlspecialchars($company['phone'] ?? 'N/A') ?></small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i>
                                <?= date('M d, Y', strtotime($company['created_at'])) ?>
                            </small>
                            <div class="btn-group">
                                <a href="index.php?page=companies&action=edit&id=<?= $company['id'] ?>" 
                                   class="btn-modern btn-modern-ghost btn-modern-sm" onclick="event.stopPropagation()">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn-modern btn-modern-ghost btn-modern-sm text-danger" 
                                        onclick="event.stopPropagation(); deleteCompany('<?= $company['id'] ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function filterCompanies() {
    const search = document.getElementById('searchCompany').value.toLowerCase();
    const industry = document.getElementById('filterIndustry').value.toLowerCase();
    const status = document.getElementById('filterStatus').value.toLowerCase();
    
    document.querySelectorAll('.company-card').forEach(card => {
        const name = card.dataset.name;
        const cardIndustry = card.dataset.industry;
        const cardStatus = card.dataset.status;
        
        const matchSearch = !search || name.includes(search);
        const matchIndustry = !industry || cardIndustry === industry;
        const matchStatus = !status || cardStatus === status;
        
        card.style.display = (matchSearch && matchIndustry && matchStatus) ? '' : 'none';
    });
}

function deleteCompany(id) {
    if (confirm('Are you sure you want to delete this company?')) {
        window.location.href = `index.php?page=companies&action=delete&id=${id}`;
    }
}
</script>