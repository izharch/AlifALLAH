ALTER TABLE `gallery`
ADD COLUMN `added_by` INT(11) NOT NULL AFTER `file`;

ALTER TABLE `library`
ADD COLUMN `added_by` INT(11) NOT NULL AFTER `thumbnail`;

ALTER TABLE `media`
ADD COLUMN `added_by` INT(11) NOT NULL AFTER `thumbnail`;