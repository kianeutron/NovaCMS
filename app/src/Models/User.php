<?php

namespace NovaCMS\Models;

class User
{
    public function __construct(
        public ?int $id,
        public string $username,
        public string $email,
        public string $passwordHash,
        public ?string $firstName,
        public ?string $lastName,
        public string $role,
        public string $status,
        public ?string $createdAt,
        public ?string $updatedAt,
        public ?string $lastLoginAt
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['id']) ? $data['id'] : null,
            $data['username'],
            $data['email'],
            isset($data['password_hash']) ? $data['password_hash'] : '',
            isset($data['first_name']) ? $data['first_name'] : null,
            isset($data['last_name']) ? $data['last_name'] : null,
            isset($data['role']) ? $data['role'] : 'author',
            isset($data['status']) ? $data['status'] : 'active',
            isset($data['created_at']) ? $data['created_at'] : null,
            isset($data['updated_at']) ? $data['updated_at'] : null,
            isset($data['last_login_at']) ? $data['last_login_at'] : null
        );
    }

    public function getFullName(): string
    {
        return trim((isset($this->firstName) ? $this->firstName : '') . ' ' . (isset($this->lastName) ? $this->lastName : '')) ?: $this->username;
    }
}
