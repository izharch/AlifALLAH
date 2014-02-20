ALTER TABLE `gallery`
DROP COLUMN `shared`,
DROP COLUMN `approved`,
ADD COLUMN `share_status` ENUM('not_shared','pending','shared','disapproved') NOT NULL DEFAULT 'not_shared';

ALTER TABLE `library`
DROP COLUMN `shared`,
DROP COLUMN `approved`,
ADD COLUMN `share_status` ENUM('not_shared','pending','shared','disapproved') NOT NULL DEFAULT 'not_shared';

ALTER TABLE `media`
DROP COLUMN `shared`,
DROP COLUMN `approved`,
ADD COLUMN `share_status` ENUM('not_shared','pending','shared','disapproved') NOT NULL DEFAULT 'not_shared';