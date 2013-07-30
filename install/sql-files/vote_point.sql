	CREATE TABLE `vote_point` (
	  `key` int(11) NOT NULL auto_increment,
	  `loginname` varchar(55) NOT NULL,
	  `point` int(11) NOT NULL default '0',
	  `last_vote1` int(11) NOT NULL default '0',
	  `last_vote2` int(11) NOT NULL default '0',
	  `last_vote3` int(11) NOT NULL default '0',
	  `date` text NOT NULL,
	  PRIMARY KEY  (`key`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;