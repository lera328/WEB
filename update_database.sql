-- Добавляем поля для поддержки Yandex ID в таблицу admins
ALTER TABLE admins 
ADD COLUMN yandex_id VARCHAR(50) NULL UNIQUE AFTER username,
ADD COLUMN email VARCHAR(255) NULL AFTER password,
ADD COLUMN last_login DATETIME NULL AFTER email,
ADD COLUMN created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER last_login;

-- Добавляем индекс для yandex_id
CREATE INDEX idx_yandex_id ON admins(yandex_id);

-- Обновляем существующих пользователей (если нужно)
-- UPDATE admins SET created_at = NOW() WHERE created_at IS NULL;
