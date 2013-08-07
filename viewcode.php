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
	<title>View Security Code</title>
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
if ($_GET[sc] && isAlphaNumeric($_GET[sc])) {
	$sql = new MySQL;
	$sql->Connect($CONFIG_sql_host,$CONFIG_sql_username,$CONFIG_sql_password);
	$query = "SELECT sc_code FROM $CONFIG_sql_cpdbname.security_code WHERE sc_id = \"".mysql_res($_GET[sc])."\"";
	$sql->result = $sql->execute_query($query,'viewcode.php');
	if($sql->count_rows()) {
		$row = $sql->fetch_row();
		$sc_code = $row[sc_code];
		$num = strlen($sc_code);
		$reg_str = "";
		for($i = 0; $i < 6; $i++) {
			$ret_str.= $sc_code[$i];
			$ret_str.=" ";
		}
		$sc_code = $ret_str;
		$display = "$lang[Reg_view_sc_code_success]: <B>$sc_code</B>";
	} else
		$display = $lang[Reg_view_sc_code_fail];
opmain_body("View Security Code","20","270");
echo "<TABLE width=\"100%\" height=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<TBODY>
		<TR class=\"topic_title5\">
			<TD colspan=\"2\">$display</TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD align=\"center\">
				<a href=\"javascript:window.close()\">$lang[Close_windows]</a>
			</TD>
		</TR>
	</TBODY>
</TABLE>
";
clmain_body();
}
echo"</body>
</html>";
mysql_close();
include_once "gzip_footer.php";
?>
