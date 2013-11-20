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
if(!$sql->total_query) $sql->total_query = 0;
$CP['end_time'] = getmicrotime();
$execution_time = ($CP[end_time] - $CP[start_time]);
$execution_time = floor(($execution_time) * 1000 + .5) * .001;
$process_gzip = $CONFIG_gzip? Enable : Disable;
mysql_close($sql->link);
echo "<TR>
	<TD colspan=\"2\" align=\"center\">
<div class=\"footerface\">
	[ <img src=\"theme/".$STORED[THEME]."/images/stat_time.gif\"> Script Execution time: $execution_time ]
	&nbsp;&nbsp;[ <img src=\"theme/".$STORED[THEME]."/images/stat_sql.gif\"> $sql->total_query queries used ]
	&nbsp;&nbsp;[ <img src=\"theme/".$STORED[THEME]."/images/stat_gzip.gif\"> GZIP  $process_gzip ]
	&nbsp;&nbsp;[ Theme: <a class=\"theme_name\" title=\"$THEME[comment]\">$THEME[name]</a> by <a href=\"mailto:$THEME[author_email]\" title=\"$THEME[author_email]\">$THEME[author]</a> ]
	<BR>$CP[name] &copy; $CP[corp]. All Rights Reserved. $CP[credit]
</div>
	</TD>
</TR>
		</TD>
	</TR>
</TABLE>
</body>
</html>";
include_once "gzip_footer.php";
?>
