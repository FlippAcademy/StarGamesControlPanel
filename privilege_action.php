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
if(checkprivilege_action($CP[login_id],g_read_privilege)) {
if($GET_code==00 && ($POST_g_id = (int)$POST_g_id) && ($POST_account = (int)$POST_account)){
	if(checkprivilege_action($CP[login_id],g_add_privilege)){
		$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.privilege WHERE account_id = \"".mysql_res($POST_account)."\"";
		$sql->result = $sql->execute_query($query,'privilege_action.php');$sql->total_query++;
		if ($sql->result()) {
			$display ="$lang[Account_isprivilege]";
		} else {
			$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.groups WHERE g_id=\"".mysql_res($POST_g_id)."\"";
			$sql->result = $sql->execute_query($query,'privilege_action.php');$sql->total_query++;
			if($sql->result()) {
				$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.privilege (account_id,privilege) values (\"".mysql_res($POST_account)."\",\"".mysql_res($POST_g_id)."\")",'privilege_action.php');$sql->total_query++;
				$display ="$lang[Success_addprivilege]";
			} else {
				$display="$lang[No_aclv]";
			}
		}
	} else {
		$display="$lang[No_privilege]";
	}
	redir("index.php?act=privilege",$display,3);
}
else if($GET_code==01 && ($POST_g_id = (int)$POST_g_id) && ($POST_account = (int)$POST_account)){
	if(checkprivilege_action($CP[login_id],g_edit_privilege)){
		$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.groups WHERE g_id=\"".mysql_res($POST_g_id)."\"";
		$sql->result = $sql->execute_query($query,'privilege_action.php');$sql->total_query++;
		$count1 = $sql->result();

		$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.privilege WHERE account_id=\"".mysql_res($POST_account)."\"";
		$sql->result = $sql->execute_query($query,'privilege_action.php');$sql->total_query++;
		$count2 = $sql->result();
		if ($count1 && $count2) {
			$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.privilege SET privilege=\"".mysql_res($POST_g_id)."\" WHERE account_id=\"".mysql_res($POST_account)."\"; ",'privilege_action.php');$sql->total_query++;
			$display="$lang[Success_editprivilege]";
		} else {
			$display="$lang[No_aclv]";
		}
	} else {
		$display="$lang[No_privilege]";
	}
	redir("index.php?act=privilege",$display,3);
}
else if($GET_code==02 && ($POST_g_id = (int)$POST_g_id)){
	if(checkprivilege_action($CP[login_id],g_edit_privilege)){
	$query = "SELECT * FROM $CONFIG_sql_cpdbname.groups WHERE g_id=\"".mysql_res($POST_g_id)."\"";
	$sql->result = $sql->execute_query($query,'privilege_action.php');$sql->total_query++;
	if ($sql->count_rows()) {
		$POST_g_1 = checkstring($POST_g_1,1);
		$POST_g_2 = truestr($POST_g_2);
		$POST_g_3 = truestr($POST_g_3);
		for ($i=2;$i<=MAX_GROUP_PRIVILEGE;$i++) {
			$g_name = "POST_g_".$i."";
			$$g_name = mysql_res($$g_name);
		}
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.groups SET
		g_title		=	\"$POST_g_1\",
		g_img		=	\"$POST_g_2\",
		g_color		=	\"$POST_g_3\",
		g_view_board	=	\"$POST_g_4\",
		g_post_new_topics	=	\"$POST_g_5\",
		g_post_polls	=	\"$POST_g_6\",
		g_vote_polls	=	\"$POST_g_7\",
		g_edit_topics	=	\"$POST_g_8\",
		g_edit_posts	=	\"$POST_g_9\",
		g_delete_topics	=	\"$POST_g_10\",
		g_delete_posts	=	\"$POST_g_11\",
		g_move_topics	=	\"$POST_g_12\",
		g_file_upload	=	\"$POST_g_13\",
		g_avatar_upload	=	\"$POST_g_14\",
		g_pinned_topics	=	\"$POST_g_15\",
		g_closed_topics	=	\"$POST_g_16\",
		g_read_news	=	\"$POST_g_17\",
		g_read_privilege	=	\"$POST_g_18\",
		g_add_news	=	\"$POST_g_19\",
		g_add_privilege	=	\"$POST_g_20\",
		g_edit_news	=	\"$POST_g_21\",
		g_edit_privilege	=	\"$POST_g_22\",
		g_delete_news	=	\"$POST_g_23\",
		g_delete_privilege	=	\"$POST_g_24\",
		g_delete_id	=	\"$POST_g_25\",
		g_view_lastestcp	=	\"$POST_g_26\",
		g_view_adminmenu	=	\"$POST_g_27\",
		g_view_topic_option	=	\"$POST_g_28\",
		g_upload_nonlimit	=	\"$POST_g_29\",
		g_post_closed_topics=	\"$POST_g_30\",
		g_non_showip	=	\"$POST_g_31\",
		g_no_delay_posts	=	\"$POST_g_32\",
		g_view_userip	=	\"$POST_g_33\",
		g_searching_id	=	\"$POST_g_34\",
		g_edit_rank_title	=	\"$POST_g_35\",
		g_edit_mes_control	=	\"$POST_g_36\",
		g_forum_manage	=	\"$POST_g_37\",
		g_account_manage	=	\"$POST_g_38\"
		WHERE g_id = \"".mysql_res($POST_g_id)."\"; ",'privilege_action.php');$sql->total_query++;
		$display="$lang[Success_editprivilege]";
		} else {
			$display="$lang[No_aclv]";
		}
		redir("index.php?act=privilege&code=03",$display,3);
	} else {
		$display="$lang[No_privilege]";
		redir("index.php?act=privilege",$display,3);
	}
}
else if($GET_code==03 && ($GET_g_id = (int)$GET_g_id)){
	if(checkprivilege_action($CP[login_id],g_delete_privilege)){
		if($GET_g_id!=1&&$GET_g_id!=2){
			$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.groups WHERE g_id=\"".mysql_res($GET_g_id)."\"";
			$sql->result = $sql->execute_query($query,'privilege_action.php');$sql->total_query++;
			if ($sql->result()) {
				$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.groups WHERE g_id=\"".mysql_res($GET_g_id)."\"",'privilege_action.php');$sql->total_query++;
				$display="$lang[Success_deleteprivilege]";
			} else {
				$display="$lang[No_aclv]";
			}
		} else {
			$display="CP can not delete this group";
		}
	redir("index.php?act=privilege&code=03",$display,3);
	} else {
		$display="$lang[No_privilege]";
		redir("index.php?act=privilege",$display,3);
	}
}
else if($GET_code==04 && $POST_g_1){
	if(checkprivilege_action($CP[login_id],g_add_privilege)){
	$POST_g_1 = checkstring($POST_g_1,1);
	$POST_g_2 = truestr($POST_g_2);
	$POST_g_3 = truestr($POST_g_3);
	for ($i=2;$i<=MAX_GROUP_PRIVILEGE;$i++) {
		$g_name = "POST_g_".$i."";
		$$g_name = mysql_res($$g_name);
	}
	$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.groups VALUES ('',
	\"$POST_g_1\",
	\"$POST_g_2\",
	\"$POST_g_3\",
	\"$POST_g_4\",
	\"$POST_g_5\",
	\"$POST_g_6\",
	\"$POST_g_7\",
	\"$POST_g_8\",
	\"$POST_g_9\",
	\"$POST_g_10\",
	\"$POST_g_11\",
	\"$POST_g_12\",
	\"$POST_g_13\",
	\"$POST_g_14\",
	\"$POST_g_15\",
	\"$POST_g_16\",
	\"$POST_g_17\",
	\"$POST_g_18\",
	\"$POST_g_19\",
	\"$POST_g_20\",
	\"$POST_g_21\",
	\"$POST_g_22\",
	\"$POST_g_23\",
	\"$POST_g_24\",
	\"$POST_g_25\",
	\"$POST_g_26\",
	\"$POST_g_27\",
	\"$POST_g_28\",
	\"$POST_g_29\",
	\"$POST_g_30\",
	\"$POST_g_31\",
	\"$POST_g_32\",
	\"$POST_g_33\",
	\"$POST_g_34\",
	\"$POST_g_35\",
	\"$POST_g_36\",
	\"$POST_g_37\",
	\"$POST_g_38\")",
	'privilege_action.php');$sql->total_query++;
	$display="$lang[Success_addprivilege]";
	redir("index.php?act=privilege&code=03",$display,3);
	} else {
		$display="$lang[No_privilege]";
		redir("index.php?act=privilege",$display,3);
	}
}
else {
	redir("index.php?act=privilege","$lang[Error]",3);
}
}
else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
?>
