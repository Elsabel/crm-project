<?php
if (empty($contact)) {
    echo '<div class="alert alert-danger">Contact not found</div>';
    return;
}
?>

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=contacts&action=index">Contacts</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($contact['first_name']) ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="index.php?page=contacts&action=edit&id=<?= $contact['id'] ?>" class="btn-modern btn-modern-outline">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <button onclick="deleteContact('<?= $contact['id'] ?>')" class="btn-modern btn-modern-danger">
                <i class="fas fa-trash me-1"></i> Delete
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Contact Details -->
        <div class="modern-card mb-4">
            <div class="modern-card-header">
                <h5 class="modern-card-title"><i class="fas fa-user me-2"></i>Contact Information</h5>
                <span class="badge-modern badge-<?= ($contact['status'] ?? 'active') == 'active' ? 'success' : 'danger' ?>">
                    <?= ucfirst($contact['status'] ?? 'active') ?>
                </span>
            </div>
            <div class="modern-card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Full Name</small>
                        <strong><?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?></strong>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Company</small>
                        <?php if (!empty($contact['companies']['name'])): ?>
                            <a href="index.php?page=companies&action=show&id=<?= $contact['company_id'] ?>">
                                <?= htmlspecialchars($contact['companies']['name']) ?>
                            </a>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Email</small>
                        <?php if ($contact['email']): ?>
                            <a href="mailto:<?= htmlspecialchars($contact['email']) ?>"><?= htmlspecialchars($contact['email']) ?></a>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Phone</small>
                        <?php if ($contact['phone']): ?>
                            <a href="tel:<?= htmlspecialchars($contact['phone']) ?>"><?= htmlspecialchars($contact['phone']) ?></a>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Position</small>
                        <strong><?= htmlspecialchars($contact['position'] ?? 'N/A') ?></strong>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Department</small>
                        <strong><?= htmlspecialchars($contact['department'] ?? 'N/A') ?></strong>
                    </div>
                </div>
                
                <?php if (!empty($contact['notes'])): ?>
                    <hr>
                    <small class="text-muted d-block">Notes</small>
                    <p><?= nl2br(htmlspecialchars($contact['notes'])) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Quick Info -->
        <div class="modern-card mb-3">
            <div class="modern-card-body text-center">
                <div class="avatar-modern avatar-lg mx-auto mb-3" 
                     style="width:80px; height:80px; font-size:28px; background: <?= getAvatarColor($contact['first_name'] . ' ' . $contact['last_name']) ?>">
                    <?= strtoupper(substr($contact['first_name'], 0, 1) . substr($contact['last_name'], 0, 1)) ?>
                </div>
                <h5><?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?></h5>
                <p class="text-muted"><?= htmlspecialchars($contact['position'] ?? 'No position') ?></p>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="modern-card mb-3">
            <div class="modern-card-header"><h5 class="modern-card-title">Quick Actions</h5></div>
            <div class="modern-card-body">
                <a href="mailto:<?= htmlspecialchars($contact['email'] ?? '') ?>" class="btn-modern btn-modern-outline w-100 mb-2">
                    <i class="fas fa-envelope me-1"></i> Send Email
                </a>
                <a href="tel:<?= htmlspecialchars($contact['phone'] ?? '') ?>" class="btn-modern btn-modern-outline w-100 mb-2">
                    <i class="fas fa-phone me-1"></i> Call
                </a>
                <a href="index.php?page=activities&action=create&contact_id=<?= $contact['id'] ?>" class="btn-modern btn-modern-outline w-100">
                    <i class="fas fa-calendar-plus me-1"></i> Schedule Activity
                </a>
            </div>
        </div>
        
        <!-- Info -->
        <div class="modern-card">
            <div class="modern-card-body">
                <small class="text-muted d-block">Created</small>
                <small><?= date('M d, Y', strtotime($contact['created_at'])) ?></small>
                <hr>
                <small class="text-muted d-block">Last Updated</small>
                <small><?= date('M d, Y', strtotime($contact['updated_at'])) ?></small>
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