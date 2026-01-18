NovaCMS

A simple content management system I built for my web development class. It runs on PHP with MVC architecture.

Getting Started

Everything runs in Docker. Just do:

docker-compose up

Then go to:
- http://localhost - main site
- http://localhost/admin/dashboard - admin area
- http://localhost:8080 - PHPMyAdmin

Login Info

Admin:
Email: admin@novacms.local
Password: admin123

Author:
Email: User.local@gmail.com
Password: Password123!

PHPMyAdmin:
Server: db
Username: developer
Password: secret123

What It Does

You can write blog posts, upload images, and organize everything by category. Posts can be drafts, published, or archived. There are different user roles (admin, author, editor, viewer) and a search feature. The admin dashboard lets you manage everything while the public site shows the published posts.

Running It

The Docker setup has NGINX, PHP 8.1, MariaDB, and PHPMyAdmin. Run docker-compose up and the database gets created automatically from the migration files. To stop it, press Ctrl+C or run docker-compose down.

There's a database export file (database_export.sql) if you want to import it through PHPMyAdmin, but it's not needed since the migrations handle everything.

How It's Organized

Controllers (app/src/Controllers/) handle the routes. AdminController runs the dashboard, PostController does CRUD stuff, AuthController handles login/logout, and SearchController does search.

Models (app/src/Models/) are Post, User, and Category. Pretty standard.

Repositories (app/src/Repositories/) keep all the SQL queries separate from the business logic.

Services (app/src/Services/) have the actual business logic. PostService for posts, AuthService for login stuff, MediaService for uploads, AuditService for tracking changes.

Views (app/src/Views/) are just PHP templates. Split into public and admin folders.

Core classes (app/src/Core/) include BaseController, Database (PDO wrapper), CSRF protection, base Repository, and Middleware.

Middleware (app/src/Middleware/) checks if you're logged in (AuthMiddleware) and if you have permission (RoleMiddleware).

CSS is in app/public/css/. I'm using Bootstrap 5.3.2 plus my own CSS framework (framework.css). Each page has its own CSS file too.

Technical Stuff

I went with MVC to keep controllers, models, and views separate. All database stuff goes through repositories so I can change queries without touching other code. Business logic is in service classes to keep controllers simple. Using Composer for autoloading with PSR-4 and FastRoute for routing.

Security stuff: bcrypt for passwords, CSRF tokens on forms, session-based auth, role permissions, htmlspecialchars for XSS protection, and prepared statements for SQL.

Check out app/src/Core/BaseController.php to see how controllers work, app/src/Core/Repository.php for the repository pattern, and app/public/css/framework.css for my CSS setup.

API

There are 5 JSON endpoints in app/src/Controllers/ApiController.php:

- GET /api/posts - paginated posts (supports ?page, ?limit, ?category)
- GET /api/posts/{id} - single post
- GET /api/posts/recent - recent posts (?limit)
- GET /api/posts/search - search (?q)
- GET /api/categories - all categories

Response looks like: {"success": true, "data": {"posts": [...], "pagination": {...}}}

JavaScript

The /posts page loads everything with AJAX. It's just vanilla JavaScript, no jQuery. The page starts empty and calls /api/posts to get JSON data, then builds the HTML and adds it to the page. You can paginate and filter by category without refreshing.

Main functions: loadPosts (AJAX call), displayPosts (builds HTML), displayPagination (page buttons), loadCategories (category dropdown).

Privacy Stuff (GDPR)

Only collecting what's needed: username, email, password, name. No tracking or analytics. Users make their own accounts. If you delete a user, their data gets deleted too (cascade deletes). Passwords are hashed, sessions don't put tokens in URLs, and database credentials stay in docker-compose.yml.

The audit_logs table tracks what happens and when.

Not implemented: cookie banners (only using PHP session), data export, privacy policy page, email notifications.

Accessibility (WCAG)

Using proper HTML with h1/h2/h3 hierarchy, nav elements, main landmarks, and correct button/link usage. Everything works with keyboard (Tab and Enter). Text has good contrast. Featured images have alt text fields. Works on mobile, tablet, and desktop. All form inputs have labels.

Could be better: no skip link, missing some ARIA labels, error messages could be clearer, some custom components need better focus indicators.

Other Notes

Built this from scratch with the Docker setup we used in class. CSS is Bootstrap + custom framework. MVC structure is based on what we learned but I added repositories and services. Admin panel is responsive and changes layout on mobile. User roles control permissions. Database migrations are in database/migrations/ and the full schema is in database_export.sql.

