

ALTER TABLE posts ADD COLUMN IF NOT EXISTS featured_image VARCHAR(255) NULL AFTER content;

ALTER TABLE posts ADD INDEX IF NOT EXISTS idx_featured_image (featured_image);

