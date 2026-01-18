<?php

namespace NovaCMS\Models;

class Category
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $slug,
        public ?string $description,
        public ?int $parentId,
        public int $sortOrder,
        public string $status,
        public ?string $createdAt,
        public ?string $updatedAt
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['name'],
            $data['slug'],
            $data['description'] ?? null,
            $data['parent_id'] ?? null,
            $data['sort_order'] ?? 0,
            $data['status'] ?? 'active',
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null
        );
    }
}

