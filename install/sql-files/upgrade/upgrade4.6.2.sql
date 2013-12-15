# 
# Database : `sgcp`
#

UPDATE `user_profile` SET `user_number` = `user_id`;
ALTER TABLE `user_profile` DROP `user_id`;
ALTER TABLE `user_profile` CHANGE `user_number` `user_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `user_profile` CHANGE `display_name` `display_name` VARCHAR( 64 ) NULL ,
CHANGE `user_sls_pass` `user_sls_pass` VARCHAR( 32 ) NULL ,
CHANGE `user_time_offset` `user_time_offset` VARCHAR( 32 ) NULL ,
CHANGE `user_ranking` `user_ranking` INT( 11 ) UNSIGNED NULL DEFAULT '0',
CHANGE `user_flood_protection` `user_flood_protection` INT( 10 ) NULL DEFAULT '0',
CHANGE `user_joined` `user_joined` INT( 10 ) NULL ,
CHANGE `user_last_login` `user_last_login` INT( 10 ) NULL;

CREATE TABLE `register_log` (
  `reg_id` int(11) NOT NULL auto_increment,
  `Date` datetime NOT NULL default '0000-00-00 00:00:00',
  `account_id` int(11) unsigned NOT NULL,
  `userid` varchar(23) NOT NULL default '',
  `level` tinyint(3) NOT NULL default '0',
  `ip` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`reg_id`),
  KEY (`account_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
