<?php
use NovaCMS\Core\Flash;

$users = isset($users) ? $users : [];
$currentPage = isset($currentPage) ? $currentPage : 1;
$totalUsers = isset($totalUsers) ? $totalUsers : 0;

$layout = function() use ($users, $currentPage, $totalUsers) {
?>
<link rel="stylesheet" href="/css/users.css">

<div class="users-container">
    <div class="container">
        <div class="users-header">
            <h1 class="users-title">Manage Users</h1>
        </div>

        <?php if (Flash::has()): ?>
            <?php foreach (Flash::get() as $flash): ?>
                <div class="manage-posts-flash <?= $flash['type'] ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <?php if ($flash['type'] === 'success'): ?>
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        <?php else: ?>
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        <?php endif; ?>
                    </svg>
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="users-table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td data-label="Username">
                                <strong><?= htmlspecialchars($user->username) ?></strong>
                            </td>
                            <td data-label="Email">
                                <?= htmlspecialchars($user->email) ?>
                            </td>
                            <td data-label="Role">
                                <span class="user-role-badge role-<?= htmlspecialchars($user->role) ?>">
                                    <?= ucfirst($user->role) ?>
                                </span>
                            </td>
                            <td data-label="Status">
                                <span class="user-status-badge status-<?= htmlspecialchars($user->status) ?>">
                                    <?= ucfirst($user->status) ?>
                                </span>
                            </td>
                            <td data-label="Last Login">
                                <?= $user->lastLoginAt ? date('M d, Y', strtotime($user->lastLoginAt)) : 'Never' ?>
                            </td>
                            <td class="users-actions">
                                <a href="/admin/users/<?= $user->id ?>/edit" class="users-action-link">
                                    Edit
                                </a>
                                <?php if ($user->id != $_SESSION['user_id']): ?>
                                    <form method="POST" action="/admin/users/<?= $user->id ?>/delete" class="users-delete-form">
                                        <?php echo NovaCMS\Core\CSRF::field(); ?>
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')" class="users-delete-btn">
                                            Delete
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p style="text-align: center; color: #6b7280;">Total Users: <strong><?= $totalUsers ?></strong></p>
    </div>
</div>

<?php
};
$content = $layout;
include __DIR__ . '/../../layouts/main.php';
?>

