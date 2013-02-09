<?php
if(!$SERVER['system_safe']) exit();
if(checkprivilege_action($CP[login_id],g_delete_id) && $CONFIG_deluser_mode) {
$day = $POST_day?$POST_day:($GET_day?$GET_day:90);
$maxusers = $POST_maxusers?$POST_maxusers:($GET_maxusers?$GET_maxusers:100);
opmain_body("Delete User");
echo "
<script  language=\"JavaScript\">
function deleteid() {
	if (confirm('Are you sure you want to delete?'))
		return true;
	else
		return false;
}
function Showusers() {
	var A1 = document.Deluser.day.value;
	var A2 = document.Deluser.maxusers.value;
	hyperlink('index.php?act=deluser&code=02&day='+A1+'&maxusers='+A2+'');
return false;
}
</script>
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<form action=\"index.php?act=deluser&code=01\" method=\"post\" enctype=\"multipart/form-data\" name=\"Deluser\" OnSubmit=\"return deleteid()\">
	<TBODY>
		<TR class=\"topic_title5\">
			<TD colspan=\"2\">$lang[Delusers_input]</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD width=\"25%\">$lang[Delusers_input_time]</TD>
			<TD width=\"75%\"><input type=\"text\" name=\"day\" value=\"".$day."\" size=\"5\" maxlength=\"10\" class=\"textinput\"> $lang[Delusers_day]</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD>$lang[Delusers_input_maxusers]</TD>
			<TD><input type=\"text\" name=\"maxusers\" value=\"".$maxusers."\" size=\"5\" maxlength=\"5\" class=\"textinput\"> $lang[Delusers_maxusers]</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD colspan=\"2\"><input name=\"delalldata\" type=\"checkbox\" value=\"1\" checked> $lang[Delusers_check_all]</TD>
		</TR>
		<TR class=\"topic_title5\">
			<TD colspan=\"2\">
				<input type=\"button\" name=\"Button\" value=\"$lang[Delusers_show_users]\" class=\"textinput\" onClick=\"Showusers();\">
				<input type=\"submit\" name=\"Submit\" value=\"$lang[Delusers_del_users]\" class=\"textinput\">
			</TD>
		</TR>
";
if ($GET_code==01 && $POST_day && $POST_maxusers) {
ini_set("max_execution_time",60*10);
$tmp_lastlogin = $CP[time]-($POST_day*3600*24);
$lastlogin = date("Y-m-d H:i:s",$tmp_lastlogin);
$query = "SELECT account_id,userid,lastlogin,logincount FROM $CONFIG_sql_dbname.login WHERE lastlogin < \"".mysql_res($lastlogin)."\" AND sex != \"S\" AND logincount > \"0\" ORDER by lastlogin ASC LIMIT ".mysql_res($POST_maxusers)."";
$sql->result = $sql->execute_query($query,'deluser.php');$sql->total_query++;
echo "		<TR class=\"topic_title6\">
			<TD colspan=\"2\">
";
while ($row = $sql->fetch_row()) {
	$diffday = ($CP[time] - strtotime($row[lastlogin]))/3600/24;
	$aid = $row["account_id"];
	$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.login WHERE account_id =\"$aid\"",'deluser.php');
	if($POST_delalldata == 1) {
		$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id =\"$aid\"",'deluser.php');
		$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.global_reg_value WHERE account_id =\"$aid\"",'deluser.php');
		$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.guild_member WHERE account_id =\"$aid\"",'deluser.php');
		$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.party WHERE leader_id =\"$aid\"",'deluser.php');
		$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.storage WHERE account_id =\"$aid\"",'deluser.php');
		$query = "SELECT char_id FROM $CONFIG_sql_dbname.char WHERE account_id=\"$aid\"";
		$sql->result2 = $sql->execute_query($query,'deluser.php');
		while ($row2 = $sql->fetch_row($sql->result2)) {
			$cid = $row2[char_id];
			$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.cart_inventory WHERE char_id =\"$cid\"",'deluser.php');
			$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.char WHERE char_id =\"$cid\"",'deluser.php');
			$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.friends WHERE char_id =\"$cid\"",'deluser.php');
			$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.global_reg_value WHERE char_id =\"$cid\"",'deluser.php');
			$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.inventory WHERE char_id =\"$cid\"",'deluser.php');
			$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.memo WHERE char_id =\"$cid\"",'deluser.php');
			$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.sc_data WHERE char_id =\"$cid\"",'deluser.php');
			$sql->execute_query("DELETE FROM $CONFIG_sql_dbname.skill WHERE char_id =\"$cid\"",'deluser.php');
		}
	}
	printf("Deleted userid: <font color=\"red\"><B>%s</B></font> (<B>%d</B> days)<BR>\n",$row[userid],$diffday);
}
echo "			</TD>
		</TR>
";
} else if ($GET_code==02 && $GET_day && $GET_maxusers) {
$tmp_lastlogin = $CP[time]-($GET_day*3600*24);
$lastlogin = date("Y-m-d H:i:s",$tmp_lastlogin);
$query = "SELECT account_id,userid,lastlogin,logincount FROM $CONFIG_sql_dbname.login WHERE lastlogin < \"".mysql_res($lastlogin)."\" AND sex != \"S\" AND logincount > \"0\" ORDER by lastlogin ASC LIMIT ".mysql_res($GET_maxusers)."";
$sql->result = $sql->execute_query($query,'deluser.php');$sql->total_query++;
echo "		<TR>
			<TD colspan=\"2\">
				<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
					<TBODY>
						<TR height=\"27\" class=\"title_bar2\" align=\"center\">
							<TD><div class=\"title_face\">No.</div></TD>
							<TD><div class=\"title_face\">User ID</div></TD>
							<TD><div class=\"title_face\">Last Login (days ago)</div></TD>
							<TD><div class=\"title_face\">Login Count</div></TD>
						</TR>
";
$i=0;
while ($row = $sql->fetch_row()) {
	$i++;
	$diffday = ($CP[time] - strtotime($row[lastlogin]))/3600/24;
	$diffday = (int)$diffday;
echo "						<TR class=\"topic_title7\" align=\"center\">
							<TD>".$i."</TD>
							<TD>".$row[userid]."</TD>
							<TD>".$row[lastlogin]." (".$diffday.")</TD>
							<TD>".$row[logincount]."</TD>
						</TR>
";
}
echo "	</TBODY>
</TABLE>
";
}
echo "	</TBODY>
	</form>
</TABLE>
";
clmain_body();
} else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
?>