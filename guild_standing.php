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
opmain_body("$lang[Guild_CSD]");
echo "
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\">
	<TBODY>
		<TR align=\"center\" class=\"topic_title3\" style=\"font-weight: bold;\">
			<TD>Castle</TD>
			<TD>Guild</TD>
			<TD>Guild Master</TD>
			<TD>Emblem</TD>
		</TR>
";
$query = "SELECT castle_id,guild_id FROM $CONFIG_sql_dbname.guild_castle WHERE guild_id!=\"\" order by castle_id ASC";
$sql->result = $sql->execute_query($query,'guild_standing.php');$sql->total_query++;
if ($sql->count_rows()>0) {
	$countstanding=0;
	include_once "config_guild.php";
	while ($row = $sql->fetch_row()) {
		$gvalue=$row[castle_id];
		if($GUILD_CASTLE[$gvalue]){
			$countstanding=1;
			$gvalue = getcastlename($gvalue);
			$query = "SELECT name,master,emblem_data FROM $CONFIG_sql_dbname.guild WHERE guild_id=\"".$row['guild_id']."\"";
			$sql->result2 = $sql->execute_query($query,'guild_standing.php');
			$grow = $sql->fetch_row($sql->result2);
			$guild_name = htmlspecialchars($grow['name']);
			$g_master_name = htmlspecialchars($grow['master']);
			$guild_id=$row[guild_id];
			$emblems[$guild_id]=$grow[emblem_data];
echo "
		<TR align=\"center\" class=\"topic_title4\">
			<TD>$gvalue</TD>
			<TD><a href=\"index.php?act=guildinfo&guild_id=$guild_id\">$guild_name</a></TD>
			<TD>$g_master_name</TD>
			<TD><img src=\"emblem.php?data=$guild_id\" alt=\"$guild_name\"></TD>
		</TR>
";
		}
	}
		if (isset($emblems)) {
			session_register(emblems);
			$_SESSION['emblems'] = $emblems;
		}
} else {
	$countstanding=1;
echo "
		<TR align=\"center\" class=\"topic_title4\">
			<TD colspan=\"4\">
				$lang[Guild_nf]
			</TD>
		</TR>
";
}
if(!$countstanding) {
echo "		<TR align=\"center\" class=\"topic_title4\">
			<TD colspan=\"4\">
				$lang[Guild_nf]
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