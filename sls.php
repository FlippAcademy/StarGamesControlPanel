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
if($GET_code==00){
opmain_body("Self Locking System");
echo "
<SCRIPT language=\"JavaScript\" src=\"function/registration.js\"></SCRIPT>
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" align=\"center\">
	<TBODY>
	<form action=\"index.php?act=sls&code=01\" method=\"post\" enctype=\"multipart/form-data\" name=\"login_form\" onSubmit=\"return CheckLogin()\">
		<TR class=\"topic_title6\">
			<TD width=\"20%\" align=\"right\">
				$lang[login_user] :
			</TD>
			<TD width=\"80%\" align=\"left\">
				<input name=\"LG_USER\" type=\"text\" size=\"28\" maxlength=\"24\" class=\"textinput\">
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD align=\"right\">
				$lang[login_sls_pass] :
			</TD>
			<TD align=\"left\">
				<input name=\"LG_PASS\" type=\"password\" size=\"28\" maxlength=\"24\" class=\"textinput\">
			</TD>
		</TR>
		<TR class=\"topic_title5\">
			<TD></TD>
			<TD>
				<input type=\"submit\" name=\"Submit\" value=\"$lang[login_sls]\" class=\"textinput\">
				<input type=\"button\" name=\"Button\" value=\"$lang[make_sls_pass]\" onClick=\"hyperlink('index.php?act=sls&code=02');\" class=\"textinput\">
			</TD>
		</TR>
	</form>
	</TBODY>
</TABLE>
";
clmain_body();
}
else if($GET_code==01 && isAlphaNumeric($POST_LG_USER) && isAlphaNumeric($POST_LG_PASS)){
	$query = "SELECT account_id, state FROM $CONFIG_sql_dbname.login WHERE userid = \"".mysql_res($POST_LG_USER)."\" LIMIT 0,1";
	$sql->result = $sql->execute_query($query,'sls.php');$sql->total_query++;
	if ($sql->count_rows()) {
		$row = $sql->fetch_row();
		$userid = $row[account_id];
		$userstate = $row[state];
		if($userstate==0 || $userstate==14){
			$POST_LG_PASS = mysql_res(checkmd5($CONFIG_md5_support,$POST_LG_PASS));
			$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id = \"".mysql_res($userid)."\" AND user_sls_pass=\"".$POST_LG_PASS."\" LIMIT 0,1";
			$sql->result = $sql->execute_query($query,'sls.php');
			if ($sql->result()) {
				if($userstate==0) {
					$userstate=14;
					$display=$lang[lock_sls];
				} else if($userstate==14) {
					$userstate=0;
					$display=$lang[unlock_sls];
				}
				$sql->execute_query("UPDATE $CONFIG_sql_dbname.login set state=\"".mysql_res($userstate)."\" WHERE account_id = \"".mysql_res($userid)."\" ",'sls.php');$sql->total_query++;
			} else {
				$query = "SELECT user_sls_pass FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id = \"".mysql_res($userid)."\" LIMIT 0,1";
				$sql->result = $sql->execute_query($query,'sls.php');
				$row2 = $sql->fetch_row();
				if(empty($row2[user_sls_pass])){
					$display=$lang[no_sls_pass];
				} else {
					$display=$lang[sls_pass_incorrect];
				}
			}
		} else {
			$display=$lang[state_incorrect];
		}
	} else {
		$display=$lang[login_wrong];
	}
redir("index.php?act=sls","$display",3);
}
else if($GET_code==02){
?>
<script language="JavaScript">function CheckSLS(){var L1 = document.sls_form.LG_USER.value; var L2 = document.sls_form.LG_PASS.value; var L3 = document.sls_form.SLS_PASS.value;if (L1.length < 4) {alert("Please enter your ID at least 4 characters."); document.sls_form.LG_USER.focus(); return false;}else if (L2.length < 4) {alert("Please enter your password at least 4 characters."); document.sls_form.LG_PASS.focus(); return false;}else if (L3.length < 4) {alert("Please enter your SLS password at least 4 characters."); document.sls_form.SLS_PASS.focus(); return false;}else {document.sls_form.Submit.disabled=true;return true;}}</script>
<?php
opmain_body("Self Locking System");
echo "
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" align=\"center\">
	<TBODY>
	<form action=\"index.php?act=sls&code=03\" method=\"post\" enctype=\"multipart/form-data\" name=\"sls_form\" onSubmit=\"return CheckSLS()\">
		<TR class=\"topic_title6\">
			<TD width=\"20%\" align=\"right\">
				$lang[login_user] :
			</TD>
			<TD width=\"80%\" align=\"left\">
				<input name=\"LG_USER\" type=\"text\" size=\"28\" maxlength=\"24\" class=\"textinput\">
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD align=\"right\">
				$lang[login_pass] :
			</TD>
			<TD align=\"left\">
				<input name=\"LG_PASS\" type=\"password\" size=\"28\" maxlength=\"24\" class=\"textinput\">
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD align=\"right\">
				$lang[login_sls_pass] :
			</TD>
			<TD align=\"left\">
				<input name=\"SLS_PASS\" type=\"password\" size=\"28\" maxlength=\"24\" class=\"textinput\">
			</TD>
		</TR>
		<TR class=\"topic_title5\">
			<TD></TD>
			<TD>
				<input type=\"submit\" name=\"Submit\" value=\"$lang[make_sls_pass]\" class=\"textinput\">
			</TD>
		</TR>
	</form>
	</TBODY>
</TABLE>
";
clmain_body();
}
else if($GET_code==03 && isAlphaNumeric($POST_LG_USER) && isAlphaNumeric($POST_LG_PASS) && isAlphaNumeric($POST_SLS_PASS)){
	$POST_LG_PASS = mysql_res(checkmd5($CONFIG_md5_support,$POST_LG_PASS));
	$query = "SELECT account_id FROM $CONFIG_sql_dbname.login WHERE userid = \"".mysql_res($POST_LG_USER)."\" AND user_pass=\"$POST_LG_PASS\" LIMIT 0,1";
	$sql->result = $sql->execute_query($query,'sls.php');$sql->total_query++;
	if ($sql->count_rows()) {
		$row = $sql->fetch_row();
		$userid = $row[account_id];
		$query = "SELECT user_sls_pass FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id = \"".mysql_res($userid)."\" LIMIT 0,1";
		$sql->result = $sql->execute_query($query,'sls.php');
		if ($sql->count_rows()) {
			$row2 = $sql->fetch_row();
			if(empty($row2[user_sls_pass])){
				$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_profile set user_sls_pass=\"".mysql_res($POST_SLS_PASS)."\" WHERE user_id = \"".mysql_res($userid)."\" ",'sls.php');$sql->total_query++;
				$display=$lang[success_make_sls_pass];
			} else {
				$display=$lang[yes_sls_pass];
			}
		} else {
			$display=$lang[login_wrong];
		}
	} else {
		$display=$lang[login_wrong];
	}
redir("index.php?act=sls","$display",3);
}
?>
