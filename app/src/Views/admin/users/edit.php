<?php
use NovaCMS\Core\Flash;
use NovaCMS\Core\CSRF;

$user = isset($user) ? $user : null;

$layout = function() use ($user) {
?>
<link rel="stylesheet" href="/css/users.css">

<div class="user-edit-container">
    <div class="container">
        <div class="users-header">
            <h1 class="users-title">Edit User: <?= htmlspecialchars($user->username) ?></h1>
            <a href="/admin/users" class="user-btn-secondary">← Back to Users</a>
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

        <!-- User Info -->
        <div class="user-edit-card">
            <h2 class="user-edit-section-title">User Information</h2>
            <div class="user-info-grid">
                <div class="user-info-item">
                    <span class="user-info-label">User ID:</span>
                    <span class="user-info-value">#<?= $user->id ?></span>
                </div>
                <div class="user-info-item">
                    <span class="user-info-label">Username:</span>
                    <span class="user-info-value"><?= htmlspecialchars($user->username) ?></span>
                </div>
                <div class="user-info-item">
                    <span class="user-info-label">Email:</span>
                    <span class="user-info-value"><?= htmlspecialchars($user->email) ?></span>
                </div>
                <div class="user-info-item">
                    <span class="user-info-label">Registered:</span>
                    <span class="user-info-value"><?= date('M d, Y', strtotime($user->createdAt)) ?></span>
                </div>
                <div class="user-info-item">
                    <span class="user-info-label">Last Login:</span>
                    <span class="user-info-value"><?= $user->lastLoginAt ? date('M d, Y H:i', strtotime($user->lastLoginAt)) : 'Never' ?></span>
                </div>
            </div>
        </div>

        <!-- Update Role -->
        <div class="user-edit-card">
            <h2 class="user-edit-section-title">Update Role</h2>
            <form method="POST" action="/admin/users/<?= $user->id ?>/update-role">
                <?= CSRF::field() ?>
                <div class="user-form-group">
                    <label for="role" class="user-form-label">Role</label>
                    <select id="role" name="role" class="user-form-select">
                        <option value="admin" <?= $user->role === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="editor" <?= $user->role === 'editor' ? 'selected' : '' ?>>Editor</option>
                        <option value="author" <?= $user->role === 'author' ? 'selected' : '' ?>>Author</option>
                        <option value="viewer" <?= $user->role === 'viewer' ? 'selected' : '' ?>>Viewer</option>
                    </select>
                    <span class="user-form-help">Admin: Full access | Editor: Manage all posts | Author: Own posts | Viewer: Read-only</span>
                </div>
                <div class="user-form-actions">
                    <button type="submit" class="user-btn-primary">Update Role</button>
                </div>
            </form>
        </div>

        <!-- Update Status -->
        <div class="user-edit-card">
            <h2 class="user-edit-section-title">Update Status</h2>
            <form method="POST" action="/admin/users/<?= $user->id ?>/update-status">
                <?= CSRF::field() ?>
                <div class="user-form-group">
                    <label for="status" class="user-form-label">Status</label>
                    <select id="status" name="status" class="user-form-select">
                        <option value="active" <?= $user->status === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $user->status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                    <span class="user-form-help">Active: Can login | Inactive: Cannot login</span>
                </div>
                <div class="user-form-actions">
                    <button type="submit" class="user-btn-primary">Update Status</button>
                </div>
            </form>
        </div>

        <!-- Reset Password -->
        <div class="user-edit-card">
            <h2 class="user-edit-section-title">Reset Password</h2>
            <div class="user-warning-box">
                <p><strong>Warning:</strong> This will reset the user's password. They will need to use the new password to login.</p>
            </div>
            <form method="POST" action="/admin/users/<?= $user->id ?>/reset-password">
                <?= CSRF::field() ?>
                <div class="user-form-group">
                    <label for="password" class="user-form-label">New Password</label>
                    <input type="password" id="password" name="password" class="user-form-input" placeholder="Enter new password" required>
                    <span class="user-form-help">Minimum 8 characters</span>
                </div>
                <div class="user-form-group">
                    <label for="confirm_password" class="user-form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="user-form-input" placeholder="Confirm new password" required>
                </div>
                <div class="user-form-actions">
                    <button type="submit" class="user-btn-primary">Reset Password</button>
                </div>
            </form>
        </div>

        <!-- Delete User -->
        <?php if ($user->id != $_SESSION['user_id']): ?>
            <div class="user-edit-card">
                <h2 class="user-edit-section-title">Danger Zone</h2>
                <div class="user-warning-box">
                    <p><strong>Warning:</strong> This action cannot be undone. All posts by this user will be orphaned.</p>
                </div>
                <form method="POST" action="/admin/users/<?= $user->id ?>/delete" onsubmit="return confirm('Are you absolutely sure you want to delete this user? This action cannot be undone.');">
                    <?= CSRF::field() ?>
                    <button type="submit" class="user-btn-danger">Delete User</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
};
$content = $layout;
include __DIR__ . '/../../layouts/main.php';
?>

