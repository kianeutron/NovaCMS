<?php

namespace NovaCMS\Repositories;

use NovaCMS\Core\Repository;

class UserRepository extends Repository
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = ?");
        $stmt->execute([$username]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function updateLastLogin(int $userId): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET last_login_at = NOW() WHERE id = ?");
        return $stmt->execute([$userId]);
    }

    public function findAllUsers(int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function countUsers(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        return (int) $stmt->fetchColumn();
    }

    public function updateRole(int $userId, string $role): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $userId]);
    }

    public function updateStatus(int $userId, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $userId]);
    }

    public function updatePassword(int $userId, string $passwordHash): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET password_hash = ? WHERE id = ?");
        return $stmt->execute([$passwordHash, $userId]);
    }
}
