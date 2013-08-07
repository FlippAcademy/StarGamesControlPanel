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
$GET_post_id = (int)$GET_post_id;
if ($GET_code==01) {
if(checkprivilege_action($CP[login_id],g_add_news)) {
opmain_body("Add News Form");
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable\">
	<TBODY>
	<form action=\"index.php?act=action_news&code=02\" method=\"post\" enctype=\"multipart/form-data\" name=\"t_post_form\" id=\"t_post_form\" onsubmit=\"return CheckAddnews()\">
		<TR class=\"topic_title5\">
			<TD width=\"100%\" colspan=\"2\">
				<div class=\"title_face3\"><B>News Settings</B></div>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD width=\"30%\">
				<div class=\"title_face4\">$lang[Title]:</div>
			</TD>
			<TD width=\"70%\">
				<input name=\"title\" type=\"text\" size=\"50\" class=\"textinput\">
			</TD>
		</TR>
		<TR class=\"topic_title5\">
			<TD colspan=\"2\">
				<div class=\"title_face3\"><B>Code Buttons</B></div>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD></TD>
			<TD>
";
				get_bbcode('t_post_form');
echo "
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD vAlign=\"top\">
";
				emotions_select('t_post_form');
echo "			</TD>
			<TD vAlign=\"top\">
				<textarea style=\"width:100%\" name=\"t_mes\" cols=\"60\" rows=\"15\" wrap=\"VIRTUAL\" class=\"textinput\"></textarea>
			</TD>
		</TR>
		<TR class=topic_title5>
			<TD colspan=\"2\" align=\"center\">
				<input name=\"Submit\" type=\"submit\" value=\"$lang[Sentnews]\" class=\"textinput\">
				<input name=\"Reset\" type=\"reset\" value=\"$lang[Resetnews]\" class=\"textinput\">
			</TD>
		</TR>
	</form>
	</TBODY>
</TABLE>
";
clmain_body();
}
}
else if ($GET_code==02) {
if(checkprivilege_action($CP[login_id],g_add_news)) {
	if (!length($POST_title,1) || !length($POST_t_mes,5) || empty($STORED_loginname)) {
		$display ="$lang[Error]";
	} else {
		$POST_title = checkstring($POST_title,1);
		$POST_t_mes = checkstring($POST_t_mes,1);
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.mainnews (title,message,poster,date) VALUES (\"".$POST_title."\",\"".$POST_t_mes."\",\"".$CP['login_name']."\",\"".$CP['time']."\")",'action_news.php');$sql->total_query++;
		$display ="$lang[Success_addnews]";
	}
redir("index.php?act=readnews","$display",3);
} else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
}
else if ($GET_code==03) {
if(checkprivilege_action($CP[login_id],g_edit_news)) {
	if(!$GET_post_id){
		$display = "$lang[Error]";
		redir("index.php?act=idx","$display",3);
	} else {
		$query = "SELECT post_id,message,title FROM $CONFIG_sql_cpdbname.mainnews WHERE post_id = \"".mysql_res($GET_post_id)."\" LIMIT 0,1";
		$sql->result = $sql->execute_query($query,'action_news.php');$sql->total_query++;
		$row = $sql->fetch_row();
		$message = my_br2nl($row[message]);
opmain_body("Edit News Form");
echo "
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable\">
	<TBODY>
	<form action=\"index.php?act=action_news&code=04&post_id=$row[post_id]\" method=\"post\" enctype=\"multipart/form-data\" name=\"t_post_form\" id=\"t_post_form\" onsubmit=\"return CheckAddnews()\">
		<TR class=\"topic_title5\">
			<TD width=\"100%\" colspan=\"2\">
				<div class=\"title_face3\"><B>News Settings</B></div>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD width=\"30%\">
				<div class=\"title_face4\">$lang[Title]</div>
			</TD>
			<TD width=\"70%\">
				<input name=\"title\" type=\"text\" id=\"title\" size=\"65\" value=\"$row[title]\" class=\"textinput\">
			</TD>
		</TR>
		<TR class=\"topic_title5\">
			<TD colspan=\"2\">
				<div class=\"title_face3\"><B>Code Buttons</B></div>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD></TD>
			<TD>
";
				get_bbcode('t_post_form');
echo "			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD vAlign=\"top\">
";
				emotions_select('t_post_form');
echo "			</TD>
			<TD vAlign=\"top\">
				<textarea style=\"width:100%\" name=\"t_mes\" cols=\"65\" rows=\"15\" wrap=\"VIRTUAL\" class=\"textinput\">$message</textarea>
			</TD>
		</TR>
		<TR class=\"topic_title5\">
			<TD colspan=\"2\" align=\"center\">
				<input name=\"Submit\" type=\"submit\" value=\"$lang[Edit]\" class=\"textinput\">
				<input name=\"Reset\" type=\"reset\" value=\"$lang[Restore]\" class=\"textinput\">
			</TD>
		</TR>
	</form>
	</TBODY>
</TABLE>
";
clmain_body();
	}
} else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
}
else if ($GET_code==04) {
if(checkprivilege_action($CP[login_id],g_edit_news)) {
	if (!$GET_post_id || !length($POST_title,1) || !length($POST_t_mes,5))
		$display = "$lang[Fail_editnews]";
	else {
		$POST_title = checkstring($POST_title,1);
		$POST_t_mes = checkstring($POST_t_mes,1);
		$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.mainnews WHERE post_id = \"".mysql_res($GET_post_id)."\" LIMIT 0,1";
		$sql->result = $sql->execute_query($query,'action_news.php');$sql->total_query++;
		if (!$sql->result()) {
			$display = $lang[Fail_editnews];
		} else {
			$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.mainnews SET title = \"$POST_title\", message = \"$POST_t_mes\", poster = \"".$CP['login_name']."\", date = \"".$CP['time']."\" WHERE post_id = \"".mysql_res($GET_post_id)."\" ;",'action_news.php');$sql->total_query++;
			$display = $lang[Success_editnews];
		}
	}
	redir("index.php?act=readnews","$display",3);
} else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
}
else if ($GET_code==05) {
if(checkprivilege_action($CP[login_id],g_delete_news)) {
	if (!$GET_post_id)
		$display = $lang[Fail_delnews];
	else {
		$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.mainnews WHERE post_id = \"".mysql_res($GET_post_id)."\" LIMIT 0,1";
		$sql->result = $sql->execute_query($query,'action_news.php');$sql->total_query++;
		if (!$sql->result()) {
			$display = $lang[Fail_delnews];
		} else {
			$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.mainnews WHERE post_id = \"".mysql_res($GET_post_id)."\"",'action_news.php');$sql->total_query++;
			$display = $lang[Success_delnews];
		}
	}
	redir("index.php?act=readnews","$display",3);
} else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
}
else
	header("location:index.php?act=idx");
?>
