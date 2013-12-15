# 
# Database : `sgcp`
#

ALTER TABLE `groups` ADD `g_move_topics` tinyint(1) AFTER `g_delete_posts` ;

UPDATE `groups` SET `g_move_topics` = '0' WHERE `g_id` =1;
UPDATE `groups` SET `g_move_topics` = '0' WHERE `g_id` =2;
UPDATE `groups` SET `g_move_topics` = '0' WHERE `g_id` =3;
UPDATE `groups` SET `g_move_topics` = '1' WHERE `g_id` =4;
UPDATE `groups` SET `g_move_topics` = '1' WHERE `g_id` =5;