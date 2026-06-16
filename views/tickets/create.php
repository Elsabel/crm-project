<div class="mb-4">
    <h2>Create Support Ticket</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=tickets&action=index">Tickets</a></li>
            <li class="breadcrumb-item active">Create New</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title"><i class="fas fa-ticket-alt me-2"></i>Ticket Details</h5>
            </div>
            <div class="modern-card-body">
                <form action="index.php?page=tickets&action=store" method="POST">
                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-input" name="subject" placeholder="Brief description of the issue" required>
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
                            <label class="form-label">Contact</label>
                            <select class="form-select" name="contact_id">
                                <option value="">Select Contact</option>
                                <?php foreach ($contacts as $contact): ?>
                                    <option value="<?= $contact['id'] ?>"><?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label">Priority</label>
                            <select class="form-select" name="priority">
                                <option value="low">🟢 Low</option>
                                <option value="medium" selected>🟡 Medium</option>
                                <option value="high">🟠 High</option>
                                <option value="urgent">🔴 Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category">
                                <option value="general">General Inquiry</option>
                                <option value="technical">Technical Support</option>
                                <option value="billing">Billing Issue</option>
                                <option value="feature">Feature Request</option>
                                <option value="bug">Bug Report</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label">Assign To</label>
                            <select class="form-select" name="assigned_to">
                                <option value="">Unassigned</option>
                                <?php foreach ($users as $user): ?>
                                    <?php if (in_array($user['role'], ['superadmin', 'admin'])): ?>
                                        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['first_name']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 form-group">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-input" name="description" rows="6" placeholder="Detailed description of the issue..." required></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="fas fa-paper-plane me-1"></i> Submit Ticket
                        </button>
                        <a href="index.php?page=tickets&action=index" class="btn-modern btn-modern-outline">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="modern-card mb-3">
            <div class="modern-card-header"><h5 class="modern-card-title">Priority Guide</h5></div>
            <div class="modern-card-body">
                <div class="mb-2"><span class="badge-modern badge-danger">Urgent</span> <small>- Critical, needs immediate attention</small></div>
                <div class="mb-2"><span class="badge-modern badge-warning">High</span> <small>- Major impact on business</small></div>
                <div class="mb-2"><span class="badge-modern badge-info">Medium</span> <small>- Moderate impact</small></div>
                <div><span class="badge-modern badge-success">Low</span> <small>- Minor issue or inquiry</small></div>
            </div>
        </div>
    </div>
</div>