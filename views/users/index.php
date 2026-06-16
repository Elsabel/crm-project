<?php
$auth = new Auth();
$currentUserId = $_SESSION['user_id'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>User Management</h2>
        <p class="text-muted">Manage system users and permissions</p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <div class="header-search d-none d-md-block">
            <i class="fas fa-search"></i>
            <input type="text" id="tableSearch" placeholder="Search users..." onkeyup="searchTable()">
        </div>
        <button class="btn-modern btn-modern-primary" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add User
        </button>
    </div>
</div>

<!-- Stats Row -->
<div class="stats-grid mb-4">
    <div class="stat-card primary">
        <div class="stat-card-header"><div class="stat-card-icon"><i class="fas fa-users"></i></div></div>
        <div class="stat-value"><?= count($users) ?></div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card success">
        <div class="stat-card-header"><div class="stat-card-icon"><i class="fas fa-check-circle"></i></div></div>
        <div class="stat-value"><?= count(array_filter($users, function($u) { return ($u['status'] ?? '') == 'active'; })) ?></div>
        <div class="stat-label">Active</div>
    </div>
    <div class="stat-card danger">
        <div class="stat-card-header"><div class="stat-card-icon"><i class="fas fa-shield-alt"></i></div></div>
        <div class="stat-value"><?= count(array_filter($users, function($u) { return ($u['role'] ?? '') == 'superadmin'; })) ?></div>
        <div class="stat-label">Super Admins</div>
    </div>
    <div class="stat-card info">
        <div class="stat-card-header"><div class="stat-card-icon"><i class="fas fa-building"></i></div></div>
        <div class="stat-value"><?= count(array_filter($users, function($u) { return ($u['role'] ?? '') == 'company'; })) ?></div>
        <div class="stat-label">Company Users</div>
    </div>
</div>

<!-- Users Table -->
<div class="modern-card">
    <div class="modern-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="modern-card-title"><i class="fas fa-list me-2"></i>All Users</h5>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" id="roleFilter" onchange="searchTable()" style="width:auto; min-width:130px;">
                <option value="">All Roles</option>
                <option value="superadmin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="company">Company</option>
            </select>
            <select class="form-select form-select-sm" id="statusFilter" onchange="searchTable()" style="width:auto; min-width:130px;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="modern-table" id="usersTable">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th style="width:120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-users fa-3x mb-3 d-block opacity-50"></i>
                                <p>No users found</p>
                                <button class="btn-modern btn-modern-primary btn-modern-sm" onclick="openAddModal()">
                                    <i class="fas fa-plus me-1"></i> Add First User
                                </button>
                            </td>
                        </tr>
                    <?php else: $i = 1; foreach ($users as $user): ?>
                        <tr class="user-row" 
                            data-name="<?= strtolower($user['first_name'] . ' ' . $user['last_name']) ?>"
                            data-email="<?= strtolower($user['email']) ?>"
                            data-role="<?= $user['role'] ?>"
                            data-status="<?= $user['status'] ?>">
                            <td><?= $i++ ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-modern avatar-sm me-2" style="background: <?= getAvatarColor($user['first_name'] . ' ' . $user['last_name']) ?>">
                                        <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong>
                                    </div>
                                </div>
                            </td>
                            <td><small><?= htmlspecialchars($user['email']) ?></small></td>
                            <td>
                                <span class="badge-modern badge-<?= getRoleBadgeColor($user['role']) ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td><small><?= htmlspecialchars($user['companies']['name'] ?? '—') ?></small></td>
                            <td>
                                <span class="badge-modern badge-<?= ($user['status'] ?? 'active') == 'active' ? 'success' : 'danger' ?> status-badge">
                                    <?= ucfirst($user['status'] ?? 'active') ?>
                                </span>
                            </td>
                            <td><small class="text-muted"><?= isset($user['last_login']) ? date('M d, Y', strtotime($user['last_login'])) : 'Never' ?></small></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn-modern btn-modern-ghost btn-modern-sm" 
                                            onclick="openEditModal('<?= $user['id'] ?>', '<?= htmlspecialchars(addslashes($user['first_name'])) ?>', '<?= htmlspecialchars(addslashes($user['last_name'])) ?>', '<?= htmlspecialchars(addslashes($user['email'])) ?>', '<?= $user['role'] ?>', '<?= $user['status'] ?>', '<?= $user['company_id'] ?? '' ?>')"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-modern btn-modern-ghost btn-modern-sm" 
                                            onclick="quickToggleStatus('<?= $user['id'] ?>', '<?= $user['status'] ?>')"
                                            title="<?= ($user['status'] ?? 'active') == 'active' ? 'Deactivate' : 'Activate' ?>">
                                        <i class="fas fa-<?= ($user['status'] ?? 'active') == 'active' ? 'toggle-on text-success' : 'toggle-off text-muted' ?>"></i>
                                    </button>
                                    <?php if ($user['id'] != $currentUserId): ?>
                                        <button class="btn-modern btn-modern-ghost btn-modern-sm text-danger" 
                                                onclick="confirmDelete('<?= $user['id'] ?>', '<?= htmlspecialchars(addslashes($user['first_name'] . ' ' . $user['last_name'])) ?>')"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal-overlay" id="userModal">
    <div class="modal-modern" style="max-width:600px;">
        <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">
                <i class="fas fa-user-plus me-2"></i>Add New User
            </h5>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="userForm" onsubmit="saveUser(event)">
                <input type="hidden" name="id" id="userId">
                <input type="hidden" name="action" id="formAction" value="store">
                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-input" name="first_name" id="firstName" required placeholder="John">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-input" name="last_name" id="lastName" required placeholder="Doe">
                    </div>
                    <div class="col-md-12 form-group">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-input" name="email" id="email" required placeholder="john@example.com">
                    </div>
                    <div class="col-md-6 form-group" id="passwordGroup">
                        <label class="form-label">Password <span class="text-danger" id="passwordReq">*</span></label>
                        <input type="password" class="form-input" name="password" id="password" minlength="6" placeholder="Min 6 characters">
                        <small class="text-muted" id="passwordHint">Leave blank to keep current password</small>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" name="role" id="role" required onchange="toggleCompanySelect()">
                            <option value="">Select Role</option>
                            <option value="superadmin">Super Admin</option>
                            <option value="admin">Admin</option>
                            <option value="company">Company User</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group" id="companySelectGroup" style="display:none;">
                        <label class="form-label">Company</label>
                        <select class="form-select" name="company_id" id="companyId">
                            <option value="">Select Company</option>
                            <?php 
                            $companyModel = new Company();
                            $companies = $companyModel->getAll();
                            foreach ($companies as $comp): 
                            ?>
                                <option value="<?= $comp['id'] ?>"><?= htmlspecialchars($comp['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" id="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn-modern btn-modern-primary" id="submitBtn">
                        <i class="fas fa-save me-1"></i> Save User
                    </button>
                    <button type="button" class="btn-modern btn-modern-outline" onclick="closeModal()">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-modern" style="max-width:420px;">
        <div class="modal-header">
            <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Confirm Delete</h5>
            <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body text-center">
            <i class="fas fa-user-slash fa-3x text-danger mb-3"></i>
            <p>Are you sure you want to delete user <strong id="deleteUserName"></strong>?</p>
            <p class="text-danger"><small>This action cannot be undone.</small></p>
        </div>
        <div class="modal-footer">
            <button class="btn-modern btn-modern-outline" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-modern btn-modern-danger" id="confirmDeleteBtn">
                <i class="fas fa-trash me-1"></i> Delete User
            </button>
        </div>
    </div>
</div>

<script>
// Open Add Modal
function openAddModal() {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus me-2"></i>Add New User';
    document.getElementById('formAction').value = 'store';
    document.getElementById('userId').value = '';
    document.getElementById('firstName').value = '';
    document.getElementById('lastName').value = '';
    document.getElementById('email').value = '';
    document.getElementById('password').value = '';
    document.getElementById('role').value = '';
    document.getElementById('status').value = 'active';
    document.getElementById('companyId').value = '';
    document.getElementById('passwordReq').style.display = '';
    document.getElementById('passwordHint').style.display = 'none';
    document.getElementById('password').required = true;
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-1"></i> Save User';
    document.getElementById('companySelectGroup').style.display = 'none';
    document.getElementById('userModal').classList.add('active');
}

// Open Edit Modal
function openEditModal(id, firstName, lastName, email, role, status, companyId) {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-edit me-2"></i>Edit User';
    document.getElementById('formAction').value = 'update';
    document.getElementById('userId').value = id;
    document.getElementById('firstName').value = firstName;
    document.getElementById('lastName').value = lastName;
    document.getElementById('email').value = email;
    document.getElementById('password').value = '';
    document.getElementById('role').value = role;
    document.getElementById('status').value = status;
    document.getElementById('companyId').value = companyId || '';
    document.getElementById('passwordReq').style.display = 'none';
    document.getElementById('passwordHint').style.display = '';
    document.getElementById('password').required = false;
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-1"></i> Update User';
    document.getElementById('companySelectGroup').style.display = role === 'company' ? 'block' : 'none';
    document.getElementById('userModal').classList.add('active');
}

// Close Modal
function closeModal() {
    document.getElementById('userModal').classList.remove('active');
    document.getElementById('userForm').reset();
}

// Toggle Company Select
function toggleCompanySelect() {
    const role = document.getElementById('role').value;
    document.getElementById('companySelectGroup').style.display = role === 'company' ? 'block' : 'none';
}

// Save User (Add or Update)
function saveUser(event) {
    event.preventDefault();
    
    const action = document.getElementById('formAction').value;
    const id = document.getElementById('userId').value;
    const password = document.getElementById('password').value;
    
    // Validate password for new users
    if (action === 'store' && !password) {
        alert('Password is required for new users');
        return;
    }
    
    if (password && password.length < 6) {
        alert('Password must be at least 6 characters');
        return;
    }
    
    // Build form data
    const formData = new FormData(document.getElementById('userForm'));
    
    // Determine URL
    const url = action === 'store' 
        ? 'index.php?page=users&action=store' 
        : `index.php?page=users&action=update&id=${id}`;
    
    // Submit form
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            window.location.reload();
        } else {
            alert('Error saving user. Please try again.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i> Save User';
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i> Save User';
    });
}

// Quick Toggle Status
function quickToggleStatus(id, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    const action = currentStatus === 'active' ? 'deactivate' : 'activate';
    
    if (confirm(`Are you sure you want to ${action} this user?`)) {
        window.location.href = `index.php?page=users&action=update&id=${id}&quick_status=${newStatus}`;
    }
}

// Delete Confirmation
let deleteId = null;
function confirmDelete(id, name) {
    deleteId = id;
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    deleteId = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteId) {
        window.location.href = `index.php?page=users&action=delete&id=${deleteId}`;
    }
});

// Table Search & Filter
function searchTable() {
    const search = document.getElementById('tableSearch').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    document.querySelectorAll('.user-row').forEach(row => {
        const name = row.dataset.name;
        const email = row.dataset.email;
        const role = row.dataset.role;
        const status = row.dataset.status;
        
        const matchSearch = !search || name.includes(search) || email.includes(search);
        const matchRole = !roleFilter || role === roleFilter;
        const matchStatus = !statusFilter || status === statusFilter;
        
        row.style.display = (matchSearch && matchRole && matchStatus) ? '' : 'none';
    });
}

// Close modals on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('active'));
    }
});
</script>

<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease;
}

.modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.modal-modern {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e6e9ef;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    background: white;
    z-index: 1;
    border-radius: 12px 12px 0 0;
}

.modal-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

.modal-close {
    width: 32px;
    height: 32px;
    border: none;
    background: #f5f6f8;
    border-radius: 8px;
    cursor: pointer;
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.modal-close:hover {
    background: #e6e9ef;
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e6e9ef;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

/* Responsive */
@media (max-width: 768px) {
    .modal-modern {
        width: 95%;
        max-height: 95vh;
        border-radius: 8px;
    }
    
    .header-search {
        display: none;
    }
}
</style>