<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Contacts</h2>
        <p class="text-muted">Manage your contacts and relationships</p>
    </div>
    <a href="index.php?page=contacts&action=create" class="btn-modern btn-modern-primary">
        <i class="fas fa-plus"></i> Add Contact
    </a>
</div>

<!-- Search -->
<div class="modern-card mb-4">
    <div class="modern-card-body">
        <div class="row g-3">
            <div class="col-md-5">
                <input type="text" class="form-input" id="searchContact" placeholder="Search contacts..." onkeyup="filterContacts()">
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterCompany" onchange="filterContacts()">
                    <option value="">All Companies</option>
                    <?php 
                    $companies = array_unique(array_column($contacts, 'companies.name') ?? []);
                    foreach ($companies as $comp): if($comp): ?>
                        <option value="<?= strtolower($comp) ?>"><?= htmlspecialchars($comp) ?></option>
                    <?php endif; endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="filterStatus" onchange="filterContacts()">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Contacts List -->
<div class="modern-card">
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="modern-table" id="contactsTable">
                <thead>
                    <tr>
                        <th>Contact</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($contacts)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-address-book fa-3x mb-3 d-block"></i>
                                <p>No contacts found</p>
                                <a href="index.php?page=contacts&action=create" class="btn-modern btn-modern-primary btn-modern-sm">Add First Contact</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($contacts as $contact): ?>
                            <tr class="contact-row" 
                                data-name="<?= strtolower($contact['first_name'] . ' ' . $contact['last_name']) ?>"
                                data-company="<?= strtolower($contact['companies']['name'] ?? '') ?>"
                                data-status="<?= $contact['status'] ?? 'active' ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-modern avatar-sm me-2" style="background: <?= getAvatarColor($contact['first_name'] . ' ' . $contact['last_name']) ?>">
                                            <?= strtoupper(substr($contact['first_name'], 0, 1) . substr($contact['last_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <a href="index.php?page=contacts&action=show&id=<?= $contact['id'] ?>" class="fw-semibold">
                                                <?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($contact['companies']['name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($contact['email'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($contact['phone'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($contact['position'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="badge-modern badge-<?= ($contact['status'] ?? 'active') == 'active' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($contact['status'] ?? 'active') ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="index.php?page=contacts&action=show&id=<?= $contact['id'] ?>" 
                                           class="btn-modern btn-modern-ghost btn-modern-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="index.php?page=contacts&action=edit&id=<?= $contact['id'] ?>" 
                                           class="btn-modern btn-modern-ghost btn-modern-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteContact('<?= $contact['id'] ?>')" 
                                                class="btn-modern btn-modern-ghost btn-modern-sm text-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterContacts() {
    const search = document.getElementById('searchContact').value.toLowerCase();
    const company = document.getElementById('filterCompany').value.toLowerCase();
    const status = document.getElementById('filterStatus').value.toLowerCase();
    
    document.querySelectorAll('.contact-row').forEach(row => {
        const name = row.dataset.name;
        const rowCompany = row.dataset.company;
        const rowStatus = row.dataset.status;
        
        const matchSearch = !search || name.includes(search);
        const matchCompany = !company || rowCompany.includes(company);
        const matchStatus = !status || rowStatus === status;
        
        row.style.display = (matchSearch && matchCompany && matchStatus) ? '' : 'none';
    });
}

function deleteContact(id) {
    if (confirm('Are you sure you want to delete this contact?')) {
        window.location.href = `index.php?page=contacts&action=delete&id=${id}`;
    }
}
</script>