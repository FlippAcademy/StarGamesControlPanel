<?php
if(!$SERVER['system_safe']) exit();
if ($STORED_loginname && $STORED_loginpass) {
if ($GET_code == '01') {}
else if ($GET_code == '02' && strstr($POST_email1,'@') !== false && strstr($POST_email2,'@') !== false && length($POST_password,4,24) && $CONFIG_change_email) {
	$POST_password = mysql_res(checkmd5($CONFIG_md5_support,$POST_password));
	$POST_email1 = mysql_res($POST_email1);
	$POST_email2 = mysql_res($POST_email2);
	$query = "SELECT account_id FROM $CONFIG_sql_dbname.login WHERE account_id = \"".$CP[login_id]."\" AND user_pass = \"".$POST_password."\" AND email =\"".$POST_email1."\"";
	$sql->result = $sql->execute_query($query,'change_profile.php');$sql->total_query++;
	$count1 = $sql->count_rows();

	$query = "SELECT account_id FROM $CONFIG_sql_dbname.login WHERE email = \"".$POST_email2."\"";
	$sql->result = $sql->execute_query($query,'change_profile.php');$sql->total_query++;
	$count2 = $sql->count_rows();
	if (!$count1>0) {
		$display ="$lang[change_wrong_email]";
	} else if($count2) {
		$display = "$lang[Reg_emailused]";
	} else {
		$sql->execute_query("UPDATE $CONFIG_sql_dbname.login SET email = \"".$POST_email2."\" WHERE account_id = \"".$CP['login_id']."\" ",'change_profile.php');$sql->total_query++;
		$display ="$lang[change_right_email]";
	}
}
else if ($GET_code == '03') {
	$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id = \"".$CP['login_id']."\" ";
	$sql->result = $sql->execute_query($query,'change_profile.php');$sql->total_query++;
	if (!$sql->result() || $POST_avatar_width > 150 || $POST_avatar_height > 150) {
		$display ="$lang[change_wrong_avatar]";
	} else {
		//$avatars_upload = upload_avatars($_FILES[upavatar]);
		$avatars_upload = upload_avatars("");
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_profile SET user_avatar=\"".mysql_res($avatars_upload['url'])."\",user_avatar_width=\"".mysql_res($POST_avatar_width)."\",user_avatar_height=\"".mysql_res($POST_avatar_height)."\" WHERE user_id = \"".$CP['login_id']."\" ",'change_profile.php');$sql->total_query++;
		$display ="$avatars_upload[error] $lang[change_right_avatar]";
	}
}
else if ($GET_code == '04' ) {
	$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id = \"".$CP['login_id']."\" ";
	$sql->result = $sql->execute_query($query,'change_profile.php');$sql->total_query++;
	if (!$sql->result()) {
		$display ="$lang[change_wrong_signature]";
	} else if (!length($POST_t_mes,0,$CONFIG_max_signature_length)) {
		$display ="Your signature is more than $CONFIG_max_signature_length characters";
	} else {
		$POST_t_mes = checkstring($POST_t_mes,1);
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_profile SET user_signature_message=\"".$POST_t_mes."\" WHERE user_id = \"".$CP['login_id']."\" ",'change_profile.php');$sql->total_query++;
		$display = "$lang[change_right_signature]";
	}
}
else if ($GET_code == '05' && $CONFIG_change_slspassword && length($POST_slspassword,4,24) && length($POST_slspassword2,4,24)) {
	$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id = \"".$CP['login_id']."\" AND user_sls_pass = \"".mysql_res($POST_slspassword)."\"";
	$sql->result = $sql->execute_query($query,'change_profile.php');$sql->total_query++;
	if (!$sql->result()) {
		$display ="$lang[change_wrong_sls_pass]";
	} else {
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_profile SET user_sls_pass = \"".mysql_res($POST_slspassword2)."\" WHERE user_id = \"".$CP['login_id']."\" ",'change_profile.php');$sql->total_query++;
		$display ="$lang[change_right_sls_pass]";
	}
}
else if ($GET_code == '06') {
	$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id = \"".$CP['login_id']."\"";
	$sql->result = $sql->execute_query($query,'change_profile.php');$sql->total_query++;
	if (!$sql->result()) {
		$display ="$lang[change_wrong_time_offset]";
	} else {
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_profile SET user_time_offset = \"".mysql_res($POST_u_timezone)."\" WHERE user_id = \"".$CP['login_id']."\" ",'change_profile.php');$sql->total_query++;
		$display ="$lang[change_right_time_offset]";
	}
}
else if ($GET_code == '07' && length($POST_display_name,4,24)) {
	$POST_password = mysql_res(checkmd5($CONFIG_md5_support,$POST_password));
	$POST_display_name = checkstring($POST_display_name,1);
	$query = "SELECT userid FROM $CONFIG_sql_dbname.login WHERE account_id = \"".$CP['login_id']."\" AND user_pass = \"".$POST_password."\"";
	$sql->result = $sql->execute_query($query,'change_profile.php');$sql->total_query++;
	$row = $sql->fetch_row();
	if (!$sql->count_rows()) {
		$display = "$lang[change_wrong_display_name]";
	} else {
		$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.user_profile WHERE display_name = \"".mysql_res($POST_display_name)."\"";
		$sql->result = $sql->execute_query($query,'change_profile.php');$sql->total_query++;
		$count1 = $sql->result();

		$query = "SELECT COUNT(*) FROM $CONFIG_sql_dbname.login WHERE userid = \"".$POST_display_name."\" AND userid != \"".$row["userid"]."\"";
		$sql->result = $sql->execute_query($query,'change_profile.php');$sql->total_query++;
		$count2 = $sql->result();
		if($count1 || $count2)
			$display = sprintf("$lang[change_wrong_display_name2]",$POST_display_name);
		else {
			$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_profile SET display_name = \"".$POST_display_name."\" WHERE user_id = \"".$CP['login_id']."\"",'change_profile.php');$sql->total_query++;
			$display = "$lang[change_right_display_name]";
		}
	}
}
else
	$display ="$lang[Error]";
redir("index.php?act=login","$display",3);
}
?>
