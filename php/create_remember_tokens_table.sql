-- SQL script to create the remember_tokens table for "Remember Me" functionality
-- Run this script in your MySQL database

CREATE TABLE IF NOT EXISTS `remember_tokens` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_uuid` VARCHAR(255) NOT NULL,
  `token_hash` VARCHAR(255) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_uuid` (`user_uuid`),
  KEY `expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Note: This assumes your users table has a 'uuid' column
-- If you're using 'id' instead, adjust the foreign key accordingly
