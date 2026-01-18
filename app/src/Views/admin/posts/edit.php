<?php
use NovaCMS\Core\Flash;

$post = isset($post) ? $post : null;
$categories = isset($categories) ? $categories : [];
$layout = function() use ($post, $categories) {
?>
<link rel="stylesheet" href="/css/edit-post.css">

<div class="create-post-container">
    <div class="page-header">
        <a href="/admin/posts" class="back-link">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" style="margin-right: 0.5rem;">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            Back to Posts
        </a>
        <h1 class="page-title">Edit Post</h1>
    </div>

    <?php if (Flash::has()): ?>
        <?php foreach (Flash::get() as $flash): ?>
            <div class="flash-message <?= $flash['type'] ?>">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <?php if ($flash['type'] === 'success'): ?>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    <?php else: ?>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    <?php endif; ?>
                </svg>
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <form method="POST" action="/admin/posts/<?= $post->id ?>/update" enctype="multipart/form-data" style="margin-top: 2rem;">
        <?php echo NovaCMS\Core\CSRF::field(); ?>
        
        <div style="margin-bottom: 1.5rem;">
            <label for="title" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Title *</label>
            <input type="text" id="title" name="title" required value="<?= htmlspecialchars($post->title) ?>"
                   style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1.1rem;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Featured Image</label>
            
            <?php if ($post->featuredImage): ?>
                <div style="margin-bottom: 1rem;">
                    <img src="<?= htmlspecialchars($post->featuredImage) ?>" alt="Current featured image" 
                         style="max-width: 300px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px; display: block; margin-bottom: 0.5rem;">
                    <label style="display: inline-flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="remove_featured_image" value="1" style="margin-right: 0.5rem;">
                        Remove current image
                    </label>
                </div>
            <?php endif; ?>
            
            <input type="file" id="featured_image" name="featured_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                   style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            <small style="color: #666; display: block; margin-top: 0.25rem;">
                <?= $post->featuredImage ? 'Upload a new image to replace the current one.' : 'Optional. Max 5MB. Formats: JPG, PNG, GIF, WebP' ?>
            </small>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="excerpt" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Excerpt</label>
            <textarea id="excerpt" name="excerpt" rows="3"
                      style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;"><?= $post->excerpt ? htmlspecialchars($post->excerpt) : '' ?></textarea>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="content" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Content *</label>
            <textarea id="content" name="content" required rows="20"
                      style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-family: monospace;"><?= htmlspecialchars($post->content) ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label for="category_id" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Category</label>
                <select id="category_id" name="category_id" 
                        style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">— No Category —</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category->id ?>" <?= $post->categoryId == $category->id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Status</label>
                <select id="status" name="status" 
                        style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="draft" <?= $post->status === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= $post->status === 'published' ? 'selected' : '' ?>>Published</option>
                    <option value="archived" <?= $post->status === 'archived' ? 'selected' : '' ?>>Archived</option>
                </select>
            </div>
        </div>

        <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;">
            <p style="margin: 0; font-size: 0.9rem; color: #666;">
                <strong>Post URL:</strong> /posts/<?= htmlspecialchars($post->slug) ?>
            </p>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn">Update Post</button>
                <a href="/admin/posts" class="btn" style="background: #6c757d;">Cancel</a>
                <a href="/posts/<?= htmlspecialchars($post->slug) ?>" target="_blank" class="btn" style="background: #28a745;">View Post</a>
            </div>
        </div>
    </form>
</div>
<?php
};
$content = $layout;
include __DIR__ . '/../../layouts/main.php';
?>

