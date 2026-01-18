-- Insert admin user with correct password hash
-- Password: admin123
-- This uses the exact bcrypt hash that PHP's password_hash() generates

DELETE FROM users WHERE email = 'admin@novacms.local';

INSERT INTO users (username, email, password_hash, first_name, last_name, role, status, created_at, updated_at) 
VALUES (
    'admin',
    'admin@novacms.local',
    '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5KLjnAV8Sz0ai',
    'Admin',
    'User',
    'admin',
    'active',
    NOW(),
    NOW()
);

