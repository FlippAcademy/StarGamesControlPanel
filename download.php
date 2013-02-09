<?php
if(!$SERVER['system_safe']) exit();
if (empty($STORED_loginname) && empty($STORED_loginpass) && !$CONFIG_download_mode) {
	redir("index.php?act=login","$lang[Must_login]",3);
} else {
opmain_body("Download",150);
echo "
<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\">
	<TBODY>
		<TR class=\"topic_title5\">
			<TD width=\"100%\" height=\"25\" vAlign=\"top\"></TD>
		</TR>
		<TR class=\"topic_title6\" height=\"100%\">
			<TD class=\"title_face4\" vAlign=\"top\">
";
				include_once "setting_ini_files/download.ini";
echo "			</TD>
		</TR>
		<TR class=\"topic_title5\">
			<TD width=\"100%\" height=\"25\"></TD>
		</TR>
	<TBODY>
</TABLE>
";
clmain_body();
}
?>