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
if(!$SERVER['system_safe']) exit();
if ($STORED_loginname && $STORED_loginpass && !empty($guild_rank_menu)) {
if(!isset($GET_st)) $GET_st = 0;
$GET_st = (int)$GET_st;
$page=get_page($GET_st,$CONFIG_guild_per_page);
$query = "SELECT COUNT(*) FROM $CONFIG_sql_dbname.guild";
$sql->result = $sql->execute_query($query,'guild_rank.php');$sql->total_query++;
$total = $sql->result();

$query = "SELECT name,master,guild_id,emblem_data,guild_lv,average_lv FROM $CONFIG_sql_dbname.guild ORDER by guild_lv DESC LIMIT ".mysql_res($GET_st).",".mysql_res($CONFIG_guild_per_page)."";
$sql->result = $sql->execute_query($query,'guild_rank.php');$sql->total_query++;
echo "<TABLE width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<TBODY>
		<TR>
			<TD align=\"right\">
";
				get_selectpage($total,$CONFIG_guild_per_page,$page,"index.php?act=g_rank");
echo "
			</TD>
		</TR>
	</TBODY>
</TABLE>
<BR>
";
opmain_body("Guild Ranking");
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\">
	<TBODY>
		<TR align=\"center\" class=\"topic_title3\" style=\"font-weight: bold;\">
			<TD>No.</TD>
			<TD>Guild</TD>
			<TD>Guild Master</TD>
			<TD>Guild Level</TD>
			<TD>Average Level</TD>
			<TD>Emblem</TD>
		</TR>
";
if ($sql->count_rows()) {
	$countstanding=0;
	$i=($page-1)*$CONFIG_guild_per_page;
	while ($grow = $sql->fetch_row()) {
		$i++;
		$countstanding=1;
		$guild_name = htmlspecialchars($grow['name']);
		$g_master_name = htmlspecialchars($grow['master']);
		$guild_id=$grow[guild_id];
		$emblems[$guild_id]=$grow[emblem_data];
echo "		<TR align=\"center\" class=\"topic_title4\">
			<TD>$i</TD>
			<TD><a href=\"index.php?act=guildinfo&guild_id=$guild_id\">$guild_name</a></TD>
			<TD>$g_master_name</TD>
			<TD>$grow[guild_lv]</TD>
			<TD>$grow[average_lv]</TD>
			<TD><img src=\"emblem.php?data=$guild_id\" alt=\"$guild_name\"></TD>
		</TR>
";
	}
		if (isset($emblems)) {
			session_register(emblems);
			$_SESSION['emblems'] = $emblems;
		}
} else {
	$countstanding=1;
echo "		<TR align=\"center\" class=\"topic_title4\">
			<TD colspan=\"6\">
				$lang[GuildR_nf]
			</TD>
		</TR>
";
}
if(!$countstanding) {
echo "		<TR align=\"center\" class=\"topic_title4\">
			<TD colspan=\"6\">
				$lang[GuildR_nf]
			</TD>
		</TR>
";
}
echo "	</TBODY>
</TABLE>
";
clmain_body();
}
?>
