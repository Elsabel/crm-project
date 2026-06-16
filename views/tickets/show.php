<?php if (empty($ticket)): ?>
    <div class="modern-card">
        <div class="modern-card-body text-center py-5">
            <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
            <h4>Ticket Not Found</h4>
            <p class="text-muted">The ticket you're looking for doesn't exist or has been removed.</p>
            <a href="index.php?page=tickets&action=index" class="btn-modern btn-modern-primary">
                <i class="fas fa-list me-1"></i> View All Tickets
            </a>
        </div>
    </div>
<?php else: ?>

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2>
                <span class="badge-modern badge-primary me-2">#<?= htmlspecialchars($ticket['id'] ?? 'N/A') ?></span>
                <?= htmlspecialchars($ticket['subject']) ?>
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=tickets&action=index">Tickets</a></li>
                    <li class="breadcrumb-item active">View Ticket</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="index.php?page=tickets&action=index" class="btn-modern btn-modern-outline">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
            <form method="POST" action="index.php?page=tickets&action=update_status&id=<?= $ticket['id'] ?>" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
                    <option value="open" <?= ($ticket['status'] ?? '') == 'open' ? 'selected' : '' ?>>Open</option>
                    <option value="in_progress" <?= ($ticket['status'] ?? '') == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="waiting" <?= ($ticket['status'] ?? '') == 'waiting' ? 'selected' : '' ?>>Waiting</option>
                    <option value="resolved" <?= ($ticket['status'] ?? '') == 'resolved' ? 'selected' : '' ?>>Resolved</option>
                    <option value="closed" <?= ($ticket['status'] ?? '') == 'closed' ? 'selected' : '' ?>>Closed</option>
                </select>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Ticket Details Card -->
        <div class="modern-card mb-4">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="modern-card-title">
                    <i class="fas fa-info-circle me-2"></i>Ticket Details
                </h5>
                <div>
                    <span class="badge-modern badge-<?= getPriorityColor($ticket['priority'] ?? 'medium') ?> me-2">
                        <?= ucfirst($ticket['priority'] ?? 'medium') ?>
                    </span>
                    <span class="badge-modern badge-<?= getStatusColor($ticket['status'] ?? 'open') ?>">
                        <?= ucfirst(str_replace('_', ' ', $ticket['status'] ?? 'open')) ?>
                    </span>
                </div>
            </div>
            <div class="modern-card-body">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Company</small>
                        <strong>
                            <?php if (!empty($ticket['companies']['name'])): ?>
                                <a href="index.php?page=companies&action=show&id=<?= $ticket['company_id'] ?>">
                                    <?= htmlspecialchars($ticket['companies']['name']) ?>
                                </a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </strong>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Contact</small>
                        <strong>
                            <?php 
                            $contactName = trim(($ticket['contacts']['first_name'] ?? '') . ' ' . ($ticket['contacts']['last_name'] ?? ''));
                            echo $contactName ? htmlspecialchars($contactName) : 'N/A';
                            ?>
                        </strong>
                    </div>
                    <div class="col-md-4 mb-3">
                        <small class="text-muted d-block">Category</small>
                        <span class="badge-modern badge-info"><?= ucfirst($ticket['category'] ?? 'general') ?></span>
                    </div>
                    <div class="col-md-4 mb-3">
                        <small class="text-muted d-block">Priority</small>
                        <span class="badge-modern badge-<?= getPriorityColor($ticket['priority'] ?? 'medium') ?>">
                            <?= ucfirst($ticket['priority'] ?? 'medium') ?>
                        </span>
                    </div>
                    <div class="col-md-4 mb-3">
                        <small class="text-muted d-block">Status</small>
                        <span class="badge-modern badge-<?= getStatusColor($ticket['status'] ?? 'open') ?>">
                            <?= ucfirst(str_replace('_', ' ', $ticket['status'] ?? 'open')) ?>
                        </span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Created By</small>
                        <small><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Created Date</small>
                        <small><?= date('M d, Y H:i', strtotime($ticket['created_at'] ?? 'now')) ?></small>
                    </div>
                </div>
                
                <hr>
                
                <h6 class="mb-3">Description</h6>
                <div class="p-3 rounded" style="background:#f9f9f9; min-height:100px;">
                    <?= nl2br(htmlspecialchars($ticket['description'] ?? 'No description provided.')) ?>
                </div>
            </div>
        </div>
        
        <!-- Replies Section -->
        <div class="modern-card mb-4">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="fas fa-comments me-2"></i>Replies 
                    <span class="badge-modern badge-primary ms-2"><?= count($replies ?? []) ?></span>
                </h5>
            </div>
            <div class="modern-card-body">
                <?php if (empty($replies)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-comment-slash fa-2x mb-2"></i>
                        <p>No replies yet. Be the first to respond!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($replies as $reply): ?>
                        <div class="d-flex mb-3 pb-3 border-bottom">
                            <div class="me-3">
                                <div class="avatar-modern avatar-sm" style="background: <?= getAvatarColor(($reply['users']['first_name'] ?? '') . ' ' . ($reply['users']['last_name'] ?? '')) ?>">
                                    <?= strtoupper(substr($reply['users']['first_name'] ?? 'U', 0, 1) . substr($reply['users']['last_name'] ?? '', 0, 1)) ?>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between mb-1">
                                    <strong><?= htmlspecialchars(($reply['users']['first_name'] ?? '') . ' ' . ($reply['users']['last_name'] ?? 'User')) ?></strong>
                                    <small class="text-muted"><?= date('M d, Y H:i', strtotime($reply['created_at'])) ?></small>
                                </div>
                                <?php if ($reply['is_internal'] ?? false): ?>
                                    <span class="badge-modern badge-warning mb-1">Internal Note</span>
                                <?php endif; ?>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($reply['message'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <!-- Reply Form -->
                <div class="mt-3 pt-3 border-top">
                    <h6>Add Reply</h6>
                    <form action="index.php?page=tickets&action=reply&id=<?= $ticket['id'] ?>" method="POST">
                        <div class="form-group">
                            <textarea class="form-input" name="message" rows="4" required 
                                      placeholder="Type your reply here..."></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_internal" id="internalNote" value="1">
                                <label class="form-check-label" for="internalNote">Internal note (only visible to team)</label>
                            </div>
                            <button type="submit" class="btn-modern btn-modern-primary">
                                <i class="fas fa-paper-plane me-1"></i> Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Ticket Status Card -->
        <div class="modern-card mb-3">
            <div class="modern-card-header">
                <h5 class="modern-card-title"><i class="fas fa-cog me-2"></i>Ticket Actions</h5>
            </div>
            <div class="modern-card-body">
                <form method="POST" action="index.php?page=tickets&action=update_status&id=<?= $ticket['id'] ?>">
                    <label class="form-label">Update Status</label>
                    <select name="status" class="form-select mb-2">
                        <option value="open" <?= ($ticket['status'] ?? '') == 'open' ? 'selected' : '' ?>>Open</option>
                        <option value="in_progress" <?= ($ticket['status'] ?? '') == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="waiting" <?= ($ticket['status'] ?? '') == 'waiting' ? 'selected' : '' ?>>Waiting</option>
                        <option value="resolved" <?= ($ticket['status'] ?? '') == 'resolved' ? 'selected' : '' ?>>Resolved</option>
                        <option value="closed" <?= ($ticket['status'] ?? '') == 'closed' ? 'selected' : '' ?>>Closed</option>
                    </select>
                    <button type="submit" class="btn-modern btn-modern-outline w-100">
                        <i class="fas fa-save me-1"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Ticket Info Card -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title"><i class="fas fa-clock me-2"></i>Timeline</h5>
            </div>
            <div class="modern-card-body">
                <div class="mb-2">
                    <small class="text-muted">Created</small>
                    <br>
                    <small><?= date('M d, Y H:i', strtotime($ticket['created_at'] ?? 'now')) ?></small>
                </div>
                <?php if (!empty($ticket['resolved_at'])): ?>
                <div class="mb-2">
                    <small class="text-muted">Resolved</small>
                    <br>
                    <small><?= date('M d, Y H:i', strtotime($ticket['resolved_at'])) ?></small>
                </div>
                <?php endif; ?>
                <div>
                    <small class="text-muted">Last Updated</small>
                    <br>
                    <small><?= date('M d, Y H:i', strtotime($ticket['updated_at'] ?? $ticket['created_at'] ?? 'now')) ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>