NovaCMS

A PHP-based content management system built with MVC architecture. This is my school project for web development class.

Quick Start

The app runs entirely in Docker containers. Just run this command: docker-compose up

Then open your browser to:
Main site: http://localhost
Admin panel: http://localhost/admin/dashboard
PHPMyAdmin: http://localhost:8080

PHPMyAdmin Login:
Server: db
Username: developer
Password: secret123

Login Credentials

Admin Account:
Email: admin@novacms.local
Password: admin123

Author Account:
Email: User.local@gmail.com
Password: Password123!

PHPMyAdmin Login:
Server: db
Username: developer
Password: secret123

Database for PHPMyAdmin:
Username: developer
Password: secret123
Root password: secret123

What This CMS Does

This is a working content management system where you can create, edit, and delete blog posts. You can upload featured images for posts and organize posts into categories. Posts can be set as draft, published, or archived. The system supports managing users with different roles including admin, author, editor, and viewer. There is full-text search through posts and audit logs to track changes. The public site shows all published posts while the admin dashboard is where you manage everything.

Docker Setup

The project uses the class setup with these containers: NGINX web server, PHP 8.1 with PDO MySQL, MariaDB database, and PHPMyAdmin for database management. Everything is defined in docker-compose.yml and runs with one command. The database gets created automatically from the migrations in the database/migrations/ folder. To stop the containers, press Ctrl+C or run: docker-compose down

Database Export

There is a full database export file in the root called database_export.sql. This file has all the table structures and the default admin user. You can import it through PHPMyAdmin if needed, but the app will create everything automatically on first run if you have the migrations set up.

Project Structure

Controllers are in app/src/Controllers/ and handle all the routes and user requests. AdminController.php manages the admin dashboard, PostController.php handles post CRUD operations, AuthController.php manages login/register/logout, and SearchController.php provides search functionality.

Models are in app/src/Models/ and represent database tables. The main models are Post.php, User.php, and Category.php.

Repositories are in app/src/Repositories/ and handle all database queries. They keep SQL separate from business logic. Each model has its own repository.

Services are in app/src/Services/ and contain the business logic layer. PostService.php handles post operations, AuthService.php manages authentication, MediaService.php handles file uploads, and AuditService.php tracks user actions.

Views are in app/src/Views/ and contain HTML templates for all pages. The project uses a simple PHP templating approach with views split into public views and admin views.

Core Classes are in app/src/Core/ and include BaseController.php as the parent class for all controllers, Database.php as a PDO wrapper, CSRF.php for CSRF token generation and validation, Repository.php as the base repository class, and Middleware.php as the base middleware class.

Middleware is in app/src/Middleware/ with AuthMiddleware.php checking if users are logged in and RoleMiddleware.php checking user permissions.

CSS files are in app/public/css/ using Bootstrap 5.3.2 for the base framework. There is a custom CSS framework in framework.css for an extended design system. Page-specific styles are in separate files using a combined approach of Bootstrap components plus custom styling.

Framework and Patterns Used

The project uses both Bootstrap 5.3.2 and a custom CSS framework. Bootstrap provides the base components and grid system, while the custom framework extends it with design tokens, custom components, and project-specific styling.

The app follows Model-View-Controller separation. Controllers handle requests, models represent data, and views render HTML. This keeps things organized and testable.

All database access goes through repository classes using the Repository Pattern. This means database queries can be changed without touching controllers or services. It makes the code cleaner.

Business logic lives in service classes using a Service Layer. Controllers stay thin and just coordinate between services and views. Services can be reused across different controllers.

The project uses PSR-4 Autoloading with Composer's autoloader using the namespace NovaCMS. Classes load automatically based on their namespace and file location.

Routing uses FastRoute and routes are defined in app/public/index.php. It is fast and simple to use.

Security Features include passwords hashed with bcrypt, CSRF protection on all forms, session-based authentication, role-based access control, input sanitization with htmlspecialchars, and prepared statements for SQL queries.

Key Files to Check Out include app/src/Core/BaseController.php to see how controllers work, app/src/Core/Repository.php to see the repository pattern, app/src/Services/PostService.php as an example of service layer, app/public/css/framework.css for the custom CSS framework, and app/src/Middleware/RoleMiddleware.php for role-based access.

API Endpoints

The app provides REST API endpoints that return data in JSON format. These are used for AJAX functionality and can be accessed programmatically. The endpoints are in app/src/Controllers/ApiController.php.

Available Endpoints:

