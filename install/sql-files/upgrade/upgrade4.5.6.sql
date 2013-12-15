# 
# Database : `sgcp`
#

ALTER TABLE `status` CHANGE `last_checked` `last_checked` varchar(255)  NOT NULL default '';
ALTER TABLE `user_profile` ADD `display_name` varchar(255)  NOT NULL default '' AFTER `user_id` ;

UPDATE `status` SET `last_checked` = '';