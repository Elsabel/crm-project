<?php
// Shared functions used across views - Define ONCE here

if (!function_exists('getPageTitle')) {
    function getPageTitle($page) {
        $titles = [
            'dashboard' => 'Dashboard',
            'companies' => 'Companies',
            'contacts' => 'Contacts',
            'deals' => 'Deals Pipeline',
            'activities' => 'Activities',
            'reports' => 'Reports & Analytics',
            'tickets' => 'Support Tickets',
            'notifications' => 'Notifications',
            'users' => 'User Management',
            'profile' => 'My Profile',
            'settings' => 'Settings'
        ];
        return $titles[$page] ?? 'CRM Pro';
    }
}

if (!function_exists('getAvatarColor')) {
    function getAvatarColor($name) {
        $colors = ['#6161ff', '#00c875', '#ffcb00', '#ff4757', '#579bfc', '#a358df', '#e2445c', '#7b68ee'];
        $index = crc32($name) % count($colors);
        return $colors[abs($index)];
    }
}

if (!function_exists('getRoleBadgeColor')) {
    function getRoleBadgeColor($role) {
        switch($role) {
            case 'superadmin': return 'danger';
            case 'admin': return 'warning';
            case 'company': return 'info';
            default: return 'secondary';
        }
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo($timestamp) {
        $diff = time() - strtotime($timestamp);
        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return floor($diff / 60) . 'm ago';
        if ($diff < 86400) return floor($diff / 3600) . 'h ago';
        if ($diff < 604800) return floor($diff / 86400) . 'd ago';
        return date('M d', strtotime($timestamp));
    }
}

if (!function_exists('getPriorityColor')) {
    function getPriorityColor($priority) {
        switch($priority) {
            case 'urgent': return 'danger';
            case 'high': return 'warning';
            case 'medium': return 'info';
            case 'low': return 'success';
            default: return 'info';
        }
    }
}

if (!function_exists('getStatusColor')) {
    function getStatusColor($status) {
        switch($status) {
            case 'open': return 'warning';
            case 'in_progress': return 'info';
            case 'resolved': return 'success';
            case 'closed': return 'secondary';
            case 'waiting': return 'secondary';
            default: return 'secondary';
        }
    }
}

if (!function_exists('getStageColor')) {
    function getStageColor($stage) {
        switch($stage) {
            case 'lead': return 'secondary';
            case 'qualified': return 'info';
            case 'proposal': return 'warning';
            case 'negotiation': return 'primary';
            case 'closed_won': return 'success';
            case 'closed_lost': return 'danger';
            default: return 'secondary';
        }
    }
}

if (!function_exists('getActivityIcon')) {
    function getActivityIcon($type) {
        switch($type) {
            case 'call': return 'phone';
            case 'email': return 'envelope';
            case 'meeting': return 'users';
            case 'task': return 'check-square';
            case 'follow_up': return 'reply';
            default: return 'tasks';
        }
    }
}