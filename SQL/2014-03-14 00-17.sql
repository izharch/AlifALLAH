ALTER TABLE `user`
ADD COLUMN `added_at` DATETIME DEFAULT NULL,
ADD COLUMN `confirmation_token` VARCHAR(32) DEFAULT NULL,
ADD COLUMN `status` ENUM('unverified', 'active', 'blocked') DEFAULT 'unverified';