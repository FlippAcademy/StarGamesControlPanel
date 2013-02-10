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
if(empty($_GET[code])) {
if(!$SERVER['system_safe']) exit();
include_once "gzip_header.php";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
<head>
	<title>SGCP Install System</title>
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
<form action=\"install.php?code=01\" method=\"post\" enctype=\"multipart/form-data\" name=\"Create_DB\" OnSubmit=\"document.Create_DB.Submit.disabled=true;\")>
	<input type=\"hidden\" name=\"system_safe\" value=\"1\">
		<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"topic_title\">
			<TBODY>
				<TR class=\"title_bar\" height=29>
					<TD>
						<div class=\"m_title\">
							<B>&nbsp;&nbsp;Creating Database: $CONFIG_sql_cpdbname</B>
						</font>
					</TD>
				</TR>
				<TR>
					<TD>
						<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
							<TBODY>
								<TR class=\"topic_title5\">
									<TD height=\"27\">
										<div class=\"title_face\">
											<B>CP can't working now it must to create a $CONFIG_sql_cpdbname database. You want to create it at this time.</B>
										</font>
									</TD>
								</TR>
								<TR class=\"topic_title5\">
									<TD width=\"100%\" align=\"center\">
										<input type=\"submit\" name=\"Submit\" value=\"Create a database\" class=\"textinput\">
									</TD>
								</TR>
							</TBODY>
						</TABLE>
					</TD>
				</TR>
			</TBODY>
		</TABLE>
</form>
</body>
</html>";
mysql_close();
include_once "gzip_footer.php";
}
else if ($_GET[code]=='01') {
if($_POST['system_safe'] != '1')
	header("location:index.php?act=idx");
require "memory.php";
require "function/lib_install.php";
include_once "gzip_header.php";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
<head>
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
<body ".$background.">
";
$sql = new MySQL;
$sql->Connect($CONFIG_sql_host,$CONFIG_sql_username,$CONFIG_sql_password);

$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"sgcp_install\" AND memory_value1=\"1\"";
$sql->result = mysql_query($query);
if(!$sql->result()) {
	if(mysql_select_db($CONFIG_sql_cpdbname)) {
		$dropdb = "DROP DATABASE `$CONFIG_sql_cpdbname`;";
		mysql_query($dropdb);
	}
	$createdb = "CREATE DATABASE `$CONFIG_sql_cpdbname`;";
	if(mysql_query($createdb)) {
		if ($contents = file_get_contents("install/sql-files/sgcp_database.sql")) {
			$cpi = new CP_Install;
			$execute_query = $cpi->promt_query($contents);
			for($i=0;$i<count($execute_query);$i++) {
				mysql_db_query($CONFIG_sql_cpdbname,$execute_query[$i]);
			}
			redir("index.php?act=idx","Automatic System : has created a $CONFIG_sql_cpdbname database",3);
		} else {
			redir("index.php?act=idx","Automatic System : Could not read sgcp_database.sql file",3);
		}
	} else {
		redir("index.php?act=idx","Automatic System : creating $CONFIG_sql_cpdbname database was failed",3);
	}
} else {
	header_location("index.php?act=idx");
}
echo "
</body>
</html>";
mysql_close();
include_once "gzip_footer.php";
}
?>