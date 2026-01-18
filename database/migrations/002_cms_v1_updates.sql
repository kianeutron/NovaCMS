-- NovaCMS v1.0 Updates
-- Phase 0 & 1: Role enforcement, CSRF, Audit logs
-- Created: 2026-01-01


CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    metadata TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE posts ADD COLUMN IF NOT EXISTS author_name VARCHAR(100) NULL AFTER author_id;

ALTER TABLE posts ADD COLUMN IF NOT EXISTS meta_title VARCHAR(255) NULL AFTER content;
ALTER TABLE posts ADD COLUMN IF NOT EXISTS meta_description TEXT NULL AFTER meta_title;


ALTER TABLE posts MODIFY COLUMN status ENUM('draft', 'published', 'archived') DEFAULT 'draft';


CREATE TABLE IF NOT EXISTS post_slug_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    old_slug VARCHAR(255) NOT NULL,
    new_slug VARCHAR(255) NOT NULL,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    INDEX idx_old_slug (old_slug),
    INDEX idx_post_id (post_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS post_revisions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    excerpt TEXT,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_post_id (post_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE posts ADD COLUMN IF NOT EXISTS scheduled_at TIMESTAMP NULL AFTER published_at;
ALTER TABLE posts ADD INDEX IF NOT EXISTS idx_scheduled_at (scheduled_at);

