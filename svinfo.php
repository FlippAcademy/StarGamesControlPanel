<?php
if(!$SERVER['system_safe']) exit();
$query = "SELECT memory_value3 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object=\"server_info\"";
$sql->result = $sql->execute_query($query,'main.php');$sql->total_query++;
$row = $sql->fetch_row();
$serverinfo_mes = $row[memory_value3] ? replace_text($row[memory_value3]) : "--------------------";
opmain_body("Server Information",150);
echo "
<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\">
	<TBODY>
		<TR class=\"topic_title5\">
			<TD width=\"100%\"  height=\"25\" vAlign=\"top\"></TD>
		</TR>
		<TR class=\"topic_title6\" height=\"100%\">
			<TD class=\"title_face4\" vAlign=\"top\">
				<div class=\"poststyle\">".$serverinfo_mes."</div>
			</TD>
		</TR>
		<TR class=\"topic_title5\">
			<TD width=\"100%\" height=\"25\"></TD>
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
?>