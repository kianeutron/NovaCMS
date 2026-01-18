<?php

namespace NovaCMS\Middleware;

use NovaCMS\Core\Middleware;

/**
 * AuthMiddleware - Ensures user is authenticated
 * Used to protect routes that require login
 */
class AuthMiddleware extends Middleware
{
    public function handle(): bool
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        return true;
    }
}
