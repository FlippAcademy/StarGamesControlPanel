<!--
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
-->
<?php
require_once "memory.php";
$sql = new MySQL;
$sql->Connect($CONFIG_sql_host,$CONFIG_sql_username,$CONFIG_sql_password);
$CP['start_time'] = getmicrotime();
$query = "SELECT memory_value1 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"sgcp_install\" AND memory_value1=\"1\"";
$sql->result = mysql_query($query);
if($sql->count_rows()) {
	$query = "SELECT memory_value2,memory_value3 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"ip_blacklist\" AND memory_value1=\"".mysql_res($CP['ip_address'])."\"";
	$sql->result = $sql->execute_query($query,'index.php');
	$check_iplist = $sql->count_rows();
	$iplist = $sql->fetch_row();
	if($_COOKIE['IP_Blacklist'] || $check_iplist) {
		$query = "SELECT memory_value3 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"ip_blacklist\" AND memory_value3=\"".mysql_res($_COOKIE['IP_Blacklist'])."\"";
		$sql->result = $sql->execute_query($query,'index.php');
		if($_COOKIE['IP_Blacklist'] && !$sql->count_rows())
			do_blacklist(4);
		else {
			$ip_state = $iplist[memory_value2];
			if($_COOKIE[IP_Blacklist] && $_COOKIE[IP_Blacklist] == $iplist[memory_value3] && $ip_state != "block")
				do_blacklist(2,$iplist[memory_value3]);
			else if($check_iplist && empty($_COOKIE[IP_Blacklist])) {
				switch($ip_state) {
					case block:
						do_blacklist(1,$iplist[memory_value3]);
						break;
				}
			} else do_blacklist(3);
		}
	}
	$sql->check_database($CONFIG_sql_dbname);
	if($GET_init_load) {
		if($CONFIG_save_type != '1') {
			session_start();
		}
		switch($GET_init_load) {
			case "cpupdate":
				if(isset($_SESSION[CP_UPDATE])) {
					header("location:$CP[cp_update_link]");
					session_unregister(CP_UPDATE);
				}
		}
	}
	else if($GET_showtopic && $GET_view) {
		$GET_showtopic = (int)$GET_showtopic;
		if($GET_view=='getnewpost') {
			$query = "SELECT reply_id FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id =\"".mysql_res($GET_showtopic)."\" ORDER by reply_id DESC";
			$sql->result = $sql->execute_query($query,'index.php');
			$count_rows_topic = $sql->count_rows();
			$row_get_topic = $sql->fetch_row();
			$reply_id=$row_get_topic[reply_id];
		}
		else if($GET_view=='findpost' && $GET_p) {
			$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id =\"".mysql_res($GET_showtopic)."\" && reply_id<=\"".mysql_res($GET_p)."\" ORDER by reply_id DESC";
			$sql->result = $sql->execute_query($query,'index.php');
			$count_rows_topic = $sql->result();
			$reply_id=$GET_p;
		} else
			header("location:index.php?act=idx");

		if ($count_rows_topic <= $CONFIG_per_page) {
			$pages = '1';
		} else if (($count_rows_topic % $CONFIG_per_page)=='0') {
			$pages=($count_rows_topic / $CONFIG_per_page);
		} else {
			$pages=($count_rows_topic/$CONFIG_per_page)+1;
			$pages=(int)$pages;
		}
		$st=$CONFIG_per_page*($pages-1);
		header("location:index.php?showtopic=$GET_showtopic&st=$st&#entry$reply_id");
	}
	else
		include_once "action.php";
} else {
	include_once "install.php";
}
?>