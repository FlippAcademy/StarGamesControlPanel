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
$POST_t = (int)$POST_t;
$POST_f = (int)$POST_f;
if(checkprivilege_action($CP[login_id],g_view_topic_option) && $POST_t) {
$query = "SELECT topic_description FROM $CONFIG_sql_cpdbname.board_topic WHERE topic_id =\"".mysql_res($POST_t)."\"";
$sql->result = $sql->execute_query($query,'moderate.php');$sql->total_query++;
if ($sql->count_rows()) {
$row = $sql->fetch_row();
$topic_description = $row["topic_description"];
if($POST_code=='00') {}
if($POST_code=='01' && checkprivilege_action($CP[login_id],g_closed_topics)) {
	if ($POST_topic_closed=='1') {
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.board_topic SET closed_mode=\"0\" WHERE topic_id=\"".mysql_res($POST_t)."\"");$sql->total_query++;
	} else {
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.board_topic SET closed_mode=\"1\" WHERE topic_id=\"".mysql_res($POST_t)."\"",'moderate.php');$sql->total_query++;
	}
header_location("index.php?showtopic=$POST_t");
}
if($POST_code=='02' && checkprivilege_action($CP[login_id],g_delete_topics)) {
echo "
<form action=\"index.php?act=mod\" method=\"post\" enctype=\"multipart/form-data\" name=\"Delete_Topic_Form\" OnSubmit=\"document.Delete_Topic_Form.Submit.disabled=true;\")>
	<input type=\"hidden\" name=\"code\" value=\"06\">
	<input type=\"hidden\" name=\"t\" value=\"$POST_t\">
		<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"topic_title\">
			<TBODY>
				<TR>
					<TD class=\"title_bar\" height=\"29\">
						<a class=\"m_title\">&nbsp;&nbsp;Deleting Topic: $topic_name</a>
					</TD>
				</TR>
				<TR>
					<TD>
						<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
							<TBODY>
								<TR class=\"topic_title5\">
									<TD height=\"27\">
										<div class=\"title_face\">
											Deleting Topic, only continue if you wish to delete this topic, there will be no other confirmation screens.
										</div>
									</TD>
								</TR>
								<TR class=\"topic_title5\">
									<TD width=\"100%\" align=\"center\">
										<input type=\"submit\" name=\"Submit\" value=\"Delete�this�topic\" class=\"textinput\">
									</TD>
								</TR>
							</TBODY>
						</TABLE>
					</TD>
				</TR>
			</TBODY>
		</TABLE>
</form>
";
}
if($POST_code=='03' && checkprivilege_action($CP[login_id],g_edit_topics)) {
echo"
<form action=\"index.php?act=mod\" method=\"post\" enctype=\"multipart/form-data\" name=\"Edit_Topic_Form\" OnSubmit=\"return CheckEdittopic()\">
<input type=\"hidden\" name=\"code\" value=\"05\">
<input type=\"hidden\" name=\"t\" value=\"$POST_t\">
	<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"topic_title\">
		<TBODY>
			<TR>
				<TD class=\"title_bar\" height=\"29\">
					<a class=\"m_title\">&nbsp;&nbsp;Editing Topic: $topic_name</a>
				</TD>
			</TR>
			<TR>
				<TD>
					<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
						<TBODY>
							<TR class=\"topic_title5\">
								<TD width=\"100%\" colspan=\"2\" height=\"27\">
									<div class=\"title_face\">
										Editing Topic Details
									</div>
								</TD>
							</TR>
							<TR class=\"topic_title6\">
								<TD width=\"30%\">
									<div class=\"title_face4\">
										<B>Topic Title</B>
									</div>
								</TD>
								<TD width=\"70%\">
									<input name=\"t_title\" type=\"text\" size=\"40\" maxlength=\"50\" class=\"textinput\" value=\"$topic_name\">
								</TD>
							</TR>
							<TR class=\"topic_title6\">
								<TD width=\"30%\">
									<div class=\"title_face4\">
										<B>Topic Description</B>
									</div>
								</TD>
								<TD width=\"70%\">
									<input name=\"t_desc\" type=\"text\" size=\"40\" maxlength=\"40\" class=\"textinput\" value=\"$topic_description\">
								</TD>
							</TR>
							<TR class=\"topic_title5\">
								<TD width=\"100%\" colspan=\"2\" align=\"center\">
									<input type=\"submit\" name=\"Submit\" value=\"Edit�this�topic\" class=\"textinput\">
								</TD>
							</TR>
						</TBODY>
					</TABLE>
				</TD>
			</TR>
		</TBODY>
	</TABLE>
</form>
";
}
if($POST_code=='04' && checkprivilege_action($CP[login_id],g_pinned_topics)) {
	if ($POST_topic_pin=='1') {
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.board_topic set pinned_mode='0' WHERE topic_id=\"".mysql_res($POST_t)."\"",'moderate.php');$sql->total_query++;
	} else {
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.board_topic set pinned_mode=\"1\" WHERE topic_id=\"".mysql_res($POST_t)."\"",'moderate.php');$sql->total_query++;
	}
