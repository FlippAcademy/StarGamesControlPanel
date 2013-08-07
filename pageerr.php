/*
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
*/
<?php
require "memory.php";
include_once "gzip_header.php";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
<head>
	<title>Connection failed</title>
	<meta name=\"Author\" content=\"".$CP[author]."\">
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
";
switch($GET_code) {
	case 01:
		$ERROR['MES'] = "You have been blocked by Administrator. Please contact your administrator.";
		break;
	default:
		$ERROR['MES'] = "There isn't an error message in this code.";
		break;
}
opmain_body("CP Error Message",0,"80%");
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<TBODY>
		<TR height=\"27\" class=\"title_bar2\">
			<TD><div class=\"title_face\"></div></TD>
		</TR>
		<TR height=\"27\" class=\"topic_title7\">
			<TD><B>$ERROR[MES]</B></TD>
		</TR>
		<TR height=\"27\" class=\"topic_title5\">
			<TD></TD>
		</TR>
	</TBODY>
</TABLE>
";
clmain_body();
echo "</body>
</html>";
include_once "gzip_footer.php";
?>
