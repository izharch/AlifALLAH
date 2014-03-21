ALTER TABLE likes
ADD COLUMN entity_added_by INT(11) NOT NULL AFTER entity_type;