<?php
$openCount = $openCount ?? 0;
$inProgressCount = $inProgressCount ?? 0;
$resolvedCount = $resolvedCount ?? 0;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Support Tickets</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Tickets</li>
            </ol>
        </nav>
    </div>
    <a href="index.php?page=tickets&action=create" class="btn-modern btn-modern-primary">
        <i class="fas fa-plus"></i> New Ticket
    </a>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card warning">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-ticket-alt"></i></div>
        </div>
        <div class="stat-value"><?= $openCount ?></div>
        <div class="stat-label">Open Tickets</div>
    </div>
    
    <div class="stat-card info">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-spinner"></i></div>
        </div>
        <div class="stat-value"><?= $inProgressCount ?></div>
        <div class="stat-label">In Progress</div>
    </div>
    
    <div class="stat-card success">
        <div class="stat-card-header">
            <div class="stat-card-icon"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-value"><?= $resolvedCount ?></div>
        <div class="stat-label">Resolved</div>
    </div>
</div>

<!-- Tickets List -->
<div class="modern-card">
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Company</th>
                        <th>Subject</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tickets)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No tickets found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td>
                                    <span class="badge-modern badge-primary"><?= $ticket['ticket_number'] ?? 'N/A' ?></span>
                                </td>
                                <td><?= htmlspecialchars($ticket['companies']['name'] ?? 'N/A') ?></td>
                                <td>
                                    <a href="index.php?page=tickets&action=show&id=<?= $ticket['id'] ?>">
                                        <?= htmlspecialchars($ticket['subject']) ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge-modern badge-<?= getPriorityColor($ticket['priority'] ?? 'medium') ?>">
                                        <?= ucfirst($ticket['priority'] ?? 'medium') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-modern badge-<?= getStatusColor($ticket['status'] ?? 'open') ?>">
                                        <?= ucfirst(str_replace('_', ' ', $ticket['status'] ?? 'open')) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($ticket['users']['first_name'])): ?>
                                        <?= htmlspecialchars($ticket['users']['first_name'] . ' ' . $ticket['users']['last_name']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Unassigned</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= isset($ticket['created_at']) ? date('M d, Y', strtotime($ticket['created_at'])) : 'N/A' ?></td>
                                <td>
                                    <a href="index.php?page=tickets&action=show&id=<?= $ticket['id'] ?>" 
                                       class="btn-modern btn-modern-ghost btn-modern-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>