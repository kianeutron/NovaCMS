<?php

namespace NovaCMS\Controllers;

class HelloController extends BaseController
{
    public function index () {
    $posts = Post::all();
    $this -> view('home', ['posts' => $posts]);
    }
}