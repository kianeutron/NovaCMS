<?php
use NovaCMS\Core\Flash;

$posts = isset($posts) ? $posts : [];
$currentStatus = isset($currentStatus) ? $currentStatus : null;
$layout = function() use ($posts, $currentStatus) {
?>
<link rel="stylesheet" href="/css/manage-posts.css">

<div class="manage-posts-container">
    <div class="manage-posts-header">
        <h1 class="manage-posts-title">Manage Posts</h1>
        <a href="/admin/posts/create" class="manage-posts-create-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Create New Post
        </a>
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

    <!-- Filter tabs -->
    <div class="manage-posts-filters">
        <a href="/admin/posts" class="manage-posts-filter-tab <?= !$currentStatus ? 'active' : '' ?>">All</a>
        <a href="/admin/posts?status=published" class="manage-posts-filter-tab <?= $currentStatus === 'published' ? 'active' : '' ?>">Published</a>
        <a href="/admin/posts?status=draft" class="manage-posts-filter-tab <?= $currentStatus === 'draft' ? 'active' : '' ?>">Drafts</a>
        <a href="/admin/posts?status=archived" class="manage-posts-filter-tab <?= $currentStatus === 'archived' ? 'active' : '' ?>">Archived</a>
    </div>

    <?php if (empty($posts)): ?>
        <div class="manage-posts-empty">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2" style="margin: 0 auto 1rem;">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="12" y1="18" x2="12" y2="12"></line>
                <line x1="9" y1="15" x2="15" y2="15"></line>
            </svg>
            <h2 class="manage-posts-empty-title">No posts found</h2>
            <p class="manage-posts-empty-text">Get started by creating your first post</p>
            <a href="/admin/posts/create" class="manage-posts-create-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Create Your First Post
            </a>
        </div>
    <?php else: ?>
        <div class="manage-posts-table-wrapper">
            <table class="manage-posts-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td data-label="Title" class="manage-posts-title-cell">
                                <?= htmlspecialchars($post->title) ?>
                            </td>
                        <td data-label="Author" class="manage-posts-author-cell">
                            <?= $post->authorName ? htmlspecialchars($post->authorName) : '—' ?>
                        </td>
                        <td data-label="Category" class="manage-posts-category-cell">
                            <?= $post->categoryName ? htmlspecialchars($post->categoryName) : '—' ?>
                        </td>
                            <td data-label="Status">
                                <span class="manage-posts-status-badge status-<?= htmlspecialchars($post->status) ?>">
                                    <?= ucfirst($post->status) ?>
                                </span>
                            </td>
                            <td data-label="Date" class="manage-posts-date-cell">
                                <?= date('M d, Y', strtotime($post->createdAt)) ?>
                            </td>
                            <td class="manage-posts-actions">
                                <a href="/admin/posts/<?= $post->id ?>/edit" class="manage-posts-action-link">
                                    Edit
                                </a>
                                <a href="/posts/<?= htmlspecialchars($post->slug) ?>" target="_blank" class="manage-posts-action-link view">
                                    View
                                </a>
                                <form method="POST" action="/admin/posts/<?= $post->id ?>/delete" class="manage-posts-delete-form">
                                    <?php echo NovaCMS\Core\CSRF::field(); ?>
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this post?')" class="manage-posts-delete-btn">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php
};
$content = $layout;
include __DIR__ . '/../../layouts/main.php';
?>

