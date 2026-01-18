<?php

namespace NovaCMS\Controllers;

use NovaCMS\Core\BaseController;
use NovaCMS\Services\PostService;

class HomeController extends BaseController
{
    private PostService $postService;

    public function __construct()
    {
        $this->postService = new PostService();
    }

    public function home(): void
    {
        try {
            $posts = $this->postService->getPublishedPosts(1, 5);
            $this->render('home/index', ['posts' => $posts]);
        } catch (\Exception $e) {
            $this->render('errors/500', ['error' => 'An error occurred while loading the home page']);
        }
    }
}

