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
if(checkprivilege_action($CP[login_id],g_read_privilege)) {
if($GET_code==00){
opmain_body("Privilege Control");
echo "
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" align=\"center\">
	<TBODY>
		<form action=\"index.php?act=privilege&code=01\" method=\"post\" enctype=\"multipart/form-data\" name=\"Addprivilege\" onsubmit=\"return CheckAddprivilege()\">
		<TR class=\"topic_title5\">
			<TD width=\"50%\">
				$lang[Search_Privilege]
			</TD>
			<TD width=\"50%\">
				<input name=\"account\" type=\"text\" size=\"12\" class=\"textinput\">
				<input name=\"Submit\" type=\"submit\" value=\"$lang[OK]\" class=\"textinput\">
			</TD>
		</TR>
		</form>
		<TR align=\"center\" class=\"topic_title3\">
			<TD colspan=\"2\">
				<input type=\"button\" value=\"$lang[AddprivilegeID]\" class=\"textinput\" OnClick=\"hyperlink('index.php?act=privilege&code=02');\">
				<input type=\"button\" value=\"$lang[Privilege_Groups]\" class=\"textinput\" OnClick=\"hyperlink('index.php?act=privilege&code=03');\">
			</TD>
		</TR>
	</TBODY>
</TABLE>
";
clmain_body();
}
if($GET_code==01 && ($POST_account = (int)$POST_account)){
opmain_body("Searching an account id ".$POST_account."");
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" align=\"center\" class=\"emptytable\">
	<TBODY>
";
	$query = "SELECT privilege FROM $CONFIG_sql_cpdbname.privilege WHERE account_id = \"".mysql_res($POST_account)."\"";
	$sql->result = $sql->execute_query($query,'privilege.php');$sql->total_query++;
	$row = $sql->fetch_row();
	if ($sql->count_rows()) {
		$query = "SELECT g_id,g_title FROM $CONFIG_sql_cpdbname.groups ORDER by g_id ASC";
		$sql->result = $sql->execute_query($query,'privilege.php');$sql->total_query++;
echo "		<TR class=\"topic_title5\">
			<TD colspan=\"2\">
";
		while($row2 = $sql->fetch_row()) {
echo "				<b>$row2[g_id] = $row2[g_title],</b>
";
		}
echo "
			</TD>
		</TR>
	<form action=\"index.php?act=privilege_action&code=01\" method=\"post\" enctype=\"multipart/form-data\">
		<input type=\"hidden\" name=\"account\" value=\"$POST_account\">
		<TR class=\"topic_title6\">
			<TD width=\"40%\">
				$lang[Pls_Input_aclv]
			</TD>
			<TD width=\"60%\">
				<input name=\"g_id\" type=\"text\" size=\"1\" class=\"textinput\" value=\"$row[privilege]\" maxlength=\"3\">
				<input name=\"Submit\" type=\"submit\" value=\"$lang[OK]\" class=\"textinput\">
			</TD>
		</TR>
	</form>
";
}
else {
echo "		<TR class=\"topic_title5\" height=\"25\">
			<TD></TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD>$lang[Search_Privilege_Fail]</TD>
		</TR>
";
}
echo "	</TBODY>
</TABLE>
";
clmain_body();
}
if($GET_code==02){
opmain_body("Add Privilege Form");
	$query = "SELECT g_id,g_title FROM $CONFIG_sql_cpdbname.groups ORDER by g_id ASC";
	$sql->result = $sql->execute_query($query,'privilege.php');$sql->total_query++;
echo "
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" align=\"center\" class=\"emptytable\">
	<TBODY>
		<TR class=\"topic_title5\">
			<TD colspan=\"3\">
";
	while($row = $sql->fetch_row()) {
echo "				<B>$row[g_id] = $row[g_title],</B>
";
	}
echo "
			</TD>
		</TR>
	<form action=\"index.php?act=privilege_action&code=00\" method=\"post\" enctype=\"multipart/form-data\" name=\"Addprivilege\" onsubmit=\"return CheckAddprivilege()\">
		<TR class=\"topic_title6\">
			<TD width=\"50%\">
				$lang[Account] :
				<input name=\"account\" type=\"text\" size=\"12\" class=\"textinput\">
			</TD>
			<TD width=\"20%\">
				$lang[Privilege_level] :
				<input name=\"g_id\" type=\"text\" size=\"1\" class=\"textinput\" value=\"2\" maxlength=\"3\">
			</TD>
			<TD width=\"30%\">
				<input name=\"Submit\" type=\"submit\" value=\"$lang[Sentprivilege]\" class=\"textinput\">
				<input name=\"Reset\" type=\"reset\" value=\"$lang[Resetprivilege]\" class=\"textinput\">
			</TD>
		</TR>
	</form>
	</TBODY>
</TABLE>
";
clmain_body();
}
if($GET_code==03){
opmain_body("Privilege Group Control");
	$query = "SELECT g_id,g_title FROM $CONFIG_sql_cpdbname.groups ORDER by g_id ASC";
	$sql->result = $sql->execute_query($query,'privilege.php');$sql->total_query++;
echo "
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" class=\"topic_title6\">
	<TR>
		<TD>
			<TABLE width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
				<form name=\"ADD_Privilege\">
					<TR>
						<TD align=\"right\">
							<input type=\"button\" value=\"$lang[Addprivilegegroup]\" class=\"textinput\" OnClick=\"hyperlink('index.php?act=privilege&code=04');\">
						</TD>
					</TR>
				</form>
			</TABLE>
		</TD>
	</TR>
	<TR>
		<TD>
			<TABLE width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"topic_title\" align=\"center\">
				<TBODY>
					<TR align=\"center\" class=\"title_bar\" height=\"29\">
						<TD>
							<a class=\"m_title\">Privilege Groups</a>
						</TD>
					</TR>
					<TR>
						<TD>
							<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\">
								<TBODY>
									<TR align=\"center\" class=\"topic_title3\" height=\"20\">
										<TD width=\"15%\">
										<B>Group ID</B>
										</TD>
										<TD width=\"71%\">
											<B>Title</B>
										</TD>
										<TD width=\"7%\">
										</TD>
										<TD width=\"7%\">
										</TD>
									</TR>
";
	while($row = $sql->fetch_row()) {
echo "
									<TR align=\"center\" class=\"topic_title4\">
										<TD>
											$row[g_id]
										</TD>
										<TD>
											$row[g_title]
										</TD>
										<TD>
											<a href=\"index.php?act=privilege&code=03&gid=$row[g_id]\" title=\"á¡éä¢\"><img src =\"theme/$STORED[THEME]/images/edit.gif\" border=\"0\"></a>
										</TD>
										<TD>
											<a href=\"index.php?act=privilege_action&code=03&g_id=$row[g_id]\" title=\"Åº\"><img src =\"theme/$STORED[THEME]/images/drop.gif\" border=\"0\"></a>
										</TD>
									</TR>
";
	}
echo "
								</TBODY>
							</TABLE>
						</TD>
					</TR>
				</TBODY>
			</TABLE>
";
	if($GET_gid = (int)$GET_gid){
		$query = "SELECT * FROM $CONFIG_sql_cpdbname.groups WHERE g_id=\"".mysql_res($GET_gid)."\"";
		$sql->result = $sql->execute_query($query,'privilege.php');$sql->total_query++;
		$group = $sql->fetch_row();
echo "
<BR>
			<TABLE width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"topic_title\" align=\"center\">
				<TBODY>
				<form action=\"index.php?act=privilege_action&code=02\" method=\"post\" enctype=\"multipart/form-data\">
					<input type=\"hidden\" name=\"g_id\" value=\"$GET_gid\">
					<TR align=\"center\" class=\"title_bar\" height=\"29\">
						<TD>
							<a class=\"m_title\">Edit Group : $group[g_title]
						</TD>
					</TR>
					<TR>
						<TD>
							<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\">
								<TR align=\"center\">
									<TD class=\"topic_title4\">
										$lang[Privilegemes_1]
									</TD>
									<TD class=\"topic_title6\">
										<input name=\"g_1\" type=\"text\" size=\"12\" class=\"textinput\" value=\"$group[1]\">
									</TD>
								</TR>
								<TR align=\"center\">
									<TD class=\"topic_title4\">
										$lang[Privilegemes_2]
									</TD>
									<TD class=\"topic_title6\">
										<input name=\"g_2\" type=\"text\" size=\"12\" class=\"textinput\" value=\"$group[2]\">
									</TD>
								</TR>
								<TR align=\"center\">
									<TD class=\"topic_title4\">
										$lang[Privilegemes_3]
									</TD>
									<TD class=\"topic_title6\">
										<input name=\"g_3\" type=\"text\" size=\"12\" class=\"textinput\" value=\"$group[3]\">
									</TD>
								</TR>
";
		for($a=4;$a<=MAX_GROUP_PRIVILEGE;$a++){
			$privilegemes="Privilegemes_$a";
			if($group[$a]) {
				$selected="checked";
				$unselected="";
			} else {
				$selected="";
				$unselected="checked";
			}
echo "
								<TR align=\"center\">
									<TD class=\"topic_title4\">
										$lang[$privilegemes]
									</TD>
									<TD class=\"topic_title6\">
										Yes
										<input type=\"radio\" name=\"g_$a\" value=\"1\" class=\"textinput\" ".$selected.">&nbsp;
										<input type=\"radio\" name=\"g_$a\" value=\"0\" class=\"textinput\" ".$unselected.">
										No
									</TD>
								</TR>
";
}
echo "
								<TR class=\"topic_title5\">
									<TD colspan=\"2\" align=\"center\">
										<input name=\"Submit\" type=\"submit\" value=\"$lang[Save]\" class=\"textinput\">
										<input name=\"Reset\" type=\"reset\" id=\"Reset\" value=\"$lang[Restore]\" class=\"textinput\">
									</TD>
								</TR>
							</TABLE>
						</TD>
					</TR>
				</form>
				</TBODY>
			</TABLE>
";
} 
echo "
			<BR>
		</TD>
	</TR>
</TABLE>

";
clmain_body();
}
if($GET_code==04){
if(checkprivilege_action($CP[login_id],g_add_privilege)) {
?>
<script language=JavaScript>
	function CheckAddPrivilegeGroup(){
		var A1 = document.AddPrivilegeGroup.g_1.value;
		if (A1.length < 1) {
			alert("Please enter a title at least 1 characters.");
			document.AddPrivilegeGroup.g_1.focus();return false;
		} else {
			document.AddPrivilegeGroup.Submit.disabled=true;
			return true;
		}
	}
</script>
<?php
opmain_body("Add Privilege Group");
echo "
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" class=\"topic_title6\">
	<TR>
		<TD>
			<BR>
			<TABLE width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"topic_title\" align=\"center\">
				<TBODY>
					<form action=\"index.php?act=privilege_action&code=04\" method=\"post\" enctype=\"multipart/form-data\" name=\"AddPrivilegeGroup\" OnSubmit=\"return CheckAddPrivilegeGroup();\">
					<TR align=\"center\" class=\"title_bar\" height=\"29\">
						<TD>
							<a class=\"m_title\">Add Privilege Group Form
						</TD>
					</TR>
					<TR>
						<TD>
							<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\">
								<TR align=\"center\">
									<TD class=\"topic_title4\">
										$lang[Privilegemes_1]
									</TD>
									<TD class=\"topic_title6\">
										<input name=\"g_1\" type=\"text\" size=\"12\" class=\"textinput\">
									</TD>
								</TR>
								<TR align=\"center\">
									<TD class=\"topic_title4\">
										$lang[Privilegemes_2]
									</TD>
									<TD class=\"topic_title6\">
										<input name=\"g_2\" type=\"text\" size=\"12\" class=\"textinput\">
									</TD>
								</TR>
								<TR align=\"center\">
									<TD class=\"topic_title4\">
										$lang[Privilegemes_3]
									</TD>
									<TD class=\"topic_title6\">
										<input name=\"g_3\" type=\"text\" size=\"12\" class=\"textinput\">
									</TD>
								</TR>
";
for($a=4;$a<=MAX_GROUP_PRIVILEGE;$a++){
	$privilegemes="Privilegemes_$a";
echo "
								<TR align=\"center\">
									<TD class=\"topic_title4\">
										$lang[$privilegemes]
									</TD>
									<TD class=\"topic_title6\">
										Yes
										<input type=\"radio\" name=\"g_$a\" value=\"1\" class=\"textinput\">&nbsp;
										<input type=\"radio\" name=\"g_$a\" value=\"0\" class=\"textinput\" checked>
										No
									</TD>
								</TR>
";
}
echo "
								<TR class=\"topic_title5\">
									<TD colspan=\"2\" align=\"center\">
										<input name=\"Submit\" type=\"submit\" value=\"$lang[Save]\" class=\"textinput\">
										<input name=\"Reset\" type=\"reset\" value=\"$lang[Restore]\" class=\"textinput\">
									</TD>
								</TR>
							</TABLE>
						</TD>
					</TR>
				</form>
				</TBODY>
			</TABLE>
			<BR>
		</TD>
	</TR>
</TABLE>
";
clmain_body();
}
else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
}

}
else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
?>