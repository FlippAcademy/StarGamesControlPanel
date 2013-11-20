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
$query = "SELECT memory_value1,memory_value3 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"forum_category\" ORDER by memory_value2 ASC";
$sql->result = $sql->execute_query($query,'forum.php');$sql->total_query++;
echo "
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<TBODY>
		<TR>
			<TD>
				<B><a href=\"index.php?act=forum\">".$CONFIG_forum_name."</a></B><BR><BR>
			</TD>
		</TR>
	</TBODY>
</TABLE>
<BR>
";
if($sql->count_rows()) {
	$CP['g_id'] = checkprivilege($CP[login_id]);
	while ($row = $sql->fetch_row()) {
	$query = "SELECT forum_id,forum_title,forum_description FROM $CONFIG_sql_cpdbname.forum WHERE category_id=\"$row[memory_value1]\" ORDER by forum_id ASC";
	$sql->result2 = $sql->execute_query($query,'forum.php');
echo "<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"topic_title\">
	<TBODY>
		<TR>
			<TD class=\"title_bar\" height=\"29\">
				<a class=\"m_title\">&nbsp;&nbsp;$row[memory_value3]</a>
			</TD>
		</TR>
		<TR>
			<TD>
				<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
					<TBODY>
						<TR height=\"27\" class=\"title_bar2\">
							<TD width=\"6%\"></TD>
							<TD width=\"49%\"><div class=\"title_face\">$lang[Forum]</div></TD>
							<TD width=\"10%\"><div class=\"title_face\" align=\"center\">$lang[Topic]</div></TD>
							<TD width=\"10%\"><div class=\"title_face\" align=\"center\">$lang[Reply]</div></TD>
							<TD width=\"25%\"><div class=\"title_face\">$lang[Last_Action]</div></TD>
						</TR>
";
	if($sql->count_rows($sql->result2)) {
		$i=0;
		while ($frow = $sql->fetch_row($sql->result2)) {
			if(check_forum_perm($frow[forum_id],$CP['g_id'],'show_perm')) {
			$i++;
			$forum_description = $frow[forum_description]?"<BR>$frow[forum_description]":"";
			//$query = "SELECT topic_id, forum_id, topic_name, topic_replying, topic_lastreply_name, topic_last_action_date FROM $CONFIG_sql_cpdbname.board_topic WHERE forum_id =\"$frow[forum_id]\" ORDER by topic_last_action_date DESC";
			$query = "SELECT us.display_name,t.topic_id, t.forum_id, t.topic_name, t.topic_replying, t.topic_lastreply_name, t.topic_last_action_date FROM $CONFIG_sql_cpdbname.board_topic t
					LEFT JOIN $CONFIG_sql_cpdbname.user_profile us ON (us.user_id=t.topic_lastreply_name)
				WHERE t.forum_id =\"$frow[forum_id]\" ORDER by t.topic_last_action_date DESC";
			$sql->result3 = $sql->execute_query($query,'forum.php');
			$total_topic = $sql->count_rows($sql->result3);
			if ($total_topic) {
				$trow = $sql->fetch_row($sql->result3);
				$forum_replying = $sql->execute_query("SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.board_reply WHERE forum_id=\"$trow[forum_id]\"",'forum.php',0);
				$forum_replying = $sql->result($forum_replying) - $total_topic;
				$forum_replying = $forum_replying<1?0:$forum_replying;
				if($CP[time] - $trow[topic_last_action_date] <= 3600) {
					$status_forum = "bf_new.gif";
					$title_forum = $lang[New_Post];
				} else {
					$status_forum = "bf_nonew.gif";
					$title_forum = $lang[No_New_Post];
				}
				$last_action_date = get_date("M j y, H:i:s A",$trow[topic_last_action_date]);
				$last_topic_name = "<a href=\"index.php?showtopic=$trow[topic_id]&view=getnewpost\">$trow[topic_name]</a>";
				//$ltrn = get_username($trow[topic_lastreply_name]);
				$ltrn = get_displayname($trow[display_name],$trow[topic_lastreply_name]);
				$last_topic_reply_name = $ltrn!=$lang[Guest]?"<a href=\"index.php?showuser=".md5($trow[topic_lastreply_name])."\">$ltrn</a>":$lang[Guest];
			} else {
				$forum_replying = 0;
				$last_action_date = "--";
				$last_topic_name = "----";
				$last_topic_reply_name = "";
				$status_forum = "bf_nonew.gif";
				$title_forum = $lang[No_New_Post];		
			}
			if(!check_forum_perm($frow[forum_id],$CP['g_id'],'read_perm'))
				$last_topic_name = "<i>$lang[Forum_Protected]</i>";
			if (!$trow[forum_replying]) $trow[forum_replying] = '0';
echo"						<TR class=\"topic_title7\">
							<TD align=\"center\"><img src=\"theme/$STORED[THEME]/images/$status_forum\" alt=\"$title_forum\"></TD>
							<TD><B><a href=\"index.php?showforum=$frow[forum_id]\">$frow[forum_title]</a></B>$forum_description</TD>
							<TD align=\"center\">$total_topic</TD>
							<TD align=\"center\">$forum_replying</TD>
							<TD>
								$last_action_date <BR>
								<B>$lang[In]</B>: $last_topic_name <BR>
								<B>$lang[By]</B>: $last_topic_reply_name<BR>
							</TD>
						</TR>
";
			}
		}
		if($i=='0') {
echo"						<TR class=\"topic_title7\">
							<TD colspan=\"5\" align=\"center\"><B>$lang[No_Forum]</B></TD>
						</TR>
";
		}
	} else {
echo"						<TR class=\"topic_title7\">
							<TD colspan=\"5\" align=\"center\"><B>$lang[No_Forum]</B></TD>
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
";
	}
echo "<BR><BR>
";
$sql->result = $sql->execute_query("SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.board_reply",'forum.php',0);
$total_post = $sql->result();

$sql->result = $sql->execute_query("SELECT COUNT(*) FROM $CONFIG_sql_dbname.login WHERE sex != \"S\"",'forum.php',0);
$total_registered = $sql->result();

$query = "SELECT us.display_name, account_id FROM $CONFIG_sql_dbname.login
		LEFT JOIN $CONFIG_sql_cpdbname.user_profile us ON (us.user_id=account_id)
	WHERE sex != \"S\" ORDER by account_id DESC LIMIT 0,1";
$sql->result = $sql->execute_query($query,'forum.php');$sql->total_query++;
$nrow = $sql->fetch_row();
$last_registered_name = get_displayname($nrow[display_name],$nrow[account_id]);

$query = "SELECT memory_value1, memory_value2 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"most_users_online\"";
$sql->result = $sql->execute_query($query,'forum.php');$sql->total_query++;
$mrow = $sql->fetch_row();

$query = "SELECT user_id, display_name, user_last_login FROM $CONFIG_sql_cpdbname.user_profile WHERE user_online =\"1\" ORDER by user_last_login DESC";
$sql->result = $sql->execute_query($query,'forum.php');$sql->total_query++;
$member_online = $sql->count_rows();


$guest_online = $user_online - $member_online;
if($guest_online<0) $guest_online='0';
$mdate = get_date("M j y, H:i A",$mrow[memory_value2]);
$i="";
while ($urow = $sql->fetch_row()) {
	//$username = get_username($urow[user_id]);
	$username = get_displayname($urow[display_name],$urow[user_id]);
	$user_last_login = get_date("H:i A",$urow[user_last_login]);
	$color = checkprivilege_action($urow[user_id],g_color);
	$members_online = "$members_online".$i."<a href=\"index.php?showuser=".md5($urow[user_id])."\" title=\"$user_last_login\"><font color=\"$color\">$username</font></a>";
	$i=", ";
}
$FR_Stat_1 = sprintf("$lang[FR_Stat_1]",$user_online);
$FR_Stat_2 = sprintf("
				$lang[FR_Stat_2]<BR>
				$lang[FR_Stat_3]<BR>
				$lang[FR_Stat_4]<BR>
				$lang[FR_Stat_5]",$total_post,$total_registered,"<a href=\"index.php?showuser=".md5($nrow[account_id])."\">$last_registered_name</a>",$mrow[memory_value1],$mdate);
opmain_body("$lang[FR_Stat]");
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<TBODY>
		<TR class=\"topic_title5\">
			<TD colspan=\"2\"><div class=\"title_face\">$FR_Stat_1</div></TD>
		</TR>
		<TR class=\"topic_title4\">
			<TD rowspan=\"2\" width=\"6%\" align=\"center\"><img src=\"theme/$STORED[THEME]/images/user.gif\" alt=\"Active Users\"></TD>
			<TD width=\"94%\"><B>$guest_online</B> $lang[Guests], <B>$member_online</B> $lang[Members]</TD>
		</TR>
		<TR class=\"topic_title4\">
			<TD>".$members_online."&nbsp;</TD>
		</TR>
		<TR class=\"topic_title5\">
			<TD colspan=\"2\"><div class=\"title_face\">$lang[FR_Stat]</div></TD>
		</TR>
		<TR class=\"topic_title4\">
			<TD align=\"center\"><img src=\"theme/$STORED[THEME]/images/stats.gif\" alt=\"Forum Stats\"></TD>
			<TD>$FR_Stat_2
			</TD>
		</TR>		
	</TBODY>
</TABLE>
";
clmain_body();
} else {
opmain_body("CP Message");
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<TBODY>
		<TR height=\"27\" class=\"title_bar2\">
			<TD><div class=\"title_face\">CP Message</div></TD>
		</TR>
		<TR class=\"topic_title7\">
			<TD align=\"center\"><B>$lang[No_Category]</B></TD>
		</TR>
	</TBODY>
</TABLE>
";
clmain_body();
}
} else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
?>
