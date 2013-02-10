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
$GET_post_id = (int)$GET_post_id;
$query = "SELECT title,message,poster,date FROM $CONFIG_sql_cpdbname.mainnews WHERE post_id = \"".mysql_res($GET_post_id)."\" LIMIT 0,1";
$sql->result = $sql->execute_query($query,'shownews.php');$sql->total_query++;
if ($sql->count_rows()) {
$row = $sql->fetch_row();
$title_news = $row[title];
$reply_message = replace_text($row[message]);
$poster_news=$row[poster];
$date_news = date("Y-m-j",$row[date]);
opmain_body("$lang[Title] : $title_news",150);
echo "
<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\">
	<TR class=\"topic_title5\">
		<TD width=\"100%\" vAlign=\"top\">
			&nbsp;
		</TD>
	</TR>
	<TR class=\"topic_title6\" height=\"100%\">
		<TD class=\"title_face4\" vAlign=\"top\">
			<div class=\"poststyle\">$reply_message</div>
		</TD>
	</TR>
	<TR class=\"topic_title5\">
		<TD align=\"right\" vAlign=\"top\">
			<B>$lang[Announced] <U>$poster_news</U>, $lang[Date]: $date_news</B>
		</TD>
	</TR>
</TABLE>
<script type='text/javascript'>
	var max_width = ".$CONFIG_max_img_width.";
	var max_height = ".$CONFIG_max_img_height.";
	var total_img_resize = ".$CP[images_num].";
	window.onload=resize_img;
</script>
";
clmain_body();
} else {
	redir("index.php?act=idx","$lang[News_nf]",3);
}
?>