<?php

namespace NovaCMS\ViewModels;

class PostViewModel
{
    public function __construct(
        public int $id,
        public string $title,
        public string $slug,
        public string $excerpt,
        public string $content,
        public ?string $featuredImage,
        public string $authorName,
        public ?string $categoryName,
        public string $publishedAt,
        public int $views
    ) {}
}

