<div class="mb-4">
    <h2>New Activity</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=activities&action=index">Activities</a></li>
            <li class="breadcrumb-item active">New</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title"><i class="fas fa-calendar-plus me-2"></i>Activity Details</h5>
            </div>
            <div class="modern-card-body">
                <form action="index.php?page=activities&action=store" method="POST">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label">Activity Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" required id="activityType">
                                <option value="">Select Type</option>
                                <option value="call">📞 Call</option>
                                <option value="email">✉️ Email</option>
                                <option value="meeting">👥 Meeting</option>
                                <option value="task">✅ Task</option>
                                <option value="follow_up">🔄 Follow Up</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-input" name="subject" placeholder="Activity subject" required>
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
                        <div class="col-md-6 form-group">
                            <label class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-input" name="due_date" required id="dueDate">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-12 form-group">
                            <label class="form-label">Description</label>
                            <textarea class="form-input" name="description" rows="4" placeholder="Activity description..."></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="fas fa-save me-1"></i> Save Activity
                        </button>
                        <a href="index.php?page=activities&action=index" class="btn-modern btn-modern-outline">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="modern-card">
            <div class="modern-card-header"><h5 class="modern-card-title">Quick Schedule</h5></div>
            <div class="modern-card-body">
                <button onclick="setDueDate(1)" class="btn-modern btn-modern-outline w-100 mb-2">📅 Tomorrow</button>
                <button onclick="setDueDate(3)" class="btn-modern btn-modern-outline w-100 mb-2">📅 In 3 Days</button>
                <button onclick="setDueDate(7)" class="btn-modern btn-modern-outline w-100">📅 Next Week</button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('dueDate').value = new Date(Date.now() + 86400000).toISOString().slice(0, 16);

function setDueDate(days) {
    const date = new Date(Date.now() + (days * 86400000));
    document.getElementById('dueDate').value = date.toISOString().slice(0, 16);
}
</script>