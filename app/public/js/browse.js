// Posts browse page - AJAX functionality
document.addEventListener('DOMContentLoaded', function() {
    const postsContainer = document.getElementById('posts-container');
    const paginationContainer = document.getElementById('pagination-container');
    const loadingIndicator = document.getElementById('loading-indicator');
    const errorContainer = document.getElementById('error-container');
    const categoryFilter = document.getElementById('category-filter');

    let currentPage = 1;
    let currentCategory = '';

    // Category filter change handler
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            currentCategory = this.value;
            currentPage = 1;
            loadPosts();
        });
    }

    /**
     * Load posts from API
     */
    async function loadPosts() {
        try {
            // Show loading state
            if (loadingIndicator) loadingIndicator.style.display = 'block';
            if (errorContainer) errorContainer.style.display = 'none';
            if (postsContainer) postsContainer.style.opacity = '0.5';

            // Build API URL with query parameters
            const params = new URLSearchParams({
                page: currentPage,
                limit: 9
            });
            
            if (currentCategory) {
                params.append('category', currentCategory);
            }

            const response = await fetch(`/api/posts?${params.toString()}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success) {
                displayPosts(data.data.posts);
                displayPagination(data.data.pagination);
            } else {
                throw new Error(data.message || 'Failed to load posts');
            }

        } catch (error) {
            console.error('Error loading posts:', error);
            if (errorContainer) {
                errorContainer.textContent = 'Failed to load posts. Please try again later.';
                errorContainer.style.display = 'block';
            }
            if (postsContainer) {
                postsContainer.innerHTML = '<p class="text-center">Unable to load posts at this time.</p>';
            }
        } finally {
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            if (postsContainer) postsContainer.style.opacity = '1';
        }
    }

    /**
     * Display posts in the grid
     */
    function displayPosts(posts) {
        if (!postsContainer) return;

        if (!posts || posts.length === 0) {
            postsContainer.innerHTML = '<div class="row"><div class="col-12"><p class="text-center">No posts found.</p></div></div>';
            return;
        }

        let postsHtml = '<div class="row">';
        postsHtml += posts.map(post => `
            <div class="col-md-4 mb-4">
                <article class="card h-100">
                    ${post.featured_image ? `
                        <img src="${escapeHtml(post.featured_image)}" 
                             class="card-img-top" 
                             alt="${escapeHtml(post.title)}"
                             style="height: 200px; object-fit: cover;">
                    ` : ''}
                    <div class="card-body d-flex flex-column">
                        <h2 class="card-title h5">
                            <a href="/posts/${escapeHtml(post.slug)}" class="text-decoration-none">
                                ${escapeHtml(post.title)}
                            </a>
                        </h2>
                        <p class="card-text text-muted small mb-2">
                            By ${escapeHtml(post.author_name)} | 
                            ${escapeHtml(post.category_name)} |
                            ${new Date(post.published_at).toLocaleDateString()}
                        </p>
                        <p class="card-text flex-grow-1">
                            ${escapeHtml(post.excerpt || post.content.substring(0, 150))}...
                        </p>
                        <a href="/posts/${escapeHtml(post.slug)}" class="btn btn-primary mt-auto">
                            Read More
                        </a>
                    </div>
                </article>
            </div>
        `).join('');
        postsHtml += '</div>';

        postsContainer.innerHTML = postsHtml;
    }

    /**
     * Display pagination controls
     */
    function displayPagination(pagination) {
        if (!paginationContainer || !pagination) return;

        if (pagination.total_pages <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        let paginationHtml = '<nav aria-label="Posts pagination"><ul class="pagination justify-content-center">';

        // Previous button
        paginationHtml += `
            <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${pagination.current_page - 1}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        `;

        // Page numbers
        for (let i = 1; i <= pagination.total_pages; i++) {
            paginationHtml += `
                <li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }

        // Next button
        paginationHtml += `
            <li class="page-item ${pagination.current_page === pagination.total_pages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${pagination.current_page + 1}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        `;

        paginationHtml += '</ul></nav>';
        paginationContainer.innerHTML = paginationHtml;

        // Add click handlers to pagination links
        paginationContainer.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = parseInt(this.getAttribute('data-page'));
                if (page >= 1 && page <= pagination.total_pages) {
                    currentPage = page;
                    loadPosts();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Initial load
    loadPosts();
});
