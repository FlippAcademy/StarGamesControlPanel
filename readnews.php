<?php
if(!$SERVER['system_safe']) exit();
if(checkprivilege_action($CP[login_id],g_read_news)) {
opmain_body("Read News");
echo "
<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<TBODY>
";
		include_once "function/inc_news.php";
echo "
	</TBODY>
</TABLE>
";
clmain_body();
echo "
<BR>
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<form>
	<TD align=\"right\">
		<input type=\"button\" value=\"$lang[Addnews]\" onClick=\"hyperlink('index.php?act=action_news&code=01');\"class=\"textinput\">
	</TD>
	</form>
</TABLE>
";
}
else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
?>