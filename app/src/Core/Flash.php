<?php

namespace NovaCMS\Core;

class Flash
{
    private static string $key = '_flash_messages';
    
    public static function set(string $type, string $message): void
    {
        if (!isset($_SESSION[self::$key])) {
            $_SESSION[self::$key] = [];
        }
        
        $_SESSION[self::$key][] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    public static function success(string $message): void
    {
        self::set('success', $message);
    }
    
    public static function error(string $message): void
    {
        self::set('error', $message);
    }
    
    public static function info(string $message): void
    {
        self::set('info', $message);
    }
    
    public static function warning(string $message): void
    {
        self::set('warning', $message);
    }
    
    public static function get(): array
    {
        $messages = isset($_SESSION[self::$key]) ? $_SESSION[self::$key] : [];
        unset($_SESSION[self::$key]);
        return $messages;
    }
    
    public static function has(): bool
    {
        return isset($_SESSION[self::$key]) && !empty($_SESSION[self::$key]);
    }
}

