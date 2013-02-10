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