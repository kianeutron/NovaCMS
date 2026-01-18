<?php

namespace NovaCMS\Services;

use NovaCMS\Models\Category;
use NovaCMS\Repositories\CategoryRepository;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
    }

    public function getAllActive(): array
    {
        $categories = $this->categoryRepository->findActive();
        return array_map(fn($data) => Category::fromArray($data), $categories);
    }

    public function getAllActiveCategories(): array
    {
        return $this->getAllActive();
    }

    public function getCategoryBySlug(string $slug): ?Category
    {
        $categoryData = $this->categoryRepository->findBySlug($slug);
        return $categoryData ? Category::fromArray($categoryData) : null;
    }

    public function createCategory(array $data): Category
    {
        $data['slug'] = $this->generateSlug($data['name']);
        $categoryId = $this->categoryRepository->create($data);
        $categoryData = $this->categoryRepository->findById($categoryId);
        return Category::fromArray($categoryData);
    }

    private function generateSlug(string $name): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
    }
}

