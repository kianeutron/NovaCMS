<?php

namespace NovaCMS\ViewModels;

class PostListViewModel
{
    public function __construct(
        public array $posts,
        public int $currentPage,
        public int $totalPages,
        public int $perPage
    ) {}
}

