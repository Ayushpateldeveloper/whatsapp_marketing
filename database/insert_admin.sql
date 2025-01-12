-- Replace the [HASHED_PASSWORD] with the hash generated from create_admin.php
INSERT INTO users (username, password, status) 
VALUES ('admin', '[HASHED_PASSWORD]', 'active');

-- Example of how it might look (DO NOT USE THIS EXACT HASH, generate your own):
-- INSERT INTO users (username, password, status) 
-- VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active');