<?php
$auth = new Auth();
$user = $auth->user();

if (!$user) {
    header('Location: index.php?page=login');
    exit;
}
?>

<div class="mb-4">
    <h2>My Profile</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">My Profile</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    <!-- Left Column - Profile Card -->
    <div class="col-12 col-md-5 col-lg-4">
        <!-- Profile Info Card -->
        <div class="modern-card mb-4">
            <div class="modern-card-body text-center p-4">
                <!-- Avatar -->
                <div class="position-relative d-inline-block mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold mx-auto"
                         style="width: 100px; height: 100px; font-size: 36px; background: <?= getAvatarColor($user['first_name'] . ' ' . $user['last_name']) ?>;">
                        <?= strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? '', 0, 1)) ?>
                    </div>
                    <span class="position-absolute bottom-0 end-0 p-1 rounded-circle" 
                          style="background: <?= ($user['status'] ?? 'active') == 'active' ? '#00c875' : '#c4c4c4' ?>; 
                                 width: 20px; height: 20px; border: 3px solid white;"
                          title="<?= ucfirst($user['status'] ?? 'active') ?>"></span>
                </div>
                
                <h4 class="mb-1"><?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></h4>
                <span class="badge-modern badge-<?= getRoleBadgeColor($user['role'] ?? 'guest') ?> mb-2">
                    <?= ucfirst($user['role'] ?? 'User') ?>
                </span>
                <p class="text-muted small mb-3">
                    <i class="fas fa-envelope me-1"></i> <?= htmlspecialchars($user['email'] ?? 'No email') ?>
                </p>
                
                <?php if (!empty($user['companies']['name'])): ?>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-building me-1"></i> <?= htmlspecialchars($user['companies']['name']) ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Stats Card -->
        <div class="modern-card mb-4">
            <div class="modern-card-body">
                <div class="d-flex justify-content-around text-center">
                    <div>
                        <div class="fw-bold fs-5">
                            <span class="badge-modern badge-<?= ($user['status'] ?? 'active') == 'active' ? 'success' : 'danger' ?>">
                                <?= ucfirst($user['status'] ?? 'active') ?>
                            </span>
                        </div>
                        <small class="text-muted">Status</small>
                    </div>
                    <div>
                        <div class="fw-bold fs-5">
                            <i class="fas fa-clock text-muted"></i>
                        </div>
                        <small class="text-muted"><?= !empty($user['last_login']) ? date('M d', strtotime($user['last_login'])) : 'Never' ?></small>
                        <br><small class="text-muted">Last Login</small>
                    </div>
                    <div>
                        <div class="fw-bold fs-5">
                            <i class="fas fa-calendar text-muted"></i>
                        </div>
                        <small class="text-muted"><?= !empty($user['created_at']) ? date('M Y', strtotime($user['created_at'])) : 'N/A' ?></small>
                        <br><small class="text-muted">Member Since</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column - Edit Forms -->
    <div class="col-12 col-md-7 col-lg-8">
        <!-- Personal Information -->
        <div class="modern-card mb-4">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="fas fa-user-edit me-2" style="color: var(--primary);"></i>Personal Information
                </h5>
            </div>
            <div class="modern-card-body">
                <form action="index.php?page=profile&action=update" method="POST" onsubmit="return validateProfileForm()">
                    <div class="row g-3">
                        <div class="col-md-6 form-group">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" name="first_name" 
                                       value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" 
                                       placeholder="Enter first name" required>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" name="last_name" 
                                       value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" 
                                       placeholder="Enter last name" required>
                            </div>
                        </div>
                        <div class="col-12 form-group">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <input type="email" class="form-control border-start-0" name="email" 
                                       value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                                       placeholder="Enter email address" required>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="text-muted mb-3">
                        <i class="fas fa-lock me-2"></i>Change Password 
                        <small class="fw-normal">(leave blank to keep current)</small>
                    </h6>
                    
                    <div class="row g-3">
                        <div class="col-md-6 form-group">
                            <label class="form-label">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-key text-muted"></i>
                                </span>
                                <input type="password" class="form-control border-start-0" 
                                       name="new_password" id="new_password"
                                       minlength="6" placeholder="Min 6 characters">
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-key text-muted"></i>
                                </span>
                                <input type="password" class="form-control border-start-0" 
                                       name="confirm_password" id="confirm_password"
                                       minlength="6" placeholder="Re-enter password">
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="text-muted mb-3">
                        <i class="fas fa-info-circle me-2"></i>Account Information 
                        <small class="fw-normal">(read-only)</small>
                    </h6>
                    
                    <div class="row g-3">
                        <div class="col-md-4 form-group">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control bg-light" 
                                   value="<?= ucfirst($user['role'] ?? 'User') ?>" readonly>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control bg-light" 
                                   value="<?= ucfirst($user['status'] ?? 'active') ?>" readonly>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label">Member Since</label>
                            <input type="text" class="form-control bg-light" 
                                   value="<?= !empty($user['created_at']) ? date('M d, Y', strtotime($user['created_at'])) : 'N/A' ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="fas fa-save me-1"></i> Update Profile
                        </button>
                        <a href="index.php?page=dashboard" class="btn-modern btn-modern-outline">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Additional styles for profile page */
.input-group-text {
    border: 1px solid #e6e9ef;
    border-radius: 8px 0 0 8px;
    padding: 10px 14px;
}

.form-control {
    border: 1px solid #e6e9ef;
    padding: 10px 14px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: #6161ff;
    box-shadow: 0 0 0 3px rgba(97, 97, 255, 0.1);
    outline: none;
}

.form-control.bg-light {
    background-color: #f8f9fa !important;
    cursor: not-allowed;
}

.form-control.border-start-0 {
    border-left: 0;
    border-radius: 0 8px 8px 0;
}

.input-group-text.border-end-0 {
    border-right: 0;
}

hr {
    border-color: #e6e9ef;
    opacity: 0.5;
}
</style>

<script>
function validateProfileForm() {
    const newPassword = document.getElementById('new_password')?.value || '';
    const confirmPassword = document.getElementById('confirm_password')?.value || '';
    
    if (newPassword || confirmPassword) {
        if (newPassword !== confirmPassword) {
            alert('Passwords do not match!');
            return false;
        }
        if (newPassword.length > 0 && newPassword.length < 6) {
            alert('Password must be at least 6 characters!');
            return false;
        }
    }
    
    // Show loading state
    const btn = document.querySelector('button[type="submit"]');
    if (btn) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';
        btn.disabled = true;
    }
    
    return true;
}
</script>