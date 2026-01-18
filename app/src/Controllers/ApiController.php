<?php

namespace NovaCMS\Controllers;

use NovaCMS\Core\BaseController;
use NovaCMS\Services\PostService;
use NovaCMS\Services\CategoryService;

/**
 * API Controller
 * Provides JSON endpoints for AJAX functionality
 */
class ApiController extends BaseController
{
    private PostService $postService;
    private CategoryService $categoryService;

    public function __construct()
    {
        $this->postService = new PostService();
        $this->categoryService = new CategoryService();
    }

    /**
     * GET /api/posts
     * Returns all published posts in JSON format
     */
    public function getPosts(): void
    {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;

            $posts = $this->postService->getPublishedPosts($page, $limit, $categoryId);
            $total = $this->postService->getTotalPublishedCount($categoryId);

            $this->json([
                'success' => true,
                'data' => [
                    'posts' => $posts,
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $limit,
                        'total' => $total,
                        'total_pages' => ceil($total / $limit)
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            $this->json([
                'success' => false,
                'error' => 'An error occurred while fetching posts'
            ]);
        }
    }

    /**
     * GET /api/posts/{id}
     * Returns a single post by ID in JSON format
     */
    public function getPost(array $vars): void
    {
        try {
            $id = (int)$vars['id'];
            $post = $this->postService->getPostById($id);

            if (!$post || $post->status !== 'published') {
                http_response_code(404);
                $this->json([
                    'success' => false,
                    'error' => 'Post not found'
                ]);
                return;
            }

            $this->json([
                'success' => true,
                'data' => $post
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            $this->json([
                'success' => false,
                'error' => 'An error occurred while fetching post'
            ]);
        }
    }

    /**
     * GET /api/categories
     * Returns all active categories in JSON format
     */
    public function getCategories(): void
    {
        try {
            $categories = $this->categoryService->getAllActiveCategories();

            $this->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            $this->json([
                'success' => false,
                'error' => 'An error occurred while fetching categories'
            ]);
        }
    }

    /**
     * GET /api/posts/search
     * Search posts by query string
     */
    public function searchPosts(): void
    {
        try {
            $query = $_GET['q'] ?? '';
            
            if (empty($query)) {
                $this->json([
                    'success' => false,
                    'error' => 'Search query is required'
                ]);
                return;
            }

            $results = $this->postService->searchPosts($query);

            $this->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            $this->json([
                'success' => false,
                'error' => 'An error occurred while searching posts'
            ]);
        }
    }

    /**
     * GET /api/posts/recent
     * Returns recent posts
     */
    public function getRecentPosts(): void
    {
        try {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
            $posts = $this->postService->getRecentPosts($limit);

            $this->json([
                'success' => true,
                'data' => $posts
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            $this->json([
                'success' => false,
                'error' => 'An error occurred while fetching recent posts'
            ]);
        }
    }
}
