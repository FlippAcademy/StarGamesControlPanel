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
if(checkprivilege_action($CP[login_id],g_edit_rank_title)){
if ($GET_code==00) {
opmain_body("Member Titles/Ranks");
echo "
<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\" class=\"emptytable3\">
	<TBODY>
		<TR height=\"27\" class=\"title_bar2\">
			<TD width=\"40%\" class=\"title_face\" align=\"center\">Title</TD>
			<TD width=\"40%\" class=\"title_face\" align=\"center\">Min Posts</TD>
			<TD width=\"10%\"></TD>
			<TD width=\"10%\"></TD>
		</TR>
";
	$query = "SELECT title_id,title,min_post FROM $CONFIG_sql_cpdbname.rank_title ORDER by min_post ASC";
	$sql->result = $sql->execute_query($query,'rank_title.php');$sql->total_query++;
	if ($sql->count_rows()) {
		while($row = $sql->fetch_row()) {
echo "
		<TR>
			<TD align=\"center\" class=\"topic_title4\"><B>$row[title]</B></TD>
			<TD align=\"center\" class=\"topic_title6\">$row[min_post]</TD>
			<TD align=\"center\" class=\"topic_title4\"><a href=\"index.php?act=rank_title&code=02&id=$row[title_id]\" title=\"Edit\"><img src =\"theme/$STORED[THEME]/images/edit.gif\" border=\"0\"></a></TD>
			<TD align=\"center\" class=\"topic_title6\"><a href=\"index.php?act=rank_title&code=04&id=$row[title_id]\" title=\"Remove\"><img src =\"theme/$STORED[THEME]/images/drop.gif\" border=\"0\"></a></TD>
		</TR>
";
		}
	} else {
echo "
		<TR>
			<TD colspan=\"4\">No Member Ranks/Titles in database</TD>
		</TR>
";
	}
echo "
	<TBODY>
</TABLE>
";
clmain_body();
echo "<BR>";
opmain_body("Add a Member Rank");
echo "
<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\" class=\"emptytable3\">
	<TBODY>
		<form action=\"index.php?act=rank_title&code=01\" method=\"post\" enctype=\"multipart/form-data\">
		<TR class=\"topic_title4\">
			<TD width=\"40%\"><B>Rank Title</B></TD>
			<TD width=\"60%\"><input name=\"title\" type=\"text\" size=\"25\" class=\"textinput\"></TD>
		</TR>
		<TR class=\"topic_title4\">
			<TD><B>Minimum number of posts needed</B></TD>
			<TD><input name=\"min_post\" type=\"text\" size=\"25\" class=\"textinput\"></TD>
		</TR>
		<TR class=\"topic_title5\" align=\"center\">
			<TD colspan=\"2\"><input type=\"Submit\" name=\"Submit\" value=\"Add this rank\" class=\"textinput\"></TD>
		</TR>
		</form>
	<TBODY>
</TABLE>
";
clmain_body();
}
else if ($GET_code==01) {
	$POST_min_post = (int)$POST_min_post;
	if (empty($POST_title))
		redir("index.php?act=idx","$lang[Error]",3);
	else {
		$POST_title = checkstring($POST_title,1);
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.rank_title (title,min_post) VALUES (\"".$POST_title."\",\"".mysql_res($POST_min_post)."\")",'rank_title.php',0);
		header_location("index.php?act=rank_title");
	}
}
else if ($GET_code==02) {
	$GET_id = (int)$GET_id;
	if (empty($GET_id))
		redir("index.php?act=idx","$lang[Error]",3);
	else {
		$query = "SELECT title,min_post FROM $CONFIG_sql_cpdbname.rank_title WHERE title_id = \"".mysql_res($GET_id)."\"";
		$sql->result = $sql->execute_query($query,'rank_title.php');$sql->total_query++;
		if ($sql->count_rows()) {
			$row = $sql->fetch_row();
opmain_body("Edit Member Rank");
echo "
<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\" class=\"emptytable3\">
	<TBODY>
		<form action=\"index.php?act=rank_title&code=03&id=$GET_id\" method=\"post\" enctype=\"multipart/form-data\">
		<TR class=\"topic_title4\">
			<TD width=\"40%\"><B>Rank Title</B></TD>
			<TD width=\"60%\"><input name=\"title\" type=\"text\" size=\"25\" value=\"$row[title]\" class=\"textinput\"></TD>
		</TR>
		<TR class=\"topic_title4\">
			<TD><B>Minimum number of posts needed</B></TD>
			<TD><input name=\"min_post\" type=\"text\" size=\"25\" value=\"$row[min_post]\" class=\"textinput\"></TD>
		</TR>
		<TR class=\"topic_title5\" align=\"center\">
			<TD colspan=\"2\">
				<input type=\"Submit\" name=\"Submit\" value=\"Update this rank\" class=\"textinput\">
				<input type=\"Reset\" name=\"Reset\" value=\"Reset\" class=\"textinput\">
			</TD>
		</TR>
		</form>
	<TBODY>
</TABLE>
";
clmain_body();
		} else {
			redir("index.php?act=idx","$lang[Error]",3);
		}
	}
}
else if ($GET_code==03) {
	$GET_id = (int)$GET_id;
	$POST_min_post = (int)$POST_min_post;
	if (empty($GET_id))
		redir("index.php?act=idx","$lang[Error]",3);
	else {
		$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.rank_title WHERE title_id = \"".mysql_res($GET_id)."\"";
		$sql->result = $sql->execute_query($query,'rank_title.php');$sql->total_query++;
		if ($sql->result()) {
			$POST_title = checkstring($POST_title,1);
			$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.rank_title SET title=\"".$POST_title."\",min_post=\"".mysql_res($POST_min_post)."\" WHERE title_id=\"".mysql_res($GET_id)."\"",'rank_title.php',0);
			header_location("index.php?act=rank_title");
		} else {
			redir("index.php?act=idx","$lang[Error]",3);
		}
	}
}
else if ($GET_code==04) {
	$GET_id = (int)$GET_id;
	if (empty($GET_id))
		redir("index.php?act=idx","$lang[Error]",3);
	else {
		$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.rank_title WHERE title_id = \"".mysql_res($GET_id)."\"";
		$sql->result = $sql->execute_query($query,'rank_title.php');$sql->total_query++;
		if ($sql->result()) {
			$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.rank_title WHERE title_id=\"".mysql_res($GET_id)."\"",'rank_title.php',0);
			header_location("index.php?act=rank_title");
		} else {
			redir("index.php?act=idx","$lang[Error]",3);
		}
	}
}
else {
	redir("index.php?act=idx","$lang[Error]",3);
}
} else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
?>
