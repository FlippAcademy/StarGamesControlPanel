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
$GET_guild_id = (int)$GET_guild_id;
$query = "SELECT name FROM $CONFIG_sql_dbname.guild WHERE guild_id=\"".mysql_res($GET_guild_id)."\"";
$sql->result = $sql->execute_query($query,'guildinfo.php');$sql->total_query++;
$row = $sql->fetch_row();
opmain_body("".$row['name']." Guild");
echo "
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\">
	<TBODY>
		<TR align=\"center\" class=\"topic_title3\" style=\"font-weight: bold;\">
			<TD>No.</TD>
			<TD>Name</TD>
			<TD>Class</TD>
			<TD>Level</TD>
			<TD>EXP Donated</TD>
			<TD>Position</TD>
		</TR>
";
if($sql->count_rows()) {
	$query = "SELECT account_id,name,class,lv,exp,position FROM $CONFIG_sql_dbname.guild_member WHERE guild_id=\"".mysql_res($GET_guild_id)."\" ORDER BY position ASC";
	$sql->result = $sql->execute_query($query,'guildinfo.php');$sql->total_query++;
	$IS_SEARCHING_ID=checkprivilege_action($CP[login_id],g_searching_id)?1:0;
	$countstanding=0;
	$i=0;
	while ($grow = $sql->fetch_row()) {
		$i++;
		$countstanding=1;
		$jobid=$grow['class'];
		$query = "SELECT name FROM $CONFIG_sql_dbname.guild_position WHERE guild_id=\"".mysql_res($GET_guild_id)."\" and position=\"".$grow['position']."\"";
		$sql->result2 = $sql->execute_query($query,'guildinfo.php');
		$prow = $sql->fetch_row($sql->result2);
		$name=$IS_SEARCHING_ID?"<a href=\"index.php?act=searching_id&account_id=$grow[account_id]\">".htmlspecialchars($grow['name'])."</a>":"".htmlspecialchars($grow['name'])."";
echo "
		<TR align=\"center\" class=\"topic_title4\">
			<TD>$i</TD>
			<TD>$name</TD>
			<TD>$jobname[$jobid]</TD>
			<TD>$grow[lv]</TD>
			<TD>$grow[exp]</TD>
			<TD>".htmlspecialchars($prow['name'])."</TD>
		</TR>
";
	}
		if (isset($emblems)) {
			session_register(emblems);
			$_SESSION['emblems'] = $emblems;
		}
} else {
	$countstanding=1;
echo "
		<TR align=\"center\" class=\"topic_title4\">
			<TD colspan=\"6\">
				No guilds in database!
			</TD>
		</TR>
";
}
if(!$countstanding) {
echo "
		<TR align=\"center\" class=\"topic_title4\">
			<TD colspan=\"6\">
				No guilds in database!
			</TD>
		</TR>
";
}
echo "
	</TBODY>
</TABLE>
";
clmain_body();
?>