header_location("index.php?showtopic=$POST_t");
}
if($POST_code=='05' && checkprivilege_action($CP[login_id],g_edit_topics)) {
	$POST_t_title = checkstring($POST_t_title,1);
	$POST_t_desc = checkstring($POST_t_desc,1);
	$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.board_topic set topic_name = \"".$POST_t_title."\",topic_description = \"".$POST_t_desc."\" WHERE topic_id=\"".mysql_res($POST_t)."\" ",'moderate.php');$sql->total_query++;
header_location("index.php?showtopic=$POST_t");
}
if($POST_code=='06' && checkprivilege_action($CP[login_id],g_delete_topics)) {
$query = "SELECT forum_id FROM $CONFIG_sql_cpdbname.board_topic WHERE topic_id =\"".mysql_res($POST_t)."\"";
$sql->result = $sql->execute_query($query,'moderate.php');
$row = $sql->fetch_row();
	$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id =\"".mysql_res($POST_t)."\" ",'moderate.php');
	$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.board_topic WHERE topic_id =\"".mysql_res($POST_t)."\" ",'moderate.php');
	$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.poll WHERE topic_id =\"".mysql_res($POST_t)."\" ",'moderate.php');
	$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.poll_vote WHERE topic_id =\"".mysql_res($POST_t)."\" ",'moderate.php');
	$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.voters WHERE topic_id =\"".mysql_res($POST_t)."\" ",'moderate.php');
header_location("index.php?showforum=$row[forum_id]");
}
if($POST_code=='07' && checkprivilege_action($CP[login_id],g_move_topics)) {
$forum_name = get_forumname($POST_f);
$topic_name = get_topicname($POST_t);
opmain_body("Move Topic ".$forum_name." > ".$topic_name."");
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
	<form action=\"index.php?act=mod\" method=\"post\" enctype=\"multipart/form-data\" name=\"Move_Topic_Form\">
	<input type=\"hidden\" name=\"code\" value=\"08\">
	<input type=\"hidden\" name=\"f\" value=\"$POST_f\">
	<input type=\"hidden\" name=\"t\" value=\"$POST_t\">
	<TR class=\"topic_title5\">
		<TD colspan=\"2\" height=\"27\">
			<div class=\"title_face\">Please select the destination forum and method of moving</div>
		</TD>
	</TR>
	<TR class=\"topic_title6\">
		<TD width=\"30%\">
			<div class=\"title_face4\"><b>Move this topic from New Forum to</b></div>
		</TD>
		<TD width=\"70%\">
			<select name=\"select_forum\" class=\"selectmenu\">
";
$query = "SELECT memory_value1,memory_value3 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"forum_category\" ORDER by memory_value2 ASC";
$sql->result = $sql->execute_query($query,'forum_manage.php');$sql->total_query++;
if($sql->count_rows()) {
	while ($row = $sql->fetch_row()) {
		$query = "SELECT forum_id,forum_title FROM $CONFIG_sql_cpdbname.forum WHERE category_id=\"".$row[memory_value1]."\" ORDER by forum_id ASC";
		$sql->result2 = $sql->execute_query($query,'forum_manage.php');
echo "				<optgroup label=\"$row[memory_value3]\"></optgroup>\n";
		if($sql->count_rows($sql->result2)) {
			while ($row2 = $sql->fetch_row($sql->result2)) {
echo "				<option value=\"$row2[forum_id]\">&nbsp;&nbsp;&#0124;-- $row2[forum_title]</option>\n";
			}
		}
	}
}
echo "			</select>
		</TD>
	</TR>
	<TR class=\"topic_title5\" align=\"center\">
		<TD colspan=\"2\">
			<input type=\"submit\" name=\"Submit\" value=\"Move�this�topic\" class=\"textinput\">
		</TD>
	</TR>
	</form>
</TABLE>
";
clmain_body();
}
if($POST_code=='08' && checkprivilege_action($CP[login_id],g_move_topics) && check_category($POST_f) && check_category($POST_select_forum) && $POST_t) {
$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.board_reply SET forum_id=\"".mysql_res($POST_select_forum)."\" WHERE topic_id =\"".mysql_res($POST_t)."\" AND forum_id = \"".mysql_res($POST_f)."\" ",'moderate.php');
$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.board_topic SET forum_id=\"".mysql_res($POST_select_forum)."\" WHERE topic_id =\"".mysql_res($POST_t)."\" AND forum_id = \"".mysql_res($POST_f)."\" ",'moderate.php');
header_location("index.php?showtopic=$POST_t");
}

}

}
else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
?>
