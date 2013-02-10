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
}
else {
$GET_f = (int)$GET_f;
$GET_p = (int)$GET_p;
$GET_t = (int)$GET_t;
$GET_qpid = (int)$GET_qpid;

$CP['g_id'] = checkprivilege($CP[login_id]);
if ($GET_code==00 && $GET_f) {
if(check_forum_perm($GET_f,$CP['g_id'],'start_perm')) {
echo "
<script language='JavaScript'>
function CheckPoll(){
var TotalChoice=0;var P1 = document.t_post_form.t_p_title.value;var C1 = document.t_post_form.t_p_answer_1.value;var C2 = document.t_post_form.t_p_answer_2.value;var C3 = document.t_post_form.t_p_answer_3.value;var C4 = document.t_post_form.t_p_answer_4.value;var C5 = document.t_post_form.t_p_answer_5.value;var C6 = document.t_post_form.t_p_answer_6.value;var C7 = document.t_post_form.t_p_answer_7.value;var C8 = document.t_post_form.t_p_answer_8.value;var C9 = document.t_post_form.t_p_answer_9.value;var C10 = document.t_post_form.t_p_answer_10.value;
if (P1.length < 3) {alert('You must enter messages of Poll Question at least 3 characters to post.'); document.t_post_form.t_p_title.focus(); return false;}
if (C1.length) TotalChoice++;if (C2.length) TotalChoice++;if (C3.length) TotalChoice++;if (C4.length) TotalChoice++;if (C5.length) TotalChoice++;if (C6.length) TotalChoice++;if (C7.length) TotalChoice++;if (C8.length) TotalChoice++;if (C9.length) TotalChoice++;if (C10.length) TotalChoice++;
if(TotalChoice<2) {alert('You must enter messages of Choice at least 2 choices.');return false;}
}
function Change_Choice(x){if(x<10){document.getElementById(x+1).style.display = '';document.getElementById(x+100).style.display = 'none';document.getElementById(x+101).style.display = '';document.getElementById(x+200).style.display = 'none';document.getElementById(x+201).style.display = '';} else {alert('You cannot make a new choice.');}}
function Clear_Choice(x){document.getElementById(x).style.display = 'none';document.getElementById(x+100).style.display = 'none';document.getElementById(x+200).style.display = 'none';document.getElementById(x-1).style.display = '';document.getElementById(x+100-1).style.display = '';if(x>3)document.getElementById(x+200-1).style.display = '';document.getElementById(x+300).value = '';}
</script>
<form action=\"index.php?act=insert_topic&code=00\" method=\"post\" enctype=\"multipart/form-data\" id=\"t_post_form\" OnSubmit=\"return CheckPostmessage('t_post_form')\">
<input type=\"hidden\" name=\"f\" value=\"$GET_f\">
<input type=\"hidden\" name=\"newpoll\" value=\"0\">
<div id=\"post_preview\"></div>
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"topic_title\">
	<TBODY>
		<TR>
			<TD class=\"title_bar\" height=\"29\">
				<a class=\"m_title\">&nbsp;&nbsp;Post a new topic</a>
			</TD>
		</TR>
		<TR>
			<TD>
				<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable\">
					<TBODY>
						<TR class=\"topic_title5\">
							<TD width=\"100%\" colspan=\"2\">
								<div class=\"title_face3\"><B>Topic Settings</B></div>
							</TD>
						</TR>
						<TR class=\"topic_title6\">
							<TD width=\"30%\">
								<div class=\"title_face4\">Topic Title</div>
							</TD>
							<TD width=\"70%\">
								<input name=\"t_title\" type=\"text\" size=\"40\" maxlength=\"50\" class=\"textinput\">
							</TD>
						</TR>
						<TR class=\"topic_title6\">
							<TD width=\"30%\">
								<div class=\"title_face4\">Topic Description</div>
							</TD>
							<TD width=\"70%\">
								<input name=\"t_desc\" type=\"text\" size=\"40\" maxlength=\"40\" class=\"textinput\">
							</TD>
						</TR>
						<TR class=\"topic_title5\">
							<TD width=\"100%\" colspan=\"2\">
								<div class=\"title_face3\"><B>Poll Options</B></div>
							</TD>
						</TR>
						<TR class=\"topic_title6\">
							<TD colspan=\"2\" id=\"poll_form\"><a href=\"#\" onClick=\"show_poll_form(); return false;\">Click here to manage this topic's poll</a></TD>
						</TR>
";
include_once "function/post.php";
echo "						<TR class=\"topic_title5\">
							<TD width=\"100%\" colspan=\"2\" align=\"center\">
								<input type=\"submit\" name=\"Submit\" value=\"Post New Topic\" class=\"textinput\" onclick=\"return CheckPostlength('t_post_form','$CONFIG_max_post_length');\">
								<input type=\"button\" name=\"Preview\" value=\"Preview Post\" class=\"textinput\" onClick=\"preview_post('t_post_form');\">
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
} else {
	redir("index.php?act=forum","$lang[No_privilege]",3);
}
}
if ($GET_code==01 && $GET_f) {
if(check_forum_perm($GET_f,$CP['g_id'],'reply_perm')) {
$query = "SELECT closed_mode FROM $CONFIG_sql_cpdbname.board_topic WHERE topic_id =\"".mysql_res($GET_t)."\"";
$sql->result = $sql->execute_query($query,'action_post.php');$sql->total_query++;
$row = $sql->fetch_row();
$closed_topic = $row["closed_mode"];
if ($sql->count_rows()>0 && ($closed_topic!='1' || checkprivilege_action($CP[login_id],g_post_closed_topics))) {
echo "
<form action=\"index.php?act=insert_topic&code=01\" method=\"post\" enctype=\"multipart/form-data\" id=\"t_post_form\" OnSubmit=\"return CheckReplymessage('t_post_form')\">
<input type=\"hidden\" name=\"f\" value=\"$GET_f\">
<input type=\"hidden\" name=\"t\" value=\"$GET_t\">
<div id=\"post_preview\"></div>
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"topic_title\">
	<TBODY>
		<TR>
			<TD class=\"title_bar\" height=\"29\">
				<a class=\"m_title\">&nbsp;&nbsp;Replying to ".$topic_name."</a>
			</TD>
		</TR>
		<TR>
			<TD>
				<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable\">
					<TBODY>
";
include_once "function/post.php";
echo "						<TR class=\"topic_title5\">
							<TD width=\"100%\" colspan=\"2\" align=\"center\">
								<input type=\"submit\" name=\"Submit\" value=\"Add Reply\" class=\"textinput\" onclick=\"return CheckPostlength('t_post_form','$CONFIG_max_post_length');\">
								<input type=\"button\" name=\"Preview\" value=\"Preview Post\" class=\"textinput\" onClick=\"preview_post('t_post_form');\">
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
else {
	redir("index.php?act=webboard","$lang[No_topic]",3);
}
} else {
	redir("index.php?act=forum","$lang[No_privilege]",3);
}
}
if ($GET_code==02 && $GET_f && $GET_p) {
if(check_forum_perm($GET_f,$CP['g_id'],'reply_perm')) {
$query = "SELECT reply_user_id FROM $CONFIG_sql_cpdbname.board_reply WHERE reply_id =\"".mysql_res($GET_p)."\"";
$sql->result = $sql->execute_query($query,'action_post.php');$sql->total_query++;
$row_check = $sql->fetch_row();
$checkuserid = $row_check["reply_user_id"];

$query = "SELECT topic_name,topic_description FROM $CONFIG_sql_cpdbname.board_topic WHERE topic_id =\"".mysql_res($GET_t)."\"";
$sql->result = $sql->execute_query($query,'action_post.php');$sql->total_query++;
$row = $sql->fetch_row();

if ($sql->count_rows()>0 && ($checkuserid==$CP[login_id] || checkprivilege_action($CP[login_id],g_edit_posts)) && !empty($CP[login_id])) {
	$query = "SELECT reply_id FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id = \"".mysql_res($GET_t)."\" ORDER by reply_id LIMIT 1";
	$sql->result = $sql->execute_query($query,'action_post.php');$sql->total_query++;
	$row_result2 = $sql->fetch_row();
	$reply_id_start = $row_result2[0];
	if($reply_id_start == $GET_p) {
		$return = "CheckPostmessage('t_post_form')";
		$IS_EDIT_TOPIC = 1;
	} else {
		$return = "CheckReplymessage('t_post_form')";
		$IS_EDIT_TOPIC = 0;
	}
echo "
<form action=\"index.php?act=insert_topic&code=02\" method=\"post\" enctype=\"multipart/form-data\" id=\"t_post_form\" OnSubmit=\"return ".$return."\">
<input type=\"hidden\" name=\"f\" value=\"$GET_f\">
<input type=\"hidden\" name=\"t\" value=\"$GET_t\">
<input type=\"hidden\" name=\"p\" value=\"$GET_p\">
<div id=\"post_preview\"></div>
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"topic_title\">
	<TBODY>
		<TR>
			<TD class=\"title_bar\" height=\"29\">
				<a class=\"m_title\">&nbsp;&nbsp;Editing a post in ".$topic_name."</a>
			</TD>
		</TR>
		<TR>
			<TD>
				<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable\">
					<TBODY>
";
	if($IS_EDIT_TOPIC) {
echo "					<input type=\"hidden\" name=\"edit_topic\" value=\"1\">
							<TR class=\"topic_title5\">
							<TD width=\"100%\" colspan=\"2\">
								<div class=\"title_face3\"><B>Topic Settings</B></div>
							</TD>
						</TR>
						<TR class=\"topic_title6\">
							<TD width=\"30%\">
								<div class=\"title_face4\">Topic Title</div>
							</TD>
							<TD width=\"70%\">
								<input name=\"t_title\" type=\"text\" size=\"40\" maxlength=\"50\" class=\"textinput\" value=\"$row[topic_name]\">
							</TD>
						</TR>
						<TR class=\"topic_title6\">
							<TD width=\"30%\">
								<div class=\"title_face4\">Topic Description</div>
							</TD>
							<TD width=\"70%\">
								<input name=\"t_desc\" type=\"text\" size=\"40\" maxlength=\"40\" class=\"textinput\" value=\"$row[topic_description]\">
							</TD>
						</TR>
";
	}
include_once "function/post.php";
echo "						<TR class=\"topic_title5\">
							<TD width=\"100%\" colspan=\"2\" align=\"center\">
								<input type=\"submit\" name=\"Submit\" value=\"Submit Modified Post\" class=\"textinput\" onclick=\"return CheckPostlength('t_post_form','$CONFIG_max_post_length');\">
								<input type=\"button\" name=\"Preview\" value=\"Preview Post\" class=\"textinput\" onClick=\"preview_post('t_post_form');\">
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
else {
	redir("index.php?act=webboard","$lang[No_topic]",3);
}
} else {
	redir("index.php?act=forum","$lang[No_privilege]",3);
}
}
if ($GET_code==03 && checkprivilege_action($CP[login_id],g_delete_posts)) {
$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id =\"".mysql_res($GET_t)."\" AND reply_id = \"".mysql_res($GET_p)."\" ";
$sql->result = $sql->execute_query($query,'action_post.php');$sql->total_query++;
if ($sql->result()) {
	$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id =\"".mysql_res($GET_t)."\" AND reply_id = \"".mysql_res($GET_p)."\" ",'action_post.php');$sql->total_query++;
	$query = "SELECT reply_user_id FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id =\"".mysql_res($GET_t)."\" order by reply_id DESC LIMIT 1";
	$sql->result = $sql->execute_query($query,'action_post.php');$sql->total_query++;
	$row_reply = $sql->fetch_row();
	$lastnewreplyid = $row_reply[reply_user_id];
	$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.board_topic SET topic_replying=topic_replying-1,topic_lastreply_name=\"".$lastnewreplyid."\" WHERE topic_id =\"".mysql_res($GET_t)."\" ",'action_post.php');$sql->total_query++;
	redir("index.php?showtopic=$GET_t&view=getnewpost","$lang[Delete_topic]",3);
}
else {
	redir("index.php?act=webboard","$lang[No_topic]",3);
}
}

}
?>
