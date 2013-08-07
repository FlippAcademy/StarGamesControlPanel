/*
// =========================================================================
//    ______                                              
//   / __/ /____ ________ ____ ___ _  ___ ___             
//  _\ \/ __/ _ `/ __/ _ `/ _ `/  ' \/ -_|_-<             
// /___/\__/\_,_/_/  \_, /\_,_/_/_/_/\__/___/             
//   _____          /___/        __  ___                __
//  / ___/__  ___  / /________  / / / _ \___ ____  ___ / /
// / /__/ _ \/ _ \/ __/ __/ _ \/ / / ___/ _ `/ _ \/ -_) / 
// \___/\___/_//_/\__/_/  \___/_/ /_/   \_,_/_//_/\__/_/ 
// =========================================================================
// Copyright (c) Stargames Control Panel - Licensed under GNU GPL.
// See LICENSE File
// =========================================================================
// Project Lead by: Mysterious
// =========================================================================
*/
<?php
if(!$SERVER['system_safe']) exit();
if ($GET_showtopic) {
	$CP['title'] = get_topicname($GET_showtopic);
	$CP['file_include'] = "showtopic.php";
}
else if ($GET_showforum) {
	$forum_name = get_forumname($GET_showforum);
	$CP['title'] = $forum_name;
	$CP['file_include'] = "showforum.php";
}
else if ($GET_showuser) {
	$CP['title'] = "User Information";
	$CP['file_include'] = "showuser.php";
}
else {
switch($GET_act) {
	case login:
		$CP['title'] = "Login";
		$CP['file_include'] = "login.php";
		break;
	case login_action:
		$CP['title'] = "Redirect.. to Login";
		$CP['file_include'] = "login_action.php";
		break;
	case logout:
		$CP['title'] = "Redirect.. to Index";
		$CP['file_include'] = "logout.php";
		break;
	case register:
		$CP['title'] = "Registration";
		$CP['file_include'] = "register.php";
		break;
	case download:
		$CP['title'] = "Download";
		$CP['file_include'] = "download.php";
		break;
	case lostpass:
		$CP['title'] = "Lost Password";
		$CP['file_include'] = "lostpass.php";
		break;
	case info:
		$CP['title'] = "Server Information";
		$CP['file_include'] = "svinfo.php";
		break;
	case change_profile:
		$CP['title'] = "Change Profile";
		$CP['file_include'] = "change_profile.php";
		break;
	case action_news:
		switch($GET_code) {
			case 01:
			case 02:
				$CP['title'] = "Add news";
				break;
			case 03:
			case 04:
				$CP['title'] = "Edit news";
				break;
			case 05:
				$CP['title'] = "Deleting news";
				break;
		}
		$CP['file_include'] = "action_news.php";
		break;
	case readnews:
		$CP['title'] = "News Control";
		$CP['file_include'] = "readnews.php";
		break;
	case shownews:
		$CP['title'] = "Show news";
		$CP['file_include'] = "shownews.php";
		break;
	case privilege:
		$CP['title'] = "Privilege Control";
		$CP['file_include'] = "privilege.php";
		break;
	case privilege_action:
		$CP['title'] = "Privilege Action";
		$CP['file_include'] = "privilege_action.php";
		break;
	case deluser:
		$CP['title'] = "Delete User";
		$CP['file_include'] = "deluser.php";
		break;
	case bugreport:
		$CP['title'] = "Bug report";
		$CP['file_include'] = "bugreport.php";
		break;
	case forum:
		$CP['title'] = "Forum";
		$CP['file_include'] = "forum.php";
		break;
	case p_rank:
		$CP['title'] = "Player Ranking";
		$CP['file_include'] = "player_rank.php";
		break;
	case g_rank:
		session_start();
		$CP['title'] = "Guild Ranking";
		$CP['file_include'] = "guild_rank.php";
		break;
	case guildstanding:
		session_start();
		$CP['title'] = "Guild Castle Standing";
		$CP['file_include'] = "guild_standing.php";
		break;
	case checkcp:
		if($CONFIG_save_type != '1') {
			session_start();
		}
		session_register("CP_UPDATE");
		$_SESSION[CP_UPDATE] = "1";
		$CP['title'] = "CP Update";
		$CP['file_include'] = "check_cp.php";
		break;
	case post:
		switch($GET_code) {
			case 00:
			case 04:
				$CP['title'] = "Posting New Topic";
				break;
			case 01:
				$topic_name = get_topicname($GET_t);
				$CP['title'] = "Replying in $topic_name";
				break;
			case 02:
				$topic_name = get_topicname($GET_t);
				$CP['title'] = "Editing Post $topic_name";
				break;
			case 03:
				$CP['title'] = "Please stand by...";
				break;
			case 05:
				$CP['title'] = "Deleting news";
				break;
		}
		$CP['file_include'] = "action_post.php";
		break;
	case insert_topic:
		switch($GET_code) {
			case 00:
				$CP['title'] = "Inserting New Topic";
				break;
			case 01:
			case 02:
			case 03:
				$CP['title'] = "Please stand by...";
				break;
		}
		$CP['file_include'] = "insert_topic.php";
		break;
	case mod:
		$topic_name = get_topicname($POST_t);
		switch($POST_code) {
			case 02:
				$CP['title'] = "Delete Topic: $topic_name";
				break;
			case 03:
				$CP['title'] = "Editing Topic: $topic_name";
				break;
		}
		$CP['file_include'] = "moderate.php";
		break;
	case sls:
		$CP['title'] = "Self Locking System";
		$CP['file_include'] = "sls.php";
		break;
	case guildinfo:
		$CP['title'] = "Guild Information";
		$CP['file_include'] = "guildinfo.php";
		break;
	case searching_id:
		$CP['title'] = "Searching Account ID";
		$CP['file_include'] = "searching_id.php";
		break;
	case searching_char:
		$CP['title'] = "Searching Char ID";
		$CP['file_include'] = "searching_char.php";
		break;
	case rank_title:
		$CP['title'] = "Member Titles/Ranks";
		$CP['file_include'] = "rank_title.php";
		break;
	case mesctrl:
		$CP['title'] = "Message Control";
		$CP['file_include'] = "mescontrol.php";
		break;
	case forum_manage:
		$CP['title'] = "Forum Management";
		$CP['file_include'] = "forum_manage.php";
		break;
	case account_manage:
		$CP['title'] = "Account Management";
		$CP['file_include'] = "account_manage.php";
		break;
	case activate:
		$CP['title'] = "Activate ID";
		$CP['file_include'] = "active_id.php";
		break;
	case idx:
	default:
		if($CONFIG_show_guild_standing)
			session_start();
		$CP['title'] = "Index";
		$CP['file_include'] = "main.php";
	}
}
?>
