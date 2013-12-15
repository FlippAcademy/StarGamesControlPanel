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
if($GET_code==00) {
opmain_body("Registration Agreement");
echo "
<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\" class=\"emptytable3\">
	<TBODY>
		<TR class=\"topic_title5\">
			<TD width=\"100%\" height=\"25\"></TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD class=\"title_face4\" vAlign=\"top\">
";
				include_once "pages/registration_rules.php";
echo "
			</TD>
		</TR>
		<form name=\"Register\">
		<TR class=\"topic_title5\">
			<TD width=\"100%\" align=\"center\">
				<input name=\"Button\" value=\"$lang[Reg_confirm]\" type=\"button\" onClick=\"document.Register.Button.disabled=true; hyperlink('index.php?act=register&code=01&readed=true');\" class=\"textinput\">
			</TD>
		</TR>
		</form>
	<TBODY>
</TABLE>
";
clmain_body();
}
else if($GET_code==01) {
if ($GET_readed != 'true')
	header("location:index.php?act=register");
if (!$CONFIG_register_mode) {
	redir("index.php?act=idx","$lang[Reg_closed]",3);
} else {
	if ($CONFIG_security_mode) {
		$sc_time = $CP['time'] - (60*30);
		$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.security_code WHERE sc_time < ".mysql_res($sc_time)."",'register.php',0);
		$sc_id = md5(uniqid(microtime()));
		$sc_code = generate_password(6);
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.security_code VALUES (\"".mysql_res($sc_id)."\",\"".mysql_res($sc_code)."\",".$CP['time'].")",'register.php',0);
	}
opmain_body("Registration Form");
echo "
<SCRIPT language=\"JavaScript\" src=\"function/registration.js\"></SCRIPT>
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" align=\"center\" class=\"emptytable3\">
	<TBODY>
	<form action=\"index.php?act=register&code=02\" method=\"post\" enctype=\"multipart/form-data\" name=\"regis_form\" onsubmit=\"return CheckRegis();\">
		<TR class=\"topic_title5\" height=\"25\">
			<TD colspan=\"2\"></TD>
		</TR>
		<TR id=\"attn_userid\" class=\"reg_attention\" style=\"display:none\"><TD id=\"attn_userid_\" colspan=\"2\" style=\"font-weight:bold\"></TD></TR>
		<TR id=\"attn_pass\" class=\"reg_attention\" style=\"display:none\"><TD id=\"attn_pass_\" colspan=\"2\" style=\"font-weight:bold\"></TD></TR>
		<TR id=\"attn_pass2\" class=\"reg_attention\" style=\"display:none\"><TD id=\"attn_pass2_\" colspan=\"2\" style=\"font-weight:bold\"></TD></TR>
		<TR id=\"attn_slspass\" class=\"reg_attention\" style=\"display:none\"><TD id=\"attn_slspass_\" colspan=\"2\" style=\"font-weight:bold\"></TD></TR>
		<TR id=\"attn_slspass2\" class=\"reg_attention\" style=\"display:none\"><TD id=\"attn_slspass2_\" colspan=\"2\" style=\"font-weight:bold\"></TD></TR>
		<TR id=\"attn_email\" class=\"reg_attention\" style=\"display:none\"><TD id=\"attn_email_\" colspan=\"2\" style=\"font-weight:bold\"></TD></TR>
		<TR class=\"topic_title6\">
			<TD width=\"50%\" align=\"right\">
				$lang[Reg_id] :
			</TD>
			<TD width=\"50%\">
				<input name=\"userid\" type=\"text\" size=\"20\" maxlength=\"24\" class=\"textinput\" onblur=\"check_reg('userid')\">
				<span id=\"_attn_userid\"></span>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD align=\"right\">
				$lang[Reg_pass] :
			</TD>
			<TD>
				<input name=\"userpass\" type=\"password\" size=\"20\" maxlength=\"24\" class=\"textinput\" onblur=\"check_reg('pass')\">
				<span id=\"_attn_pass\"></span>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD align=\"right\">
				$lang[Reg_pass2] :

			</TD>
			<TD>
				<input name=\"userpass2\" type=\"password\" size=\"20\" maxlength=\"24\" class=\"textinput\" onblur=\"check_reg('pass2')\">
				<span id=\"_attn_pass2\"></span>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD align=\"right\">
				$lang[Reg_sls_pass] :

			</TD>
			<TD>
				<input name=\"userslspass\" type=\"password\" size=\"20\" maxlength=\"24\" class=\"textinput\" onblur=\"check_reg('slspass')\">
				<span id=\"_attn_slspass\"></span>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD align=\"right\">
				$lang[Reg_sls_pass2] :

			</TD>
			<TD>
				<input name=\"userslspass2\" type=\"password\" size=\"20\" maxlength=\"24\" class=\"textinput\" onblur=\"check_reg('slspass2')\">
				<span id=\"_attn_slspass2\"></span>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD align=\"right\">
				$lang[Reg_sex] :

			</TD>
			<TD>
				<select name=\"sex\" class=\"textinput\">
					<option value=\"M\" selected>Male</option>
					<option value=\"F\">Female</option>
				</select>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD align=\"right\">
				$lang[Reg_email] :

			</TD>
			<TD>
				<input name=\"email\" type=\"text\" size=\"20\" class=\"textinput\" onblur=\"check_reg('email')\">
				<span id=\"_attn_email\"></span>
			</TD>
		</TR>
";
	if ($CONFIG_security_mode) {
echo "
		<input name=\"security_id\" type=\"hidden\" value=\"".$sc_id."\">
		<TR class=\"topic_title6\">
			<TD align=\"right\">
				$lang[Reg_security_code] :

			</TD>
			<TD>
				<img src=\"reg_code.php?sc=$sc_id\"><BR>
				<i><a href=\"javascript:ViewSC_Code('$sc_id');\">$lang[Reg_view_sc_code]</a></i>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD align=\"right\">
				$lang[Reg_security_code_confirm] :

			</TD>
			<TD>
				<input name=\"security_code\" type=\"text\" size=\"20\" maxlength=\"6\" class=\"textinput\">
			</TD>
		</TR>
";
	}
echo "
		<TR class=\"topic_title5\">
			<TD align=\"center\" colspan=\"2\">
				<input type=\"submit\" name=\"Submit\" value=\"$lang[Reg_insert]\" class=\"textinput\">
				<input type=\"reset\" name=\"reset\" value=\"$lang[Reg_edit]\" class=\"textinput\">
			</TD>
		</TR>
	</form>
	</TBODY>
</TABLE>
";
clmain_body();
}
}
else if($GET_code==02) {
if (!$CONFIG_register_mode) {
	redir("index.php?act=idx","$lang[Reg_closed]",3);
} else {
	if (empty($POST_userid) && empty($POST_userpass) && empty($POST_email)) {
		redir("index.php?act=register","$lang[Error]",3);
	} else {
		if(
		length($POST_userid,4,24) &&
		length($POST_userpass,4,24) &&
		length($POST_userslspass,4,24) &&
		isMailform($POST_email) &&
		isAlphaNumeric($POST_userid) &&
		isAlphaNumeric($POST_userpass) &&
		isAlphaNumeric($POST_userslspass) &&
		($POST_sex == "M" || $POST_sex == "F"))
		{
			$activeid = '0';
			$active_mes = "";
			$userpass = mysql_res(checkmd5($CONFIG_md5_support,$POST_userpass));
			$POST_email = mysql_res($POST_email);
			$query = "SELECT userid FROM $CONFIG_sql_dbname.login WHERE userid = \"".mysql_res($POST_userid)."\"";
			$sql->result = $sql->execute_query($query,'register.php');$sql->total_query++;
			$count1 = $sql->count_rows();

			$query = "SELECT email FROM $CONFIG_sql_dbname.login WHERE email = \"".$POST_email."\"";
			$sql->result = $sql->execute_query($query,'register.php');$sql->total_query++;
			$count2 = $sql->count_rows();
			if ($CONFIG_security_mode) {
				$query= "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.security_code WHERE sc_id = \"".mysql_res($POST_security_id)."\" AND sc_code = \"".mysql_res($POST_security_code)."\"";
				$sql->result = $sql->execute_query($query,'register.php');$sql->total_query++;
				$count3 = $sql->result();
			}
			if ($count1) {
				redir_back("$lang[Reg_idused_]<BR><BR>$lang[Reg_idused2]");
			} else if ($count2) {
				redir_back("$lang[Reg_emailused]<BR><BR>$lang[Reg_emailused2]");
			} else if ($CONFIG_security_mode && !$count3) {
				redir_back("$lang[Reg_security_code_fail]");
			} else {
				if ($CONFIG_register_mode == '2') {
					$query = "SELECT memory_value1 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object=\"activate_id\" ORDER by memory_value1 DESC LIMIT 1";
					$sql->result = $sql->execute_query($query,'register.php');$sql->total_query++;
					$row_activeid = $sql->fetch_row();
					$active_id = $row_activeid['memory_value1']+1;
					$active_key = md5(uniqid(microtime()));
					$active_url = "http://".$_SERVER[HTTP_HOST]."".$_SERVER[PHP_SELF]."?act=activate&a=".$active_id."&act_key=".$active_key."";
					$mail_subject = "Registration Confirmation ( SGCP user registration confirmation )";
					$mail_messages = sprintf("
$lang[EMA_mes_1]


$lang[EMA_mes_2]


$lang[EMA_mes_3]

$lang[EMA_mes_4]

$lang[EMA_mes_5]

$lang[EMA_mes_3]


$lang[EMA_mes_6]


$active_url


$lang[EMA_mes_7]
%s.
",$CONFIG_server_name,$POST_userid,$POST_userpass,$CONFIG_server_name);
					if(SendMail($POST_email,$mail_subject,$mail_messages)) {
						$active_mes = $lang["EMA_send_success"];
						$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.memory (memory_object,memory_value1,memory_value3) VALUES (\"activate_id\",\"".mysql_res($active_id)."\",\"".mysql_res($active_key)."\")",'register.php');
						$activeid = '2';
					} else
						$activeid = '1';
				}
				if ($activeid=='1')
					redir("index.php?act=register","$lang[EMA_send_fail]",3);
				else {
					if ($activeid=='2') {
						$sql->execute_query("INSERT INTO $CONFIG_sql_dbname.login (userid,user_pass,sex,email,group_id,state) VALUES (\"".mysql_res($POST_userid)."\",\"".$userpass."\",\"".mysql_res($POST_sex)."\",\"".$POST_email."\",0,1)",'register.php');$sql->total_query++;
					} else {
						$sql->execute_query("INSERT INTO $CONFIG_sql_dbname.login (userid,user_pass,sex,email,group_id) VALUES (\"$POST_userid\",\"".$userpass."\",\"".mysql_res($POST_sex)."\",\"".$POST_email."\",0)",'register.php');$sql->total_query++;
					}
					$query = "SELECT account_id,group_id FROM $CONFIG_sql_dbname.login WHERE userid=\"".mysql_res($POST_userid)."\" LIMIT 1";
					$sql->result = $sql->execute_query($query,'register.php');$sql->total_query++;
					$row = $sql->fetch_row();
					$aid = $row[account_id];
					$group_id = $row[group_id];
					if ($activeid=='2') {
						$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.memory SET memory_value2=\"".mysql_res($aid)."\" WHERE memory_object=\"activate_id\" AND memory_value1=\"".mysql_res($active_id)."\" AND memory_value3=\"".mysql_res($active_key)."\"",'register.php');
					}
					$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.user_profile (user_id,display_name,user_sls_pass,user_time_offset ,user_joined) VALUES (\"".mysql_res($aid)."\",\"".mysql_res($POST_userid)."\",\"".mysql_res($POST_userslspass)."\",\"".mysql_res($CONFIG_time_offset)."\",\"".$CP['time']."\")",'register.php');$sql->total_query++;
					$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.privilege (account_id,privilege) VALUES (\"".mysql_res($aid)."\",\"2\")",'register.php');$sql->total_query++;
					if ($CONFIG_log_register)
						$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.register_log (Date,account_id,userid,group_id,ip) VALUES (NOW(),\"".mysql_res($aid)."\",\"".mysql_res($POST_userid)."\",\"".mysql_res($group_id)."\",\"".$CP['ip_address']."\")",'register.php');$sql->total_query++;
					if ($CONFIG_security_mode) {
						$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.security_code WHERE sc_id = \"".mysql_res($POST_security_id)."\"",'register.php',0);
					}
					redir("index.php?act=register","$lang[Reg_success]$active_mes",3);
				}
			}
		} else {
			redir("index.php?act=register","$lang[Error]",3);
		}
	}
}
}
else
	header("location:index.php?act=register");
?>