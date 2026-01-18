<?php

namespace NovaCMS\Controllers;

use NovaCMS\Core\BaseController;
use NovaCMS\Services\PostService;
use NovaCMS\Services\CategoryService;

class SearchController extends BaseController
{
    private PostService $postService;
    private CategoryService $categoryService;

    public function __construct()
    {
        $this->postService = new PostService();
        $this->categoryService = new CategoryService();
    }

    public function index(): void
    {
        try {
            $query = isset($_GET['q']) ? trim($_GET['q']) : '';
            $categoryId = isset($_GET['category']) && $_GET['category'] !== '' ? (int)$_GET['category'] : null;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            
            $results = [];
            $totalResults = 0;
            
            if ($query !== '') {
                $results = $this->postService->searchPosts($query, $categoryId, $page, 20);
                $totalResults = $this->postService->countSearchResults($query, $categoryId);
            }
            
            $categories = $this->categoryService->getAllActive();
            
            $this->render('search/index', [
                'query' => $query,
                'categoryId' => $categoryId,
                'results' => $results,
                'totalResults' => $totalResults,
                'currentPage' => $page,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            $this->render('errors/500', ['error' => 'An error occurred while searching']);
        }
    }
}

