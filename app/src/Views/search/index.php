<?php
$query = isset($query) ? $query : '';
$categoryId = isset($categoryId) ? $categoryId : null;
$results = isset($results) ? $results : [];
$totalResults = isset($totalResults) ? $totalResults : 0;
$currentPage = isset($currentPage) ? $currentPage : 1;
$categories = isset($categories) ? $categories : [];

$layout = function() use ($query, $categoryId, $results, $totalResults, $currentPage, $categories) {
?>
<link rel="stylesheet" href="/css/search.css">

<div class="search-container">
    <div class="container">
        <div class="search-header">
            <h1 class="search-title">Search Posts</h1>
        </div>

        <!-- Search Form -->
        <form method="GET" action="/search" class="search-form">
            <div class="search-form-grid">
                <div class="search-form-row">
                    <div class="search-form-group">
                        <label for="search-query" class="search-form-label">Search Query</label>
                        <input 
                            type="text" 
                            id="search-query" 
                            name="q" 
                            class="search-input" 
                            placeholder="e.g., php web development"
                            value="<?= htmlspecialchars($query) ?>"
                            autofocus
                        >
                    </div>
                    
                    <div class="search-form-group">
                        <label for="search-category" class="search-form-label">Category</label>
                        <select id="search-category" name="category" class="search-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category->id ?>" <?= $categoryId == $category->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="search-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    Search
                </button>
            </div>
        </form>

        <?php if ($query !== ''): ?>
            <!-- Results Info -->
            <div class="search-results-info">
                <p>
                    Found <strong><?= $totalResults ?></strong> result<?= $totalResults !== 1 ? 's' : '' ?> 
                    for <span class="search-query-highlight">"<?= htmlspecialchars($query) ?>"</span>
                    <?php if ($categoryId): ?>
                        <?php 
                        $selectedCategory = null;
                        foreach ($categories as $cat) {
                            if ($cat->id == $categoryId) {
                                $selectedCategory = $cat;
                                break;
                            }
                        }
                        ?>
                        <?php if ($selectedCategory): ?>
                            in category <strong><?= htmlspecialchars($selectedCategory->name) ?></strong>
                        <?php endif; ?>
                    <?php endif; ?>
                </p>
            </div>

            <?php if (empty($results)): ?>
                <!-- Empty State -->
                <div class="search-empty-state">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                        <line x1="11" y1="8" x2="11" y2="14"></line>
                        <line x1="8" y1="11" x2="14" y2="11"></line>
                    </svg>
                    <h2 class="search-empty-title">No results found</h2>
                    <p class="search-empty-text">Try different keywords or remove the category filter</p>
                </div>
            <?php else: ?>
                <!-- Results Grid -->
                <div class="search-results-grid">
                    <?php foreach ($results as $post): ?>
                        <article class="search-result-card">
                            <h2 class="search-result-title">
                                <a href="/posts/<?= htmlspecialchars($post->slug) ?>">
                                    <?= htmlspecialchars($post->title) ?>
                                </a>
                            </h2>
                            
                            <?php if ($post->excerpt): ?>
                                <p class="search-result-excerpt"><?= htmlspecialchars($post->excerpt) ?></p>
                            <?php endif; ?>
                            
                            <div class="search-result-meta">
                                <?php if ($post->categoryName): ?>
                                    <span class="search-result-category">
                                        <?= htmlspecialchars($post->categoryName) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <span class="search-result-meta-item">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <?= $post->views ?> views
                                </span>
                                
                                <?php if ($post->publishedAt): ?>
                                    <span class="search-result-meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        <?= date('M d, Y', strtotime($post->publishedAt)) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalResults > 20): ?>
                    <div class="search-pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="/search?q=<?= urlencode($query) ?><?= $categoryId ? '&category=' . $categoryId : '' ?>&page=<?= $currentPage - 1 ?>" class="search-pagination-btn">
                                ← Previous
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($totalResults > ($currentPage * 20)): ?>
                            <a href="/search?q=<?= urlencode($query) ?><?= $categoryId ? '&category=' . $categoryId : '' ?>&page=<?= $currentPage + 1 ?>" class="search-pagination-btn">
                                Next →
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <!-- Initial State -->
            <div class="search-empty-state">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <h2 class="search-empty-title">Enter a search query</h2>
                <p class="search-empty-text">Type keywords in the search box above to find posts</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
};
$content = $layout;
include __DIR__ . '/../layouts/main.php';
?>

