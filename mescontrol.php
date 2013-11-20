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
if(checkprivilege_action($CP[login_id],g_edit_mes_control)){
if ($GET_code==00 || $GET_code==01) {
	if ($GET_code==00) {
		$mes_title = "Ro Message";
		$form_code = "02";
		$memory_object = "ro_message";
	} else if ($GET_code==01) {
		$mes_title = "Server Information";
		$form_code = "03";
		$memory_object = "server_info";		
	}
$query = "SELECT memory_value3 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object=\"".mysql_res($memory_object)."\"";
$sql->result = $sql->execute_query($query,'mescontrol.php');$sql->total_query++;
$row = $sql->fetch_row();
$message = my_br2nl($row[memory_value3]);
$message_preview = $row[memory_value3] ? replace_text($row[memory_value3]) : "--------------------";
opmain_body("$mes_title");
echo "<form action=\"index.php?act=mesctrl&code=$form_code\" method=\"post\" enctype=\"multipart/form-data\" name=\"t_post_form\" id=\"t_post_form\" OnSubmit=\"document.t_post_form.Submit.disabled=true; return true;\">
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable\">
	<TBODY>
		<TR class=\"topic_title5\">
			<TD width=\"100%\" colspan=\"2\">
				<div class=\"title_face3\"><B>Code Buttons</B></div>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD width=\"30%\"> </TD>
			<TD width=\"70%\">
";
				get_bbcode('t_post_form');
echo "			</TD>
		</TR>
		<TR class=\"topic_title5\">
			<TD colspan=\"2\">
				<div class=\"title_face3\"><B>Enter $mes_title</B></div>
			</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD vAlign=\"top\">
";
				emotions_select('t_post_form');
echo "			</TD>
			<TD vAlign=\"top\">
				<textarea style=\"width:100%\" name=\"t_mes\" cols=\"60\" rows=\"15\" class=\"textinput\">$message</textarea>
			</TD>
		</TR>
		<TR class=\"topic_title5\">
			<TD align=\"center\">
				<select name=\"change_edit\" onChange=\"if(document.t_post_form.change_edit.value==-1){return false;}else{var A1=document.t_post_form.change_edit.value; document.location.replace(''+A1+'');}\" class=\"selectmenu\">
					<option value=\"-1\" selected>���� --�Select Action�--����</option>
";
$selected = "value=\"-1\" class=\"slbackground\"";
$ADD_OPTION1 = $GET_code==00?$selected:"value=\"index.php?act=mesctrl&code=00\"";
$ADD_OPTION2 = $GET_code==01?$selected:"value=\"index.php?act=mesctrl&code=01\"";
echo "					<option ".$ADD_OPTION1.">- Edit Ro Message</option>
					<option ".$ADD_OPTION2.">- Edit Server Information</option>
				</select>
			</TD>
			<TD>
				<input type=\"submit\" name=\"Submit\" value=\"Submit\" class=\"textinput\">
				<input type=\"reset\" name=\"Reset\" value=\"Restore\" class=\"textinput\">
			</TD>
		</TR>
		<TR class=\"topic_title6\" height=\"100%\">
			<TD class=\"title_face4\" vAlign=\"top\" colspan=\"2\">
				<div class=\"poststyle\">$message_preview</div>
			</TD>
		</TR>
	</TBODY>
</TABLE>
</form>
<script type='text/javascript'>
	var max_width = ".$CONFIG_max_img_width.";
	var max_height = ".$CONFIG_max_img_height.";
	var total_img_resize = ".$CP[images_num].";
	window.onload=resize_img;
</script>
";
clmain_body();
}
else if ($GET_code==02) {
	$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.memory WHERE memory_object=\"ro_message\"";
	$sql->result = $sql->execute_query($query,'mescontrol.php');$sql->total_query++;
	$POST_t_mes = checkstring($POST_t_mes,1);
	if ($sql->result()) {
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.memory SET memory_value1=\"".$CP['login_name']."\", memory_value2=\"".$CP['time']."\", memory_value3=\"".$POST_t_mes."\" WHERE memory_object=\"ro_message\"",'mescontrol.php',0);
	} else {
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.memory (memory_object,memory_value1,memory_value2,memory_value3) VALUES (\"ro_message\",\"".$CP['login_name']."\",\"".$CP['time']."\",\"".$POST_t_mes."\")",'mescontrol.php',0);
	}
	header_location("index.php?act=mesctrl&code=00");
}
else if ($GET_code==03) {
	$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.memory WHERE memory_object=\"server_info\"";
	$sql->result = $sql->execute_query($query,'mescontrol.php');$sql->total_query++;
	$POST_t_mes = checkstring($POST_t_mes,1);
	if ($sql->result()) {
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.memory SET memory_value3=\"".$POST_t_mes."\" WHERE memory_object=\"server_info\"",'mescontrol.php',0);
	} else {
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.memory (memory_object,memory_value3) VALUES (\"server_info\",\"".$POST_t_mes."\")",'mescontrol.php',0);
	}
	header_location("index.php?act=mesctrl&code=01");

}
else {
	redir("index.php?act=idx","$lang[Error]",3);
}
} else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
?>
