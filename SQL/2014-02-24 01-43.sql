ALTER TABLE `media`
CHANGE COLUMN `thumbnail` `thumbnail` VARCHAR(255) DEFAULT NULL;

ALTER TABLE `library`
CHANGE COLUMN `thumbnail` `thumbnail` VARCHAR(255) DEFAULT NULL;