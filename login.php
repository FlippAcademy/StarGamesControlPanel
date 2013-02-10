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
if (empty($STORED_loginname) && empty($STORED_loginpass)) {
opmain_body("Login Form");
echo "
<SCRIPT language=\"JavaScript\" src=\"function/registration.js\"></SCRIPT>
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" align=\"center\">
	<TBODY>
	<form action=\"index.php?act=login_action\" method=\"post\" enctype=\"multipart/form-data\" name=\"login_form\" onSubmit=\"return CheckLogin()\">
		<TR class=\"topic_title6\">
			<TD width=\"20%\">
				$lang[login_user] :
			</TD>
			<TD width=\"80%\">
				<input name=\"LG_USER\" type=\"text\" size=\"24\" maxlength=\"24\" class=\"textinput\">
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD>
				$lang[login_pass] :
			</TD>
			<TD>
				<input name=\"LG_PASS\" type=\"password\" size=\"24\" maxlength=\"24\" class=\"textinput\">
			</TD>
		</TR>
		<TR class=\"topic_title5\" align=\"center\">
			<TD colspan=\"2\">
				<input type=\"hidden\" name=\"referer\" value=\"$Referer\">
				<input type=\"submit\" name=\"Submit\" value=\"$lang[login]\" class=\"textinput\">
				<input type=\"reset\" name=\"Reset\" value=\"$lang[Resetprivilege]\" class=\"textinput\">
			</TD>
		</TR>
	</form>
	</TBODY>
</TABLE>
";
clmain_body();
} else {
echo "<script type='text/javascript'>
var check_sls = 0;\n";
if ($CONFIG_change_password_with_sls)
echo "check_sls = 1;";
echo "function CheckDisplayname(){var p1 = document.ch_display_name.display_name.value; var p2 = document.ch_display_name.password.value;if (p1.length < 4 || p1.length > 24) {alert('Please enter your display name between 4 - 24 characters.');document.ch_display_name.display_name.focus();return false;}else if (p2.length < 4 || p2.length > 24) {alert('Please enter password between 4 - 24 characters.');document.ch_display_name.password.focus();return false;}else {document.ch_display_name.Submit.disabled=true;return true;}}
function CheckPass(){var p1 = document.ch_pass.password.value; var p2 = document.ch_pass.password2.value; if(check_sls) var p3 = document.ch_pass.slspassword.value; var p4 = document.ch_pass.confirmpass.value;if ((p3.length < 4 || p3.length > 24) && check_sls) {alert('Please enter your SLS password between 4 - 24 characters.');document.ch_pass.slspassword.focus();return false;}else if (p1.length < 4 || p1.length > 24) {alert('Please enter your old password between 4 - 24 characters.');document.ch_pass.password.focus();return false;}else if (p2.length < 4 || p2.length > 24) {alert('Please enter your new password between 4 - 24 characters.');document.ch_pass.password2.focus();return false;}else if (p2 == p1) {alert('Old password does not like new password.');document.ch_pass.password2.focus();return false;}
else if (p4.length < 4 || p4.length > 24) {alert('Please enter confirm password between 4 - 24 characters.');document.ch_pass.confirmpass.focus();return false;}else if (p4 != p2) {alert('Confirm password must be as new password.');document.ch_pass.confirmpass.focus();return false;}else {document.ch_pass.Submit.disabled=true;return true;}}
function CheckSLSPass(){var p1 = document.ch_sls_pass.slspassword.value; var p2 = document.ch_sls_pass.slspassword2.value;if (p1.length < 4 || p1.length > 24) {alert('Please enter your old SLS password between 4 - 24 characters.');document.ch_sls_pass.slspassword.focus();return false;}else if (p2.length < 4 || p2.length > 24) {alert('Please enter your new SLS password between 4 - 24 characters.');document.ch_sls_pass.slspassword2.focus();return false;}else if (p2 == p1) {alert('Old SLS password does not like new SLS password.');document.ch_sls_pass.slspassword2.focus();return false;}else {document.ch_sls_pass.Submit.disabled=true;return true;}}
function CheckEMail(){var m1 = document.ch_email.email1.value;var m2 = document.ch_email.email2.value;var m3 = document.ch_email.password.value;if (m1 == m2) {alert('Old E-Mail does not like new E-Mail.');document.ch_email.email2.focus();return false;}else if (m2.indexOf('@') == -1) {alert('New E-Mail is not right.');document.ch_email.email2.focus();return false;}else if (m3.length < 4 || m3.length > 24) {alert('Please enter your password between 4 - 24 characters.');document.ch_email.password.focus();return false;}else {document.ch_email.Submit.disabled=true;return true;}}
function CheckAvatar(){var A1 = document.ch_avatar.avatar_width.value;var A2 = document.ch_avatar.avatar_height.value;if (A1.length < 1|| A1 > 150) {alert('Your width avatar can not more 150 pixels');document.ch_avatar.avatar_width.focus();return false;}else if (A2.length < 1|| A2 > 150) {alert('Your height avatar can not more 150 pixels');document.ch_avatar.avatar_height.focus();return false;}else {document.ch_avatar.Submit.disabled=true;return true;}}
</script>
";
get_cp_profile($CP[login_id]);
if($CP[login_sex]=='M')
	$sex="$lang[login_male]";
else
	$sex="$lang[login_female]";
opmain_body("User Profile");
echo "
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"topic_title6\">
	<TR>
		<TD>
			<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"10\" class=\"topic_title6\">
				<TR>
					<TD width=\"30%\" align=\"left\">
						<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
							<TR>
								<TD>
									$reply_avatar<BR><BR>
									$rank_title<BR>
									<img src=\"theme/$STORED[THEME]/images/groups/$reply_imgroup\" alt=\"Group Icon\"><BR><BR>
									$lang[Group]: $reply_group<BR>
									$lang[Posts]: $reply_post<BR>
									$lang[Mem_No]: $reply_number<BR>
									$lang[Join]: $reply_joined<BR><BR>
									$status_bar
								</TD>
							</TR>
						</TABLE>
					</TD>
					<TD>
						<TABLE align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
							<TBODY vAlign=\"top\">
								<TR vAlign=\"top\">
									<TD width=\"50%\" align=\"right\">$lang[login_user] :</TD>
									<TD width=\"50%\" align=\"left\"><B>$CP[login_name]</B></TD>
								</TR>
								<TR>
									<TD align=\"right\">$lang[login_pass] :</TD>
									<TD align=\"left\">********</TD>
								</TR>
								<TR>
									<TD align=\"right\">$lang[login_sex] :</TD>
									<TD align=\"left\">$sex</TD>
								</TR>
							</TBODY>
						</TABLE>
					</TD>
				</TR>
			</TABLE>
";
if(!empty($reply_signature)) {
	$reply_signature_ = replace_text($reply_signature);
	opmain_body("Signature",0,'90%');
echo "
			<TABLE width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\">
				<TR class=\"topic_title5\">
					<TD height=\"15\"></TD>
				</TR>
				<TR class=\"topic_title8\">
					<TD>$reply_signature_</TD>
				</TR>
				<TR class=\"topic_title5\">
					<TD height=\"15\"></TD>
				</TR>
			</TABLE>
";
clmain_body();
echo "			<BR>";
}
$query = "SELECT account_id,name,base_level,job_level,class,zeny FROM $CONFIG_sql_dbname.char WHERE account_id =\"".$CP['login_id']."\"";
$sql->result = $sql->execute_query($query,'login.php');$sql->total_query++;
if($sql->count_rows()) {
echo "
			<TABLE width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"topic_title\" align=\"center\">
				<TBODY>
					<TR align=\"center\" class=\"title_bar\" height=\"29\">
						<TD>
							<a class=\"m_title\">Character in user: $CP[login_name]</a>
						</TD>
					</TR>
					<TR>
						<TD>
							<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=1\"\" align=\"center\">
								<TBODY>
									<TR align=\"center\" class=\"topic_title3\" style=\"font-weight: bold;\">
										<TD>No.</TD>
										<TD>Name</TD>
										<TD>Class</TD>
										<TD>Base Level</TD>
										<TD>Job Level</TD>
										<TD>Zeny</TD>
									</TR>
";
	$n=0;
	while ($c_row = $sql->fetch_row()) {
	if(empty($char_manage_menu))
		$charname = $c_row[name];
	else
		$charname = "<a href=\"index.php?act=searching_char&account_id=$c_row[account_id]\">$c_row[name]</a>";
	$n++;
	$jobid="$c_row[class]";
echo "
									<TR align=\"center\" class=\"topic_title4\">
										<TD>$n</TD>
										<TD>$charname</TD>
										<TD>$jobname[$jobid]</TD>
										<TD>$c_row[base_level]</TD>
										<TD>$c_row[job_level]</TD>
										<TD>$c_row[zeny]</TD>
									</TR>
";
	}
echo "								</TBODY>
							</TABLE>
						</TD>
					</TR>
				</TBODY>
			</TABLE>
			<BR>
";
}
			opmain_body("User Settings",0,'90%');
echo "			<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable\">
				<TBODY>
					<TR class=\"topic_title5\">
						<TD width=\"100%\" colspan=\"2\">
							<div class=\"title_face3\"><B>Display Name Settings</B></div>
						</TD>
					</TR>
					<form action=\"index.php?act=change_profile&code=07\" method=\"post\" enctype=\"multipart/form-data\" name=\"ch_display_name\" onSubmit=\"return CheckDisplayname()\">
					<TR class=\"topic_title6\">
						<TD width=\"30%\">
							$lang[login_display_name]
						</TD>
						<TD width=\"70%\">
							<input name=\"display_name\" value=\"$display_name\" type=\"text\" size=\"28\" maxlength=\"24\" class=\"textinput\">
						</TD>
					</TR>
					<TR class=\"topic_title6\">
						<TD width=\"30%\">
							Password
						</TD>
						<TD width=\"70%\">
							<input name=\"password\" type=\"password\" size=\"28\" maxlength=\"24\" class=\"textinput\">
						</TD>
					</TR>
					<TR class=\"topic_title5\">
						<TD width=\"100%\" colspan=\"2\" align=\"center\">
							<input type=\"submit\" name=\"Submit\" value=\"$lang[display_name_change]\" class=\"textinput\">
						</TD>
					</TR>
					</form>
";
if($CONFIG_change_password) {
echo "					<TR class=\"topic_title5\">
						<TD width=\"100%\" colspan=\"2\">
							<div class=\"title_face3\"><B>Password Settings</B></div>
						</TD>
					</TR>
					<form action=\"index.php?act=change_profile&code=01\" method=\"post\" enctype=\"multipart/form-data\" name=\"ch_pass\" onSubmit=\"return CheckPass()\">
";
	if($CONFIG_change_password_with_sls) {
echo "
					<TR class=\"topic_title6\">
						<TD width=\"30%\">
							SLS Password
						</TD>
						<TD width=\"70%\">
							<input name=\"slspassword\" type=\"password\" size=\"28\" maxlength=\"24\" class=\"textinput\">
						</TD>
					</TR>
";
	}
echo "					<TR class=\"topic_title6\">
						<TD width=\"30%\">
							$lang[login_password]
						</TD>
						<TD width=\"70%\">
							<input name=\"password\" type=\"password\" size=\"28\" maxlength=\"24\" class=\"textinput\">
						</TD>
					</TR>
					<TR class=\"topic_title6\">
						<TD width=\"30%\">
							$lang[login_newpassword]
						</TD>
						<TD width=\"70%\">
							<input name=\"password2\" type=\"password\" size=\"28\" maxlength=\"24\" class=\"textinput\">
						</TD>
					</TR>
					<TR class=\"topic_title6\">
						<TD width=\"30%\">
							$lang[login_confirmpassword]
						</TD>
						<TD width=\"70%\">
							<input name=\"confirmpass\" type=\"password\" size=\"28\" maxlength=\"24\" class=\"textinput\">
						</TD>
					</TR>
					<TR class=\"topic_title5\">
						<TD width=\"100%\" colspan=\"2\" align=\"center\">
							<input type=\"submit\" name=\"Submit\" value=\"$lang[password_change]\" class=\"textinput\">
						</TD>
					</TR>
					</form>
";
}
if($CONFIG_change_slspassword) {
echo "					<TR class=\"topic_title5\">
						<TD width=\"100%\" colspan=\"2\">
							<div class=\"title_face3\"><B>SLS Password Settings</B></div>
						</TD>
					</TR>
					<form action=\"index.php?act=change_profile&code=05\" method=\"post\" enctype=\"multipart/form-data\" name=\"ch_sls_pass\" onSubmit=\"return CheckSLSPass()\">
					<TR class=\"topic_title6\">
						<TD width=\"30%\">
							$lang[login_sls_password]
						</TD>
						<TD width=\"70%\">
							<input name=\"slspassword\" type=\"password\" size=\"28\" maxlength=\"24\" class=\"textinput\">
						</TD>
					</TR>
					<TR class=\"topic_title6\">
						<TD width=\"30%\">
							$lang[login_sls_newpassword]
						</TD>
						<TD width=\"70%\">
							<input name=\"slspassword2\" type=\"password\" size=\"28\" maxlength=\"24\" class=\"textinput\">
						</TD>
					</TR>
					<TR class=\"topic_title5\">
						<TD width=\"100%\" colspan=\"2\" align=\"center\">
							<input type=\"submit\" name=\"Submit\" value=\"$lang[sls_password_change]\" class=\"textinput\">
						</TD>
					</TR>
					</form>
";
}
if($CONFIG_change_email) {
echo "					<form action=\"index.php?act=change_profile&code=02\" method=\"post\" enctype=\"multipart/form-data\" name=\"ch_email\" onSubmit=\"return CheckEMail()\">
					<TR class=\"topic_title5\">
						<TD width=\"100%\" colspan=\"2\">
							<div class=\"title_face3\"><B>E-Mail Settings</B></div>
						</TD>
					</TR>
					<TR class=\"topic_title6\">
						<TD width=\"30%\">
							$lang[login_email]
						</TD>
						<TD width=\"70%\">
							<input name=\"email1\" type=\"text\" size=\"28\" class=\"textinput\">
						</TD>
					</TR>
					<TR class=\"topic_title6\">
						<TD width=\"30%\">
							$lang[login_newemail]
						</TD>
						<TD width=\"70%\">
							<input name=\"email2\" type=\"text\" size=\"28\" class=\"textinput\">
						</TD>
					</TR>
					<TR class=\"topic_title6\">
						<TD width=\"30%\">
							$lang[Reg_pass2]
						</TD>
						<TD width=\"70%\">
							<input name=\"password\" type=\"password\" size=\"28\" class=\"textinput\">
						</TD>
					</TR>
					<TR class=\"topic_title5\">
						<TD width=\"100%\" colspan=\"2\" align=\"center\">
							<input type=\"submit\" name=\"Submit\" value=\"$lang[email_change]\" class=\"textinput\">
						</TD>
					</TR>
					</form>
";
}
echo "					<form action=\"index.php?act=change_profile&code=03\" method=\"post\" enctype=\"multipart/form-data\" name=\"ch_avatar\" onSubmit=\"return CheckAvatar()\">
					<TR class=\"topic_title5\">
						<TD width=\"100%\" colspan=\"2\">
							<div class=\"title_face3\"><B>Avatar Settings</B></div>
						</TD>
					</TR>
					<TR class=\"topic_title6\">
						<TD width=\"30%\">
							$lang[login_avatar]
						</TD>
						<TD width=\"70%\">
							<input name=\"avatar_url\" type=\"text\" size=\"28\" class=\"textinput\" value=\"$reply_avatar_url\">
						</TD>
					</TR>
";
if($CONFIG_upload_avatar && checkprivilege_action($CP[login_id],g_avatar_upload)){
echo "					<TR class=\"topic_title6\">
						<TD>
							$lang[login_upavatar]
						</TD>
						<TD>
							<input name=\"upavatar\" type=\"file\" class=\"textinput\">
						</TD>
					</TR>
";
}
echo "					<TR class=\"topic_title6\">
						<TD>
							$lang[login_avatar_size]
						</TD>
						<TD>
							width: <input name=\"avatar_width\" type=\"text\" size=\"2\" maxlength=\"3\" class=\"textinput\" value=\"$reply_avatar_width\">
							height: <input name=\"avatar_height\" type=\"text\" size=\"2\" maxlength=\"3\" class=\"textinput\" value=\"$reply_avatar_height\"> pixels
						</TD>
					</TR>
					<TR class=\"topic_title5\">
						<TD width=\"100%\" colspan=\"2\" align=\"center\">
							<input type=\"submit\" name=\"Submit\" value=\"$lang[avatar_change]\" class=\"textinput\">
						</TD>
					</TR>
					</form>
";
$vmes = "document.t_post_form.t_mes";
echo "					<form action=\"index.php?act=change_profile&code=04\" method=\"post\" enctype=\"multipart/form-data\" name=\"t_post_form\" id=\"t_post_form\" OnSubmit=\"if($vmes.value.length > $CONFIG_max_signature_length) {alert('Please input your signature less than $CONFIG_max_signature_length characters ['+$vmes.value.length+']'); $vmes.focus(); return false;} document.t_post_form.Submit.disabled=true; return true;\">
					<TR class=\"topic_title5\">
						<TD width=\"100%\" colspan=\"2\">
							<div class=\"title_face3\"><B>Signature Settings</B></div>
						</TD>
					</TR>
					<TR class=\"topic_title6\">
						<TD></TD>
						<TD>
";
								get_bbcode('t_post_form');
$edit_signature = my_br2nl($reply_signature);
echo "						</TD>
					</TR>
					<TR class=\"topic_title6\">
						<TD vAlign=\"top\">
							Edit your signature
						</TD>
						<TD>
							<textarea name=\"t_mes\" cols=\"65\" rows=\"10\" class=\"textinput\">".$edit_signature."</textarea>
						</TD>
					</TR>
					<TR class=\"topic_title5\">
						<TD width=\"100%\" colspan=\"2\" align=\"center\">
							<input type=\"submit\" name=\"Submit\" value=\"$lang[signature_change]\" class=\"textinput\">
						</TD>
					</TR>
					</form>
					<form action=\"index.php?act=change_profile&code=06\" method=\"post\" enctype=\"multipart/form-data\" name=\"ch_time_offset\" onSubmit=\"document.ch_time_offset.Submit.disabled=true;\">
					<TR class=\"topic_title5\">
						<TD width=\"100%\" colspan=\"2\">
							<div class=\"title_face3\"><B>Time-Zone Offset Settings</B></div>
						</TD>
					</TR>
					<TR class=\"topic_title6\">
						<TD width=\"30%\">
							$lang[time_offset_setting]
						</TD>
						<TD width=\"70%\">
							<select name=\"u_timezone\" class=\"textinput\">
";
include_once "function/time_zone.php";
$time_zone = array_keys($Timezone);
for($i=0;$i<count($time_zone);$i++) {
	$tmz_val = $time_zone[$i];
	$tmz_list = $Timezone["".$tmz_val.""];
	$selected = ($CP[time_offset] == $tmz_val)?" selected":"";
echo "								<option value=\"$tmz_val\"".$selected.">".$tmz_list."</option>\n";
}
echo "							</select>
						</TD>
					</TR>
					<TR class=\"topic_title5\">
						<TD width=\"100%\" colspan=\"2\" align=\"center\">
							<input type=\"submit\" name=\"Submit\" value=\"$lang[time_offset_change]\" class=\"textinput\">
						</TD>
					</TR>
					</form>
				</TBODY>
			</TABLE>
";
clmain_body();
echo "
			<BR>
		</TD>
	</TR>
</TABLE>
";
clmain_body();
}
?>