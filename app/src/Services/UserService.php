<?php

namespace NovaCMS\Services;

use NovaCMS\Models\User;
use NovaCMS\Repositories\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function getAllUsers(int $page = 1, int $perPage = 50): array
    {
        $offset = ($page - 1) * $perPage;
        $users = $this->userRepository->findAllUsers($perPage, $offset);
        return array_map(fn($data) => User::fromArray($data), $users);
    }

    public function countUsers(): int
    {
        return $this->userRepository->countUsers();
    }

    public function getUserById(int $id): ?User
    {
        $userData = $this->userRepository->findById($id);
        return $userData ? User::fromArray($userData) : null;
    }

    public function updateRole(int $userId, string $role): bool
    {
        $validRoles = ['admin', 'editor', 'author', 'viewer'];
        
        if (!in_array($role, $validRoles)) {
            throw new \Exception('Invalid role');
        }
        
        return $this->userRepository->updateRole($userId, $role);
    }

    public function updateStatus(int $userId, string $status): bool
    {
        $validStatuses = ['active', 'inactive'];
        
        if (!in_array($status, $validStatuses)) {
            throw new \Exception('Invalid status');
        }
        
        return $this->userRepository->updateStatus($userId, $status);
    }

    public function resetPassword(int $userId, string $newPassword): bool
    {
        if (strlen($newPassword) < 8) {
            throw new \Exception('Password must be at least 8 characters');
        }
        
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->userRepository->updatePassword($userId, $passwordHash);
    }

    public function deleteUser(int $userId): bool
    {
        // Prevent deletion of the last admin
        $user = $this->getUserById($userId);
        if ($user && $user->role === 'admin') {
            $allUsers = $this->getAllUsers(1, 1000);
            $adminCount = count(array_filter($allUsers, fn($u) => $u->role === 'admin'));
            
            if ($adminCount <= 1) {
                throw new \Exception('Cannot delete the last admin user');
            }
        }
        
        return $this->userRepository->delete($userId);
    }
}

