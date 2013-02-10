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
if ($GET_code==00) {
opmain_body("Bug Report");
echo "
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" align=\"center\">
	<TBODY>
	<form action=\"index.php?act=bugreport&code=01\" method=\"post\" enctype=\"multipart/form-data\" name=\"Addreport\" onsubmit=\"return CheckAddreport()\">
		<TR class=\"topic_title5\">
			<TD colspan=\"2\">
				&nbsp;
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD width=\"30%\" align=\"right\">
				$lang[Bug_user] :
			</TD>
			<TD width=\"70%\" align=\"left\">
";
if (empty($STORED_loginname) && empty($STORED_loginpass))
	echo "<input name=\"poster\" type=\"text\" size=\"25\" class=\"textinput\">";
else
	echo "<input name=\"poster\" type=\"text\" value=\"$CP[login_name]\" size=\"25\" readonly=\"true\" class=\"textinput\">";

	if ($CONFIG_security_mode) {
		$sc_time = $CP['time'] - (60*30);
		$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.security_code WHERE sc_time < ".mysql_res($sc_time)."",'register.php',0);
		$sc_id = md5(uniqid(microtime()));
		$sc_code = generate_password(6);
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.security_code VALUES (\"".mysql_res($sc_id)."\",\"".mysql_res($sc_code)."\",".$CP['time'].")",'register.php',0);
	}

echo "
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD vAlign=\"top\" align=\"right\">
				$lang[Bug_mreport] :
			</TD>
			<TD align=\"left\">
				<textarea style=\"width:100%\" name=\"report\" cols=\"60\" rows=\"7\" wrap=\"VIRTUAL\" class=\"textinput\"></textarea>
			</TD>
		</TR>
";
	if ($CONFIG_security_mode) {
echo "		<input name=\"security_id\" type=\"hidden\" value=\"".$sc_id."\">
		<TR class=\"topic_title6\">
			<TD vAlign=\"top\" align=\"right\"></TD>
			<TD align=\"left\">
				<input name=\"security_code\" type=\"text\" size=\"20\" maxlength=\"6\" class=\"textinput\">
				<img src=\"reg_code.php?sc=$sc_id\" align=\"absmiddle\">
			</TD>
		</TR>
";
	}
echo "		<TR class=\"topic_title5\">
			<TD colspan=\"2\" align=\"center\">
				<input type=\"submit\" name=\"Submit\" value=\"$lang[Sentnews]\" class=textinput>
				<input name=\"Reset\" type=\"reset\" value=\"$lang[Resetnews]\" class=textinput>
			</TD>
		</TR>
	</form>
	</TBODY>
</TABLE>
";
if(!isset($GET_st)) $GET_st = 0;
$GET_st = (int)$GET_st;
echo "<span id=\"bugreport\"></span>
<script type='text/javascript'>page_select('bugreport','st=$GET_st')</script>";
clmain_body();
}
else if ($GET_code==01) {
if (!length($POST_poster,4,24) || !length($POST_report,5,512)) {
	redir("index.php?act=bugreport",$lang[Error],3);
} else {
	if ($CONFIG_security_mode) {
		$query= "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.security_code WHERE sc_id = \"".mysql_res($POST_security_id)."\" AND sc_code = \"".mysql_res($POST_security_code)."\"";
		$sql->result = $sql->execute_query($query,'register.php');$sql->total_query++;
		$count = $sql->result();
	}
	if ($CONFIG_security_mode && !$count) {
		redir_back("$lang[Reg_security_code_fail]");
	} else {
		if(!checkprivilege_action($CP[login_id],g_non_showip)) {
			$ip = $CP['ip_address'];
		} else $ip = '---------------';
		if (!$CP[login_sex])
			$CP[login_sex] = 'M';
		$POST_poster = checkstring($POST_poster,1);
		$POST_report = checkstring($POST_report,1);
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.bugreport (report,poster,sex,ip,date) VALUES (\"".$POST_report."\",\"".$POST_poster."\",\"".$CP['login_sex']."\",\"".$ip."\",\"".$CP['time']."\")",'bugreport.php');$sql->total_query++;
		if ($CONFIG_security_mode) {
			$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.security_code WHERE sc_id = \"".mysql_res($POST_security_id)."\"",'register.php',0);
		}
		redir("index.php?act=bugreport",$lang[Success_addreport],3);
	}
}
}
else if ($GET_code==02) {
if (checkprivilege_action($CP[login_id],g_edit_news)) {
if (!$GET_post_id) {
	$display = $lang[Error];
	redir("index.php?act=idx","$display",3);
	} else {
	$query = "SELECT post_id,poster,report FROM $CONFIG_sql_cpdbname.bugreport WHERE post_id = \"".mysql_res($GET_post_id)."\" LIMIT 0,1";
	$sql->result = $sql->execute_query($query,'bugreport.php');$sql->total_query++;
	$row = $sql->fetch_row();
	$report = my_br2nl($row[report]);
opmain_body("Edit Bug Report");
echo "
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" align=\"center\">
	<TBODY>
	<form action=\"index.php?act=bugreport&code=04&post_id=$row[post_id]\" method=\"post\" enctype=\"multipart/form-data\" name=\"Addreport\" id=\"bugreport\" onsubmit=\"return CheckAddreport()\">
		<TR class=\"topic_title5\">
			<TD colspan=\"2\">
				<font class=\"title_face3\">
					<B>&nbsp;Report Form</B>
				</font>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD width=\"30%\" align=\"right\">
				$lang[Bug_user] :
			</TD>
			<TD width=\"70%\" align=\"left\">
				<input name=\"poster\" type=\"text\" value=\"$row[poster]\" size=\"25\" readonly=\"true\" class=\"textinput\">
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD width=\"30%\" valign=\"top\" align=\"right\">
				$lang[Bug_mreport] :
			</TD>
			<TD width=\"70%\" align=\"left\">
				<textarea name=\"report\" cols=\"50\" rows=\"7\" wrap=\"VIRTUAL\" class=\"textinput\">$report</textarea>
			</TD>
		</TR>
		<TR class=\"topic_title5\">
			<TD colspan=\"2\" align=\"center\">
				<input type=\"submit\" name=\"Submit\" value=\"$lang[Sentnews]\" class=\"textinput\">
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
else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
}
else if ($GET_code==03) {
if (!$GET_post_id || !checkprivilege_action($CP[login_id],g_delete_news))
	$display = "$lang[Error]";
else {
	$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.bugreport WHERE post_id = \"".mysql_res($GET_post_id)."\" LIMIT 0,1";
	$sql->result = $sql->execute_query($query,'bugreport.php');$sql->total_query++;
	if (!$sql->result()) {
		$display = "$lang[Error]";
	} else {
		$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.bugreport WHERE post_id = \"".mysql_res($GET_post_id)."\"",'bugreport.php');$sql->total_query++;
		$display = "$lang[Success_delnews]";
	}
}
redir("index.php?act=bugreport","$display",3);
}
else if ($GET_code==04) {
if (!$GET_post_id || !checkprivilege_action($CP[login_id],g_edit_news) || !length($POST_poster,4,24) || !length($POST_report,5,512))
	$display = "$lang[Error]";
else {
	if(!checkprivilege_action($CP[login_id],g_non_showip)) {
		$ip = $CP['ip_address'];
	} else
		$ip = '---------------';
	if (!$CP[login_sex])
		$CP[login_sex] = 'M';
	$POST_poster = checkstring($POST_poster,1);
	$POST_report = checkstring($POST_report,1);
	$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.bugreport WHERE post_id = \"".mysql_res($GET_post_id)."\" LIMIT 0,1";
	$sql->result = $sql->execute_query($query,'bugreport.php');$sql->total_query++;
	if (!$sql->result())
		$display = "$lang[Error]";
	else {
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.bugreport SET report = \"$POST_report\", poster = \"".$CP['login_name']."\", sex = \"".$CP['login_sex']."\", ip = \"".$ip."\", date = \"".$CP[time]."\" WHERE post_id = \"".mysql_res($GET_post_id)."\" ;",'bugreport.php');$sql->total_query++;
		$display = "$lang[Success_editnews]";
	}
}
redir("index.php?act=bugreport","$display",3);
}
else
	header("location:index.php?act=bugreport");
?>