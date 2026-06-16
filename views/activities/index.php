<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Activities</h2>
        <p class="text-muted">Track tasks, calls, meetings and more</p>
    </div>
    <a href="index.php?page=activities&action=create" class="btn-modern btn-modern-primary">
        <i class="fas fa-plus"></i> New Activity
    </a>
</div>

<!-- Stats -->
<div class="stats-grid mb-4">
    <?php
    $pending = count(array_filter($activities, function($a) { return ($a['status'] ?? '') == 'pending'; }));
    $completed = count(array_filter($activities, function($a) { return ($a['status'] ?? '') == 'completed'; }));
    $overdue = count(array_filter($activities, function($a) { 
        return ($a['status'] ?? '') == 'pending' && strtotime($a['due_date']) < time(); 
    }));
    ?>
    <div class="stat-card warning"><div class="stat-card-header"><div class="stat-card-icon"><i class="fas fa-clock"></i></div></div><div class="stat-value"><?= $pending ?></div><div class="stat-label">Pending</div></div>
    <div class="stat-card success"><div class="stat-card-header"><div class="stat-card-icon"><i class="fas fa-check-circle"></i></div></div><div class="stat-value"><?= $completed ?></div><div class="stat-label">Completed</div></div>
    <div class="stat-card danger"><div class="stat-card-header"><div class="stat-card-icon"><i class="fas fa-exclamation-triangle"></i></div></div><div class="stat-value"><?= $overdue ?></div><div class="stat-label">Overdue</div></div>
</div>

<!-- Activities List -->
<div class="modern-card">
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Type</th>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($activities)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No activities found</td></tr>
                    <?php else: ?>
                        <?php foreach ($activities as $activity): 
                            $isOverdue = ($activity['status'] ?? '') == 'pending' && strtotime($activity['due_date']) < time();
                        ?>
                            <tr class="<?= $isOverdue ? 'table-danger' : '' ?>">
                                <td><strong><?= htmlspecialchars($activity['subject']) ?></strong></td>
                                <td><span class="badge-modern badge-info"><i class="fas fa-<?= getActivityIcon($activity['type'] ?? 'task') ?> me-1"></i><?= ucfirst($activity['type'] ?? 'N/A') ?></span></td>
                                <td><?= htmlspecialchars($activity['companies']['name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars(($activity['contacts']['first_name'] ?? '') . ' ' . ($activity['contacts']['last_name'] ?? '')) ?: 'N/A' ?></td>
                                <td class="<?= $isOverdue ? 'text-danger fw-bold' : '' ?>"><?= date('M d, Y H:i', strtotime($activity['due_date'])) ?></td>
                                <td><span class="badge-modern badge-<?= ($activity['status'] ?? '') == 'completed' ? 'success' : (($activity['status'] ?? '') == 'cancelled' ? 'danger' : 'warning') ?>"><?= ucfirst($activity['status'] ?? 'pending') ?></span></td>
                                <td>
                                    <a href="index.php?page=activities&action=edit&id=<?= $activity['id'] ?>" class="btn-modern btn-modern-ghost btn-modern-sm"><i class="fas fa-edit"></i></a>
                                    <button onclick="deleteActivity('<?= $activity['id'] ?>')" class="btn-modern btn-modern-ghost btn-modern-sm text-danger"><i class="fas fa-trash"></i></button>
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
function deleteActivity(id) {
    if (confirm('Are you sure you want to delete this activity?')) {
        window.location.href = `index.php?page=activities&action=delete&id=${id}`;
    }
}
</script>