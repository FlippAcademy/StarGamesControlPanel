# 
# Database : `sgcp`
#

ALTER TABLE `board_topic` ADD `forum_id` int(11) NOT NULL AFTER `topic_id` ;
UPDATE `board_topic` SET `forum_id` = '1' WHERE `forum_id` =0;

DROP TABLE IF EXISTS `forum`;
CREATE TABLE `forum` (
  `forum_id` int(11) NOT NULL auto_increment,
  `category_id` int(11) NOT NULL,
  `forum_title` varchar(255) default '',
  `forum_description` varchar(255) default '',
  PRIMARY KEY  (`forum_id`)
) TYPE=MyISAM;

INSERT INTO `memory` (memory_object,memory_value1,memory_value3) VALUES ('forum_category','1','A Test Category');
INSERT INTO `forum` (category_id,forum_title,forum_description) VALUES ('1','A Test Forum','A test forum that may be removed at any time');

ALTER TABLE `groups` ADD `g_color` varchar(255) default '' AFTER `g_img` ;
ALTER TABLE `groups` ADD `g_forum_manage` tinyint(1) AFTER `g_edit_mes_control` ;
ALTER TABLE `groups` ADD `g_account_manage` tinyint(1) AFTER `g_forum_manage` ;
UPDATE `groups` SET `g_color` = 'gray', `g_forum_manage` = '0' WHERE `g_id` =3;
UPDATE `groups` SET `g_color` = 'green', `g_forum_manage` = '0' WHERE `g_id` =4;
UPDATE `groups` SET `g_color` = 'red',`g_forum_manage` = '1' WHERE `g_id` =5;
UPDATE `groups` SET `g_forum_manage` = '0', `g_account_manage` = '0' WHERE `g_id` =1;
UPDATE `groups` SET `g_forum_manage` = '0', `g_account_manage` = '0' WHERE `g_id` =2;
UPDATE `groups` SET `g_forum_manage` = '0', `g_account_manage` = '0' WHERE `g_id` =3;
UPDATE `groups` SET `g_forum_manage` = '0', `g_account_manage` = '0' WHERE `g_id` =4;
UPDATE `groups` SET `g_forum_manage` = '1', `g_account_manage` = '1' WHERE `g_id` =5;