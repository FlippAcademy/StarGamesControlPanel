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
if (checkprivilege_action($CP['login_id'],g_account_manage)) {
if($GET_manage) {
switch($GET_manage) {
	case updateaccount:
		$HTTP_REFERER = get_referer();
		$POST_account_id = mysql_res($POST_account_id);
		$POST_user_pass = mysql_res($POST_user_pass);
		$POST_lastlogin = mysql_res($POST_lastlogin);
		$POST_sex = mysql_res($POST_sex);
		$POST_email = mysql_res($POST_email);
		$POST_unban_time = mysql_res($POST_unban_time);
		$POST_state = mysql_res($POST_state);
		$POST_display_name = checkstring($POST_display_name,1);
		$sql->execute_query("UPDATE $CONFIG_sql_dbname.login SET user_pass=\"$POST_user_pass\", lastlogin=\"$POST_lastlogin\", sex=\"$POST_sex\", email=\"$POST_email\", unban_time=\"$POST_unban_time\", state=\"$POST_state\" WHERE account_id=\"$POST_account_id\"",'account_manage.php');
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_profile SET display_name=\"$POST_display_name\" WHERE user_id=\"$POST_account_id\"",'account_manage.php');
		header_location("$HTTP_REFERER");
		break;
	case activeaccount:
		$POST_account_id = mysql_res($POST_account_id);
		$sql->execute_query("UPDATE $CONFIG_sql_dbname.login SET state=\"0\" WHERE account_id=\"$POST_account_id\"",'account_manage.php');
		$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"activate_id\" AND memory_value2=\"$POST_account_id\"",'account_manage.php');
		header_location("index.php?act=account_manage&code=view_inactiveid");
		break;
	case deleteinactiveid:
		$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.login WHERE account_id =\"".mysql_res($GET_account_id)."\"",'account_manage.php');
		$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"activate_id\" AND memory_value2=\"".mysql_res($GET_account_id)."\"",'account_manage.php');
		$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id =\"".mysql_res($GET_account_id)."\"",'account_manage.php');
		header_location("index.php?act=account_manage&code=view_inactiveid");
		break;
	default:
		header_location("index.php?act=account_manage");
		break;
}
}
else {
?>
<script language="JavaScript">
function CheckSearch(code) {
	var A1 = document.Searching_Option.search_value.value;
	var A2 = document.Searching_Option.ctype.value;
	var A3 = document.Searching_Option.searchby.value;
	hyperlink('index.php?act=account_manage&code='+code+'&searchby='+A3+'&search_value='+A1+'&ctype='+A2+'');
return false;
}
</script>
<?php
echo "- <a href=\"index.php?act=account_manage&code=view_account\">Account Management</a><BR>
- <a href=\"index.php?act=account_manage&code=view_inactiveid\">View inactive account</a><BR>
<BR>
";
if(empty($GET_code) || $GET_code=="view_account") {
if(!isset($GET_st)) $GET_st = 0;
$GET_st = (int)$GET_st;
$searchby_selected = array('','','','');
switch($GET_searchby) {
	default:
	case userid:
		$searchby_selected[0] = 'selected';
		break;
	case account_id:
		$searchby_selected[1] = 'selected';
		break;
	case email:
		$searchby_selected[2] = 'selected';
		break;
	case last_ip:
		$searchby_selected[3] = 'selected';
		break;
	case state:
		$searchby_selected[4] = 'selected';
		break;
}
opmain_body("Searching Option");
echo "<TABLE width=\"100%\" cellspacing=\"5\" cellpadding=\"0\" align=\"center\" class=\"topic_title6\">
	<form name=\"Searching_Option\" method=\"post\" enctype=\"multipart/form-data\" OnSubmit=\"return CheckSearch('view_account');\">
	<TBODY>
		<TR>
			<TD width=\"10%\">
				<select name=\"searchby\" class=\"textinput\">
					<option value=\"userid\"".$searchby_selected[0].">User ID</option>
					<option value=\"account_id\"".$searchby_selected[1].">Account ID</option>
					<option value=\"email\"".$searchby_selected[2].">Email</option>
					<option value=\"last_ip\"".$searchby_selected[3].">Last IP</option>
					<option value=\"state\"".$searchby_selected[4].">State</option>
				</select>
			</TD>
			<TD width=\"90%\">
				<input type=\"text\" name=\"search_value\" value=\"".checkstring($GET_search_value)."\" size=\"20\" class=\"textinput\">
				<select name=\"ctype\" class=\"textinput\">
					<option value=\"anyword\"".$s_anyword.">Any words</option>
					<option value=\"allword\"".$s_allword.">All words</option>
				</select>
				<input type=\"submit\" name=\"Submit\" value=\"Search\" class=\"textinput\">
			</TD>
		</TR>
	</TBODY>
	</form>
</TABLE>
";
clmain_body();
$search_value = mysql_res($GET_search_value);
if($GET_ctype == 'allword') {
	$s_allword = ' selected';
	$search_ctype = "= \"$search_value\"";
} else {
	$GET_ctype = 'anyword';
	$s_anyword = ' selected';
	$search_ctype = "LIKE \"%".$search_value."%\"";
}
if(!isset($GET_st)) $GET_st = 0;
$GET_st = (int)$GET_st;
$page=get_page($GET_st,$CONFIG_AM_per_page);
if($GET_search_value) {
	$search_userid = " AND ".mysql_res($GET_searchby)." ".$search_ctype."";
} else {
	$search_userid = "";
}
$sql->result = $sql->execute_query("SELECT COUNT(*) FROM $CONFIG_sql_dbname.login WHERE sex != \"S\"".$search_userid."",'account_manage.php');
$total = $sql->result();
$query = "SELECT * FROM $CONFIG_sql_dbname.login WHERE sex != \"S\"".$search_userid." ORDER by account_id ASC LIMIT ".mysql_res($GET_st).",".mysql_res($CONFIG_AM_per_page)."";
$sql->result = $sql->execute_query($query,'account_manage.php');$sql->total_query++;
echo"<BR>
<TABLE width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<TBODY>
		<TR>
			<TD align=\"right\">
";
				get_selectpage($total,$CONFIG_AM_per_page,$page,"index.php?act=account_manage&code=view_account&searchby=$GET_searchby&search_value=$GET_search_value&ctype=$GET_ctype");
echo "			</TD>
		</TR>
	</TBODY>
</TABLE>
<BR>
";
opmain_body("Account Management");
if($sql->count_rows()) {
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<TBODY>
		<TR height=\"27\" class=\"title_bar2\" align=\"center\">
			<TD><div class=\"title_face\">No.</div></TD>
			<TD><div class=\"title_face\">User ID</div></TD>
			<TD><div class=\"title_face\">Display name</div></TD>
			<TD><div class=\"title_face\">Password</div></TD>
			<TD><div class=\"title_face\">Last login</div></TD>
			<TD><div class=\"title_face\">Sex</div></TD>
			<TD><div class=\"title_face\">E-Mail</div></TD>
			<TD><div class=\"title_face\">Level</div></TD>
			<TD><div class=\"title_face\">Last IP</div></TD>
			<TD><div class=\"title_face\">Ban until</div></TD>
			<TD><div class=\"title_face\">State</div></TD>
			<TD></TD>
		</TR>
";
	$i = ($page-1)*$CONFIG_AM_per_page;
	$j = 0;
	while ($row = $sql->fetch_row()) {
		$i++;
		if ($GET_searchby == "last_ip") {
			$query = "SELECT name,class,base_level,job_level,last_map FROM $CONFIG_sql_dbname.char WHERE account_id = \"".$row[account_id]."\" AND online = \"1\" LIMIT 1";
			$sql->result2 = $sql->execute_query($query,'account_manage.php');
			if ($sql->count_rows($sql->result2)) {
				$row2 = $sql->fetch_row($sql->result2);
				$view_char_online[$j] = array($row["account_id"],$row["userid"],$row2["name"],$row2["class"],$row2["base_level"],$row2["job_level"],$row2["last_map"]);
				$j++;
			}
		}
		$display_name = get_username($row[account_id]);
		$ip_address = $row[last_ip]?"<a href=\"index.php?act=account_manage&code=view_account&searchby=last_ip&search_value=$row[last_ip]&ctype=$GET_ctype\">$row[last_ip]</a>":"-";
echo "		<form action=\"index.php?act=account_manage&manage=updateaccount\" method=\"post\" enctype=\"multipart/form-data\" name=\"Account_Manage_Form\">
		<input type=\"hidden\" name=\"account_id\" value=\"$row[account_id]\">
		<TR class=\"topic_title7\" align=\"center\">
			<TD>$row[account_id]</TD>
			<TD><a href=\"index.php?act=searching_id&account_id=$row[account_id]\" title=\"click here to manage this account\">$row[userid]</a></TD>
			<TD><input name=\"display_name\" type=\"text\" size=\"10\" maxlength=\"24\" class=\"textinput\" value=\"$display_name\"></TD>
			<TD><input name=\"user_pass\" type=\"text\" size=\"10\" maxlength=\"24\" class=\"textinput\" value=\"$row[user_pass]\"></TD>
			<TD><input name=\"lastlogin\" type=\"text\" size=\"10\" class=\"textinput\" value=\"$row[lastlogin]\"></TD>
			<TD><input name=\"sex\" type=\"text\" size=\"1\" maxlength=\"1\" class=\"textinput\" value=\"$row[sex]\"></TD>
			<TD><input name=\"email\" type=\"text\" size=\"10\" maxlength=\"50\" class=\"textinput\" value=\"$row[email]\"></TD>
			<TD><input name=\"level\" type=\"text\" size=\"2\" maxlength=\"3\" class=\"textinput\" value=\"$row[level]\" readonly></TD>
			<TD>$ip_address</TD>
			<TD><input name=\"unban_time\" type=\"text\" size=\"6\" class=\"textinput\" value=\"$row[unban_time]\"></TD>
			<TD><input name=\"state\" type=\"text\" size=\"1\" maxlength=\"2\" class=\"textinput\" value=\"$row[state]\"></TD>
			<TD><input type=\"submit\" name=\"Submit\" value=\"OK\" class=\"textinput\"><input type=\"reset\" name=\"Reset\" value=\"Restore\" class=\"textinput\"></TD>
		</TR>
		</form>
";
	}
echo "	</TBODY>
</TABLE>
";
} else {
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<TBODY>
		<TR height=\"27\" class=\"title_bar2\">
			<TD><div class=\"title_face\">CP Message</div></TD>
		</TR>
		<TR class=\"topic_title7\">
			<TD align=\"center\"><B>There isn't an account in your database.</B></TD>
		</TR>
	</TBODY>
</TABLE>
";
}
clmain_body();
	if ($GET_searchby == "last_ip" && $j) {
echo "<BR>\n";
opmain_body("View characters online of this IP (".$j." players)");
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<TBODY>
		<TR height=\"27\" class=\"title_bar2\" align=\"center\">
			<TD><div class=\"title_face\">No.</div></TD>
			<TD><div class=\"title_face\">User ID</div></TD>
			<TD><div class=\"title_face\">Name</div></TD>
			<TD><div class=\"title_face\">Class</div></TD>
			<TD><div class=\"title_face\">Base Level</div></TD>
			<TD><div class=\"title_face\">Job Level</div></TD>
			<TD><div class=\"title_face\">Last Map</div></TD>
		</TR>
";
		for ($i=0;$i<$j;$i++) {
			$jobid = $view_char_online[$i][3];
echo "		<TR class=\"topic_title7\" align=\"center\">
			<TD>".$view_char_online[$i][0]."</TD>
			<TD>".$view_char_online[$i][1]."</TD>
			<TD>".$view_char_online[$i][2]."</TD>
			<TD>".$jobname[$jobid]."</TD>
			<TD>".$view_char_online[$i][4]."</TD>
			<TD>".$view_char_online[$i][5]."</TD>
			<TD>".$view_char_online[$i][6]."</TD>
		</TR>
";
		}
echo "	</TBODY>
</TABLE>
";
clmain_body();
	}
}
else if($GET_code=="view_inactiveid") {
if(!isset($GET_st)) $GET_st = 0;
$GET_st = (int)$GET_st;
$page=get_page($GET_st,$CONFIG_AM_per_page);
$sql->result = $sql->execute_query("SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.memory WHERE memory_object=\"activate_id\"",'account_manage.php');
$total = $sql->result();
$query = "SELECT memory_value1,memory_value2 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object=\"activate_id\" ORDER by memory_value1 ASC LIMIT ".mysql_res($GET_st).",".mysql_res($CONFIG_AM_per_page)."";
$sql->result = $sql->execute_query($query,'account_manage.php');$sql->total_query++;
echo"<TABLE width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<TBODY>
		<TR>
			<TD align=\"right\">
";
				get_selectpage($total,$CONFIG_AM_per_page,$page,"index.php?act=account_manage&code=view_inactiveid");
echo "			</TD>
		</TR>
	</TBODY>
</TABLE>
<BR>
";
opmain_body("Inactive Account");
if($sql->count_rows()) {
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<TBODY>
		<TR height=\"27\" class=\"title_bar2\" align=\"center\">
			<TD width=\"5%\"><div class=\"title_face\">No.</div></TD>
			<TD width=\"25%\"><div class=\"title_face\">User ID</div></TD>
			<TD width=\"20%\"><div class=\"title_face\">Registed date</div></TD>
			<TD width=\"50%\"></TD>
		</TR>
";
	$i = 0;
	while ($row = $sql->fetch_row()) {
		$i++;
		$query = "SELECT user_joined FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id=\"$row[memory_value2]\"";
		$sql->result2 = $sql->execute_query($query,'account_manage.php');
		$urow = $sql->fetch_row($sql->result2);
		$user_joined = get_date("j-M y",$urow[user_joined]);
		$userid = get_username($row[memory_value2]);
echo "		<form action=\"index.php?act=account_manage&manage=activeaccount\" method=\"post\" enctype=\"multipart/form-data\" name=\"Account_Manage_Form\">
		<input type=\"hidden\" name=\"account_id\" value=\"$row[memory_value2]\">
		<TR class=\"topic_title7\" align=\"center\">
			<TD>$i</TD>
			<TD>$userid</TD>
			<TD>$user_joined</TD>
			<TD><input type=\"submit\" name=\"Submit\" value=\"Active this account\" class=\"textinput\"><input type=\"button\" name=\"Remove\" value=\"Delete this account\" class=\"textinput\" onClick=\"hyperlink('index.php?act=account_manage&manage=deleteinactiveid&account_id=$row[memory_value2]');\"></TD>
		</TR>
		</form>
";
	}
echo "	</TBODY>
</TABLE>
";
} else {
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<TBODY>
		<TR height=\"27\" class=\"title_bar2\">
			<TD><div class=\"title_face\">CP Message</div></TD>
		</TR>
		<TR class=\"topic_title7\">
			<TD align=\"center\"><B>There isn't an inactive account.</B></TD>
		</TR>
	</TBODY>
</TABLE>
";
}
clmain_body();
}
}
} else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
?>