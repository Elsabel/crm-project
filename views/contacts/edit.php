<div class="mb-4">
    <h2>Edit Contact</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=contacts&action=index">Contacts</a></li>
            <li class="breadcrumb-item active">Edit: <?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?></li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="modern-card-title"><i class="fas fa-user-edit me-2"></i>Edit Contact Information</h5>
                <span class="badge-modern badge-<?= ($contact['status'] ?? 'active') == 'active' ? 'success' : 'danger' ?>">
                    <?= ucfirst($contact['status'] ?? 'active') ?>
                </span>
            </div>
            <div class="modern-card-body">
                <form action="index.php?page=contacts&action=update&id=<?= $contact['id'] ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-input" name="first_name" value="<?= htmlspecialchars($contact['first_name']) ?>" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-input" name="last_name" value="<?= htmlspecialchars($contact['last_name']) ?>" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" name="email" value="<?= htmlspecialchars($contact['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-input" name="phone" value="<?= htmlspecialchars($contact['phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Company <span class="text-danger">*</span></label>
                            <select class="form-select" name="company_id" required>
                                <?php foreach ($companies as $comp): 
                                    $sel = ($contact['company_id'] ?? '') == $comp['id'] ? 'selected' : '';
                                ?>
                                    <option value="<?= $comp['id'] ?>" <?= $sel ?>><?= htmlspecialchars($comp['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-input" name="position" value="<?= htmlspecialchars($contact['position'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="department">
                                <?php $depts = ['Sales','Marketing','Finance','HR','IT','Operations','Executive','Other'];
                                foreach ($depts as $d): 
                                    $sel = ($contact['department'] ?? '') == $d ? 'selected' : '';
                                ?>
                                    <option value="<?= $d ?>" <?= $sel ?>><?= $d ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="active" <?= ($contact['status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= ($contact['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-12 form-group">
                            <label class="form-label">Notes</label>
                            <textarea class="form-input" name="notes" rows="3"><?= htmlspecialchars($contact['notes'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="fas fa-save me-1"></i> Update Contact
                        </button>
                        <a href="index.php?page=contacts&action=index" class="btn-modern btn-modern-outline">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="button" onclick="deleteContact('<?= $contact['id'] ?>')" class="btn-modern btn-modern-danger ms-auto">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="modern-card">
            <div class="modern-card-header"><h5 class="modern-card-title">Contact Info</h5></div>
            <div class="modern-card-body">
                <p><strong>Created:</strong> <?= date('M d, Y', strtotime($contact['created_at'])) ?></p>
                <p><strong>Updated:</strong> <?= date('M d, Y', strtotime($contact['updated_at'])) ?></p>
            </div>
        </div>
    </div>
</div>

<script>
function deleteContact(id) {
    if (confirm('Are you sure you want to delete this contact?')) {
        window.location.href = `index.php?page=contacts&action=delete&id=${id}`;
    }
}
</script>