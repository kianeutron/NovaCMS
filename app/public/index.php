<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', ['NovaCMS\\Controllers\\HomeController', 'home']);
    
    $r->addRoute('GET', '/login', ['NovaCMS\\Controllers\\AuthController', 'showLogin']);
    $r->addRoute('POST', '/login', ['NovaCMS\\Controllers\\AuthController', 'login']);
    $r->addRoute('GET', '/register', ['NovaCMS\\Controllers\\AuthController', 'showRegister']);
    $r->addRoute('POST', '/register', ['NovaCMS\\Controllers\\AuthController', 'register']);
    $r->addRoute('GET', '/logout', ['NovaCMS\\Controllers\\AuthController', 'logout']);
    
    $r->addRoute('GET', '/posts', ['NovaCMS\\Controllers\\PostController', 'browse']);
    $r->addRoute('GET', '/posts/{slug}', ['NovaCMS\\Controllers\\PostController', 'show']);
    
    $r->addRoute('GET', '/search', ['NovaCMS\\Controllers\\SearchController', 'index']);
    
    // API Endpoints (JSON)
    $r->addRoute('GET', '/api/posts', ['NovaCMS\\Controllers\\ApiController', 'getPosts']);
    $r->addRoute('GET', '/api/posts/{id:\d+}', ['NovaCMS\\Controllers\\ApiController', 'getPost']);
    $r->addRoute('GET', '/api/posts/recent', ['NovaCMS\\Controllers\\ApiController', 'getRecentPosts']);
    $r->addRoute('GET', '/api/posts/search', ['NovaCMS\\Controllers\\ApiController', 'searchPosts']);
    $r->addRoute('GET', '/api/categories', ['NovaCMS\\Controllers\\ApiController', 'getCategories']);
    
    $r->addRoute('GET', '/admin/dashboard', ['NovaCMS\\Controllers\\AdminController', 'dashboard']);
    
    // Admin Post Management
    $r->addRoute('GET', '/admin/posts', ['NovaCMS\\Controllers\\Admin\\AdminPostController', 'index']);
    $r->addRoute('GET', '/admin/posts/create', ['NovaCMS\\Controllers\\Admin\\AdminPostController', 'create']);
    $r->addRoute('POST', '/admin/posts/store', ['NovaCMS\\Controllers\\Admin\\AdminPostController', 'store']);
    $r->addRoute('GET', '/admin/posts/{id:\d+}/edit', ['NovaCMS\\Controllers\\Admin\\AdminPostController', 'edit']);
    $r->addRoute('POST', '/admin/posts/{id:\d+}/update', ['NovaCMS\\Controllers\\Admin\\AdminPostController', 'update']);
    $r->addRoute('POST', '/admin/posts/{id:\d+}/delete', ['NovaCMS\\Controllers\\Admin\\AdminPostController', 'delete']);
    
    // Admin User Management
    $r->addRoute('GET', '/admin/users', ['NovaCMS\\Controllers\\Admin\\AdminUserController', 'index']);
    $r->addRoute('GET', '/admin/users/{id:\d+}/edit', ['NovaCMS\\Controllers\\Admin\\AdminUserController', 'edit']);
    $r->addRoute('POST', '/admin/users/{id:\d+}/update-role', ['NovaCMS\\Controllers\\Admin\\AdminUserController', 'updateRole']);
    $r->addRoute('POST', '/admin/users/{id:\d+}/update-status', ['NovaCMS\\Controllers\\Admin\\AdminUserController', 'updateStatus']);
    $r->addRoute('POST', '/admin/users/{id:\d+}/reset-password', ['NovaCMS\\Controllers\\Admin\\AdminUserController', 'resetPassword']);
    $r->addRoute('POST', '/admin/users/{id:\d+}/delete', ['NovaCMS\\Controllers\\Admin\\AdminUserController', 'delete']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        require __DIR__ . '/../src/Views/errors/404.php';
        break;
        
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;
        
    case FastRoute\Dispatcher::FOUND:
        [$className, $method] = $routeInfo[1];
        $params = $routeInfo[2];
        
        $controller = new $className();
        $controller->$method(...array_values($params));
        break;
}
