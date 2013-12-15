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
// To change the message that is outputted, edit the file in /lang/English.php 
-->
<?php
if(!$SERVER['system_safe']) exit();
if($CONFIG_lost_pass_mode || $CONFIG_md5_support>='1') {
if($GET_code==00) {
opmain_body("$lang[Mn_lostpass]");
echo"<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<form action=\"index.php?act=lostpass&code=01\" method=\"post\" enctype=\"multipart/form-data\" name=\"Lost_Pass\">
	<TBODY>
		<TR height=\"27\" class=\"title_bar2\">
			<TD colspan=\"2\"><div class=\"title_face\">$lang[LP_form]</div></TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD width=\"20%\"><B>$lang[LP_input]</B></TD>
			<TD width=\"80%\"><input name=\"userid\" type=\"text\" size=\"24\" maxlength=\"24\" class=\"textinput\"> $lang[LP_info]</TD>
		</TR>
		<TR class=\"topic_title5\" align=\"center\">
			<TD colspan=\"2\"><input type=\"submit\" name=\"Submit\" value=\"$lang[LP_submit]\" class=\"textinput\"></TD>
		</TR>
	</TBODY>
	</form>
</TABLE>
";
clmain_body();
}
else if($GET_code==01) {
$query = "SELECT userid,user_pass,email FROM $CONFIG_sql_dbname.login WHERE userid = \"".mysql_res($POST_userid)."\"";
$sql->result = $sql->execute_query($query,'lostpass.php',0);$sql->total_query++;
if($sql->count_rows()) {
	$row = $sql->fetch_row();
	$mail_subject = "Lost Password ( SGCP user registration information )";
$mail_messages = sprintf("

$lang[LP_mes_1]

$lang[LP_mes_2]
$lang[LP_mes_3]
$lang[LP_mes_4]

$lang[LP_mes_5]
$lang[LP_mes_6]

$lang[LP_mes_7]
$lang[LP_mes_8]
",$CONFIG_server_name,$CONFIG_server_name,$row[userid],$row[user_pass],$CONFIG_server_name,$CONFIG_admin_email,$CONFIG_server_name);

	if(SendMail($row[email],$mail_subject,$mail_messages))
		redir("index.php?act=lostpass","$lang[LP_send_success]",3);
	else
		redir("index.php?act=lostpass","$lang[LP_send_fail]",3);
} else {
	redir("index.php?act=lostpass","$lang[LP_no_found]",3);
}
}
}
?>
