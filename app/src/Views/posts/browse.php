<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Posts - NovaCMS</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="/css/framework.css">
    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/posts.css">
    <link rel="stylesheet" href="/css/browse-posts.css">
</head>
<body>
    <?php 
    $isLoggedIn = isset($_SESSION['user_id']);
    $userName = $isLoggedIn ? ($_SESSION['user_name'] ?? $_SESSION['username'] ?? 'User') : null;
    $userRole = $_SESSION['role'] ?? null;
    require __DIR__ . '/../partials/site-header.php'; 
    ?>

    <main class="container browse-main">
        <h1>Browse All Posts</h1>
        <p class="browse-subtitle">
            This page uses AJAX to load posts dynamically without page refresh
        </p>

        <div id="posts-container">
            <div class="loading">Loading posts...</div>
        </div>

        <div id="pagination-container"></div>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
    
    <script src="/js/browse.js"></script>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
