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
$GET_account_id = (int)$GET_account_id;
if(checkprivilege_action($CP[login_id],g_searching_id)){
	$query = "SELECT account_id,userid,email,lastlogin,last_ip,sex,state FROM $CONFIG_sql_dbname.login WHERE account_id =\"".mysql_res($GET_account_id)."\"";
	$sql->result = $sql->execute_query($query,'searching_id.php');$sql->total_query++;
if ($sql->count_rows()) {
if($GET_code==00) {
	$row = $sql->fetch_row();
	$last_ip = $row['last_ip'];
	//for($i=0,$x=0;$i<15;$i++) { if($last_ip[$i]=='.') $x++; else $ip[$x].=$last_ip[$i]; }

	$query = "SELECT COUNT(*) FROM $CONFIG_sql_dbname.ipbanlist WHERE list=\"".mysql_res($last_ip)."\"";
	$sql->result = $sql->execute_query($query,'searching_id.php');$sql->total_query++;
	$ipban_row = $sql->result();

	$query = "SELECT memory_value2 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"ip_blacklist\" AND memory_value1=\"".mysql_res($last_ip)."\"";
	$sql->result = $sql->execute_query($query,'searching_id.php');$sql->total_query++;
	$blacklist_row = $sql->fetch_row();

	$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.ranking_ignore WHERE account_id =\"".mysql_res($GET_account_id)."\"";
	$sql->result = $sql->execute_query($query,'searching_id.php');$sql->total_query++;

	if($row[sex] == 'M') {
		$M_Select='selected';
		$F_Select='';
	} else {
		$M_Select='';
		$F_Select='selected';
	}
	if($row[state] == '5') {
		$Block_Text='Unblock this ID';
		$Blockallid_Text='Unblock all ID like this IP';
	} else {
		$Block_Text='Block this ID';
		$Blockallid_Text='Block all ID like this IP';
	}
	if ($ipban_row > 0) {
		$Blockip_Text='Unblock this IP';	
	} else {
		$Blockip_Text='Block this IP';
	}
	if ($blacklist_row[memory_value2] == "block") {
		$Blacklist_Text='Unblock from this site';
	} else {
		$Blacklist_Text='Block from this site';
	}
	if ($sql->result()) {
		$Ignore_Ranking_Text='Remove Ignore Player Ranking';
	} else {
		$Ignore_Ranking_Text='Ignore Player Ranking';
	}
opmain_body("Searching Account ID : $GET_account_id");
echo "
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
	<TBODY>
	<form action=\"index.php?act=searching_id&code=01&account_id=$GET_account_id\" method=\"post\" enctype=\"multipart/form-data\" name=\"Searching_ID\" OnSubmit=\"\">
		<TR class=\"topic_title5\">
			<TD width=\"100%\" colspan=\"2\">
				<div class=\"title_face3\">
					<B>Account ID Settings</B>
				</div>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD width=\"15%\">
				<div class=\"title_face3\">
					<B>ID :</B>
				</div>
			</TD>
			<TD width=\"85%\">
				<a href=\"index.php?showuser=".md5($row[account_id])."\">$row[userid]</a> <a href=\"index.php?act=account_manage&code=view_account&searchby=userid&search_value=$row[userid]&ctype=anyword\"><i>&lt;Manage Account&gt;</i></a>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD>
				<div class=\"title_face3\">
					<B>Last Login :</B>
				</div>
			</TD>
			<TD>
				<input name=\"lastlogin\" type=\"text\" size=\"24\" class=\"textinput\" value=\"$row[lastlogin]\" readonly=\"true\">
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD>
				<div class=\"title_face3\">
					<B>Sex :</B>
				</div>
			</TD>
			<TD>
				<select name=\"sex\" class=\"textinput\">
					<option value=\"M\" ".$M_Select.">Male</option>
					<option value=\"F\" ".$F_Select.">Female</option>
				</select>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD>
				<div class=\"title_face3\">
					<B>E-MAIL :</B>
				</div>
			</TD>
			<TD>
				<input name=\"email\" type=\"text\" size=\"24\" class=\"textinput\" value=\"$row[email]\">
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD>
				<div class=\"title_face3\">
					<B>Last IP :</B>
				</div>
			</TD>
			<TD>
				<input name=\"last_ip\" type=\"text\" size=\"24\" class=\"textinput\" value=\"$row[last_ip]\" readonly=\"true\">
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD colspan=2 align=\"center\">
				<input type=\"submit\" name=\"Submit\" value=\"Save\" class=\"textinput\">
				<input type=\"reset\" name=\"Reset\" value=\"Restore\" class=\"textinput\">
			</TD>
		</TR>
	</form>
	<form name=\"Select_Action_ID\" method=\"post\" enctype=\"multipart/form-data\">
		<TR class=\"topic_title5\">
			<TD colspan=2 align=\"center\">
				<select name=\"Action\" class=\"textinput\" OnChange=document.location.replace(\"\"+document.Select_Action_ID.Action.value+\"\");>
					<option value=\"index.php?act=searching_id&account_id=$GET_account_id\" selected>Select Action</option>
					<option value=\"index.php?act=searching_id&code=02&account_id=$GET_account_id\">$Block_Text</option>
					<option value=\"index.php?act=searching_id&code=04&account_id=$GET_account_id\">$Blockip_Text</option>
					<option value=\"index.php?act=searching_id&code=05&account_id=$GET_account_id\">$Blockallid_Text</option>
					<option value=\"index.php?act=searching_id&code=06&account_id=$GET_account_id\">$Blacklist_Text</option>
					<option value=\"index.php?act=searching_id&code=03&account_id=$GET_account_id\">$Ignore_Ranking_Text</option>
				</select>
			</TD>
		</TR>
	</form>
	</TBODY>
</TABLE>
";
clmain_body();
}
else if($GET_code==01) {
	if (isMailform($POST_email)) {
		$POST_sex = ($POST_sex=='M')?"M":"F";
		$sql->execute_query("UPDATE $CONFIG_sql_dbname.login SET sex=\"".mysql_res($POST_sex)."\",email=\"".mysql_res($POST_email)."\" WHERE account_id=\"".mysql_res($GET_account_id)."\"",'searching_id.php');
		header_location("index.php?act=searching_id&account_id=$GET_account_id");
	} else {
		redir("index.php?act=searching_id&account_id=".$GET_account_id."",$lang['Reg_attn_9'],3);
	}
}
else if($GET_code==02) {
	$row = $sql->fetch_row();
	if ($row[state] == '5') $state = '0'; else $state = '5';
	$sql->execute_query("UPDATE $CONFIG_sql_dbname.login SET state=\"$state\" WHERE account_id=\"".mysql_res($GET_account_id)."\"",'searching_id.php');
	header_location("index.php?act=searching_id&account_id=$GET_account_id");
}
else if($GET_code==03) {
	$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.ranking_ignore WHERE account_id =\"".mysql_res($GET_account_id)."\"";
	$sql->result = $sql->execute_query($query,'searching_id.php');
	if ($sql->result()) {
		$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.ranking_ignore WHERE account_id =\"".mysql_res($GET_account_id)."\"",'searching_id.php');
	} else {
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.ranking_ignore (account_id) values (\"".mysql_res($GET_account_id)."\")",'searching_id.php');
	}
	header_location("index.php?act=searching_id&account_id=$GET_account_id");
}
else if($GET_code==04) {
	$row = $sql->fetch_row();
	$last_ip = $row[last_ip];
	//for($i=0,$x=0;$i<15;$i++) { if($last_ip[$i]=='.') $x++; else $ip[$x].=$last_ip[$i]; }
	//$iplist = "list=\"$ip[0].*.*.*\" OR list=\"$ip[0].$ip[1].*.*\" OR list=\"$ip[0].$ip[1].$ip[2].*\" OR list=\"$ip[0].$ip[1].$ip[2].$ip[3]\"";
	$iplist = "list=\"".mysql_res($last_ip)."\"";
	$query = "SELECT COUNT(*) FROM $CONFIG_sql_dbname.ipbanlist WHERE ".$iplist."";
	$sql->result = $sql->execute_query($query,'searching_id.php');
	if ($sql->result()) {
		$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.ipbanlist WHERE ".$iplist."",'searching_id.php');
	} else {
		$sql->execute_query("INSERT INTO $CONFIG_sql_dbname.ipbanlist (list,btime,rtime,reason) values (\"".$last_ip."\",NOW() ,\"9999-12-31 00:00:00\",\"This ip had banned from sgcp.\")",'searching_id.php');
	}
	header_location("index.php?act=searching_id&account_id=$GET_account_id");
}
else if($GET_code==05) {
	$row = $sql->fetch_row();
	if ($row[state] == '5') $state = '0'; else $state = '5';
	$sql->execute_query("UPDATE $CONFIG_sql_dbname.login SET state=\"".mysql_res($state)."\" WHERE last_ip=\"".mysql_res($row['last_ip'])."\"",'searching_id.php');
	header_location("index.php?act=searching_id&account_id=$GET_account_id");
} else if($GET_code==06) {
	$row = $sql->fetch_row();
	$last_ip = $row[last_ip];
	$query = "SELECT memory_value2 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"ip_blacklist\" AND memory_value1=\"".mysql_res($last_ip)."\"";
	$sql->result = $sql->execute_query($query,'searching_id.php');
	$blacklist_row = $sql->fetch_row();
	if ($sql->count_rows()) {
		if ($blacklist_row[memory_value2] == 'block') $state = 'unblock'; else $state = 'block';
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.memory SET memory_value2=\"".mysql_res($state)."\" WHERE memory_object=\"ip_blacklist\" AND memory_value1=\"".mysql_res($last_ip)."\"",'searching_id.php');
	} else {
		$blacklist_code = md5(uniqid(microtime()));
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.memory (memory_object,memory_value1,memory_value2,memory_value3) values (\"ip_blacklist\",\"".mysql_res($last_ip)."\",\"block\",\"".mysql_res($blacklist_code)."\")",'searching_id.php');
	}
	header_location("index.php?act=searching_id&account_id=$GET_account_id");
} else {
	redir("index.php?act=idx","$lang[Error]",3);
}
} else {
	redir("index.php?act=idx","$lang[Error]",3);
}

} else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
?>
