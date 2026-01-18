<?php
$errorMessage = isset($error) ? $error : null;
$layout = function() use ($errorMessage) {
?>
<link rel="stylesheet" href="/css/auth.css">

<div class="auth-container">
    <h1>Login</h1>
    
    <?php if ($errorMessage): ?>
        <div class="auth-error">
            <?= htmlspecialchars($errorMessage) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="/login" class="auth-form">
        <?php echo NovaCMS\Core\CSRF::field(); ?>
        
        <div class="auth-form-group">
            <label for="email" class="auth-form-label">Email</label>
            <input type="email" id="email" name="email" required class="auth-form-input">
        </div>
        
        <div class="auth-form-group">
            <label for="password" class="auth-form-label">Password</label>
            <input type="password" id="password" name="password" required class="auth-form-input">
        </div>
        
        <button type="submit" class="btn auth-form-submit">Login</button>
    </form>
    
    <p class="auth-footer">
        Don't have an account? <a href="/register">Register here</a>
    </p>
</div>
<?php
};
$content = $layout;
include __DIR__ . '/../layouts/main.php';
?>

