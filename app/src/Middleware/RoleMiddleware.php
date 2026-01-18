<?php

namespace NovaCMS\Middleware;

use NovaCMS\Core\Middleware;

/**
 * RoleMiddleware - Ensures user has required role
 * Used for authorization (admin, editor, author, viewer)
 */
class RoleMiddleware extends Middleware
{
    private array $allowedRoles;

    public function __construct(array $allowedRoles = [])
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function handle(): bool
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userRole = $_SESSION['role'] ?? null;
        
        if (!empty($this->allowedRoles) && !in_array($userRole, $this->allowedRoles)) {
            http_response_code(403);
            echo '403 Forbidden - You do not have permission to access this resource.';
            exit;
        }

        return true;
    }
}
