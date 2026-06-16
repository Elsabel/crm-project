<div class="row mb-4">
    <div class="col-md-12">
        <h2>Edit Activity</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=activities&action=index">Activities</a></li>
                <li class="breadcrumb-item active">Edit: <?= htmlspecialchars($activity['subject']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Activity Details</h5>
        <span class="badge bg-<?= $activity['status'] == 'completed' ? 'success' : ($activity['status'] == 'cancelled' ? 'danger' : 'warning') ?>">
            <?= ucfirst($activity['status']) ?>
        </span>
    </div>
    <div class="card-body">
        <form action="index.php?page=activities&action=update&id=<?= $activity['id'] ?>" method="POST" class="needs-validation" novalidate>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label">Activity Type *</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="">Select Type</option>
                        <?php
                        $types = [
                            'call' => '📞 Call',
                            'email' => '✉️ Email',
                            'meeting' => '👥 Meeting',
                            'task' => '✅ Task',
                            'follow_up' => '🔄 Follow Up'
                        ];
                        foreach ($types as $value => $label):
                            $selected = $activity['type'] == $value ? 'selected' : '';
                        ?>
                            <option value="<?= $value ?>" <?= $selected ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Please select activity type</div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="subject" class="form-label">Subject *</label>
                    <input type="text" class="form-control" id="subject" name="subject" 
                           value="<?= htmlspecialchars($activity['subject']) ?>" required>
                    <div class="invalid-feedback">Please enter subject</div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="company_id" class="form-label">Company *</label>
                    <select class="form-select" id="company_id" name="company_id" required>
                        <option value="">Select Company</option>
                        <?php foreach ($companies as $company): 
                            $selected = $activity['company_id'] == $company['id'] ? 'selected' : '';
                        ?>
                            <option value="<?= $company['id'] ?>" <?= $selected ?>>
                                <?= htmlspecialchars($company['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Please select company</div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="contact_id" class="form-label">Contact</label>
                    <select class="form-select" id="contact_id" name="contact_id">
                        <option value="">Select Contact (Optional)</option>
                        <?php foreach ($contacts as $contact): 
                            $selected = $activity['contact_id'] == $contact['id'] ? 'selected' : '';
                        ?>
                            <option value="<?= $contact['id'] ?>" <?= $selected ?>>
                                <?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="deal_id" class="form-label">Related Deal</label>
                    <select class="form-select" id="deal_id" name="deal_id">
                        <option value="">Select Deal (Optional)</option>
                        <?php foreach ($deals as $deal): 
                            $selected = $activity['deal_id'] == $deal['id'] ? 'selected' : '';
                        ?>
                            <option value="<?= $deal['id'] ?>" <?= $selected ?>>
                                <?= htmlspecialchars($deal['title']) ?> 
                                ($<?= number_format($deal['value'] ?? 0, 0) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="due_date" class="form-label">Due Date *</label>
                    <input type="datetime-local" class="form-control" id="due_date" name="due_date" 
                           value="<?= date('Y-m-d\TH:i', strtotime($activity['due_date'])) ?>" required>
                    <div class="invalid-feedback">Please select due date</div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="pending" <?= $activity['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="completed" <?= $activity['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $activity['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="4"><?= htmlspecialchars($activity['description'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Activity
                </button>
                <a href="index.php?page=activities&action=index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="button" onclick="deleteActivity('<?= $activity['id'] ?>')" 
                        class="btn btn-danger float-end">
                    <i class="fas fa-trash"></i> Delete Activity
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Activity Timeline -->
<div class="card mt-4">
    <div class="card-header">
        <h5>Activity Timeline</h5>
    </div>
    <div class="card-body">
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-marker bg-primary"></div>
                <div class="timeline-content">
                    <h6>Activity Created</h6>
                    <small class="text-muted">
                        <?= date('M d, Y H:i', strtotime($activity['created_at'])) ?>
                    </small>
                </div>
            </div>
            
            <?php if ($activity['updated_at'] != $activity['created_at']): ?>
            <div class="timeline-item">
                <div class="timeline-marker bg-warning"></div>
                <div class="timeline-content">
                    <h6>Last Updated</h6>
                    <small class="text-muted">
                        <?= date('M d, Y H:i', strtotime($activity['updated_at'])) ?>
                    </small>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Form validation
(function () {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

function deleteActivity(id) {
    if (confirm('Are you sure you want to delete this activity? This action cannot be undone.')) {
        window.location.href = `index.php?page=activities&action=delete&id=${id}`;
    }
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -22px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item:last-child::before {
    bottom: auto;
    height: 20px;
}

.timeline-marker {
    position: absolute;
    left: -28px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    padding-bottom: 10px;
}
</style>