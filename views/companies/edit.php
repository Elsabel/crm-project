<div class="mb-4">
    <h2>Edit Company</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=companies&action=index">Companies</a></li>
            <li class="breadcrumb-item active">Edit: <?= htmlspecialchars($company['name']) ?></li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="modern-card-title"><i class="fas fa-edit me-2"></i>Edit Company Information</h5>
                <span class="badge-modern badge-<?= ($company['status'] ?? 'active') == 'active' ? 'success' : 'danger' ?>">
                    <?= ucfirst($company['status'] ?? 'active') ?>
                </span>
            </div>
            <div class="modern-card-body">
                <form action="index.php?page=companies&action=update&id=<?= $company['id'] ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-input" name="name" value="<?= htmlspecialchars($company['name']) ?>" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" name="email" value="<?= htmlspecialchars($company['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-input" name="phone" value="<?= htmlspecialchars($company['phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Website</label>
                            <input type="url" class="form-input" name="website" value="<?= htmlspecialchars($company['website'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Industry</label>
                            <select class="form-select" name="industry">
                                <option value="">Select Industry</option>
                                <?php 
                                $industries = ['Technology', 'Healthcare', 'Finance', 'Education', 'Manufacturing', 'Retail', 'Other'];
                                foreach ($industries as $ind):
                                    $sel = ($company['industry'] ?? '') == $ind ? 'selected' : '';
                                ?>
                                    <option value="<?= $ind ?>" <?= $sel ?>><?= $ind ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="active" <?= ($company['status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= ($company['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                <option value="lead" <?= ($company['status'] ?? '') == 'lead' ? 'selected' : '' ?>>Lead</option>
                            </select>
                        </div>
                        <div class="col-12 form-group">
                            <label class="form-label">Address</label>
                            <textarea class="form-input" name="address" rows="3"><?= htmlspecialchars($company['address'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="fas fa-save me-1"></i> Update Company
                        </button>
                        <a href="index.php?page=companies&action=index" class="btn-modern btn-modern-outline">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="button" onclick="deleteCompany('<?= $company['id'] ?>')" class="btn-modern btn-modern-danger ms-auto">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="modern-card mb-3">
            <div class="modern-card-header"><h5 class="modern-card-title">Company Info</h5></div>
            <div class="modern-card-body">
                <p><strong>Created:</strong> <?= date('M d, Y', strtotime($company['created_at'])) ?></p>
                <p><strong>Updated:</strong> <?= date('M d, Y', strtotime($company['updated_at'])) ?></p>
                <hr>
                <a href="index.php?page=companies&action=show&id=<?= $company['id'] ?>" class="btn-modern btn-modern-outline w-100">
                    <i class="fas fa-eye me-1"></i> View Details
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function deleteCompany(id) {
    if (confirm('Are you sure you want to delete this company? This action cannot be undone.')) {
        window.location.href = `index.php?page=companies&action=delete&id=${id}`;
    }
}
</script>