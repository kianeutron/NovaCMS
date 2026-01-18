<?php

namespace NovaCMS\Controllers;

use NovaCMS\Core\BaseController;
use NovaCMS\Services\PostService;
use NovaCMS\Services\AuthService;
use NovaCMS\Services\CategoryService;

class AdminController extends BaseController
{
    private PostService $postService;
    private AuthService $authService;
    private CategoryService $categoryService;

    public function __construct()
    {
        $this->postService = new PostService();
        $this->authService = new AuthService();
        $this->categoryService = new CategoryService();
        
        // Redirect to login if not authenticated
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        $this->checkAuth();
        $this->checkRole();
    }

    public function dashboard(): void
    {
        try {
            $user = $this->authService->getCurrentUser();
            $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            
            // If user is author, show only their own posts
            if ($userRole === 'author') {
                $posts = $this->postService->getPostsForAuthor($userId, 1, 5);
            } else {
                $posts = $this->postService->getPublishedPosts(1, 5);
            }
            
            // Get category count
            $categories = $this->categoryService->getAllActive();
            $categoryCount = count($categories);
            
            $this->render('admin/dashboard', [
                'user' => $user,
                'posts' => $posts,
                'categoryCount' => $categoryCount
            ]);
        } catch (\Exception $e) {
            $this->render('errors/500', ['error' => 'An error occurred while loading the dashboard']);
        }
    }

    private function checkAuth(): void
    {
        // Simple inline auth check
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            exit;
        }
    }

    private function checkRole(): void
    {
        // Simple inline role check
        $allowedRoles = ['admin', 'author'];
        $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        
        if (!$userRole || !in_array($userRole, $allowedRoles)) {
            http_response_code(403);
            echo '403 Forbidden - You do not have permission to access this resource.';
            exit;
        }
    }
}

