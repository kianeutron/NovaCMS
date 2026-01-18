<?php
$layout = function() use ($posts) {
?>
<section class="hero">
  <div class="container hero-grid">
    <div>
      <h1 class="hero-title">Welcome to NovaCMS</h1>
      <p class="hero-subtitle">Create and publish content with speed. Manage posts, media, and categories from a lightweight PHP CMS.</p>
      <div class="hero-cta">
        <a class="btn-cta" href="/admin/dashboard">Go to Dashboard</a>
        <a class="btn-outline" href="/posts">Browse Posts</a>
      </div>
    </div>
    <aside>
      <!-- Small promo / stats box -->
      <div class="post-card">
        <div class="quick-stats-title">Quick Stats</div>
        <div class="post-meta">
          <div>Posts: <?= isset($posts) ? count($posts) : 0 ?></div>
        </div>
      </div>
    </aside>
  </div>
</section>

<section class="section">
  <div class="container">
    <h2 class="section-title">Latest Posts</h2>

    <?php if (empty($posts)): ?>
      <p class="text-muted">No posts available yet.</p>
    <?php else: ?>
      <div class="posts-grid">
        <?php foreach ($posts as $post): ?>
          <article class="post-card">
            <a class="link" href="/posts/<?= htmlspecialchars($post['slug']) ?>">
              <h3 class="post-title"><?= htmlspecialchars($post['title']) ?></h3>
            </a>
            <?php if (!empty($post['excerpt'])): ?>
              <p class="post-excerpt"><?= htmlspecialchars($post['excerpt']) ?></p>
            <?php endif; ?>
            <div class="post-meta">
              <div class="views">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <?= $post['views'] ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="view-all">
        <a href="/posts" class="btn-cta">View All Posts</a>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php
};
$content = $layout;
include __DIR__ . '/../layouts/main.php';
?>
