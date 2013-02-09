<?php
if(!$SERVER['system_safe']) exit();
if (checkprivilege_action($CP[login_id],g_view_lastestcp)) {
opmain_body("SGCP Update");
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<TBODY>
		<TR height=\"27\" class=\"title_bar2\">
			<TD colspan=\"2\"><span class=\"m_title\">&nbsp;&nbsp;Automatic Update</span></TD>
		</TR>
		<TR class=\"topic_title8\">
			<TD colspan=\"2\">
				<span id=\"cp_update\">".$lang['CPUD_cp_update']."</span>
			</TD>
		</TR>
		<form name=\"CP_Update\">
		<TR class=\"topic_title8\">
			<TD width=\"40%\"><span id=\"cp_update_mes\">".$lang['CPUD_check_mes']."</span></TD>
			<TD width=\"60%\"><span id=\"cp_update_button\"><input name=\"Button\" value=\"".$lang['CPUD_check_button']."\" type=\"button\" onClick=\"this.disabled=true; check_cp_update();\" class=\"textinput\"></span></TD>
		</TR>
		</form>
		<TR height=\"25\" class=\"topic_title5\">
			<TD colspan=\"2\"></TD>
		</TR>
	</TBODY>
</TABLE>
";
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"0\" align=\"center\" class=\"emptytable3\">
	<TBODY>
		<TR height=\"27\" class=\"title_bar2\">
			<TD><span class=\"m_title\">&nbsp;&nbsp;Manual Update</span></TD>
		</TR>
		<TR>
			<TD><iframe name=\"CP_update\" src=\"index.php?init_load=cpupdate\" width=\"100%\" height=\"200\" frameborder=\"0\" scroll=\"yes\"></iframe></TD>
		</TR>
		<TR height=\"25\" class=\"topic_title5\">
			<TD></TD>
		</TR>
	</TBODY>
</TABLE>
";
clmain_body();
} else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
?>