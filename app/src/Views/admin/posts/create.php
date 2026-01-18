<?php
use NovaCMS\Core\Flash;

$categories = isset($categories) ? $categories : [];
$layout = function() use ($categories) {
?>
<link rel="stylesheet" href="/css/create-post.css">

<div class="create-post-container">
    <div class="container">
        <div class="create-post-header">
            <a href="/admin/posts" class="create-post-back-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to Posts
            </a>
            <h1 class="create-post-title">Create New Post</h1>
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

        <div class="create-post-card">
            <form method="POST" action="/admin/posts/store" enctype="multipart/form-data">
                <?php echo NovaCMS\Core\CSRF::field(); ?>
                
                <!-- Basic Information -->
                <div class="post-form-section">
                    <h2 class="post-section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Basic Information
                    </h2>
                    
                    <div class="post-form-group">
                        <label for="title" class="post-form-label">
                            Post Title<span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               required 
                               class="post-form-input post-title-input"
                               placeholder="Enter your post title..."
                               autocomplete="off">
                    </div>

                    <div class="post-form-group">
                        <label for="excerpt" class="post-form-label">Excerpt</label>
                        <textarea id="excerpt" 
                                  name="excerpt" 
                                  rows="3"
                                  class="post-form-textarea"
                                  placeholder="Write a short summary that will appear in post listings..."></textarea>
                        <span class="post-form-help">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            Optional. A brief description of your post (recommended for SEO)
                        </span>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="post-form-section">
                    <h2 class="post-section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        Featured Image
                    </h2>
                    
                    <div class="post-form-group">
                        <label for="featured_image" class="post-form-label">Upload Image</label>
                        <div class="post-file-upload-wrapper">
                            <input type="file" 
                                   id="featured_image" 
                                   name="featured_image" 
                                   accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                                   class="post-file-upload-input">
                        </div>
                        <span class="post-form-help">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            Optional. Max 5MB. Accepted formats: JPG, PNG, GIF, WebP
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="post-form-section">
                    <h2 class="post-section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Content
                    </h2>
                    
                    <div class="post-form-group">
                        <label for="content" class="post-form-label">
                            Post Content<span class="required">*</span>
                        </label>
                        <textarea id="content" 
                                  name="content" 
                                  required 
                                  class="post-form-textarea post-content-textarea"
                                  placeholder="Write your post content here...

You can use Markdown formatting, plain HTML, or just plain text.

Start writing your content..."></textarea>
                        <span class="post-form-help">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            Tip: Use Markdown for easy formatting or write in plain HTML
                        </span>
                    </div>
                </div>

                <!-- Settings -->
                <div class="post-form-section">
                    <h2 class="post-section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        Post Settings
                    </h2>
                    
                    <div class="post-form-grid">
                        <div class="post-form-group">
                            <label for="category_id" class="post-form-label">Category</label>
                            <select id="category_id" name="category_id" class="post-form-select">
                                <option value="">— Select Category —</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category->id ?>"><?= htmlspecialchars($category->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="post-form-group">
                            <label for="status" class="post-form-label">Status</label>
                            <select id="status" name="status" class="post-form-select">
                                <option value="draft" selected>Draft</option>
                                <option value="published">Published</option>
                            </select>
                            <span class="post-form-help">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                </svg>
                                Choose "Draft" to save without publishing
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="post-form-actions">
                    <button type="submit" class="post-btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Create Post
                    </button>
                    <a href="/admin/posts" class="post-btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/js/admin-create-post.js"></script>

<?php
};
$content = $layout;
include __DIR__ . '/../../layouts/main.php';
