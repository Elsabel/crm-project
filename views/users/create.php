<div class="mb-4">
    <h2>Add New User</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=users&action=index">Users</a></li>
            <li class="breadcrumb-item active">Add New</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title"><i class="fas fa-user-plus me-2"></i>User Information</h5>
            </div>
            <div class="modern-card-body">
                <form action="index.php?page=users&action=store" method="POST">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-input" name="first_name" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-input" name="last_name" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-input" name="email" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-input" name="password" required minlength="6">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" name="role" required onchange="toggleCompanyField(this.value)">
                                <option value="">Select Role</option>
                                <option value="superadmin">Super Admin</option>
                                <option value="admin">Admin</option>
                                <option value="company">Company User</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group" id="companyField" style="display:none;">
                            <label class="form-label">Company</label>
                            <select class="form-select" name="company_id">
                                <option value="">Select Company</option>
                                <?php foreach ($companies as $company): ?>
                                    <option value="<?= $company['id'] ?>"><?= htmlspecialchars($company['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="fas fa-save me-1"></i> Create User
                        </button>
                        <a href="index.php?page=users&action=index" class="btn-modern btn-modern-outline">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="modern-card">
            <div class="modern-card-header"><h5 class="modern-card-title">Role Permissions</h5></div>
            <div class="modern-card-body">
                <div class="mb-3"><span class="badge-modern badge-danger">Super Admin</span><br><small>Full system access, user management</small></div>
                <div class="mb-3"><span class="badge-modern badge-warning">Admin</span><br><small>Manage companies, deals, reports</small></div>
                <div><span class="badge-modern badge-info">Company</span><br><small>Limited to assigned company data</small></div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCompanyField(role) {
    document.getElementById('companyField').style.display = role === 'company' ? 'block' : 'none';
}
</script>