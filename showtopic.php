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
$GET_showtopic = (int)$GET_showtopic;
if(checkprivilege_action($CP[login_id],g_view_board)){
$query = "SELECT forum_id,topic_name,topic_description,closed_mode,pinned_mode FROM $CONFIG_sql_cpdbname.board_topic WHERE topic_id =\"".mysql_res($GET_showtopic)."\"";
$sql->result2 = $sql->execute_query($query,'showtopic.php');$sql->total_query++;
$row = $sql->fetch_row($sql->result2);
$forum_id = $row[forum_id];
if($category_id=check_category($forum_id)) {
$CP['g_id'] = checkprivilege($CP[login_id]);
if(check_forum_perm($forum_id,$CP['g_id'],'read_perm')) {
if(!isset($GET_st)) $GET_st = 0;
$GET_st = (int)$GET_st;
$page=get_page($GET_st,$CONFIG_per_page);
$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id =\"".mysql_res($GET_showtopic)."\"";
$sql->result = $sql->execute_query($query,'showtopic.php');$sql->total_query++;
$total = $sql->result();

$query = "SELECT reply_id,reply_user_id,reply_emo,reply_date,reply_edit_date,reply_message,reply_ip,reply_edit_name,reply_upload
	FROM $CONFIG_sql_cpdbname.board_reply
	WHERE topic_id =\"".mysql_res($GET_showtopic)."\" ORDER by reply_id ASC LIMIT ".mysql_res($GET_st).",".mysql_res($CONFIG_per_page)."";
$sql->result = $sql->execute_query($query,'showtopic.php');$sql->total_query++;

$topic_name = $row["topic_name"];
$topic_description = $row["topic_description"];
$topic_closed = $row["closed_mode"];
$topic_pin = $row["pinned_mode"];

$category_name = get_categoryname($category_id);
$forum_name = get_forumname($forum_id);

if ($sql->count_rows($sql->result2)) {
	$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.board_topic SET topic_reading=topic_reading+1 WHERE topic_id=\"".mysql_res($GET_showtopic)."\" ",'showtopic.php',0);
	if (!empty($topic_description))
		$topic_description=", $topic_description";
echo "
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<TBODY>
		<TR>
			<TD colspan=\"2\">
				<B><a href=\"index.php?act=forum\">".$CONFIG_forum_name."</a> -> <a href=\"#\" onclick=\"return false;\">$category_name</a> -> <a href=\"index.php?showforum=$forum_id\">$forum_name</a></B><BR><BR>
			</TD>
		</TR>
		<TR>
			<TD>
";
				get_selectpage($total,$CONFIG_per_page,$page,"index.php?showtopic=$GET_showtopic");
echo "			</TD>
			<TD align=\"right\">
";
	if($topic_closed=='1' && checkprivilege_action($CP[login_id],g_post_closed_topics))
		$t_reply = "<a href=\"index.php?act=post&code=01&f=$forum_id&t=$GET_showtopic\"><img src =\"theme/$STORED[THEME]/images/webboard/t_closed.gif\" border=\"0\" alt=\"Closed Topic\"></a>";
	else if ($topic_closed=='1')
		$t_reply = "<img src =\"theme/$STORED[THEME]/images/webboard/t_closed.gif\" border=\"0\" alt=\"Closed Topic\">";
	else
		$t_reply = "<a href=\"index.php?act=post&code=01&f=$forum_id&t=$GET_showtopic\"><img src =\"theme/$STORED[THEME]/images/webboard/t_reply.gif\" border=\"0\" alt=\"Reply to this topic\"></a>";
get_menuwb($forum_id,2,$t_reply);
echo "			</TD>
		</TR>
	</TBODY>
</TABLE>
<BR>
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"topic_title\">
	<TBODY>
		<TR>
			<TD class=\"title_bar\" height=\"29\">
				<font color=\"#FFFFFF\">&nbsp;&nbsp;<img src=\"theme/$STORED[THEME]/images/nav_m.gif\">&nbsp;<B>$topic_name$topic_description</B></font>
			</TD>
		</TR>
		<TR>
			<TD>
				<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
					<TBODY>
";
	$query = "SELECT * FROM $CONFIG_sql_cpdbname.poll WHERE topic_id =\"".mysql_res($GET_showtopic)."\"";
	$sql->result2 = $sql->execute_query($query,'showtopic.php');$sql->total_query++;
	if($sql->count_rows($sql->result2)) {
		$poll_row = $sql->fetch_row($sql->result2);
echo "					<form action=\"index.php?act=insert_topic&code=03\" method=\"post\">
						<input type=\"hidden\" name=\"clickvote\" value=\"1\">
						<input type=\"hidden\" name=\"t\" value=\"$GET_showtopic\">
						<TR class=\"topic_title5\">
							<TD align=\"right\" colspan=\"3\" height=\"28\">
								<!--<div style=\"font-weight:bold;padding:4px;margin-top:1px\">[ Edit ] &nbsp; [ Delete ]</div>-->
							</TD>
						</TR>
";
		$query = "SELECT * FROM $CONFIG_sql_cpdbname.voters WHERE member_id = \"".$CP['login_id']."\" and topic_id = \"".mysql_res($GET_showtopic)."\"";
		$sql->result3 = $sql->execute_query($query,'showtopic.php');
		if($sql->count_rows($sql->result3) || !checkprivilege_action($CP[login_id],g_vote_polls)) {
			$query = "SELECT * FROM $CONFIG_sql_cpdbname.poll_vote WHERE topic_id = \"".mysql_res($GET_showtopic)."\"";
			$sql->result3 = $sql->execute_query($query,'showtopic.php');
			$pvote = $sql->fetch_row($sql->result3);
echo "						<TR class=\"topic_title8\">
							<TD align=\"center\" colspan=\"3\">
								<TABLE cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"padding:6px\">
									<TR align=\"center\">
										<TD colspan=\"3\">
											<B>$poll_row[poll_question]</B>
										</TD>
									</TR>
";
			for($v=1,$totalvote=0;$v<=10;$v++){
				$vote="vote".$v."";
				$totalvote+=$pvote[$vote];
			}
			for($c=1;$c<=10;$c++){
				$choice="choice".$c."";
				$vote="vote".$c."";
				$pwidth=$pvote[$vote]*200/$totalvote;
				$ppercent=$pvote[$vote]*100/$totalvote;
				if(!empty($poll_row[$choice])) {
echo "									<TR>
										<TD align=\"left\">
											$poll_row[$choice]
										</TD>
										<TD>
											[ $pvote[$vote] ]
										</TD>
										<TD>
											<img src=\"theme/$STORED[THEME]/images/webboard/bar_left.gif\" height=\"11\" width=\"4\"><img src=\"theme/$STORED[THEME]/images/webboard/bar.gif\" height=\"11\" width=\"$pwidth\"><img src=\"theme/$STORED[THEME]/images/webboard/bar_right.gif\" height=\"11\" width=\"4\"> [";printf("%.2f",$ppercent);echo"%]
										</TD>
									</TR>
";
				}
			}
echo "									<TR>
										<TD colspan=\"3\" align=\"center\">
											<B>Total Votes: $totalvote</B>
										</TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
						<TR class=\"topic_title5\">
							<TD align=\"center\" colspan=\"3\">
								<div style=\"font-weight:bold;padding:4px;margin-top:1px\">";
			if(!checkprivilege_action($CP[login_id],g_vote_polls)) {
echo "									You don't have a privilege to vote
";
			} else {
echo "									You have already voted in this poll
";
			}
echo "								</div>
							</TD>
						</TR>
					</form>
";
		} else {
echo "						<TR class=\"topic_title8\">
							<TD align=\"center\" colspan=\"3\">
								<TABLE cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"padding:6px\">
									<TR align=\"center\">
										<TD>
											<B>$poll_row[poll_question]</B>
										</TD>
									</TR>
";
			for($c=1;$c<=10;$c++){
				$choice="choice$c";
				if(!empty($poll_row[$choice]))
echo "									<TR>
										<TD align=\"left\">
											<input type=\"radio\" name=\"poll_vote\" value=\"$c\" class=\"textinput\">&nbsp;
											<B>$poll_row[$choice]</B>
										</TD>
									</TR>
";
			 }
echo "								</TABLE>
							</TD>
						</TR>
						<TR class=\"topic_title5\">
							<TD align=\"center\" colspan=\"3\">
								<div style=\"font-weight:bold;padding:4px;margin-top:1px\">
									<input type=\"submit\" name=\"submit\" value=\"Vote!\" title=\"Add your vote to this poll\" class=\"textinput\">&nbsp;
									<input type=\"submit\" name=\"nullvote\" value=\"View Results (Null Vote)\" title=\"View results, but forfeit your vote in this poll\" class=\"textinput\">
								</div>
							</TD>
						</TR>
					</form>
";
	}
}
echo "						<TR height=\"27\">
							<TD class=\"title_bar2\" colspan=\"3\"></TD>
						</TR>
";
$IS_EDIT_POST = checkprivilege_action($CP[login_id],g_edit_posts)?1:0;
$IS_DELETE_POST = checkprivilege_action($CP[login_id],g_delete_posts)?1:0;
$IS_VIEW_IP = checkprivilege_action($CP[login_id],g_view_userip)?1:0;
$reply_num = ($page-1)*$CONFIG_per_page;
while ($row = $sql->fetch_row()) {
	$reply_num++;
	if ($reply_name = get_username($row[reply_user_id])) {
		$IS_GUEST=0;
	} else {
		$reply_name = $lang[Guest];
		$IS_GUEST=1;
	}
	if($IS_GUEST) {
		$reply_avatar="";
		$reply_avatar_width="";
		$reply_avatar_height="";
		$reply_post="";
		$reply_number="";
		$reply_joined="";
		$reply_signature="";
		$rank_title="";
		$reply_imgroup="";
		$reply_group="";
		$status_bar="";
	} else
		get_cp_profile($row[reply_user_id]);
	$reply_emo = $row[reply_emo] ? "<img src=\"theme/$STORED[THEME]/images/icon/icon$row[reply_emo].gif\" border=\"0\">&nbsp;&nbsp;" : "";
	$reply_date = get_date("M j y, H:i:s A",$row[reply_date]);
	$reply_edit_date =  get_date("M j y, H:i:s A",$row[reply_edit_date]);
	$reply_message = replace_text($row[reply_message]);
	if($IS_GUEST)
		$reply_group = "Non Member";
	if($reply_num!=1){
echo "						<TR>
							<TD colspan=\"3\" class=\"emptytable2\"></TD>
						</TR>
";
	}
echo "						<TR height=\"27\" class=\"topic_title7\">
							<TD width=\"20%\" class=\"textinput\" id=\"entry".$row[reply_id]."\">
";
	if($IS_GUEST)
echo "								<a name=\"entry$row[reply_id]\"><font size=\"2\"><B>$reply_name</B></font></a>
";
	else
echo "								<a href=\"index.php?showuser=".md5($row[reply_user_id])."\"><font size=\"2\"><B>$reply_name</B></font></a>
";
	if($IS_VIEW_IP) {
		if(checkprivilege_action($row[reply_user_id],g_non_showip))
			$reply_ip='IP: ---------- |';
		else
			$reply_ip="IP: <a href=\"http://$row[reply_ip]/\" target=\"_blank\">$row[reply_ip]</a> |";
	}
echo"							</TD>
							<TD width=\"80%\" colspan=\"2\">
								<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
									<TBODY>
										<TR>
											<TD width=\"70%\" class=\"textinput\">
												$reply_emo $lang[Post]: $reply_date
											</TD>
											<TD width=\"30%\" align=\"right\">
												$reply_ip Post: <a href=\"javascript:link_to_post('http://".$_SERVER[HTTP_HOST]."".$_SERVER[SCRIPT_NAME]."?showtopic=$GET_showtopic&view=findpost&p=$row[reply_id]')\">#$reply_num</a>
											</TD>
										</TR>
									</TBODY>
								</TABLE>
							</TD>
						<TR height=\"150\" class=\"topic_title8\">
							<TD width=\"20%\"  vAlign=\"top\" style=\"font-size: 11px; font-family: verdana, helvetica, sans-serif;\">
";
	if($IS_GUEST) {
echo "								<BR>$lang[Group]: $reply_group
";
	} else {
echo "								<BR>
								<div align=\"center\">
									$reply_avatar
								</div>
								<BR>$rank_title
								<BR><img src=\"theme/$STORED[THEME]/images/groups/$reply_imgroup\" alt=\"Group Icon\">
								<BR><BR>$lang[Group]: $reply_group
								<BR>$lang[Posts]: $reply_post
								<BR>$lang[Mem_No]: $reply_number
								<BR>$lang[Join]: $reply_joined
";
	}

echo "							</TD>
							<TD width=\"80%\" colspan=\"2\" vAlign=\"top\">
								<span id=\"replyid_".$row[reply_id]."\"><div class=\"poststyle\">$reply_message</div>
";
	if(!empty($row[reply_edit_name])) {
echo "								<BR><BR><span class=\"edit\">This post has been edited by <B>$row[reply_edit_name]</B> on $reply_edit_date</span>
";
	}
echo "								</span>
";
	if(!empty($row[reply_upload])) {
echo "								<BR><BR><a href=\"$CONFIG_uploads_folder/[$row[reply_user_id]]$row[reply_upload]\" title=\"$row[reply_upload]\" target=\"_blank\"><I>Attach file: $row[reply_upload]</I></a>
";
	}
	if(!empty($reply_signature)) {
		$reply_signature_ = replace_text($reply_signature);
echo "								<BR><BR><BR>--------------------<BR>$reply_signature_
";
	}
echo "							</TD>
						</TR>
						<TR class=\"topic_title5\">
							<TD class=\"textinput\">
								$status_bar
";
	if($IS_DELETE_POST && $reply_num != '1')
		$reply_del="<a href=\"javascript:delete_post('index.php?act=post&code=03&t=$GET_showtopic&p=$row[reply_id]')\"><img src=\"theme/$STORED[THEME]/images/webboard/p_delete.gif\" border=\"0\" alt=\"Delete�Post\"></a>";
	else
		$reply_del='';
echo "							</TD>
							<TD colspan=\"2\">
								<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
									<TBODY>
										<TR>
											<TD align=\"left\">
												<a href=\"javascript:scroll(0,0)\"><img src=\"theme/$STORED[THEME]/images/webboard/p_up.gif\" border=\"0\" alt=\"top\"></a>
											</TD>
											<TD align=\"right\">
												$reply_del
";
	if(!empty($CP[login_id]) && ($CP[login_id] == $row[reply_user_id] || $IS_EDIT_POST))
echo "<a style='text-decoration:none;' id=\"edit_post_".$row[reply_id]."\" href=\"index.php?act=post&code=02&f=$forum_id&t=$GET_showtopic&p=$row[reply_id]\"><img src=\"theme/$STORED[THEME]/images/webboard/p_edit.gif\" border=\"0\" alt=\"Edit�Post\"></a>
<script type='text/javascript'>
menu_build_menu('edit_post_".$row[reply_id]."','85',
	new Array('<a id=\'Button\' onclick=\"hyperlink(\'index.php?act=post&code=02&f=$forum_id&t=$GET_showtopic&p=$row[reply_id]\');return false;\" href=\"#\">Full Edit</a>',
	'<a id=\'Button\' onclick=\"quick_edit(\'replyid_".$row[reply_id]."\',\'p=$row[reply_id]\');return false;\" href=\"#\">Quick Edit</a>'));
</script>
";
echo "												<a href=\"index.php?act=post&code=01&f=$forum_id&t=$GET_showtopic&qpid=$row[reply_id]\"><img src=\"theme/$STORED[THEME]/images/webboard/p_quote.gif\" border=\"0\" alt=\"Quote�Post\"></a>
											</TD>
										</TR>
									</TBODY>
								</TABLE>
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
<script type='text/javascript'>
	var max_width = ".$CONFIG_max_img_width.";
	var max_height = ".$CONFIG_max_img_height.";
	var total_img_resize = ".$CP[images_num].";
	window.onload=resize_img;
</script>
<BR>
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<TBODY>
		<TR>
			<TD>
";
get_selectpage($total,$CONFIG_per_page,$page,"index.php?showtopic=$GET_showtopic");
echo "
			</TD>
			<TD align=\"right\">
";
if($qr_open = check_forum_perm($forum_id,$CP['g_id'],'reply_perm') && !$topic_closed) {
echo "	<a href=\"javascript:ShowHide('qr_open');\" title=\"Fast Reply\"><img src=\"theme/$STORED[THEME]/images/webboard/t_qr.gif\" border=\"0\"></a>
";
}
get_menuwb($forum_id,2,$t_reply);
echo "			</TD>
		</TR>
	</TBODY>
</TABLE>
";
if(checkprivilege_action($CP[login_id],g_view_topic_option)) {
	if($topic_closed=='1')
		$val01 = "$lang[Open_Topic]";
	else
		$val01 = "$lang[Close_Topic]";
	if($topic_pin=='1')
		$val04 = "$lang[Unpin_Topic]";
	else
		$val04 = "$lang[Pin_Topic]";
echo "
<form action=\"index.php?act=mod\" method=\"post\" enctype=\"multipart/form-data\" name=\"topic_option\" onsubmit=\"return CheckMod()\">
	<input type=\"hidden\" name=\"f\" value=\"$forum_id\">
	<input type=\"hidden\" name=\"t\" value=\"$GET_showtopic\">
	<input type=\"hidden\" name=\"topic_pin\" value=\"$topic_pin\">
	<input type=\"hidden\" name=\"topic_closed\" value=\"$topic_closed\">
	<select name=\"code\" class=\"textinput\">
		<option value=\"00\">Moderation Options</option>
		<option value=\"07\">-$lang[Move_Topic]</option>
";
if(checkprivilege_action($CP[login_id],g_closed_topics))
echo "		<option value=\"01\">-$val01</option>
";
if(checkprivilege_action($CP[login_id],g_delete_topics))
echo "		<option value=\"02\">-$lang[Del_Topic]</option>
";
if(checkprivilege_action($CP[login_id],g_edit_topics))
echo "		<option value=\"03\">-$lang[Edit_Topic_Title]</option>
";
if(checkprivilege_action($CP[login_id],g_pinned_topics))
echo "		<option value=\"04\">-$val04</option>
";
echo "	</select>
	<input type=\"submit\" name=\"Submit\" value=\"Go\" class=\"textinput\">
</form>
";
}
if($qr_open && !$topic_closed) {
echo "<br>
<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<TR>
		<TD id=\"qr_open\" class=\"topic_title8\" style=\"visibility:hidden;\">
";
opmain_body("Fast Reply",0,'100%');
echo "			<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">
				<TR>
					<TD>
						<TABLE width=\"80%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" class=\"tablefill\">
						<form action=\"index.php?act=insert_topic&code=01\" method=\"post\" enctype=\"multipart/form-data\" id=\"t_post_form\" OnSubmit=\"return CheckReplymessage('t_post_form')\">
						<input type=\"hidden\" name=\"f\" value=\"".$forum_id."\">
						<input type=\"hidden\" name=\"t\" value=\"".$GET_showtopic."\">
							<TR class=\"topic_title5\">
								<TD>
";
get_bbcode("t_post_form");
echo "								</TD>
							</TR>
							<TR class=\"topic_title5\" align=\"center\">
								<TD>
									<textarea style=\"width:100%;\" name=\"t_mes\" cols=\"80\" rows=\"15\" class=\"textinput\"></textarea>
								</TD>
							</TR>
							<TR class=\"topic_title5\" align=\"center\">
								<TD>
									<input type=\"submit\" name=\"Submit\" value=\"Add�Reply\" class=\"textinput\" onclick=\"return CheckPostlength('t_post_form','$CONFIG_max_post_length');\">
									<input type=\"button\" name=\"Button\" value=\"Close Fast Reply\" class=\"textinput\" onclick=\"ShowHide('qr_open')\">
								</TD>
							</TR>
						</form>
						</TABLE>
					</TD>
				</TR>
			</TABLE>
";
clmain_body();
echo "		</TD>
	</TR>
</TABLE>
";
}
} else {
	redir("index.php?act=webboard","$lang[No_topic]",3);
}
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
