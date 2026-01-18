<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description" content="NovaCMS - A modern, clean PHP Content Management System">
    <title><?= isset($title) ? $title : 'NovaCMS' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Framework CSS - Custom Design System (loads after Bootstrap) -->
    <link rel="stylesheet" href="/css/framework.css">
    
    <!-- Component-Specific CSS -->
    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>
    
    <main class="container">
        <?php $content(); ?>
    </main>
    
    <?php include __DIR__ . '/../partials/footer.php'; ?>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
