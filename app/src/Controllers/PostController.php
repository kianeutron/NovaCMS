<?php

namespace NovaCMS\Controllers;

use NovaCMS\Core\BaseController;
use NovaCMS\Services\PostService;
use NovaCMS\Services\CategoryService;

class PostController extends BaseController
{
    private PostService $postService;
    private CategoryService $categoryService;

    public function __construct()
    {
        $this->postService = new PostService();
        $this->categoryService = new CategoryService();
    }

    public function browse(): void
    {
        try {
            $this->render('posts/browse', []);
        } catch (\Exception $e) {
            $this->render('errors/500', ['error' => 'An error occurred while loading the page']);
        }
    }

    public function show(string $slug): void
    {
        try {
            $post = $this->postService->getPostBySlug($slug);
            
            if (!$post) {
                $this->notFound();
                return;
            }

            $this->render('posts/show', ['post' => $post]);
        } catch (\Exception $e) {
            $this->render('errors/500', ['error' => 'An error occurred while loading the post']);
        }
    }

    public function create(): void
    {
        try {
            $categories = $this->categoryService->getAllActive();
            $this->render('posts/create', ['categories' => $categories]);
        } catch (\Exception $e) {
            $this->render('errors/500', ['error' => 'An error occurred while loading the form']);
        }
    }

    public function store(): void
    {
        try {
            $data = $this->getPostData();
            $post = $this->postService->createPost($data);
            $this->redirect("/posts/{$post->slug}");
        } catch (\Exception $e) {
            $this->render('posts/create', ['error' => 'An error occurred while creating the post']);
        }
    }

    public function edit(int $id): void
    {
        try {
            $categories = $this->categoryService->getAllActive();
            $this->render('posts/edit', ['postId' => $id, 'categories' => $categories]);
        } catch (\Exception $e) {
            $this->render('errors/500', ['error' => 'An error occurred while loading the form']);
        }
    }

    public function update(int $id): void
    {
        try {
            $data = $this->getPostData();
            $this->postService->updatePost($id, $data);
            $this->redirect("/admin/posts");
        } catch (\Exception $e) {
            $this->render('posts/edit', ['postId' => $id, 'error' => 'An error occurred while updating the post']);
        }
    }

    public function delete(int $id): void
    {
        try {
            $this->postService->deletePost($id);
            $this->redirect('/admin/posts');
        } catch (\Exception $e) {
            $this->redirect('/admin/posts?error=delete_failed');
        }
    }

    private function getPostData(): array
    {
        return [
            'title' => isset($_POST['title']) ? $_POST['title'] : '',
            'content' => isset($_POST['content']) ? $_POST['content'] : '',
            'excerpt' => isset($_POST['excerpt']) ? $_POST['excerpt'] : null,
            'category_id' => isset($_POST['category_id']) ? $_POST['category_id'] : null,
            'status' => isset($_POST['status']) ? $_POST['status'] : 'draft',
            'author_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1,
        ];
    }
}
