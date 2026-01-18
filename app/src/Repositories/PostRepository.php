<?php

namespace NovaCMS\Repositories;

use NovaCMS\Core\Repository;

class PostRepository extends Repository
{
    protected string $table = 'posts';

    public function update(int $id, array $data): bool
    {
        $currentPost = $this->findById($id);
        
        if (isset($data['status']) && $data['status'] === 'published') {
            if (empty($currentPost['published_at']) || $currentPost['status'] !== 'published') {
                $data['published_at'] = date('Y-m-d H:i:s');
            }
        }
        
        if (isset($data['status']) && ($data['status'] === 'draft' || $data['status'] === 'archived')) {
            $data['published_at'] = null;
        }
        
        return parent::update($id, $data);
    }

    public function findPublished(int $limit = 10, int $offset = 0, ?int $categoryId = null): array
    {
        $sql = "SELECT p.*, u.username as author_name, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN users u ON p.author_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'published'";
        
        $params = [];
        
        if ($categoryId !== null) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }
        
        $sql .= " ORDER BY p.published_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findForAdmin(int $limit = 20, int $offset = 0, ?string $status = null): array
    {
        $sql = "SELECT p.*, u.username as author_name, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN users u ON p.author_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id";
        
        $params = [];
        
        if ($status) {
            $sql .= " WHERE p.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findByAuthor(int $authorId, int $limit = 20, int $offset = 0, ?string $status = null): array
    {
        $sql = "SELECT p.*, u.username as author_name, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN users u ON p.author_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.author_id = ?";
        
        $params = [$authorId];
        
        if ($status) {
            $sql .= " AND p.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $sql = "SELECT p.*, u.username as author_name, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN users u ON p.author_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.slug = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function incrementViews(int $postId): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET views = views + 1 WHERE id = ?");
        return $stmt->execute([$postId]);
    }

    public function findByCategory(int $categoryId, int $limit = 10): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE category_id = ? AND status = 'published' ORDER BY published_at DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoryId, $limit]);
        return $stmt->fetchAll();
    }



    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE slug = ? AND id != ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$slug, $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE slug = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$slug]);
        }
        
        return $stmt->fetchColumn() > 0;
    }

    public function search(string $query, ?int $categoryId = null, int $limit = 20, int $offset = 0): array
    {
        // Prepare query for BOOLEAN MODE with wildcards for partial matching
        $searchQuery = $this->prepareSearchQuery($query);
        
        $sql = "SELECT p.*, u.username as author_name, c.name as category_name,
                MATCH(p.title, p.content, p.excerpt) AGAINST(? IN BOOLEAN MODE) as relevance
                FROM {$this->table} p
                LEFT JOIN users u ON p.author_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'published'
                AND MATCH(p.title, p.content, p.excerpt) AGAINST(? IN BOOLEAN MODE)";
        
        $params = [$searchQuery, $searchQuery];
        
        if ($categoryId) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }
        
        $sql .= " ORDER BY relevance DESC, p.published_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countSearchResults(string $query, ?int $categoryId = null): int
    {
        // Prepare query for BOOLEAN MODE with wildcards for partial matching
        $searchQuery = $this->prepareSearchQuery($query);
        
        $sql = "SELECT COUNT(*) FROM {$this->table} p
                WHERE p.status = 'published'
                AND MATCH(p.title, p.content, p.excerpt) AGAINST(? IN BOOLEAN MODE)";
        
        $params = [$searchQuery];
        
        if ($categoryId) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function countPublished(?int $categoryId = null): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE status = 'published'";
        $params = [];
        
        if ($categoryId) {
            $sql .= " AND category_id = ?";
            $params[] = $categoryId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

   
    private function prepareSearchQuery(string $query): string
    {
        $query = trim(preg_replace('/\s+/', ' ', $query));
        
        if (empty($query)) {
            return '';
        }
        
        if (stripos($query, ' OR ') !== false) {
            $terms = preg_split('/\s+OR\s+/i', $query);
            $terms = array_map(function($term) {
                $term = trim($term);
                if (preg_match('/^[+\-"]/', $term)) {
                    return $term;
                }
                return $term . '*';
            }, $terms);
            return implode(' ', $terms);
        }
        
        if (stripos($query, ' AND ') !== false) {
            $terms = preg_split('/\s+AND\s+/i', $query);
            $terms = array_map(function($term) {
                $term = trim($term);
                if (preg_match('/^[+\-"]/', $term)) {
                    return $term;
                }
                return '+' . $term . '*';
            }, $terms);
            return implode(' ', $terms);
        }
        
        $terms = explode(' ', $query);
        $terms = array_map(function($term) {
            $term = trim($term);
            if (empty($term)) {
                return '';
            }
            if (preg_match('/^[+\-"]/', $term)) {
                return $term;
            }
            return '+' . $term . '*';
        }, $terms);
        
        $terms = array_filter($terms, fn($t) => !empty($t));
        return implode(' ', $terms);
    }
}
