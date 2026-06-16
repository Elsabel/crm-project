<div class="mb-4">
    <h2>Add New Deal</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=deals&action=index">Deals</a></li>
            <li class="breadcrumb-item active">Add New</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title"><i class="fas fa-handshake me-2"></i>Deal Details</h5>
            </div>
            <div class="modern-card-body">
                <form action="index.php?page=deals&action=store" method="POST">
                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="form-label">Deal Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-input" name="title" placeholder="Enter deal title" required>
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
                            <label class="form-label">Contact Person</label>
                            <select class="form-select" name="contact_id">
                                <option value="">Select Contact</option>
                                <?php foreach ($contacts as $contact): ?>
                                    <option value="<?= $contact['id'] ?>"><?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label">Deal Value ($) <span class="text-danger">*</span></label>
                            <input type="number" class="form-input" name="value" step="0.01" min="0" placeholder="0.00" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label">Stage</label>
                            <select class="form-select" name="stage" id="dealStage" onchange="updateProbability()">
                                <option value="lead">Lead</option>
                                <option value="qualified">Qualified</option>
                                <option value="proposal">Proposal</option>
                                <option value="negotiation">Negotiation</option>
                                <option value="closed_won">Closed Won</option>
                                <option value="closed_lost">Closed Lost</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label">Probability (%)</label>
                            <input type="number" class="form-input" name="probability" id="probability" min="0" max="100" value="10" readonly>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Expected Close Date</label>
                            <input type="date" class="form-input" name="expected_close_date">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="open">Open</option>
                                <option value="won">Won</option>
                                <option value="lost">Lost</option>
                                <option value="on_hold">On Hold</option>
                            </select>
                        </div>
                        <div class="col-12 form-group">
                            <label class="form-label">Notes</label>
                            <textarea class="form-input" name="notes" rows="3" placeholder="Deal description or notes..."></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="fas fa-save me-1"></i> Save Deal
                        </button>
                        <a href="index.php?page=deals&action=index" class="btn-modern btn-modern-outline">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="modern-card mb-3">
            <div class="modern-card-header"><h5 class="modern-card-title">Stage Guide</h5></div>
            <div class="modern-card-body">
                <div class="mb-2"><span class="badge-modern badge-secondary">Lead</span> <small>10% - Initial contact</small></div>
                <div class="mb-2"><span class="badge-modern badge-info">Qualified</span> <small>25% - Need identified</small></div>
                <div class="mb-2"><span class="badge-modern badge-warning">Proposal</span> <small>50% - Proposal sent</small></div>
                <div class="mb-2"><span class="badge-modern badge-primary">Negotiation</span> <small>75% - In discussion</small></div>
                <div><span class="badge-modern badge-success">Closed</span> <small>100% - Won/Lost</small></div>
            </div>
        </div>
    </div>
</div>

<script>
function updateProbability() {
    const stage = document.getElementById('dealStage').value;
    const probMap = {'lead':10, 'qualified':25, 'proposal':50, 'negotiation':75, 'closed_won':100, 'closed_lost':0};
    document.getElementById('probability').value = probMap[stage] || 10;
}
</script>