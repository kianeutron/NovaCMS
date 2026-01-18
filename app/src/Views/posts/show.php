<?php
$layout = function() use ($post) {
?>
<link rel="stylesheet" href="/css/show-post.css">

<article class="post-show-article">
    <h1 class="post-show-title"><?= htmlspecialchars($post->title) ?></h1>
    
    <div class="post-show-meta">
        <?php if ($post->publishedAt): ?>
            <span>Published: <?= date('F d, Y', strtotime($post->publishedAt)) ?></span>
        <?php endif; ?>
        <span>Views: <?= $post->views ?></span>
    </div>
    
    <?php if ($post->featuredImage): ?>
        <img src="<?= htmlspecialchars($post->featuredImage) ?>" alt="<?= htmlspecialchars($post->title) ?>" 
             class="post-show-featured-image">
    <?php endif; ?>
    
    <div class="post-show-content">
        <?= nl2br(htmlspecialchars($post->content)) ?>
    </div>
    
    <div class="post-show-footer">
        <a href="/posts" class="btn">← Back to Posts</a>
    </div>
</article>
<?php
};
$content = $layout;
include __DIR__ . '/../layouts/main.php';
?>

