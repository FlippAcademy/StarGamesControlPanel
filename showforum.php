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
if(checkprivilege_action($CP[login_id],g_view_board)){
$GET_showforum = (int)$GET_showforum;
if($category_id=check_category($GET_showforum)) {
$CP['g_id'] = checkprivilege($CP[login_id]);
if(check_forum_perm($GET_showforum,$CP['g_id'],'read_perm')) {
if(!isset($GET_st)) $GET_st = 0;
$GET_st = (int)$GET_st;
$page=get_page($GET_st,$CONFIG_t_per_page);
$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.board_topic WHERE forum_id = \"".mysql_res($GET_showforum)."\" AND pinned_mode =\"0\"";
$sql->result = $sql->execute_query($query,'showforum.php');$sql->total_query++;
$total = $sql->result();

$query = "SELECT us.display_name,t.* FROM $CONFIG_sql_cpdbname.board_topic t
		LEFT JOIN $CONFIG_sql_cpdbname.user_profile us ON (us.user_id=t.topic_starter)
	WHERE t.forum_id = \"".mysql_res($GET_showforum)."\" AND t.pinned_mode =\"0\" ORDER by t.topic_last_action_date DESC LIMIT ".mysql_res($GET_st).",".mysql_res($CONFIG_t_per_page)."";
$sql->result = $sql->execute_query($query,'showforum.php');$sql->total_query++;

$query = "SELECT us.display_name,t.* FROM $CONFIG_sql_cpdbname.board_topic t
		LEFT JOIN $CONFIG_sql_cpdbname.user_profile us ON (us.user_id=t.topic_starter)
	WHERE t.forum_id = \"".mysql_res($GET_showforum)."\" AND t.pinned_mode =\"1\" ORDER by t.topic_last_action_date DESC";
$sql->result2 = $sql->execute_query($query,'showforum.php');$sql->total_query++;

$category_name = get_categoryname($category_id);
echo "
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<TBODY>
		<TR>
			<TD colspan=\"2\">
				<B><a href=\"index.php?act=forum\">".$CONFIG_forum_name."</a> -> <a href=\"#\" onclick=\"return false;\">$category_name</a> -> <a href=\"index.php?showforum=$GET_showforum\">$forum_name</a></B><BR><BR>
			</TD>
		</TR>
		<TR>
			<TD>
";
				get_selectpage($total,$CONFIG_t_per_page,$page,"index.php?showforum=$GET_showforum");
echo "			</TD>
			<TD align=\"right\">
";
				get_menuwb($GET_showforum,1);
echo "			</TD>
		</TR>
	</TBODY>
</TABLE>
<BR>
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"topic_title\">
	<TBODY>
		<TR>
			<TD class=\"title_bar\" height=\"29\">
				<a class=\"m_title\">&nbsp;&nbsp;$forum_name</a>
			</TD>
		</TR>
		<TR>
			<TD>
				<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
					<TBODY>
						<TR height=\"27\" class=\"title_bar2\">
							<TD width=\"5%\"></TD>
							<TD width=\"5%\"></TD>
							<TD width=\"40%\"><div class=\"title_face\">$lang[Topic_Title]</div></TD>
							<TD width=\"15%\"><div class=\"title_face\" align=\"center\">$lang[Topic_Starter]</div></TD>
							<TD width=\"5%\"><div class=\"title_face\" align=\"center\">$lang[Reply]</div></TD>
							<TD width=\"5%\"><div class=\"title_face\" align=\"center\">$lang[View]</div></TD>
							<TD width=\"25%\"><div class=\"title_face\">$lang[Last_Action]</div></TD>
						</TR>
";
if($page=='1') {
echo "						<TR class=\"topic_title3\">
							<TD></TD>
							<TD></TD>
							<TD colspan=\"5\">
								<font class=\"title_face2\"><B>$lang[IM_Topic]</B></font>
							</TD>
						</TR>
";
	if($sql->count_rows($sql->result2)) {
	while ($row = $sql->fetch_row($sql->result2)) {
		$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.poll WHERE topic_id =\"".mysql_res($row['topic_id'])."\"";
		$sql->result3 = $sql->execute_query($query,'showforum.php');
		if ($sql->result($sql->result3))
			$IS_POLL=1;
		else
			$IS_POLL=0;
		$topic_starter = get_displayname($row[display_name],$row[topic_starter]);
		$topic_starter = $topic_starter==$lang[Guest]?$lang[Guest]:"<a href=\"index.php?showuser=".md5($row[topic_starter])."\">$topic_starter</a>";
		$topic_lastreply_name = get_username($row[topic_lastreply_name]);
		if ($row[closed_mode]=='0') {
			if ($IS_POLL) {
				if($CP[time] - $row[topic_last_action_date] <= 3600)
					$status_topic = "<img src=\"theme/$STORED[THEME]/images/f_poll.gif\">";
				else
					$status_topic = "<img src=\"theme/$STORED[THEME]/images/f_poll_no.gif\">";
			} else if ($row[topic_replying] >= '20') {
				if ($CP[time] - $row[topic_last_action_date] <= 3600)
					$status_topic = "<img src=\"theme/$STORED[THEME]/images/f_hot.gif\">";
				else
					$status_topic = "<img src=\"theme/$STORED[THEME]/images/f_hot_no.gif\">";
			} else {
				if ($CP[time] - $row[topic_last_action_date] <= 3600)
					$status_topic = "<img src=\"theme/$STORED[THEME]/images/f_norm.gif\">";
				else
					$status_topic = "<img src=\"theme/$STORED[THEME]/images/f_norm_no.gif\">";
			}
		} else
			$status_topic = "<img src=\"theme/$STORED[THEME]/images/f_closed.gif\">";
		if(!empty($row[topic_description]))
			$topic_description="<BR><a class=\"textinput\">$row[topic_description]</a>";
		else
			$topic_description="";
		$topic_start_date = get_date("M j y, H:i:s A",$row[topic_start_date]);
		$last_reply_date = get_date("M j y, H:i:s A",$row[topic_last_action_date]);
echo "						<TR height=\"27\" class=\"topic_title4\">
							<TD align=\"center\">$status_topic</TD>
							<TD align=\"center\"><img src=\"theme/$STORED[THEME]/images/f_pinned.gif\"></TD>
							<TD>
								<B>Pinned: <a href=\"index.php?showtopic=$row[topic_id]\" title=\"This topic was started: $topic_start_date\">$row[topic_name]</a></B>
";
		$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id =\"".mysql_res($row['topic_id'])."\"";
		$sql->result3 = $sql->execute_query($query,'showforum.php');
		$t_total = $sql->result($sql->result3);
		$t_page=ceil($t_total/$CONFIG_per_page);
		if($t_page>1) {
			get_sselectpage($t_page,$CONFIG_per_page,$row[topic_id]);
		}
echo "								$topic_description
							</TD>
							<TD align=\"center\">".$topic_starter."</TD>
							<TD align=\"center\">$row[topic_replying]</TD>
							<TD align=\"center\">$row[topic_reading]</TD>
							<TD>
								$last_reply_date<BR>
								<a href=\"index.php?showtopic=$row[topic_id]&view=getnewpost\">$lang[Last_Post]:</a>
";
		if(empty($topic_lastreply_name))
		echo "						$lang[Guest]
		";
		else {
			echo "					<a href=\"index.php?showuser=".md5($row[topic_lastreply_name])."\">$topic_lastreply_name</a>
			";
		}
echo "							</TD>
						</TR>
";
	}
	} else {
echo "						<TR height=\"27\" class=\"topic_title4\">
							<TD colspan=\"7\" align=\"center\">
								<B>Pinned topic(s) not found in this forum.</B>
							</TD>
						</TR>
";
	}
}
echo "						<TR class=\"topic_title3\">
							<TD></TD>
							<TD></TD>
							<TD colspan=\"5\">
								<font class=\"title_face2\"><B>$lang[FR_Topic]</B></font>
							</TD>
						</TR>
";
if ($sql->count_rows()) {
while ($row = $sql->fetch_row()) {
	$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.poll WHERE topic_id =\"".mysql_res($row['topic_id'])."\"";
	$sql->result2 = $sql->execute_query($query,'showforum.php');
	if ($sql->result($sql->result2))
		$IS_POLL=1;
	else
		$IS_POLL=0;
	$topic_starter = get_displayname($row[display_name],$row[topic_starter]);
	$topic_lastreply_name = get_username($row[topic_lastreply_name]);
	if ($row[closed_mode]=='0') {
		if ($IS_POLL) {
			if($CP[time] - $row[topic_last_action_date] <= 3600)
				$status_topic = "<img src=\"theme/$STORED[THEME]/images/f_poll.gif\">";
			else
				$status_topic = "<img src=\"theme/$STORED[THEME]/images/f_poll_no.gif\">";
		} else if ($row[topic_replying] >= '20') {
			if ($CP[time] - $row[topic_last_action_date] <= 3600)
				$status_topic = "<img src=\"theme/$STORED[THEME]/images/f_hot.gif\">";
			else
				$status_topic = "<img src=\"theme/$STORED[THEME]/images/f_hot_no.gif\">";
		} else {
			if ($CP[time] - $row[topic_last_action_date] <= 3600)
				$status_topic = "<img src=\"theme/$STORED[THEME]/images/f_norm.gif\">";
			else
				$status_topic = "<img src=\"theme/$STORED[THEME]/images/f_norm_no.gif\">";
		}
	} else
		$status_topic = "<img src=\"theme/$STORED[THEME]/images/f_closed.gif\">";
	$query = "SELECT reply_emo FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id =\"".mysql_res($row['topic_id'])."\" LIMIT 1";
	$sql->result2 = $sql->execute_query($query,'showforum.php');
	$row_reply = $sql->fetch_row($sql->result2);
	$reply_emo = $row_reply[reply_emo] ? "<img src=\"theme/$STORED[THEME]/images/icon/icon".$row_reply[reply_emo].".gif\" border=\"0\">" : "&nbsp;";
	if(!empty($row[topic_description]))
		$topic_description="<BR><a class=\"textinput\">$row[topic_description]</a>";
	else
		$topic_description="";
	$topictype=$IS_POLL?"Poll: ":"";
	$topic_start_date = get_date("M j y, H:i:s A",$row[topic_start_date]);
	$last_reply_date = get_date("M j y, H:i:s A",$row[topic_last_action_date]);
echo "						<TR height=\"27\" class=\"topic_title4\">
							<TD align=\"center\">$status_topic</TD>
							<TD align=\"center\">$reply_emo</TD>
							<TD>
								$topictype<a href=\"index.php?showtopic=$row[topic_id]\" title=\"This topic was started: $topic_start_date\">$row[topic_name]</a>
";
	$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id =\"".mysql_res($row[topic_id])."\"";
	$sql->result2 = $sql->execute_query($query,'showforum.php');
	$t_total = $sql->result($sql->result2);
	$t_page = ceil($t_total/$CONFIG_per_page);
	if($t_page>1){
	get_sselectpage($t_page,$CONFIG_per_page,$row[topic_id]);
	}
echo "								$topic_description
							</TD>
							<TD align=\"center\">
";
	if(empty($topic_starter))
		echo "						$lang[Guest]
		";
	else {
		echo "						<a href=\"index.php?showuser=".md5($row[topic_starter])."\">$topic_starter</a>
		";
	}
echo "							</TD>
							<TD align=\"center\">$row[topic_replying]</TD>
							<TD align=\"center\">$row[topic_reading]</TD>
							<TD>
								$last_reply_date<BR>
								<a href=\"index.php?showtopic=$row[topic_id]&view=getnewpost\">$lang[Last_Post]:</a>
";
	if(empty($topic_lastreply_name))
		echo "						$lang[Guest]
		";
	else {
		echo "						<a href=\"index.php?showuser=".md5($row[topic_lastreply_name])."\">$topic_lastreply_name</a>
		";
	}
echo "							</TD>
						</TR>
";
}
} else {
echo "						<TR height=\"27\" class=\"topic_title4\">
							<TD colspan=\"7\" align=\"center\">
								<B>Topic(s) not found in this forum.</B>
							</TD>
						</TR>
";
}
echo "					</TBODY>
				</TABLE>
			</TD>
		</TR>
	</TBODY>
</TABLE>
<BR>
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<TBODY>
		<TR>
			<TD>
";
				get_selectpage($total,$CONFIG_t_per_page,$page,"index.php?showforum=$GET_showforum");
echo "			</TD>
			<TD align=\"right\">
";
				get_menuwb($GET_showforum,1);
echo "			</TD>
		</TR>
	</TBODY>
</TABLE>

<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<TBODY>
		<TR>
			<TD class=\"textinput\" class=\"title_face4\" width=\"30%\"><img src=\"theme/$STORED[THEME]/images/f_norm.gif\">&nbsp;$lang[Open_Topic1]</TD>
			<TD class=\"textinput\" class=\"title_face4\" width=\"70%\"><img src=\"theme/$STORED[THEME]/images/f_poll.gif\">&nbsp;$lang[Open_Poll1]</TD>
		</TR>
		<TR>
			<TD class=\"textinput\" class=\"title_face4\"><img src=\"theme/$STORED[THEME]/images/f_norm_no.gif\">&nbsp;$lang[Open_Topic2]</TD>
			<TD class=\"textinput\" class=\"title_face4\"><img src=\"theme/$STORED[THEME]/images/f_poll_no.gif\">&nbsp;$lang[Open_Poll2]</TD>
		</TR>
		<TR>
			<TD class=\"textinput\" class=\"title_face4\"><img src=\"theme/$STORED[THEME]/images/f_hot.gif\">&nbsp;$lang[Hot_Topic1]</TD>
			<TD class=\"textinput\" class=\"title_face4\"><img src=\"theme/$STORED[THEME]/images/f_closed.gif\">&nbsp;$lang[Closed_Topic]</TD>
		</TR>
		<TR>
			<TD class=\"textinput\" class=\"title_face4\"><img src=\"theme/$STORED[THEME]/images/f_hot_no.gif\">&nbsp;$lang[Hot_Topic2]</TD>
		</TR>
	</TBODY>
</TABLE>
";
} else {
	redir("index.php?act=forum","$lang[No_privilege]",3);
}
} else {
	redir("index.php?act=forum","$lang[No_Forum]",3);
}
} else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
?>