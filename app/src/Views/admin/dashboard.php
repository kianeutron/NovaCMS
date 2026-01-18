<?php
$user = isset($user) ? $user : null;
$posts = isset($posts) ? $posts : [];
$categoryCount = isset($categoryCount) ? $categoryCount : 0;
$layout = function() use ($user, $posts, $categoryCount) {
?>
<link rel="stylesheet" href="/css/dashboard.css">

<div class="dashboard-container">
    <h1 class="dashboard-title">Dashboard</h1>
    
    <?php if ($user): ?>
        <div class="dashboard-welcome-card">
            <h2 class="dashboard-welcome-title">Welcome, <?= htmlspecialchars($user->getFullName()) ?>!</h2>
            <p class="dashboard-welcome-meta">
                Role: <strong><?= htmlspecialchars(ucfirst($user->role)) ?></strong> | 
                Status: <strong><?= htmlspecialchars(ucfirst($user->status)) ?></strong>
            </p>
        </div>
    <?php endif; ?>

    <div class="dashboard-stats-grid">
        <div class="dashboard-stat-card posts-stat">
            <h3 class="dashboard-stat-number"><?= count($posts) ?></h3>
            <p class="dashboard-stat-label">Recent Posts</p>
        </div>
        
        <div class="dashboard-stat-card categories-stat">
            <h3 class="dashboard-stat-number"><?= $categoryCount ?></h3>
            <p class="dashboard-stat-label">Categories</p>
        </div>
        
        <div class="dashboard-stat-card users-stat">
            <h3 class="dashboard-stat-number">1</h3>
            <p class="dashboard-stat-label">Users</p>
        </div>
    </div>

    <div class="dashboard-section">
        <h2 class="dashboard-section-title">Quick Actions</h2>
        <div class="dashboard-actions-grid">
            <a href="/admin/posts/create" class="dashboard-action-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Create New Post
            </a>
            <a href="/admin/posts" class="dashboard-action-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Manage Posts
            </a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="/admin/users" class="dashboard-action-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Manage Users
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($posts)): ?>
        <div class="dashboard-section">
            <h2 class="dashboard-section-title">Recent Posts</h2>
            <div class="dashboard-recent-posts">
                <?php foreach ($posts as $post): ?>
                    <div class="dashboard-post-item">
                        <h3 class="dashboard-post-title">
                            <a href="/posts/<?= htmlspecialchars($post['slug']) ?>">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h3>
                        <div class="dashboard-post-meta">
                            <span class="dashboard-post-status status-<?= htmlspecialchars($post['status']) ?>">
                                <?= htmlspecialchars(ucfirst($post['status'])) ?>
                            </span>
                            <span class="dashboard-post-meta-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <?= $post['views'] ?> views
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php
};
$content = $layout;
include __DIR__ . '/../layouts/main.php';
?>
