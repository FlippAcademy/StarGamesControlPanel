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
require "install/php-files/upgrade.php";
if ($GET_act=="login_action") {
	if (isAlphaNumeric($POST_LG_USER) && isAlphaNumeric($POST_LG_PASS)) {
		$POST_LG_PASS = checkmd5($CONFIG_md5_support,$POST_LG_PASS);
		$query = "SELECT us.display_name,account_id,userid,user_pass FROM $CONFIG_sql_dbname.login
				LEFT JOIN $CONFIG_sql_cpdbname.user_profile us ON (us.user_id=account_id)
			WHERE userid = \"".mysql_res($POST_LG_USER)."\" AND user_pass = \"".mysql_res($POST_LG_PASS)."\"";
		$sql->result = $sql->execute_query($query,"action.php");$sql->total_query++;
		$rows = $sql->fetch_row();
		if (!$sql->count_rows()>0) {
			$display = $lang[login_wrong];
		} else if($POST_LG_USER=="s1" || $POST_LG_USER=="s2" || $POST_LG_USER=="s3" || $POST_LG_USER=="s4" || $POST_LG_USER=="s5") {
			$display = $lang[login_wrong];
		} else {
			$pass = checkmd5($CONFIG_md5_support?0:1,$rows[user_pass]);
			if($CONFIG_save_type==1) {
				$_SESSION["loginname"] = md5($rows[account_id]);
				$_SESSION["loginpass"] = $pass;
			} else {
				CP_setCookie("loginname", md5($rows['account_id']) );
				CP_setCookie("loginpass", $pass );
			}
			$display = sprintf($lang[login_right],get_displayname($rows['display_name']));
		}
	} else
		$display = $lang[login_wrong];
}
if ($GET_act=="logout") {
	if($CONFIG_save_type==1) {
		session_unregister(loginname);
		session_unregister(loginpass);
	} else {
		CP_removeCookie("loginname");
		CP_removeCookie("loginpass");
	}
	$display = "$lang[logout]";
}

include_once "user_profile.php";
include_once "log_post.php";

if ($GET_act=="change_profile" && $CONFIG_change_password && $GET_code==01 && $STORED_loginname && $STORED_loginpass && length($POST_password,4,24) && length($POST_confirmpass,4,24)
	&& isAlphaNumeric($POST_password) && isAlphaNumeric($POST_confirmpass)) {
	$change_fail = 0;
	if ($CONFIG_change_password_with_sls && length($POST_slspassword,4,24))
		$check_sls = 1;
	else if ($CONFIG_change_password_with_sls && !length($POST_slspassword,4,24)) {
		$change_fail = 1;	
		$check_sls = 1;
	} else
		$check_sls = 0;
	if(!$change_fail) {
		$password = checkmd5($CONFIG_md5_support,$POST_password);
		$query = "SELECT account_id FROM $CONFIG_sql_dbname.login WHERE account_id = \"".$CP['login_id']."\" AND user_pass = \"".mysql_res($password)."\" AND email =\"".mysql_res($CP[login_mail])."\"";
		$sql->result = $sql->execute_query($query,'action.php');$sql->total_query++;
		$count1 = $sql->count_rows();

		if($check_sls) {
			$query = "SELECT user_sls_pass FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id = \"".$CP['login_id']."\"";
			$sql->result = $sql->execute_query($query,'action.php');$sql->total_query++;
			$row = $sql->fetch_row();
		}

		if (!$count1>0) {
			$display = $lang[change_wrong_pass];
		} else if (empty($row[user_sls_pass]) && $check_sls) {
			$display ="$lang[no_sls_pass]<BR><BR><a href=\"index.php?act=sls&code=02\">$lang[make_sls_pass]</a>";
		} else if ($POST_slspassword != $row[user_sls_pass] && $check_sls) {
			$display = $lang[change_wrong_pass];
		} else {
			$password2 = mysql_res(checkmd5($CONFIG_md5_support,$POST_confirmpass));
			if($CONFIG_md5_support)
				$pass = $password2;
			else
				$pass = md5($password2);
			if($CONFIG_save_type==1) {
				session_register(loginpass);
				$_SESSION["loginpass"] = $pass;
			} else {
				setcookie("loginpass",$pass,time()+60*60*24*30);
			}
			$sql->execute_query("UPDATE $CONFIG_sql_dbname.login SET user_pass = \"".$password2."\" WHERE account_id = \"".$CP['login_id']."\" ",'action.php');$sql->total_query++;
			$display ="$lang[change_right_pass]";
		}
	}
}
$time_check=$CP[time]-300;
if ($STORED_loginname && $STORED_loginpass) {
	$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_profile SET user_online=\"1\", user_last_login=\"".$CP['time']."\" WHERE user_id = \"".$CP['login_id']."\"",'action.php',0);
}
$sql->result = $sql->execute_query("SELECT memory_value1 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"last_check_users\"",'action.php',0);
if (!$sql->count_rows())
	$sql->result = $sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.memory ( `memory_object` , `memory_value1` ) VALUES (\"last_check_users\" , ".$CP['time'].")",'action.php',0);