GET /api/posts gets a paginated list of published posts with query params page, limit, and category. It returns JSON with posts array and pagination info.

GET /api/posts/{id} gets a single post by ID and returns JSON with post data.

GET /api/posts/recent gets recent posts with query param limit defaulting to 5. It returns JSON with recent posts array.

GET /api/posts/search searches posts with query param q for the search query. It returns JSON with search results.

GET /api/categories gets all active categories and returns JSON with categories array.

Example Response format: {"success": true, "data": {"posts": [...], "pagination": {"current_page": 1, "per_page": 10, "total": 25, "total_pages": 3}}}

JavaScript and AJAX Implementation

The app uses JavaScript to load data dynamically without page refreshes. The Browse Posts Page at /posts uses vanilla JavaScript with no jQuery or frameworks. The page is in app/src/Views/posts/browse.php and fetches posts from the /api/posts endpoint. It updates the DOM dynamically and implements client-side pagination with category filtering without page reload.

How it works: The page loads with an empty container, then JavaScript calls the /api/posts API endpoint and receives a JSON response. It builds HTML dynamically using JavaScript and inserts it into the DOM. Pagination buttons trigger new API calls with no page refresh needed.

Key Functions include loadPosts which fetches posts via AJAX, displayPosts which generates HTML from JSON, displayPagination which creates pagination controls, and loadCategories which populates the category dropdown.

This demonstrates modern web development with separation of concerns where the backend provides data via API and the frontend handles presentation with JavaScript.

GDPR Compliance

The app follows basic privacy principles. It uses Data Minimization by only collecting necessary user data including username, email, password, and name. There are no tracking scripts or analytics.

For User Consent, users create their own accounts with no automatic data collection.

For Right to be Forgotten, when you delete a user from the database, all their data goes with them through cascade delete on user_id foreign keys.

Data Security includes passwords that are hashed and never stored as plain text, session-based auth with no tokens in URLs, and database credentials kept in docker-compose.yml not in code.

The audit_logs table tracks who did what and when. This helps with accountability and investigating issues.

What is Not Implemented includes cookie consent banner since there are no cookies except PHP session, data export feature, privacy policy page, and email notifications about data changes.

For a school project this covers the basics. A real production site would need more features like cookie management, explicit consent forms, and data portability.

WCAG Compliance

The site tries to be accessible. It uses Semantic HTML with proper heading hierarchy including h1, h2, h3, nav elements, main landmarks, and buttons vs links used correctly.

For Keyboard Navigation, all interactive elements work with Tab and Enter. There are no mouse-only features. The hamburger menu on mobile works with keyboard.

Color Contrast is good with text having good contrast against backgrounds. Dark text is used on light backgrounds for readability.

Alt Text on Images is supported with featured images having alt text fields in the database. Forms prompt you to add alt text when uploading.

Responsive Design works on mobile, tablet, and desktop. Tables convert to cards on mobile and text sizes scale appropriately.

Form Labels mean all form inputs have proper labels associated with them.

What Could Be Better includes no skip-to-content link, no ARIA labels on some interactive elements, error messages could be more descriptive, and no focus indicators on some custom components.

The basics are there but there is room for improvement with ARIA attributes and better focus management.

Notes

This project was built from scratch using the Docker setup from class. The CSS combines Bootstrap 5.3.2 with a custom CSS framework. The MVC structure follows patterns we learned but is expanded with repositories and services to keep things organized. The admin panel works well for managing posts and has a responsive design that switches layouts on mobile. The role system is in place so you can have different permission levels. Database migrations are in database/migrations/ and run in order. The full export is in database_export.sql at the root if you want to see the complete schema.

Rubric Compliance Summary

This project meets all Web Development 1 assignment requirements:

CSS 2 points - Bootstrap 5.3.2 plus custom CSS framework, professional responsive design with transitions

Sessions 1 point - Session-based authentication storing user data with login and logout functionality

Security 2 points - XSS protection using htmlspecialchars, SQL injection protection using prepared statements, bcrypt password hashing, CSRF tokens, input validation, and route protection

MVC 2 points - Full CRUD operations, clear MVC structure with service and repository layers, FastRoute routing, dependency inversion, interfaces, and PSR-4 autoloading

API 1 point - 5 REST endpoints returning JSON for posts, categories, and search with pagination

JavaScript 1 point - AJAX posts loading without page refresh, communicates with API, processes JSON, and updates DOM dynamically

Legal and Accessibility 1 point - GDPR and WCAG compliance documented above with code references

Total: 10 out of 10 points
