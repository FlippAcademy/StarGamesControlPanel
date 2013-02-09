<?php
if(!$SERVER['system_safe']) exit();
include_once "header_bar.php";
if($CONFIG_show_all_id) {
	$sql->result = $sql->execute_query("SELECT COUNT(*) FROM $CONFIG_sql_dbname.login WHERE sex != \"S\"",'menu_left.php',0);
	$totalid = $sql->result();
	$sql->result = $sql->execute_query("SELECT COUNT(*) FROM $CONFIG_sql_dbname.char",'menu_left.php',0);
	$totalchar = $sql->result();
}
$SELECT_MENU = 0;
if($mquick) $quick_style="display:none;";
echo "<TR>
	<TD vAlign=\"top\">
<TABLE border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" class=\"table_main_left\"><TR><TD>
<TABLE width=\"208\" height=\"$CONFIG_height\" border=\"0\" align=\"left\" cellspacing=\"0\" cellpadding=\"0\">
	<TBODY>
		<TR height=\"20\">
			<TD><img src=\"theme/$STORED[THEME]/templates/left_box_header_01.gif\"></TD><TD width=\"100%\" background=\"theme/$STORED[THEME]/templates/left_box_header_02.gif\"></TD><TD><img src=\"theme/$STORED[THEME]/templates/left_box_header_03.gif\"></TD>
		</TR>
		<TR align=\"center\">
			<TD width=\"19\" background=\"theme/$STORED[THEME]/templates/left_box_float_01.gif\"></TD>
			<TD vAlign=\"top\" background=\"theme/$STORED[THEME]/templates/left_box_float_02.gif\">
				<TABLE width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"table_left_menu\">
					<TR>
						<TD>
				<TABLE width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"tablefill\">
						<TR align=\"center\">
							<form name=\"Current_Time\">
							<TD>
								<input type=\"text\" name=\"clock\" class=\"currenttime\" size=\"21\" maxlength=\"255\" readonly>
							</TD>
							</form>
							<SCRIPT language=\"javascript\" src=\"function/currenttime.js\"></SCRIPT>
						</TR>
				</TABLE>
				<BR>
				<TABLE width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"tablefill\">
					<TBODY>
";
if(checkprivilege_action($CP[login_id],g_view_adminmenu)) {
$SELECT_MENU = 1;
echo "						<TR align=\"center\">
							<form name=\"Adminmenu\">
							<TD>
								<select name=\"admin_options\" onChange=\"if(document.Adminmenu.admin_options.value==-1){return false;}else{javascript:change_page();}\" class=\"selectmenu\" style=\"width:90%\">
									<option value=\"-1\" selected>Admin Menu</option>
									<option value=\"index.php?act=mesctrl\">- $lang[Message_Control]</option>
									<option value=\"index.php?act=readnews\">- $lang[News_Control]</option>
									<option value=\"index.php?act=privilege\">- $lang[Privilege_Control]</option>
									<option value=\"index.php?act=forum_manage\">- $lang[Forum_Manage]</option>
									<option value=\"index.php?act=account_manage\">- $lang[Account_Manage]</option>
									<option value=\"index.php?act=rank_title\">- $lang[Member_Titles_Ranks]</option>
									<option value=\"index.php?act=deluser\">- $lang[Deleteuser]</option>
									<option value=\"index.php?act=checkcp\">- $lang[CheckCP]</option>
								</select>
							</TD>
							</form>
						</TR>
";
}
if($CONFIG_language_select_mode)
	get_language_select();
if($CONFIG_theme_select_mode)
	get_theme_select();
echo "					</TBODY>
				</TABLE>
";
if($SELECT_MENU) echo "<BR>\n";
if(empty($STORED_loginname) && empty($STORED_loginpass)){
echo "
				<SCRIPT language=\"JavaScript\" src=\"function/registration.js\"></SCRIPT>
				<TABLE width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"5\" cellpadding=\"0\" class=\"tablefill2\">
					<TBODY>
						<form action=\"index.php?act=login_action\" method=\"post\" enctype=\"multipart/form-data\" name=\"login_form2\" onSubmit=\"return CheckLogin2()\">
						<TR>
							<TD width=\"30%\">ID :</TD>
							<TD width=\"70%\"><input name=\"LG_USER\" type=\"text\" size=\"13\" maxlength=\"24\" class=\"textinput\"></TD>
						</TR>
						<TR>
							<TD>Pass :</TD>
							<TD><input name=\"LG_PASS\" type=\"password\" size=\"13\" maxlength=\"24\" class=\"textinput\"></TD>
						</TR>
						<TR align=\"center\">
							<TD colspan=\"2\">
								<input type=\"submit\" name=\"Submit\" value=\"$lang[login]\" class=\"textinput3\">
								<input type=\"reset\" name=\"Reset\" value=\"$lang[Resetprivilege]\" class=\"textinput3\">
							</TD>
						</TR>
						</form>
					</TBODY>
				</TABLE>
				<BR>
";
}
$lostpass_menu = $CONFIG_lost_pass_mode?"$IMG[ARROW]<a href=\"index.php?act=lostpass\" title=\"Lost Password\">$lang[Mn_lostpass]</a><BR>":"";
echo "<a href=javascript:hide_menu(2,\"mquick\") title=\"Open/Close this menu\"><img src =\"theme/$STORED[THEME]/menu/Quick_menu.gif\" border=\"0\"></a><BR>
				<TABLE width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
					<TBODY>
						<TR id=\"2\" style=\"$quick_style\">
							<TD vAlign=\"top\">