else {
	$last_check_users = $sql->fetch_row();
	if (($CP[time] - $last_check_users[memory_value1]) >= 300) {
		$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.user_online WHERE time < \"".$time_check."\"",'action.php',0);
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_profile SET user_online=\"0\" WHERE user_last_login < \"".$time_check."\" AND user_online != \"0\"",'action.php',0);
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.memory SET memory_value1=\"".$CP['time']."\" WHERE memory_object=\"last_check_users\"",'action.php',0);
	}
}
$sql->result = $sql->execute_query("SELECT count(*) FROM $CONFIG_sql_cpdbname.user_online WHERE session=\"".$CP['ip_address']."\"",'action.php',0);
$session_check = $sql->result();

if ($session_check==0) {
	$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.user_online VALUES (\"".$CP['ip_address']."\",".$CP['time'].")",'action.php',0);
} else {
	$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_online SET time=\"".$CP['time']."\" WHERE session=\"".$CP['ip_address']."\"",'action.php',0);
}

$sql->result = $sql->execute_query("SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.user_online",'action.php',0);
$user_online = $sql->result();
$query = "SELECT memory_value1 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"most_users_online\"";
$sql->result = $sql->execute_query($query,'action.php');
$countuser_row = $sql->fetch_row();

if ($user_online > $countuser_row[memory_value1]) {
	if($sql->count_rows())
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.memory SET memory_value1=\"".$user_online."\", memory_value2=\"".$CP['time']."\" WHERE memory_object=\"most_users_online\"",'action.php',0);
	else
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.memory (memory_object,memory_value1,memory_value2) VALUES (\"most_users_online\",$user_online,".$CP['time'].")",'action.php',0);
}
require_once "action_link.php";
require_once "header.php";
if($GET_act=="forum" || $GET_showforum || $GET_act=="post" ||
$GET_showtopic || $GET_act=="insert_topic" || $GET_act=="mod" ||
$GET_act=="account_manage" || $GET_showuser){
	$fr_group="1";
	$table_class = "";
} else {
	$fr_group="0";
	$table_class = " class=\"table_main_right\"";
	include_once "menuleft.php";
}
if (!strstr($CONFIG_width,'%')) {
	if ($fr_group) $mwidth = $CONFIG_width; else $mwidth = $CONFIG_width - 213;
} else {
	if ($fr_group) $mwidth = "100%"; else $mwidth = ($CONFIG_width*1000/100) - 210;
}
$height=$CONFIG_height - 40;
echo "
	<TD vAlign=\"top\">
<TABLE width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\"".$table_class."><TR><TD>
<TABLE width=\"$mwidth\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
	<TR>
		<TD vAlign=\"top\">
			<TABLE width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
				<TR height=\"20\">
					<TD><img src=\"theme/$STORED[THEME]/templates/main/header_main_01.gif\"></TD><TD background=\"theme/$STORED[THEME]/templates/main/header_main_02.gif\" width=\"100%\"></TD ><TD><img src=\"theme/$STORED[THEME]/templates/main/header_main_03.gif\"></TD>
				</TR>
				<TR height=\"$height\">
					<TD width=\"20\" background=\"theme/$STORED[THEME]/templates/main/float_main_01.gif\"></TD>
					<TD vAlign=\"top\" background=\"theme/$STORED[THEME]/templates/main/float_main_02.gif\">
						<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
							<TR>
								<TD vAlign=\"top\">
";
									include_once "$CP[file_include]";
echo "
								</TD>
							</TR>
						</TABLE>
					</TD>
					<TD width=\"20\" background=\"theme/$STORED[THEME]/templates/main/float_main_03.gif\"></TD>
				</TR>
				<TR height=\"20\">
					<TD><img src=\"theme/$STORED[THEME]/templates/main/footer_main_01.gif\"></TD><TD background=\"theme/$STORED[THEME]/templates/main/footer_main_02.gif\"></TD ><TD><img src=\"theme/$STORED[THEME]/templates/main/footer_main_03.gif\"></TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
</TABLE>
</TD></TR></TABLE>
	</TD>
</TR>
";
require "footer.php";
?>
