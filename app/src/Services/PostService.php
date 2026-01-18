<?php

namespace NovaCMS\Services;

use NovaCMS\Models\Post;
use NovaCMS\Repositories\PostRepository;

class PostService
{
    private PostRepository $postRepository;

    public function __construct()
    {
        $this->postRepository = new PostRepository();
    }

    public function getPublishedPosts(int $page = 1, int $perPage = 10, ?int $categoryId = null): array
    {
        $offset = ($page - 1) * $perPage;
        $posts = $this->postRepository->findPublished($perPage, $offset, $categoryId);
        return $posts; // Return raw arrays for API
    }

    public function getPostsForAdmin(int $page = 1, int $perPage = 20, ?string $status = null): array
    {
        $offset = ($page - 1) * $perPage;
        $posts = $this->postRepository->findForAdmin($perPage, $offset, $status);
        return array_map(fn($data) => Post::fromArray($data), $posts);
    }

    public function getPostsForAuthor(int $authorId, int $page = 1, int $perPage = 20, ?string $status = null): array
    {
        $offset = ($page - 1) * $perPage;
        $posts = $this->postRepository->findByAuthor($authorId, $perPage, $offset, $status);
        return array_map(fn($data) => Post::fromArray($data), $posts);
    }

    public function getPostBySlug(string $slug): ?Post
    {
        $postData = $this->postRepository->findBySlug($slug);
        
        if (!$postData) {
            return null;
        }

        $this->postRepository->incrementViews($postData['id']);
        return Post::fromArray($postData);
    }

    public function getPostById(int $id): ?Post
    {
        $postData = $this->postRepository->findById($id);
        return $postData ? Post::fromArray($postData) : null;
    }

    public function createPost(array $data): Post
    {
        $data['slug'] = $this->generateUniqueSlug($data['title']);
        $postId = $this->postRepository->create($data);
        $postData = $this->postRepository->findById($postId);
        return Post::fromArray($postData);
    }

    public function updatePost(int $id, array $data): Post
    {
        if (isset($data['title'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $id);
        }
        
        $this->postRepository->update($id, $data);
        $postData = $this->postRepository->findById($id);
        return Post::fromArray($postData);
    }

    public function deletePost(int $id): bool
    {
        return $this->postRepository->delete($id);
    }



    private function generateSlug(string $title): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        return $slug;
    }

    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $baseSlug = $this->generateSlug($title);
        $slug = $baseSlug;
        $counter = 1;

        while ($this->postRepository->slugExists($slug, $excludeId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function searchPosts(string $query, ?int $categoryId = null, int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        $posts = $this->postRepository->search($query, $categoryId, $perPage, $offset);
        return array_map(fn($data) => Post::fromArray($data), $posts);
    }

    public function countSearchResults(string $query, ?int $categoryId = null): int
    {
        return $this->postRepository->countSearchResults($query, $categoryId);
    }

    public function getTotalPublishedCount(?int $categoryId = null): int
    {
        return $this->postRepository->countPublished($categoryId);
    }

    public function getRecentPosts(int $limit = 5): array
    {
        $posts = $this->postRepository->findPublished($limit, 0);
        return array_map(fn($data) => Post::fromArray($data), $posts);
    }
}