<BR>
								$IMG[ARROW]<a href=\"index.php?act=idx\" title=\"Home\">$lang[Mn_home]</a><BR>
								$IMG[ARROW]<a href=\"index.php?act=register\" title=\"Register\">$lang[Mn_register]</a><BR>
								$IMG[ARROW]<a href=\"index.php?act=download\" title=\"Download\">$lang[Mn_download]</a><BR>
								$IMG[ARROW]<a href=\"index.php?act=guildstanding\" title=\"Guild War Report\">$lang[Guild_Standing]</a><BR>
								$IMG[ARROW]<a href=\"index.php?act=info\" title=\"Server Information\">$lang[Mn_svinfo]</a><BR>
								".$lostpass_menu."
								$IMG[ARROW]<a href=\"mailto:$CONFIG_admin_email\" title=\"Contact\">$lang[Mn_contact]</a><BR>
							</TD>
						</TR>
					<TBODY>
				</TABLE>
";
if(!empty($STORED_loginname) && !empty($STORED_loginpass)) {
	if($mmember)
		$member_style="display:none;";
	$CP[privilege_id] = checkprivilege($CP[login_id]);
	$char_manage_menu = get_members_menu($CONFIG_char_manage_mode,char_manage,$CP[privilege_id]);
	$player_rank_menu = get_members_menu($CONFIG_player_rank_mode,player_rank,$CP[privilege_id]);
	$guild_rank_menu = get_members_menu($CONFIG_guild_rank_mode,guild_rank,$CP[privilege_id]);
echo "<BR>
<img src =\"theme/$STORED[THEME]/menu/menu_line.gif\"><BR><BR>
<a href=javascript:hide_menu(3,\"mmember\") title=\"Open/Close this menu\"><img src =\"theme/$STORED[THEME]/menu/Member_menu.gif\" border=\"0\"></a><BR>
				<TABLE width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\">
					<TBODY>
						<TR id=\"3\" style=\"$member_style\">
							<TD vAlign=\"top\">
<BR>
								$IMG[ARROW]<a href=\"index.php?act=login\" title=\"My Profile\">$lang[login_profile]</a><BR>
								$char_manage_menu
								$player_rank_menu
								$guild_rank_menu
							</TD>
						</TR>
					<TBODY>
				</TABLE>
";
}
if($mstatus)
	$svstatus_style="display:none;";
echo "<BR>
<img src =\"theme/$STORED[THEME]/menu/menu_line.gif\"><BR><BR>
<a href=javascript:hide_menu(5,\"mstatus\") title=\"Open/Close this menu\"><img src =\"theme/$STORED[THEME]/menu/Server_status.gif\" border=\"0\"></a><BR>
				<TABLE width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\">
					<TBODY>
						<TR id=\"5\" style=\"$svstatus_style\">
							<TD vAlign=\"top\">
<BR>
								$IMG[ARROW]Login Server : $accsrv<BR>
								$IMG[ARROW]Char Server : $charsrv<BR>
								$IMG[ARROW]Map Server : $mapsrv<BR><BR>
								$IMG[ARROW]$lang[Users_Online] : <B>$user_online</B><BR>
								$IMG[ARROW]$lang[User] : <B>$total_online</B><BR>
";
if($CONFIG_show_all_id){
echo "								$IMG[ARROW]$lang[All_ID] : <B>$totalid</B><BR>
								$IMG[ARROW]$lang[All_CHAR] : <B>$totalchar</B><BR>
";
}
echo "							</TD>
						</TR>
					<TBODY>
				</TABLE>
<BR>
<img src =\"theme/$STORED[THEME]/menu/menu_line.gif\"><BR><BR>
<img src =\"theme/$STORED[THEME]/menu/Exchange_Link_menu.gif\"><BR>
				<TABLE width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\">
					<TBODY>
						<TR>
							<TD vAlign=\"top\">

<BR>
";
								include_once "setting_ini_files/exchange_link.ini";
echo "							</TD>
						</TR>
					<TBODY>
				</TABLE>
						</TD>
					</TR>
				</TABLE>
			</TD>
			<TD width=\"19\" background=\"theme/$STORED[THEME]/templates/left_box_float_03.gif\"></TD>
		</TR>
		<TR height=\"20\">
			<TD><img src=\"theme/$STORED[THEME]/templates/left_box_footer_01.gif\"></TD><TD width=\"100%\" background=\"theme/$STORED[THEME]/templates/left_box_footer_02.gif\"></TD><TD><img src=\"theme/$STORED[THEME]/templates/left_box_footer_03.gif\"></TD>
		</TR>
	</TBODY>
</TABLE>
</TD></TR></TABLE>
	</TD>
";
?>