<?php
if(!$SERVER['system_safe']) exit();
echo "<TR class=\"topic_title5\">
	<TD width=\"100%\" colspan=\"2\">
		<div class=\"title_face3\"><B>Code Buttons</B></div>
	</TD>
</TR>
<TR class=\"topic_title6\">
	<TD width=\"30%\"> </TD>
	<TD width=\"70%\">
";
		get_bbcode('t_post_form');
echo "	</TD>
</TR>
<TR class=\"topic_title5\">
	<TD width=\"100%\" colspan=\"2\">
		<div class=\"title_face3\"><B>Enter your post</B></div>
	</TD>
</TR>
<TR class=\"topic_title6\">
	<TD width=\"30%\" vAlign=\"top\">
";
		emotions_select('t_post_form');
echo "	</TD>
	<TD width=\"70%\" vAlign=\"top\">
";
if ($GET_code=='02') {
	$query = "SELECT reply_message,reply_emo,reply_edit_name FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id =\"".mysql_res($GET_t)."\" AND reply_id = \"".mysql_res($GET_p)."\" ";
	$sql->result = $sql->execute_query($query,'function/post.php');
	$row_replyid = $sql->fetch_row();
	$replyid_message = $POST_quick_edit?$POST_t_mes:$row_replyid["reply_message"];
	$reply_emo = $row_replyid["reply_emo"];
	$reply_edit = $row_replyid["reply_edit_name"];
}
if($GET_code=='01' && $GET_qpid = (int)$GET_qpid) {
	$query = "SELECT reply_user_id,reply_message,reply_date FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id =\"".mysql_res($GET_t)."\" AND reply_id = \"".mysql_res($GET_qpid)."\" ";
	$sql->result = $sql->execute_query($query,'function/post.php');
	$row_replyid = $sql->fetch_row();

	$query = "SELECT account_id,userid FROM $CONFIG_sql_dbname.login WHERE account_id =\"".mysql_res($row_replyid['reply_user_id'])."\"";
	$sql->result = $sql->execute_query($query,'function/post.php');
	$row_name = $sql->fetch_row();

	$reply_name = $row_name[userid]?get_username($row_name[account_id]):"Guest";
	$reply_date = get_date("M j y, H:i:s A",$row_replyid[reply_date]);
	$replyid_message = "
[QUOTE=$reply_name @ $reply_date]
$row_replyid[reply_message]
[/QUOTE]
";
}
	$replyid_message = my_br2nl($replyid_message);
echo"		<textarea style=\"width:100%\" name=\"t_mes\" cols=\"80\" rows=\"15\" class=\"textinput\">$replyid_message</textarea>
	</TD>
</TR>
<TR class=\"topic_title6\">
	<TD width=\"30%\">
		<div class=\"title_face4\"><B>Post Icons</B></div>
	</TD>
	<TD width=\"70%\">
";
for ($e=1; $e<=7; $e++) {
	if($e==$reply_emo) {
		$checked="checked";
		$is_check=1;
	} else
		$checked="";
echo "		<input name=\"t_emo\" type=\"radio\" value=\"$e\" $checked>&nbsp;&nbsp;&nbsp;<img src=\"theme/$STORED[THEME]/images/icon/icon$e.gif\">
";
	if($e!=7)
		echo "&nbsp;&nbsp;";
}
echo "<BR>
";
for ($e=8; $e<=14; $e++) {
	if($e==$reply_emo) {
		$checked="checked";
		$is_check=1;
	} else
		$checked = "";
echo "		<input name=\"t_emo\" type=\"radio\" value=\"$e\" $checked>&nbsp;&nbsp;&nbsp;<img src=\"theme/$STORED[THEME]/images/icon/icon$e.gif\">
";
if($e!=14)
echo "&nbsp;&nbsp;";
}
if($is_check!=1)
	$checked="checked";
else
	$checked="";
echo "<BR>
		<input name=\"t_emo\" type=\"radio\" value=\"0\" $checked>&nbsp;&nbsp;[ User None ]
	</TD>
</TR>
";
if ($GET_code==02) {
if(!empty($reply_edit)) $edit_checked = "checked"; else $edit_checked = "";
echo "<TR class=\"topic_title6\">
	<TD>
		<div class=\"title_face4\"><B>Edit Options</B></div>
	</TD>
	<TD>
		<input type=\"checkbox\" name=\"add_edit\" value=\"1\" class=\"textinput\" $edit_checked>&nbsp;<b>Add</b> the 'Edit by' line in this post?
	</TD>
</TR>
";
}
if ($GET_code!=02) {
if (!empty($loginname) && !empty($loginpass)){
if (checkprivilege_action($CP[login_id],g_upload_nonlimit)) $uploads_limit = 'Unlimit'; else $uploads_limit = $CONFIG_uploads_size;
echo "<TR class=\"topic_title6\">
	<TD>
		<div class=\"title_face4\"><B>Attach File</B> (File Size Limit : $uploads_limit Kb.)</div>
	</TD>
	<TD>
		<input name=\"attach\" type=\"file\" class=\"textinput\">
	</TD>
</TR>
";
}
}
if(checkprivilege_action($CP[login_id],g_view_topic_option) && $GET_code !=02) {
	$query = "SELECT pinned_mode,closed_mode FROM $CONFIG_sql_cpdbname.board_topic WHERE topic_id =\"".mysql_res($GET_t)."\" ";
	$sql->result = $sql->execute_query($query,'function/post.php');
	$row_topicid = $sql->fetch_row();
	$pinned = $row_topicid[pinned_mode];
	$closed = $row_topicid[closed_mode];

	if($pinned!=1 && $closed!=1)
		$not_select = selected;
	else
		$not_select="";
	if($pinned==1 && $closed!=1)
		$pin_select = selected;
	else
		$pin_select="";
	if($pinned!=1 && $closed==1)
		$close_select = selected;
	else
		$close_select="";
	if($pinned==1 && $closed==1)
		$both_select = selected;
	else
		$both_select="";
echo "<TR class=\"topic_title5\">
	<TD width=\"100%\" colspan=\"2\">
		<div class=\"title_face3\"><B>Options</B></div>
	</TD>
</TR>
<TR class=\"topic_title6\">
	<TD width=\"30%\">
		<div class=\"title_face4\">After posting...</div>
	</TD>
	<TD width=\"70%\">
		<select name=\"t_topic_type\" class=\"codebuttons\">
			<option value=\"0\" $not_select>( Do Nothing )</option>";
if(checkprivilege_action($CP[login_id],g_pinned_topics))
echo "			<option value=\"1\" $pin_select>Pin this topic</option>
";
if(checkprivilege_action($CP[login_id],g_closed_topics))
echo "			<option value=\"2\" $close_select>Close this topic</option>
";
if(checkprivilege_action($CP[login_id],g_pinned_topics) && checkprivilege_action($CP[login_id],g_closed_topics))
echo "			<option value=\"3\" $both_select>Pin & Close this topic</option>
		</select>
		<input type=\"hidden\" name=\"admin_reply\" value=\"1\">
	</TD>
</TR>
";
} else {
echo "		<input type=\"hidden\" name=\"t_topic_type\" value=\"0\">
";
}
?>