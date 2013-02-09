<?php
if(!$SERVER['system_safe']) exit();
getglobalvar(1);
if ($STORED_loginname && $STORED_loginpass) {
if($CONFIG_md5_support) {
	$query = "SELECT account_id,sex,email FROM $CONFIG_sql_dbname.login WHERE BINARY md5(`account_id`) = BINARY  \"".mysql_res($STORED_loginname)."\" AND user_pass = \"".mysql_res($STORED_loginpass)."\"";
} else {
	$query = "SELECT account_id,sex,email FROM $CONFIG_sql_dbname.login WHERE BINARY md5(`account_id`) = BINARY  \"".mysql_res($STORED_loginname)."\" AND md5(user_pass) = \"".mysql_res($STORED_loginpass)."\"";
}
$sql->result = $sql->execute_query($query,'user_profile.php',0);$sql->total_query++;
if ($sql->count_rows()>0) {
	$row = $sql->fetch_row();
	$CP['login_id'] = $row["account_id"];
	$CP['login_name'] = get_username($row["account_id"]);
	$CP['login_sex'] = $row["sex"];
	$CP['login_mail'] = $row["email"];
	if(empty($CP['login_id'])) {
		if($CONFIG_save_type==1) {
			session_unregister(loginname);
			session_unregister(loginpass);
		} else {
			CP_removeCookie("loginname");
			CP_removeCookie("loginpass");
		}
		header("index.php?act=login");
	}
	if(checkprivilege($CP['login_id'])==1){
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.privilege (account_id,privilege) VALUES (\"".$CP['login_id']."\",'2')",'user_profile.php');
	}
	$CP['login_nname'] = $CP['login_name'];$CP['login_logout'] = "<img height=\"1\" width=\"10\"><a href=\"index.php?act=logout\" title=\"Log Out\"><font class=\"m_title\">$lang[Logout]</font></a>";
	$query = "SELECT display_name,user_time_offset FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id = \"".$CP['login_id']."\"";
	$sql->result = $sql->execute_query($query,'user_profile.php');$sql->total_query++;
	if (!$sql->count_rows()) {
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.user_profile (user_id,display_name,user_time_offset,user_avatar,user_avatar_width,user_avatar_height,user_joined) VALUES (\"".$CP['login_id']."\",\"".$CP['login_nname']."\",\"".$CONFIG_time_offset."\",\"\",0,0,\"".$CP['time']."\")",'user_profile.php');$sql->total_query++;
	} else {
		$row = $sql->fetch_row();
		if (empty($row[display_name]))
			$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_profile SET display_name = \"".$CP['login_name']."\" WHERE user_id=\"".$CP['login_id']."\"",'user_profile.php',0);
		if (empty($row[user_time_offset]) && $row[user_time_offset] != '0') {
			$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_profile SET user_time_offset=\"".$CONFIG_time_offset."\" WHERE user_id=\"".$CP['login_id']."\"",'user_profile.php',0);
			$CP['time_offset'] = $CONFIG_time_offset;
		} else {
			$CP['time_offset'] = $row[user_time_offset];
		}
	}
} else {
	$CP['login_nname'] = $lang[Guest];
	$CP['login_logout']='';
	$STORED_loginname='';
	$STORED_loginpass='';
	$CP['time_offset'] = $CONFIG_time_offset;
	$CP[login_id]='';
	$CP['login_name']='';
	$CP['login_sex']='';
	$CP['login_mail']='';
}
} else {
	$CP['login_nname'] = $lang[Guest];
	$CP['login_logout']='';
	$STORED_loginname='';
	$STORED_loginpass='';
	$CP['time_offset'] = $CONFIG_time_offset;
	$CP[login_id]='';
	$CP['login_name']='';
	$CP['login_sex']='';
	$CP['login_mail']='';
}
?>