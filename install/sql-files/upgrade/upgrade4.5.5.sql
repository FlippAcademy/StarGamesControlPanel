# 
# Database : `sgcp`
#

ALTER TABLE `forum` ADD `forum_perm` mediumtext NOT NULL AFTER `forum_description` ;

UPDATE `forum` SET `forum_perm` = '[g1]:show_perm::read_perm::reply_perm::start_perm:[/g1][g2]:show_perm::read_perm::reply_perm::start_perm:[/g2][g3][/g3][g4]:show_perm::read_perm::reply_perm::start_perm:[/g4][g5]:show_perm::read_perm::reply_perm::start_perm:[/g5]';