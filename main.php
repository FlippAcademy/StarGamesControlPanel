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
$query = "SELECT memory_value1,memory_value2,memory_value3 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object=\"ro_message\"";
$sql->result = $sql->execute_query($query,'main.php');$sql->total_query++;
$row = $sql->fetch_row();
$announced_date = get_date("Y-m-j",$row[memory_value2]);
$announced_mes = $row[memory_value3] ? replace_text($row[memory_value3]) : "--------------------";
opmain_body("$lang[RO_Mes]",150);
echo "<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
	<TBODY>
		<TR class=\"topic_title5\">
			<TD width=\"100%\" height=\"25\" vAlign=\"top\"></TD>
		</TR>
		<TR class=\"topic_title6\" height=\"100%\">
			<TD class=\"title_face4\" vAlign=\"top\">
				<div class=\"poststyle\">$announced_mes</div>
			</TD>
		</TR>
		<TR class=\"topic_title5\">
			<TD width=\"100%\" vAlign=\"top\" align=\"right\">
				<B>$lang[Announced] <U>$row[memory_value1]</U>, $lang[Date]: $announced_date</B>
			</TD>
		</TR>
	<TBODY>
</TABLE>
<script type='text/javascript'>
	var max_width = ".$CONFIG_max_img_width.";
	var max_height = ".$CONFIG_max_img_height.";
	var total_img_resize = ".$CP[images_num].";
	window.onload=resize_img;
</script>
";
clmain_body();
include_once "news.php";
if($CONFIG_show_last_topic_reply) {
echo "<BR>
";
opmain_body("$lang[Last_TRP]");
echo "<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
	<TBODY>
";
$query = "SELECT us.display_name, t.* FROM $CONFIG_sql_cpdbname.board_topic t
		LEFT JOIN $CONFIG_sql_cpdbname.user_profile us ON (us.user_id=t.topic_lastreply_name)
	ORDER by t.topic_last_action_date DESC LIMIT ".mysql_res($CONFIG_show_last_topic_reply_per)."";
$sql->result = $sql->execute_query($query,'main.php');$sql->total_query++;
if ($sql->count_rows()>0) {
	while ($row = $sql->fetch_row()) {
		$topic_lastreply_name = get_displayname($row[display_name],$row[topic_lastreply_name]);
		$topic_lastreply_name = $topic_lastreply_name==$lang[Guest]?$lang[Guest]:"<a href=\"index.php?showuser=".md5($row[topic_lastreply_name])."\">$topic_lastreply_name</a>";
		$topic_start_date = get_date("M j y, H:i:s A",$row[topic_start_date]);
		if($trcolor2!='topic_title4')
			$trcolor2='topic_title4';
		else
			$trcolor2='topic_title3';
echo "		<TR class=\"$trcolor2\" height=\"20\">
			<TD align=\"center\" width=\"5%\"><img src=\"theme/$STORED[THEME]/images/f_norm.gif\"></TD>
			<TD width=\"70%\">
				&nbsp;&nbsp;<a href=\"index.php?showtopic=$row[topic_id]&view=getnewpost\" title=\"This topic was started: $topic_start_date\">$row[topic_name]</a>
			</TD>
			<TD width=\"25%\" align=\"left\">
				&nbsp;&nbsp;<a href=\"index.php?showtopic=$row[topic_id]&view=getnewpost\">$lang[Last_Post]</a>: ".$topic_lastreply_name."
			</TD>
		</TR>
";
	}
}
else {
echo "		<TR class=\"topic_title5\" height=\"20\"><TD></TD></TR>
		<TR class=\"topic_title4\" height=\"20\">
			<TD align=\"center\">
				<B>$lang[Topic_nf]</B>
			</TD>
		</TR>
";
}
echo "	<TBODY>
</TABLE>
";
clmain_body();
}
echo"<BR>
";
if($CONFIG_show_guild_standing)
	include_once "guild_standing.php";
?>
