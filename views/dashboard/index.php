<?php
// No function declarations needed - all in helpers/functions.php
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Dashboard</h2>
        <p class="text-muted">Welcome back, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>!</p>
    </div>
    <div>
        <a href="index.php?page=tickets&action=create" class="btn-modern btn-modern-primary">
            <i class="fas fa-plus"></i> Create Ticket
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card primary" onclick="window.location='index.php?page=companies&action=index'" style="cursor:pointer;">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-building"></i></div>
            <div class="stat-change up"><i class="fas fa-arrow-up"></i> 12%</div>
        </div>
        <div class="stat-value"><?= $data['totalCompanies'] ?? 0 ?></div>
        <div class="stat-label">Total Companies</div>
    </div>
    
    <div class="stat-card success" onclick="window.location='index.php?page=contacts&action=index'" style="cursor:pointer;">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-address-book"></i></div>
            <div class="stat-change up"><i class="fas fa-arrow-up"></i> 8%</div>
        </div>
        <div class="stat-value"><?= $data['totalContacts'] ?? 0 ?></div>
        <div class="stat-label">Total Contacts</div>
    </div>
    
    <div class="stat-card warning" onclick="window.location='index.php?page=deals&action=index'" style="cursor:pointer;">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-handshake"></i></div>
            <div class="stat-change up"><i class="fas fa-arrow-up"></i> 15%</div>
        </div>
        <div class="stat-value"><?= $data['totalDeals'] ?? 0 ?></div>
        <div class="stat-label">Active Deals</div>
    </div>
    
    <div class="stat-card info" onclick="window.location='index.php?page=reports'" style="cursor:pointer;">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-dollar-sign"></i></div>
        </div>
        <div class="stat-value">$<?= number_format($data['totalDealValue'] ?? 0, 0) ?></div>
        <div class="stat-label">Pipeline Value</div>
    </div>
</div>

<!-- Recent Deals & Upcoming Activities -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title">Recent Deals</h5>
                <a href="index.php?page=deals&action=create" class="btn-modern btn-modern-primary btn-modern-sm">
                    <i class="fas fa-plus"></i> New Deal
                </a>
            </div>
            <div class="modern-card-body p-0">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Deal</th>
                            <th>Company</th>
                            <th>Value</th>
                            <th>Stage</th>
                            <th>Expected Close</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['recentDeals'])): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No deals yet</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['recentDeals'] as $deal): ?>
                                <tr>
                                    <td>
                                        <a href="index.php?page=deals&action=show&id=<?= $deal['id'] ?>">
                                            <?= htmlspecialchars($deal['title']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($deal['companies']['name'] ?? 'N/A') ?></td>
                                    <td><strong>$<?= number_format($deal['value'] ?? 0, 2) ?></strong></td>
                                    <td>
                                        <span class="badge-modern badge-<?= getStageColor($deal['stage'] ?? 'lead') ?>">
                                            <?= ucfirst(str_replace('_', ' ', $deal['stage'] ?? 'lead')) ?>
                                        </span>
                                    </td>
                                    <td><?= isset($deal['expected_close_date']) ? date('M d, Y', strtotime($deal['expected_close_date'])) : 'N/A' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title">Upcoming Activities</h5>
                <a href="index.php?page=activities&action=create" class="btn-modern btn-modern-ghost btn-modern-sm">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
            <div class="modern-card-body">
                <?php if (empty($data['upcomingActivities'])): ?>
                    <p class="text-muted text-center py-3">No upcoming activities</p>
                <?php else: ?>
                    <?php foreach ($data['upcomingActivities'] as $activity): ?>
                        <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                            <div class="me-3">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-<?= getActivityIcon($activity['type'] ?? 'task') ?> text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?= htmlspecialchars($activity['subject']) ?></h6>
                                <small class="text-muted">
                                    <?= htmlspecialchars($activity['companies']['name'] ?? 'N/A') ?>
                                </small>
                                <br>
                                <small class="text-<?= strtotime($activity['due_date']) < time() ? 'danger' : 'muted' ?>">
                                    <i class="far fa-clock me-1"></i>
                                    <?= date('M d, Y H:i', strtotime($activity['due_date'])) ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title">Quick Actions</h5>
            </div>
            <div class="modern-card-body">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="index.php?page=companies&action=create" class="btn-modern btn-modern-outline">
                        <i class="fas fa-building"></i> Add Company
                    </a>
                    <a href="index.php?page=contacts&action=create" class="btn-modern btn-modern-outline">
                        <i class="fas fa-user-plus"></i> Add Contact
                    </a>
                    <a href="index.php?page=deals&action=create" class="btn-modern btn-modern-outline">
                        <i class="fas fa-handshake"></i> New Deal
                    </a>
                    <a href="index.php?page=activities&action=create" class="btn-modern btn-modern-outline">
                        <i class="fas fa-calendar-plus"></i> Schedule Activity
                    </a>
                    <a href="index.php?page=tickets&action=create" class="btn-modern btn-modern-outline">
                        <i class="fas fa-ticket-alt"></i> Create Ticket
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>