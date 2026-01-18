<?php

namespace NovaCMS\Core;

abstract class Middleware
{
    abstract public function handle(): bool;
    
    protected function redirect(string $path): void
    {
        header("Location: $path");
        exit;
    }
    
    protected function unauthorized(): void
    {
        http_response_code(403);
        echo '403 Forbidden - You do not have permission to access this resource.';
        exit;
    }
}

