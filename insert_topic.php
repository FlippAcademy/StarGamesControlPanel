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
if(!$SERVER['system_safe']) exit();
if (empty($STORED_loginname) && empty($STORED_loginpass) && !$CONFIG_guest_can_post) {
	redir("index.php?act=login","$lang[Must_login]",3);
} if ($POST_t_mes && strstr($POST_t_mes,"d-reborn2")) {
	redir("index.php?act=forum","$lang[Topic_insert]",3);
} else {
$POST_f = (int)$POST_f;
$POST_t = (int)$POST_t;
$POST_p = (int)$POST_p;
$CP['g_id'] = checkprivilege($CP[login_id]);
if ($GET_code==00 && check_category($POST_f) && checkprivilege_action($CP[login_id],g_post_new_topics) && length($POST_t_title,3) && length($POST_t_mes,3)) {
if(check_forum_perm($POST_f,$CP['g_id'],'start_perm')) {
	$query = "SELECT user_flood_protection FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id = \"".$CP['login_id']."\"";
	$sql->result = $sql->execute_query($query,'insert_topic.php');
	$row = $sql->fetch_row();
	$flood_protection_timer = $row[user_flood_protection];
	$disable_protection_timer = $CP[time];
	if ($disable_protection_timer <= $flood_protection_timer && !checkprivilege_action($CP[login_id],g_no_delay_posts)) {
		redir_back("$lang[Topic_delay]");
	} else if (!length($POST_t_mes,3,$CONFIG_max_post_length)) {
		redir_back("Your messages are more than $CONFIG_max_post_length characters");
	} else {
		if(checkprivilege_action($CP[login_id],g_view_topic_option)) {
			if(!$POST_t_topic_type) {
				$pinned_mode='0';
				$closed_mode='0';
			} else if($POST_t_topic_type==1 && checkprivilege_action($CP[login_id],g_pinned_topics)) {
				$pinned_mode='1';
				$closed_mode='0';
			} else if($POST_t_topic_type==2 && checkprivilege_action($CP[login_id],g_closed_topics)) {
				$pinned_mode='0';
				$closed_mode='1';
			} else if($POST_t_topic_type==3 && checkprivilege_action($CP[login_id],g_pinned_topics) && checkprivilege_action($CP[login_id],g_closed_topics)) {
				$pinned_mode='1';
				$closed_mode='1';
			}
			else {
				$pinned_mode='0';
				$closed_mode='0';
			}
		} else {
			$pinned_mode='0';
			$closed_mode='0';
		}
		$files_upload = upload_files($_FILES[attach]);
		$POST_t_title = checkstring($POST_t_title,1);
		$POST_t_desc = checkstring($POST_t_desc,1);
		$POST_t_mes = checkstring($POST_t_mes,1);
		$POST_t_emo = (int)$POST_t_emo;
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.board_topic (forum_id,pinned_mode,closed_mode,topic_name,topic_description,topic_starter,topic_lastreply_name,topic_start_date,topic_last_action_date) VALUES (\"".mysql_res($POST_f)."\",\"".mysql_res($pinned_mode)."\",\"".mysql_res($closed_mode)."\",\"".$POST_t_title."\",\"".$POST_t_desc."\",\"".$CP['login_id']."\",\"".$CP['login_id']."\",\"".$CP['time']."\",\"".$CP['time']."\")",'insert_topic.php');
		$query = "SELECT topic_id FROM $CONFIG_sql_cpdbname.board_topic ORDER by topic_id DESC LIMIT 0,1";
		$sql->result = $sql->execute_query($query,'insert_topic.php');
		$row = $sql->fetch_row();
		$newtopicid = $row["topic_id"];
		$flood_timer = $CP['time']+$CONFIG_delay_post;
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_profile SET user_ranking=user_ranking+1,user_flood_protection=\"".mysql_res($flood_timer)."\" WHERE user_id = \"".$CP['login_id']."\"",'insert_topic.php');
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.board_reply (topic_id,forum_id,reply_user_id,reply_emo,reply_message,reply_ip,reply_date,reply_upload) VALUES (\"".mysql_res($newtopicid)."\",\"".mysql_res($POST_f)."\",\"".$CP['login_id']."\",\"".mysql_res($POST_t_emo)."\",\"".$POST_t_mes."\",\"".$CP['ip_address']."\",\"".$CP[time]."\",\"".mysql_res($files_upload['name'])."\")",'insert_topic.php');
		if($POST_newpoll=='1' && $POST_t_p_title && checkprivilege_action($CP[login_id],g_post_polls)){
			$totalchoice=0;
			for($i=0;$i<10;$i++) {
				if($_POST["t_p_answer_".$i.""]) {
					$var = "POST_t_p_answer_".$i."";
					$$var = checkstring($$var,1);
					$totalchoice++;
				}
			}
			if($totalchoice>1){
				$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.poll (topic_id,starter_id,poll_question,choice1,choice2,choice3,choice4,choice5,choice6,choice7,choice8,choice9,choice10) VALUES (\"".mysql_res($newtopicid)."\",\"".$CP['login_id']."\",\"".mysql_res($POST_t_p_title)."\",\"".$POST_t_p_answer_1."\",\"".$POST_t_p_answer_2."\",\"".$POST_t_p_answer_3."\",\"".$POST_t_p_answer_4."\",\"".$POST_t_p_answer_5."\",\"".$POST_t_p_answer_6."\",\"".$POST_t_p_answer_7."\",\"".$POST_t_p_answer_8."\",\"".$POST_t_p_answer_9."\",\"".$POST_t_p_answer_10."\")",'insert_topic.php');
				$query = "SELECT poll_id FROM $CONFIG_sql_cpdbname.poll ORDER by poll_id DESC LIMIT 1";
				$sql->result = $sql->execute_query($query,'insert_topic.php');
				$prow = $sql->fetch_row();
				$newpollid = $prow["poll_id"];
				if(!$newpollid)
					$newpollid=1;
				$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.poll_vote (poll_id,topic_id) VALUES (\"".mysql_res($newpollid)."\",\"".mysql_res($newtopicid)."\")",'insert_topic.php');
			}
		}
	redir("index.php?showtopic=$newtopicid&view=getnewpost","$files_upload[error]$lang[Topic_insert]",3);
	}
} else {
	redir("index.php?act=forum","$lang[No_privilege]",3);
}
}
if ($GET_code==01 && check_category($POST_f) && checkprivilege_action($CP[login_id],g_post_new_topics) && length($POST_t_mes,3)) {
if(check_forum_perm($POST_f,$CP['g_id'],'reply_perm')) {
	$query = "SELECT user_flood_protection FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id = \"".$CP['login_id']."\"";
	$sql->result = $sql->execute_query($query,'insert_topic.php');
	$row = $sql->fetch_row();
	$flood_protection_timer = $row[user_flood_protection];
	$disable_protection_timer = $CP[time];
	if ($disable_protection_timer <= $flood_protection_timer && !checkprivilege_action($CP[login_id],g_no_delay_posts)) {
		redir_back("$lang[Topic_delay]");
	} else if (!length($POST_t_mes,3,$CONFIG_max_post_length)) {
		redir_back("Your messages are more than $CONFIG_max_post_length characters");
	} else {
		if(checkprivilege_action($CP[login_id],g_view_topic_option) && $POST_admin_reply==1) {
			if(!$POST_t_topic_type) {
				$pinned_mode='0';
				$closed_mode='0';
			} else if($POST_t_topic_type==1 && checkprivilege_action($CP[login_id],g_pinned_topics)) {
				$pinned_mode='1';
				$closed_mode='0';
			} else if($POST_t_topic_type==2 && checkprivilege_action($CP[login_id],g_closed_topics)) {
				$pinned_mode='0';
				$closed_mode='1';
			} else if($POST_t_topic_type==3 && checkprivilege_action($CP[login_id],g_pinned_topics) && checkprivilege_action($CP[login_id],g_closed_topics)) {
				$pinned_mode='1';
				$closed_mode='1';
			}
			else {
				$pinned_mode='0';
				$closed_mode='0';
			}
		}
		else {
			$query = "SELECT pinned_mode,closed_mode FROM $CONFIG_sql_cpdbname.board_topic WHERE topic_id=\"".mysql_res($POST_t)."\"";
			$sql->result = $sql->execute_query($query,'insert_topic.php');
			while ($row_topicdata = $sql->fetch_row()) {
				$pinned_mode=$row_topicdata[pinned_mode];
				$closed_mode=$row_topicdata[closed_mode];
			}
		}
		$files_upload = upload_files($_FILES['attach']);
		$flood_timer = $CP['time']+$CONFIG_delay_post;
		$POST_t_mes = checkstring($POST_t_mes,1);
		$POST_t_emo = (int)$POST_t_emo;
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_profile SET user_ranking=user_ranking+1,user_flood_protection=\"".mysql_res($flood_timer)."\" WHERE user_id = \"".$CP['login_id']."\"",'insert_topic.php');
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.board_topic SET pinned_mode=\"".mysql_res($pinned_mode)."\",closed_mode=\"".mysql_res($closed_mode)."\",topic_lastreply_name=\"".$CP['login_id']."\",topic_replying=topic_replying+1,topic_last_action_date=\"".$CP['time']."\" WHERE topic_id=\"".mysql_res($POST_t)."\"",'insert_topic.php');
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.board_reply (topic_id,forum_id,reply_user_id,reply_emo,reply_message,reply_ip,reply_date,reply_upload) VALUES (\"".mysql_res($POST_t)."\",\"".mysql_res($POST_f)."\",\"".$CP['login_id']."\",\"".mysql_res($POST_t_emo)."\",\"".$POST_t_mes."\",\"".$CP['ip_address']."\",\"".$CP['time']."\",\"".mysql_res($files_upload['name'])."\")",'insert_topic.php');
	}
	redir("index.php?showtopic=$POST_t&view=getnewpost","$files_upload[error]$lang[Topic_insert]",3);
} else {
	redir("index.php?act=forum","$lang[No_privilege]",3);
}
}
if ($GET_code==02 && check_category($POST_f) && length($POST_t_mes,3)) {
if(check_forum_perm($POST_f,$CP['g_id'],'reply_perm')) {
	$query = "SELECT topic_name FROM $CONFIG_sql_cpdbname.board_topic WHERE topic_id =\"".mysql_res($POST_t)."\"";
	$sql->result = $sql->execute_query($query,'insert_topic.php');
	$row = $sql->fetch_row();
	$topic_name = $row["topic_name"];
	$count1 = $sql->count_rows();

	$query = "SELECT reply_user_id FROM $CONFIG_sql_cpdbname.board_reply WHERE reply_id =\"".mysql_res($POST_p)."\"";
	$sql->result = $sql->execute_query($query,'insert_topic.php');
	$row_check = $sql->fetch_row();
	$checkuserid = $row_check["reply_user_id"];
	if (!length($POST_t_mes,3,$CONFIG_max_post_length)) {
		redir_back("Your messages are more than $CONFIG_max_post_length characters");
	} else if ($count1 && ($checkuserid==$CP[login_id] || checkprivilege_action($CP[login_id],g_edit_posts)) && !empty($CP[login_id])) {
		$query = "SELECT reply_id FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id = \"".mysql_res($POST_t)."\" ORDER by reply_id LIMIT 1";
		$sql->result = $sql->execute_query($query,'action_post.php');$sql->total_query++;
		$row_result2 = $sql->fetch_row();
		$reply_id_start = $row_result2[0];
		if($POST_t_title && $reply_id_start == $POST_p && $POST_edit_topic) {
			$POST_t_title = checkstring($POST_t_title,1);
			$POST_t_desc = checkstring($POST_t_desc,1);
			$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.board_topic SET topic_name=\"".$POST_t_title."\",topic_description=\"".$POST_t_desc."\" WHERE topic_id=\"".mysql_res($POST_t)."\" ",'insert_topic.php');
		}
		$reply_edit_name = $POST_add_edit ? $CP['login_name'] : "" ;
		$POST_t_mes = checkstring($POST_t_mes,1);
		$POST_t_emo = (int)$POST_t_emo;
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.board_reply SET reply_emo=\"".mysql_res($POST_t_emo)."\",reply_message=\"".$POST_t_mes."\",reply_ip=\"".$CP['ip_address']."\",reply_edit_name=\"".mysql_res($reply_edit_name)."\",reply_edit_date=\"".$CP['time']."\" WHERE topic_id=\"".mysql_res($POST_t)."\" AND reply_id=\"".mysql_res($POST_p)."\" ",'insert_topic.php');
		header_location("index.php?showtopic=$POST_t&#entry$POST_p");
	}
} else {
	redir("index.php?act=forum","$lang[No_privilege]",3);
}
}
if ($GET_code==03 && $POST_t && $POST_clickvote && checkprivilege_action($CP[login_id],g_vote_polls)) {
	$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.voters WHERE member_id = \"".$CP['login_id']."\" AND topic_id = \"".mysql_res($POST_t)."\"";
	$sql->result = $sql->execute_query($query,'insert_topic.php');$sql->total_query++;
	if($sql->result()) {
		$display=$lang[No_vote];
	} else {
		$voteid = "vote".(int)$POST_poll_vote."";
		if($nullvote!='View Results (Null Vote)'){
			$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.poll SET last_vote_date=\"".$CP['time']."\" WHERE topic_id=\"".mysql_res($POST_t)."\"",'insert_topic.php');$sql->total_query++;
			$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.poll_vote SET $voteid=".mysql_res($voteid)."+1 WHERE topic_id=\"".mysql_res($POST_t)."\"",'insert_topic.php');$sql->total_query++;
		}
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.voters (ip_address,vote_date,topic_id,member_id) VALUES (\"".$CP['ip_address']."\",\"".$CP['time']."\",\"".mysql_res($POST_t)."\",\"".$CP['login_id']."\")",'insert_topic.php');$sql->total_query++;
		$display=$lang[Topic_vote];
	}
	redir("index.php?showtopic=$POST_t","$display",3);
}
}
?>