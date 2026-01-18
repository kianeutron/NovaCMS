<?php
$errorMessage = isset($error) ? $error : null;
$layout = function() use ($errorMessage) {
?>
<link rel="stylesheet" href="/css/auth.css">

<div class="auth-container">
    <h1>Register</h1>
    
    <?php if ($errorMessage): ?>
        <div class="auth-error">
            <?= htmlspecialchars($errorMessage) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="/register" class="auth-form">
        <?php echo NovaCMS\Core\CSRF::field(); ?>
        
        <div class="auth-form-group">
            <label for="username" class="auth-form-label">Username</label>
            <input type="text" id="username" name="username" required class="auth-form-input">
        </div>
        
        <div class="auth-form-group">
            <label for="email" class="auth-form-label">Email</label>
            <input type="email" id="email" name="email" required class="auth-form-input">
        </div>
        
        <div class="auth-form-group">
            <label for="first_name" class="auth-form-label">First Name</label>
            <input type="text" id="first_name" name="first_name" class="auth-form-input">
        </div>
        
        <div class="auth-form-group">
            <label for="last_name" class="auth-form-label">Last Name</label>
            <input type="text" id="last_name" name="last_name" class="auth-form-input">
        </div>
        
        <div class="auth-form-group">
            <label for="password" class="auth-form-label">Password</label>
            <input type="password" id="password" name="password" required class="auth-form-input">
        </div>
        
        <button type="submit" class="btn auth-form-submit">Register</button>
    </form>
    
    <p class="auth-footer">
        Already have an account? <a href="/login">Login here</a>
    </p>
</div>
<?php
};
$content = $layout;
include __DIR__ . '/../layouts/main.php';
?>

