<?php

namespace NovaCMS\Services;

use NovaCMS\Models\User;
use NovaCMS\Repositories\UserRepository;

class AuthService
{
    private UserRepository $userRepository;
    private AuditService $auditService;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->auditService = new AuditService();
    }

    public function login(string $email, string $password): ?User
    {
        $userData = $this->userRepository->findByEmail($email);
        
        if (!$userData) {
            return null;
        }
        
        if (!$this->verifyPassword($password, $userData['password_hash'])) {
            return null;
        }

        $user = User::fromArray($userData);
        
        // Check if user is inactive
        if ($user->status !== 'active') {
            return null;
        }
        
        $this->updateLastLogin($user->id);
        $this->storeSession($user);
        
        // Regenerate session ID for security
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        
        // Log successful login
        $this->auditService->logUserLogin($user->id);
        
        return $user;
    }

    public function register(array $data): User
    {
        $hashedPassword = $this->hashPassword($data['password']);
        
        $userId = $this->userRepository->create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => $hashedPassword,
            'first_name' => isset($data['first_name']) ? $data['first_name'] : null,
            'last_name' => isset($data['last_name']) ? $data['last_name'] : null,
            'role' => isset($data['role']) ? $data['role'] : 'author',
            'status' => 'active'
        ]);

        $userData = $this->userRepository->findById($userId);
        return User::fromArray($userData);
    }

    public function logout(): void
    {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
        if ($userId) {
            $this->auditService->logUserLogout($userId);
        }
        
        session_destroy();
    }

    public function getCurrentUser(): ?User
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $userData = $this->userRepository->findById($_SESSION['user_id']);
        return $userData ? User::fromArray($userData) : null;
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    private function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    private function updateLastLogin(int $userId): void
    {
        $this->userRepository->updateLastLogin($userId);
    }

    private function storeSession(User $user): void
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;
    }
}
