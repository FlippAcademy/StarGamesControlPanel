# 
# Database : `sgcp`
# 

DROP TABLE IF EXISTS `mainnews`;
CREATE TABLE `mainnews` (
  `post_id` int(11) NOT NULL auto_increment,
  `title` varchar(60) NOT NULL,
  `message` text NOT NULL,
  `poster` text NOT NULL,
  `date` varchar(255) NOT NULL,
  PRIMARY KEY  (`post_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `privilege`;
CREATE TABLE `privilege` (
  `account_id` int(11) NOT NULL default '0',
  `privilege` int(11) NOT NULL default '0',
  PRIMARY KEY  (`account_id`)
) TYPE=MyISAM;
INSERT INTO `privilege` VALUES (704554,5);

DROP TABLE IF EXISTS `bugreport`;
CREATE TABLE `bugreport` (
  `post_id` int(11) NOT NULL auto_increment,
  `report` text NOT NULL,
  `poster` text NOT NULL,
  `sex` char(1) NOT NULL default 'M',
  `ip` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  PRIMARY KEY  (`post_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `forum`;
CREATE TABLE `forum` (
  `forum_id` int(11) NOT NULL auto_increment,
  `category_id` int(11) NOT NULL,
  `forum_title` varchar(255) default '',
  `forum_description` varchar(255) default '',
  `forum_perm` mediumtext NOT NULL,
  PRIMARY KEY  (`forum_id`)
) TYPE=MyISAM;
INSERT INTO `forum` (category_id,forum_title,forum_description,forum_perm) VALUES ('1','A Test Forum','A test forum that may be removed at any time','[g1]:show_perm::read_perm::reply_perm::start_perm:[/g1][g2]:show_perm::read_perm::reply_perm::start_perm:[/g2][g3][/g3][g4]:show_perm::read_perm::reply_perm::start_perm:[/g4][g5]:show_perm::read_perm::reply_perm::start_perm:[/g5]');

DROP TABLE IF EXISTS `board_reply`;
CREATE TABLE `board_reply` (
  `reply_id` int(11) NOT NULL auto_increment,
  `topic_id` int(11) NOT NULL,
  `forum_id` int(11) NOT NULL,
  `reply_user_id` varchar(255) default '',
  `reply_emo` int(11) NOT NULL,
  `reply_message` text,
  `reply_ip` varchar(255) NOT NULL default '0.0.0.0',
  `reply_date` varchar(255) default '',
  `reply_upload` varchar(255) default '',
  `reply_edit_name` varchar(255) default '',
  `reply_edit_date` varchar(255) default '',
  PRIMARY KEY  (`reply_id`)
) TYPE=MyISAM; 

DROP TABLE IF EXISTS `board_topic`;
CREATE TABLE `board_topic` (
  `topic_id` int(11) NOT NULL auto_increment,
  `forum_id` int(11) NOT NULL,
  `pinned_mode` varchar(255) NOT NULL default '0',
  `closed_mode` varchar(255) NOT NULL default '0',
  `topic_name` varchar(255) default '',
  `topic_description` varchar(255) default '',
  `topic_starter` varchar(255) NOT NULL default '',
  `topic_reading` varchar(255) NOT NULL default '0',
  `topic_replying` varchar(255) NOT NULL default '0',
  `topic_lastreply_name` varchar(255) default '',
  `topic_start_date` varchar(255) default '',
  `topic_last_action_date` varchar(255) default '',
  PRIMARY KEY  (`topic_id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `user_profile`;
CREATE TABLE `user_profile` (
  `user_number` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) unsigned NOT NULL,
  `display_name` varchar(255)  NOT NULL default '',
  `user_sls_pass` varchar(255)  NOT NULL default '',
  `user_time_offset` varchar(255) default '',
  `user_ranking` varchar(255) NOT NULL default '0',
  `user_avatar` varchar(255) default '',
  `user_avatar_width` varchar(255) NOT NULL default '0',
  `user_avatar_height` varchar(255) NOT NULL default '0',
  `user_signature_message` text,
  `user_flood_protection` varchar(12) NOT NULL default '0',
  `user_joined` varchar(255) default '',
  `user_last_login` varchar(255) default '',
  `user_online` tinyint(1) default '0',
  PRIMARY KEY  (`user_number`)
) TYPE=MyISAM; 

DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `last_checked` varchar(255) NOT NULL default '',
  `login` int(11) NOT NULL default '0',
  `char` int(11) NOT NULL default '0',
  `map` int(11) NOT NULL default '0',
  PRIMARY KEY  (`last_checked`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `user_online`;
CREATE TABLE `user_online` (
  `session` char(100) NOT NULL,
  `time` int(11) DEFAULT '0' NOT NULL
) TYPE=MyISAM;

DROP TABLE IF EXISTS `poll`;
CREATE TABLE `poll` (
  `poll_id` int(11) NOT NULL auto_increment,
  `topic_id` int(11) NOT NULL,
  `starter_id` varchar(255) default '',
  `last_vote_date` varchar(255) default '',
  `poll_question` varchar(255) default '',
  `choice1` text,
  `choice2` text,
  `choice3` text,
  `choice4` text,
  `choice5` text,
  `choice6` text,
  `choice7` text,
  `choice8` text,
  `choice9` text,
  `choice10` text,
  PRIMARY KEY  (`poll_id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `poll_vote`;
CREATE TABLE `poll_vote` (
  `poll_id` int(11) NOT NULL auto_increment,
  `topic_id` int(11) NOT NULL,
  `vote1` int(11) default '0',
  `vote2` int(11) default '0',
  `vote3` int(11) default '0',
  `vote4` int(11) default '0',
  `vote5` int(11) default '0',
  `vote6` int(11) default '0',
  `vote7` int(11) default '0',
  `vote8` int(11) default '0',
  `vote9` int(11) default '0',
  `vote10` int(11) default '0',
  PRIMARY KEY  (`poll_id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `voters`;
CREATE TABLE `voters` (
  `vid` int(11) NOT NULL auto_increment,
  `ip_address` varchar(255) default '',
  `vote_date` varchar(255) default '',
  `topic_id` varchar(255) default '',
  `member_id` varchar(255) default '',
  PRIMARY KEY  (`vid`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `g_id` int(11) NOT NULL auto_increment,
  `g_title` varchar(32) default '',
  `g_img` varchar(255) default '',
  `g_color` varchar(255) default '',
  `g_view_board` tinyint(1),
  `g_post_new_topics` tinyint(1),
  `g_post_polls` tinyint(1),
  `g_vote_polls` tinyint(1),
  `g_edit_topics` tinyint(1),
  `g_edit_posts` tinyint(1),
  `g_delete_topics` tinyint(1),
  `g_delete_posts` tinyint(1),
  `g_move_topics` tinyint(1),
  `g_file_upload` tinyint(1),
  `g_avatar_upload` tinyint(1),
  `g_pinned_topics` tinyint(1),
  `g_closed_topics` tinyint(1),
  `g_read_news` tinyint(1),
  `g_read_privilege` tinyint(1),
  `g_add_news` tinyint(1),
  `g_add_privilege` tinyint(1),
  `g_edit_news` tinyint(1),
  `g_edit_privilege` tinyint(1),
  `g_delete_news` tinyint(1),
  `g_delete_privilege` tinyint(1),
  `g_delete_id` tinyint(1),
  `g_view_lastestcp` tinyint(1),
  `g_view_adminmenu` tinyint(1),
  `g_view_topic_option` tinyint(1),
  `g_upload_nonlimit` int(11),
  `g_post_closed_topics` tinyint(1),
  `g_non_showip` tinyint(1),
  `g_no_delay_posts` tinyint(1),
  `g_view_userip` tinyint(1),
  `g_searching_id` tinyint(1),
  `g_edit_rank_title` tinyint(1),
  `g_edit_mes_control` tinyint(1),
  `g_forum_manage` tinyint(1),
  `g_account_manage` tinyint(1),
  PRIMARY KEY  (`g_id`)
) TYPE=MyISAM;
INSERT INTO `groups` VALUES (1,'Guest','guest.gif','',1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
INSERT INTO `groups` VALUES (2,'Member','member.gif','',1,1,1,1,0,0,0,0,0,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
INSERT INTO `groups` VALUES (3,'Banned','banned.gif','gray',1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
INSERT INTO `groups` VALUES (4,'Sub Admin','sub_admin.gif','green',1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,1,0,1,0,0,0,1,1,1,1,1,1,1,1,0,0,0,0);
INSERT INTO `groups` VALUES (5,'Administrator','admin.gif','red',1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);

DROP TABLE IF EXISTS `query_log`;
CREATE TABLE `query_log` (
  `action_id` int(11) NOT NULL auto_increment,
  `Date` datetime NOT NULL default '0000-00-00 00:00:00',
  `User` varchar(24) NOT NULL default '',
  `IP` varchar(20) NOT NULL default '',
  `page` varchar(24) NOT NULL default '',
  `query` text NOT NULL,
  PRIMARY KEY  (`action_id`),
  KEY `action_id` (`action_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `ranking_ignore`;
CREATE TABLE `ranking_ignore` (
  `account_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`account_id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `security_code`;
CREATE TABLE `security_code` (
  `sc_id` text NOT NULL,
  `sc_code` varchar(11) NOT NULL default '0',
  `sc_time` int(11) NOT NULL default '0'
) TYPE=MyISAM;

DROP TABLE IF EXISTS `rank_title`;
CREATE TABLE `rank_title` (
  `title_id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '0',
  `min_post` int(11) NOT NULL default '0',
  PRIMARY KEY  (`title_id`)
) TYPE=MyISAM AUTO_INCREMENT=1;
INSERT INTO `rank_title` VALUES (1,'Baby',0);
INSERT INTO `rank_title` VALUES (2,'Child',10);
INSERT INTO `rank_title` VALUES (3,'Adult',30);
INSERT INTO `rank_title` VALUES (4,'Major',50);
INSERT INTO `rank_title` VALUES (5,'Colonel',100);
INSERT INTO `rank_title` VALUES (6,'General',200);
INSERT INTO `rank_title` VALUES (7,'Sir',350);
INSERT INTO `rank_title` VALUES (8,'Baron',600);
INSERT INTO `rank_title` VALUES (9,'Marquis',900);
INSERT INTO `rank_title` VALUES (10,'King',1200);

DROP TABLE IF EXISTS `register_log`;
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

DROP TABLE IF EXISTS `memory`;
CREATE TABLE `memory` (
  `memory_id` int(11) NOT NULL auto_increment,
  `memory_object` varchar(255) default '',
  `memory_value1` varchar(255) default '',
  `memory_value2` varchar(255) default '',
  `memory_value3` text,
  PRIMARY KEY  (`memory_id`)
) TYPE=MyISAM; 
INSERT INTO `memory` (memory_object,memory_value1) VALUES ('sgcp_install','1');
INSERT INTO `memory` (memory_object,memory_value1) VALUES ('ro_message','Administrator');
INSERT INTO `memory` (memory_object,memory_value1,memory_value2,memory_value3) VALUES ('forum_category','1','1','A Test Category');
