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
include_once "gzip_header.php";
if (strstr($CONFIG_width,'%') && $CONFIG_width > 100)
	$CONFIG_width = '100%';
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
<head>
";
	get_cptitle($CP[title]);
echo "	<meta name=\"Author\" content=\"".$CP[author]."\">
	<meta name=\"Keywords\" content=\"".$CP[name]."\">
	<meta name=\"Description\" content=\"".$CP[credit]."\">
	<meta name=\"Copyright\" content=\"".$CP[name]." (c) ".$CP[corp]."\">
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-874\">
	<link rel=\"stylesheet\" type=\"text/css\" href=\"theme/$STORED[THEME]/style.css\">
	<style type=\"text/css\">
		.title_bar {
			BACKGROUND-IMAGE: url(theme/$STORED[THEME]/$THEME[title_bar_img_url])
		}
		.title_bar2 {
			BACKGROUND-IMAGE: url(theme/$STORED[THEME]/$THEME[title_bar2_img_url])
		}
	</style>
</head>
<body ".$THEME[background].">
<script  language=\"JavaScript\" src=\"function/function.js\"></script>
<script  language=\"JavaScript\" src=\"function/ajax.js\"></script>
<script  language=\"JavaScript\" src=\"function/bbcode.js\"></script>
<script type=\"text/javascript\">
	var theme = \"$STORED[THEME]\";
</script>
<div id=\"cploading\" style=\"position:absolute;left:40%;top:50%;\"></div>
";
echo "
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<TR>
		<TD>
			<TABLE width=\"$CONFIG_width\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" ".$THEME[logo_background].">
				<TR>
					<TD align=\"$THEME[logo_img_align]\"><img src=\"theme/$STORED[THEME]/$THEME[logo_img_url]\"></TD>
				</TR>
			</TABLE>
";
$access_lv=checkprivilege_action($CP[login_id],g_title);
echo "
			<TABLE width=\"$CONFIG_width\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
				<TR height=\"25\">
					<TD background=\"theme/$STORED[THEME]/templates/m_titlebar.gif\"><a href=\"index.php?act=idx\" title=\"Home\"><img src=theme/$STORED[THEME]/templates/menu_01.gif border=\"0\"></a><a href=\"index.php?act=register\" title=\"Register\"><img src=theme/$STORED[THEME]/templates/menu_02.gif border=\"0\"></a><a href=\"index.php?act=download\" title=\"Download\"><img src=theme/$STORED[THEME]/templates/menu_03.gif border=\"0\"></a><a href=\"index.php?act=bugreport\" title=\"Bug Report\"><img src=theme/$STORED[THEME]/templates/menu_04.gif border=\"0\"></a><a href=\"index.php?act=forum\" title=\"Forum\"><img src=theme/$STORED[THEME]/templates/menu_05.gif border=\"0\"></a><a href=\"index.php?act=sls\" title=\"Self Locking System\"><img src=theme/$STORED[THEME]/templates/menu_06.gif border=\"0\"></a></TD>
					<TD background=\"theme/$STORED[THEME]/templates/m_titlebar.gif\" align=\"right\"><font class=\"m_title\">$lang[Loggedin] : $CP[login_nname] ($access_lv)$CP[login_logout]</font></a>&nbsp;&nbsp;</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
</TABLE>
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<TR>
		<TD>
			<TABLE width=\"$CONFIG_width\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
";
?>
