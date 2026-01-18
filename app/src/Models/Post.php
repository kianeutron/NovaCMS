<?php

namespace NovaCMS\Models;

class Post
{
    public function __construct(
        public ?int $id,
        public string $title,
        public string $slug,
        public ?string $excerpt,
        public string $content,
        public ?string $featuredImage,
        public int $authorId,
        public ?int $categoryId,
        public string $status,
        public ?string $publishedAt,
        public ?string $createdAt,
        public ?string $updatedAt,
        public int $views,
        public ?string $authorName = null,
        public ?string $categoryName = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['title'],
            $data['slug'],
            $data['excerpt'] ?? null,
            $data['content'],
            $data['featured_image'] ?? null,
            $data['author_id'],
            $data['category_id'] ?? null,
            $data['status'] ?? 'draft',
            $data['published_at'] ?? null,
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null,
            $data['views'] ?? 0,
            $data['author_name'] ?? null,
            $data['category_name'] ?? null
        );
    }
}



