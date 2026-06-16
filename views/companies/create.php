<div class="mb-4">
    <h2>Add New Company</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=companies&action=index">Companies</a></li>
            <li class="breadcrumb-item active">Add New</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title"><i class="fas fa-building me-2"></i>Company Information</h5>
            </div>
            <div class="modern-card-body">
                <form action="index.php?page=companies&action=store" method="POST">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-input" name="name" placeholder="Enter company name" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" name="email" placeholder="company@example.com">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-input" name="phone" placeholder="+1 (555) 000-0000">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Website</label>
                            <input type="url" class="form-input" name="website" placeholder="https://example.com">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Industry</label>
                            <select class="form-select" name="industry">
                                <option value="">Select Industry</option>
                                <option value="Technology">Technology</option>
                                <option value="Healthcare">Healthcare</option>
                                <option value="Finance">Finance</option>
                                <option value="Education">Education</option>
                                <option value="Manufacturing">Manufacturing</option>
                                <option value="Retail">Retail</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="lead">Lead</option>
                            </select>
                        </div>
                        <div class="col-12 form-group">
                            <label class="form-label">Address</label>
                            <textarea class="form-input" name="address" rows="3" placeholder="Enter full address"></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="fas fa-save me-1"></i> Save Company
                        </button>
                        <a href="index.php?page=companies&action=index" class="btn-modern btn-modern-outline">
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
                <h5 class="modern-card-title"><i class="fas fa-info-circle me-2"></i>Tips</h5>
            </div>
            <div class="modern-card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Fill in as much detail as possible</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Verify email and phone number</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i> Add website for quick reference</li>
                </ul>
            </div>
        </div>
    </div>
</div>