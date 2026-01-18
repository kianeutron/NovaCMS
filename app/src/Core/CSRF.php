<?php

namespace NovaCMS\Core;

class CSRF
{
    private static string $tokenKey = 'csrf_token';
    
    public static function generateToken(): string
    {
        if (!isset($_SESSION[self::$tokenKey])) {
            $_SESSION[self::$tokenKey] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::$tokenKey];
    }
    
    public static function getToken(): ?string
    {
        return isset($_SESSION[self::$tokenKey]) ? $_SESSION[self::$tokenKey] : null;
    }
    
    public static function validateToken(string $token): bool
    {
        $sessionToken = self::getToken();
        
        if (!$sessionToken) {
            return false;
        }
        
        return hash_equals($sessionToken, $token);
    }
    
    public static function field(): string
    {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    public static function validateRequest(): bool
    {
        $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : null;
        
        if (!$token) {
            return false;
        }
        
        return self::validateToken($token);
    }
}

