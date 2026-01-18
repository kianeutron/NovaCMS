<header class="header-site">
    <div class="container header-inner">
        <h1><a href="/" class="header-brand">NovaCMS</a></h1>
        
        <!-- Hamburger Menu Button (Mobile Only) -->
        <button class="header-hamburger" id="hamburger-menu" aria-label="Toggle navigation menu">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
        
        <!-- Navigation Menu -->
        <nav class="header-nav" id="main-nav">
            <a href="/">Home</a>
            <a href="/posts">Posts</a>
            <a href="/search">Search</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/admin/dashboard">Dashboard</a>
                <a href="/logout">Logout</a>
            <?php else: ?>
                <a href="/login">Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<script src="/js/mobile-menu.js"></script>

