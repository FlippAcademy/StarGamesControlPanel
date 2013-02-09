<?php
if(!$SERVER['system_safe']) exit();
echo "<BR>
";
opmain_body("$lang[RO_News]");
echo "<TABLE width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	<TBODY>
";
		include"function/inc_news.php";
echo "	</TBODY>
</TABLE>
";
clmain_body();
?>