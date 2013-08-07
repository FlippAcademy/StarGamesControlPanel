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
if ($STORED_loginname && $STORED_loginpass && !empty($player_rank_menu)) {
opmain_body("Searching Option");
$charname =checkstring($GET_charname);
$GET_rows = (int)$GET_rows;
if($GET_ctype == 'allword') {
	$s_allword = ' selected';
	$search_ctype = "= \"".mysql_res($charname)."\"";
} else {
	$GET_ctype = 'anyword';
	$s_anyword = ' selected';
	$search_ctype = "LIKE \"%".mysql_res($charname)."%\"";
}
echo "
<TABLE width=\"100%\" cellspacing=\"5\" cellpadding=\"0\" align=\"center\" class=\"topic_title6\">
	<TBODY>
		<form name=\"Ranking_Sort\">
		<TR>
			<TD width=\"30%\">Character name:</TD>
			<TD width=\"70%\">
				<input type=\"text\" name=\"charname\" value=\"".checkstring($GET_charname)."\" size=\"20\" class=\"textinput\">
				<select name=\"ctype\" class=\"textinput\">
					<option value=\"anyword\"".$s_anyword.">Any words</option>
					<option value=\"allword\"".$s_allword.">All words</option>
				</select>
			</TD>
		</TR>
		<TR>
			<TD>Sort by class:</TD>
			<TD>
				<select name=\"job\" class=\"textinput\">
";
if(($GET_job >= '0' && $GET_job <= '25') || ($GET_job >= '4001' && $GET_job <= '4049') || ($GET_job >= '4054' && $GET_job <= '4087') || ($GET_job >= '4096' && $GET_job <= '4112') || ($GET_job == '4190' && $GET_job == '4191') || ($GET_job == '4211' && $GET_job == '4212') && $GET_job != 'all')
	$select = '';
else {
	$GET_job = 'all';
	$select = ' selected';
}
echo "					<option value=\"all\"".$select.">All Class</option>\n";
for($j=0;$j<=4212;$j++) {
	if($GET_job==$j && $GET_job != 'all')
		$select=' selected';
	else
		$select='';
echo "					<option value=\"$j\"".$select.">$jobname[$j]</option>\n";
	if($j==25)
		$j=4000;
}
echo "				</select>
			</TD>
		</TR>
		<TR>
			<TD>Type:</TD>
			<TD>
				<select name=\"ptype\" class=\"textinput\">
";
if($GET_ptype=='job_level')
	$s_joblv = ' selected';
else if($GET_ptype=='max_hp')
	$s_maxhp = ' selected';
else if($GET_ptype=='max_sp')
	$s_maxsp = ' selected';
else {
	$GET_ptype = 'base_level';
	$s_baselv = ' selected';
}
echo "					<option value=\"base_level\"".$s_baselv.">Base Level</option>
					<option value=\"job_level\"".$s_joblv.">Job Level</option>
					<option value=\"max_hp\"".$s_maxhp.">Max HP</option>
					<option value=\"max_sp\"".$s_maxsp.">Max SP</option>
				</select>
			</TD>
		</TR>
		<TR>
			<TD>Key:</TD>
			<TD>
				<select name=\"key\" class=\"textinput\">
";
if($GET_key=='ASC')
	$s_asc = 'selected';
else {
	$GET_key = 'DESC';
	$s_desc = 'selected';
}
echo "					<option value=\"DESC\" ".$s_desc.">Descending</option>
					<option value=\"ASC\" ".$s_asc.">Ascending</option>
				</select>
			</TD>
		</TR>
		<TR>
			<TD>Status:</TD>
			<TD>
				<select name=\"status\" class=\"textinput\">
";
if($GET_status=='on')
	$s_on = 'selected';
else if($GET_status=='off')
	$s_off = 'selected';
else {
	$GET_status = 'all';
	$s_sall = 'selected';
}
echo "					<option value=\"all\" ".$s_sall.">All</option>
					<option value=\"on\" ".$s_on.">Online</option>
					<option value=\"off\" ".$s_off.">Offline</option>
				</select>
			</TD>
		</TR>
";
if(empty($GET_rows))
	$GET_rows=10;
echo "		<TR>
			<TD>Row(s):</TD>
			<TD>
				<input type=\"text\" name=\"rows\" value=\"$GET_rows\" size=\"2\" maxlength=\"3\" class=\"textinput\">
				<input type=\"button\" name=\"Button\" value=\"Display\" class=\"textinput\" OnClick=\"return CheckRank();\">
			</TD>
";
if($GET_status=='on')
	$status = '1';
if($GET_status=='off')
	$status = '0';
if($GET_job!='all') {
	$job="WHERE class=\"".mysql_res((int)$GET_job)."\"";
	$where='AND';
} else {
	$job='';
	$where='WHERE';
}
if($GET_status!='all') {
	$online="$where online=\"".mysql_res((int)$status)."\"";
} else {
	$online='';
}
echo "		</form>
	</TBODY>
</TABLE>
";
clmain_body();
$i=0;
$showid="";
$query = "SELECT account_id FROM $CONFIG_sql_cpdbname.ranking_ignore";
$sql->result = $sql->execute_query($query,'player_rank.php');$sql->total_query++;
while ($RIG = $sql->fetch_row()) {
	if(!$i) {
		$showid="account_id != \"".mysql_res((int)$RIG['account_id'])."\"";
		$i=1;
	} else
		$showid.= " AND account_id != \"".mysql_res((int)$RIG[account_id])."\"";
}
if(!$CONFIG_show_gm_ranking) {
	$query = "SELECT account_id FROM $CONFIG_sql_dbname.login WHERE group_id>=\"".mysql_res($CONFIG_min_groupid_ranking)."\"";
	$sql->result = $sql->execute_query($query,'player_rank.php');$sql->total_query++;
	while ($RGM = $sql->fetch_row()) {
		if(!$i) {
			$showid="account_id != \"".mysql_res((int)$RGM[account_id])."\"";
			$i=1;
		}
		else
			$showid.= " AND account_id != \"".mysql_res((int)$RGM[account_id])."\"";
	}
}
if(empty($showid))
	$showid="";
else {
	if(!$job && !$online)
		$where2='WHERE';
	else
		$where2='AND';
	$showid="".$where2." ".$showid."";
}
if($GET_rows < 1 || $GET_rows > 100) $GET_rows=10;
if($GET_charname) {
	$search_name_1 = " AND name ".$search_ctype."";
} else {
	$search_name_1 = "";
}
if($GET_map)
	$search_map = " AND last_map LIKE \"%".mysql_res($GET_map)."%\"";
else
	$search_map = "";
if(!isset($GET_st)) $GET_st = 0;
$GET_st = (int)$GET_st;
$page=get_page($GET_st,$GET_rows);
$sql->result = $sql->execute_query("SELECT COUNT(*) FROM $CONFIG_sql_dbname.char ".$job." ".$online." ".$showid." ".$search_name_1."".$search_map."",'player_rank.php');
$total = $sql->result();

$query = "SELECT account_id,name,class,base_level,job_level,max_hp,max_sp,last_map,online
	FROM $CONFIG_sql_dbname.char
	".$job." ".$online." ".$showid." ".$search_name_1."".$search_map."
	ORDER by ".mysql_res($GET_ptype)." ".mysql_res($GET_key)." LIMIT ".mysql_res($GET_st).",".mysql_res($GET_rows)."";
$sql->result = $sql->execute_query($query,'player_rank.php');$sql->total_query++;

echo "<BR>
<TABLE width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<TBODY>
		<TR>
			<TD align=\"right\">
";
				get_selectpage($total,$GET_rows,$page,"index.php?act=p_rank&charname=$charname&ctype=$GET_ctype&job=$GET_job&ptype=$GET_ptype&map=$GET_map&key=$GET_key&status=$GET_status&rows=$GET_rows");
echo "			</TD>
		</TR>
	</TBODY>
</TABLE>
<BR>
";
opmain_body("Player(s) Ranking");
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\">
	<TBODY>
		<TR align=\"center\" class=\"topic_title3\" style=\"font-weight: bold;\">
			<TD>No.</TD>
			<TD>Name</TD>
			<TD>Class</TD>
			<TD>Base Level</TD>
			<TD>Job Level</TD>
			<TD>Max HP</TD>
			<TD>Max SP</TD>
			<TD>Last Map</TD>
			<TD>Status</TD>
		</TR>
";
if ($sql->count_rows()) {
$IS_SEARCHING_ID=checkprivilege_action($CP[login_id],g_searching_id)?1:0;
$n = ($page-1)*$GET_rows;
	while ($row = $sql->fetch_row()) {
		$n++;
		if($row[online])
			$online="<font class=\"status_on\">Online</font>";
		else
			$online="<font class=\"status_off\">Offline</font>";
		$jobid = $row['class'];
		$name=$IS_SEARCHING_ID?"<a href=\"index.php?act=searching_id&account_id=$row[account_id]\">".htmlspecialchars($row['name'])."</a>":"".htmlspecialchars($row['name'])."";
		$last_map=$IS_SEARCHING_ID?"<a href=\"index.php?act=p_rank&charname=$charname&ctype=$GET_ctype&job=$GET_job&ptype=$GET_ptype&map=$row[last_map]&key=$GET_key&status=$GET_status&rows=$GET_rows\">$row[last_map]</a>":$row[last_map];
echo "		<TR align=\"center\" class=\"topic_title4\">
			<TD>$n</TD>
			<TD>$name</TD>
			<TD>$jobname[$jobid]</TD>
			<TD>$row[base_level]</TD>
			<TD>$row[job_level]</TD>
			<TD>$row[max_hp]</TD>
			<TD>$row[max_sp]</TD>
			<TD>$last_map</TD>
			<TD>$online</TD>
		</TR>
";
	}
} else {
echo "		<TR align=\"center\" class=\"topic_title4\">
			<TD colspan=\"10\">$lang[PlayerR_nf]</TD>
		</TR>
";
}
echo "	</TBODY>
</TABLE>
";
clmain_body();
}
?>
