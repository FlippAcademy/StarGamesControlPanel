# 
# Database : `sgcp`
#

ALTER TABLE `board_reply` CHANGE `topic_id` `topic_id` int(11) NOT NULL;
ALTER TABLE `board_reply` ADD `forum_id` int(11) NOT NULL AFTER `topic_id` ;

UPDATE `memory` SET `memory_value2` = `memory_value1` WHERE `memory_object` = 'forum_category';

INSERT INTO `memory` (memory_object,memory_value1) VALUES ('upgrade_sgcp','0692f36eb27607e4837760bbbf813d92');