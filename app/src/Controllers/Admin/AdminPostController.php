<?php

namespace NovaCMS\Controllers\Admin;

use NovaCMS\Core\BaseController;
use NovaCMS\Core\CSRF;
use NovaCMS\Core\Flash;
use NovaCMS\Services\PostService;
use NovaCMS\Services\CategoryService;
use NovaCMS\Services\AuditService;
use NovaCMS\Services\MediaService;

class AdminPostController extends BaseController
{
    private PostService $postService;
    private CategoryService $categoryService;
    private AuditService $auditService;
    private MediaService $mediaService;

    public function __construct()
    {
        $this->postService = new PostService();
        $this->categoryService = new CategoryService();
        $this->auditService = new AuditService();
        $this->mediaService = new MediaService();
        $this->checkAuth();
        $this->checkRole();
    }

    public function index(): void
    {
        try {
            $page = (int) (isset($_GET['page']) ? $_GET['page'] : 1);
            $status = isset($_GET['status']) ? $_GET['status'] : null;
            
            $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            
            // If user is author, only show their own posts
            if ($userRole === 'author') {
                $posts = $this->postService->getPostsForAuthor($userId, $page, 20, $status);
            } else {
                // Admins see all posts
                $posts = $this->postService->getPostsForAdmin($page, 20, $status);
            }
            
            $this->render('admin/posts/index', [
                'posts' => $posts,
                'currentPage' => $page,
                'currentStatus' => $status
            ]);
        } catch (\Exception $e) {
            Flash::error('An error occurred while loading posts.');
            $this->render('admin/posts/index', ['posts' => [], 'currentPage' => 1, 'currentStatus' => null]);
        }
    }

    public function create(): void
    {
        try {
            $categories = $this->categoryService->getAllActive();
            $this->render('admin/posts/create', ['categories' => $categories]);
        } catch (\Exception $e) {
            Flash::error('An error occurred while loading the form.');
            $this->redirect('/admin/posts');
        }
    }

    public function store(): void
    {
        if (!CSRF::validateRequest()) {
            Flash::error('Invalid security token. Please try again.');
            $this->redirect('/admin/posts/create');
            return;
        }

        $data = $this->getPostData();
        
        // Handle featured image upload
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            try {
                $imagePath = $this->mediaService->uploadImage($_FILES['featured_image']);
                $data['featured_image'] = $imagePath;
            } catch (\Exception $e) {
                Flash::error('Image upload failed: ' . $e->getMessage());
                $this->redirect('/admin/posts/create');
                return;
            }
        }
        
        try {
            $post = $this->postService->createPost($data);
            $this->auditService->logPostCreated($post->id);
            
            Flash::success('Post created successfully.');
            $this->redirect("/admin/posts/{$post->id}/edit");
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            $this->redirect('/admin/posts/create');
        }
    }

    public function edit(int $id): void
    {
        try {
            $post = $this->postService->getPostById($id);
            
            if (!$post) {
                Flash::error('Post not found.');
                $this->redirect('/admin/posts');
                return;
            }

            // Check if user can edit this post
            if (!$this->canEditPost($post)) {
                Flash::error('You do not have permission to edit this post.');
                $this->redirect('/admin/posts');
                return;
            }

            $categories = $this->categoryService->getAllActive();
            $this->render('admin/posts/edit', [
                'post' => $post,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            Flash::error('An error occurred while loading the post.');
            $this->redirect('/admin/posts');
        }
    }

    public function update(int $id): void
    {
        if (!CSRF::validateRequest()) {
            Flash::error('Invalid security token. Please try again.');
            $this->redirect("/admin/posts/{$id}/edit");
            return;
        }

        $post = $this->postService->getPostById($id);
        
        if (!$post || !$this->canEditPost($post)) {
            Flash::error('You do not have permission to edit this post.');
            $this->redirect('/admin/posts');
            return;
        }

        $data = $this->getPostData();
        
        // Handle featured image upload
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            try {
                // Delete old image if exists
                if ($post->featuredImage) {
                    $this->mediaService->deleteImage($post->featuredImage);
                }
                
                $imagePath = $this->mediaService->uploadImage($_FILES['featured_image']);
                $data['featured_image'] = $imagePath;
            } catch (\Exception $e) {
                Flash::error('Image upload failed: ' . $e->getMessage());
                $this->redirect("/admin/posts/{$id}/edit");
                return;
            }
        }
        
        // Handle image removal
        if (isset($_POST['remove_featured_image']) && $_POST['remove_featured_image'] === '1') {
            if ($post->featuredImage) {
                $this->mediaService->deleteImage($post->featuredImage);
            }
            $data['featured_image'] = null;
        }
        
        try {
            $this->postService->updatePost($id, $data);
            $this->auditService->logPostUpdated($id);
            
            Flash::success('Post updated successfully.');
            $this->redirect("/admin/posts/{$id}/edit");
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            $this->redirect("/admin/posts/{$id}/edit");
        }
    }

    public function delete(int $id): void
    {
        if (!CSRF::validateRequest()) {
            Flash::error('Invalid security token.');
            $this->redirect('/admin/posts');
            return;
        }

        $post = $this->postService->getPostById($id);
        
        if (!$post || !$this->canEditPost($post)) {
            Flash::error('You do not have permission to delete this post.');
            $this->redirect('/admin/posts');
            return;
        }

        try {
            $this->postService->deletePost($id);
            $this->auditService->logPostDeleted($id);
            
            Flash::success('Post deleted successfully.');
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
        }
        
        $this->redirect('/admin/posts');
    }



    private function getPostData(): array
    {
        return [
            'title' => isset($_POST['title']) ? trim($_POST['title']) : '',
            'content' => isset($_POST['content']) ? trim($_POST['content']) : '',
            'excerpt' => isset($_POST['excerpt']) ? trim($_POST['excerpt']) : null,
            'category_id' => isset($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'status' => isset($_POST['status']) ? $_POST['status'] : 'draft',
            'author_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1,
        ];
    }

    private function canEditPost($post): bool
    {
        $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        if ($userRole === 'admin') {
            return true;
        }

        if ($userRole === 'author' && $post->authorId === $userId) {
            return true;
        }

        return false;
    }

    private function checkAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
    }

    private function checkRole(): void
    {
        $allowedRoles = ['admin', 'author'];
        $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        
        if (!$userRole || !in_array($userRole, $allowedRoles)) {
            http_response_code(403);
            echo '403 Forbidden - You do not have permission to access this resource.';
            exit;
        }
    }
}

