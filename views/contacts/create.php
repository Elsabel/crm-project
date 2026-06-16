<div class="mb-4">
    <h2>Add New Contact</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=contacts&action=index">Contacts</a></li>
            <li class="breadcrumb-item active">Add New</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title"><i class="fas fa-user-plus me-2"></i>Contact Information</h5>
            </div>
            <div class="modern-card-body">
                <form action="index.php?page=contacts&action=store" method="POST">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-input" name="first_name" placeholder="John" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-input" name="last_name" placeholder="Doe" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" name="email" placeholder="john@example.com">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-input" name="phone" placeholder="+1 (555) 000-0000">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Company <span class="text-danger">*</span></label>
                            <select class="form-select" name="company_id" required>
                                <option value="">Select Company</option>
                                <?php foreach ($companies as $company): ?>
                                    <option value="<?= $company['id'] ?>"><?= htmlspecialchars($company['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-input" name="position" placeholder="e.g. CEO, Manager">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="department">
                                <option value="">Select Department</option>
                                <option value="Sales">Sales</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Finance">Finance</option>
                                <option value="HR">HR</option>
                                <option value="IT">IT</option>
                                <option value="Executive">Executive</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12 form-group">
                            <label class="form-label">Notes</label>
                            <textarea class="form-input" name="notes" rows="3" placeholder="Any additional notes..."></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="fas fa-save me-1"></i> Save Contact
                        </button>
                        <a href="index.php?page=contacts&action=index" class="btn-modern btn-modern-outline">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title"><i class="fas fa-lightbulb me-2"></i>Quick Tips</h5>
            </div>
            <div class="modern-card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Use professional email</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Include direct phone line</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Select correct company</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i> Add position for context</li>
                </ul>
            </div>
        </div>
    </div>
</div>