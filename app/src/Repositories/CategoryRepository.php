<?php

namespace NovaCMS\Repositories;

use NovaCMS\Core\Repository;

class CategoryRepository extends Repository
{
    protected string $table = 'categories';

    public function findActive(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY sort_order, name");
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = ?");
        $stmt->execute([$slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}

