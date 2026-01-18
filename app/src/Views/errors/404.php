<?php
$layout = function() {
?>
<link rel="stylesheet" href="/css/errors.css">

<div class="error-container">
    <h1 class="error-code">404</h1>
    <h2 class="error-title">Page Not Found</h2>
    <p class="error-message">The page you're looking for doesn't exist.</p>
    <a href="/" class="btn error-action">Go Home</a>
</div>
<?php
};
$content = $layout;
include __DIR__ . '/../layouts/main.php';
?>

