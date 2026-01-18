
START TRANSACTION;

-- Flat categories
INSERT INTO categories (name, slug, description, parent_id, sort_order, status)
VALUES
  ('Technology', 'technology', 'Tech news, guides and tutorials', NULL, 1, 'active'),
  ('Tutorials', 'tutorials', 'Step-by-step how-tos and guides', NULL, 2, 'active'),
  ('Opinion', 'opinion', 'Editorials and opinions', NULL, 3, 'active'),
  ('Reviews', 'reviews', 'Product reviews and recommendations', NULL, 4, 'active')
ON DUPLICATE KEY UPDATE id = id; 

INSERT IGNORE INTO categories (name, slug, description, parent_id, sort_order, status)
SELECT 'Web Development', 'web-development', 'Articles about web development', c.id, 5, 'active'
FROM categories c
WHERE c.slug = 'technology'
LIMIT 1;

COMMIT;


SELECT id, name, slug, parent_id, sort_order, status, created_at
FROM categories
WHERE slug IN ('technology','tutorials','opinion','reviews','web-development')
   OR status = 'active'
ORDER BY sort_order, name;

