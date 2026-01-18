<?php

namespace NovaCMS\Services;

use NovaCMS\Core\Database;
use PDO;

class AuditService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function log(string $action, string $entityType, ?int $entityId, ?int $userId = null, ?array $metadata = null): void
    {
        $userId = $userId !== null ? $userId : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null);
        $ipAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
        
        $sql = "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, ip_address, user_agent, metadata, created_at) 
                VALUES (:user_id, :action, :entity_type, :entity_id, :ip_address, :user_agent, :metadata, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'metadata' => $metadata ? json_encode($metadata) : null
        ]);
    }

    public function logPostCreated(int $postId, ?int $userId = null): void
    {
        $this->log('post_created', 'post', $postId, $userId);
    }

    public function logPostUpdated(int $postId, ?int $userId = null): void
    {
        $this->log('post_updated', 'post', $postId, $userId);
    }

    public function logPostDeleted(int $postId, ?int $userId = null): void
    {
        $this->log('post_deleted', 'post', $postId, $userId);
    }

    public function logPostPublished(int $postId, ?int $userId = null): void
    {
        $this->log('post_published', 'post', $postId, $userId);
    }

    public function logPostUnpublished(int $postId, ?int $userId = null): void
    {
        $this->log('post_unpublished', 'post', $postId, $userId);
    }

    public function logUserLogin(int $userId): void
    {
        $this->log('user_login', 'user', $userId, $userId);
    }

    public function logUserLogout(int $userId): void
    {
        $this->log('user_logout', 'user', $userId, $userId);
    }

    public function logCategoryCreated(int $categoryId, ?int $userId = null): void
    {
        $this->log('category_created', 'category', $categoryId, $userId);
    }

    public function logCategoryUpdated(int $categoryId, ?int $userId = null): void
    {
        $this->log('category_updated', 'category', $categoryId, $userId);
    }

    public function logCategoryDeleted(int $categoryId, ?int $userId = null): void
    {
        $this->log('category_deleted', 'category', $categoryId, $userId);
    }
}

