<?php

namespace NovaCMS\Services;

class MediaService
{
    private string $uploadPath = '/app/public/uploads/images/';
    private array $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    private int $maxFileSize = 5242880; // 5MB

    public function uploadImage(array $file): ?string
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new \Exception('Invalid file upload');
        }

        // Check file size
        if ($file['size'] > $this->maxFileSize) {
            throw new \Exception('File size exceeds 5MB limit');
        }

        // Check file type
        $mimeType = null;
        $finfo = @finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo !== false) {
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            unset($finfo);
        } else {
            $mimeType = @mime_content_type($file['tmp_name']);
        }

        if (!in_array($mimeType, $this->allowedTypes)) {
            throw new \Exception('Invalid file type. Only JPG, PNG, GIF, and WebP images are allowed. Detected: ' . ($mimeType ?? 'unknown'));
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('img_', true) . '.' . $extension;
        
        // Create year/month subdirectory
        $yearMonth = date('Y/m');
        $uploadDir = $this->uploadPath . $yearMonth;
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $destination = $uploadDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new \Exception('Failed to move uploaded file');
        }

        // Return relative path for storage
        return '/uploads/images/' . $yearMonth . '/' . $filename;
    }

    public function deleteImage(string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        $fullPath = '/app/public' . $path;
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    public function getImageUrl(string $path): string
    {
        return $path;
    }
}
