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
$query = "SELECT account_id,userid FROM $CONFIG_sql_dbname.login WHERE md5(`account_id`) =\"".mysql_res($GET_showuser)."\"";
$sql->result = $sql->execute_query($query,'showuser.php');$sql->total_query++;
if($sql->count_rows()) {
	$row = $sql->fetch_row();
	$account_id = $row["account_id"];
	$userid = $row["userid"];
	$IS_SEARCHING_ID = checkprivilege_action($CP[login_id],g_searching_id)?1:0;
	$query = "SELECT user_id FROM $CONFIG_sql_cpdbname.user_profile WHERE md5(`user_id`) =\"".mysql_res($GET_showuser)."\"";
	$sql->result = $sql->execute_query($query,'showuser.php');$sql->total_query++;
	if(!$sql->count_rows()) {
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.user_profile (user_id,display_name,user_sls_pass,user_time_offset ,user_joined) VALUES (\"".mysql_res($account_id)."\",\"".mysql_res($userid)."\",\"\",\"".mysql_res($CONFIG_time_offset)."\",\"".$CP['time']."\")",'showuser.php');$sql->total_query++;
		$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.privilege (account_id,privilege) VALUES (\"".mysql_res($account_id)."\",\"2\")",'showuser.php');$sql->total_query++;
	} else {
		$row = $sql->fetch_row();
		$account_id = $row["user_id"];
	}
	$username = get_username($account_id);
	$show_username = $IS_SEARCHING_ID?"<a href=\"index.php?act=searching_id&account_id=$account_id\">$username</a>":"$username";
	get_cp_profile($account_id);
opmain_body("User Information: $username",0,'100%');
echo "<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" class=\"topic_title6\">
	<TR class=\"topic_title5\"><TD height=\"25\"></TD></TR>
	<TR>
		<TD>
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"topic_title6\">
	<TR>
		<TD>
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	<TR>
		<TD width=\"30%\" vAlign=\"top\">
			<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"10\">
				<TR>
					<TD align=\"left\">
						<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
							<TR>
								<TD>
									$reply_avatar<BR><BR>
									$rank_title<BR>
									<img src=\"theme/$STORED[THEME]/images/groups/$reply_imgroup\" alt=\"Group Icon\"><BR><BR>
									$lang[Group]: $reply_group<BR>
									$lang[Posts]: $reply_post</B><BR>
									$lang[Mem_No]: $reply_number</B><BR>
									$lang[Join]: $reply_joined<BR><BR>
									$status_bar
								</TD>
							</TR>
						</TABLE>
					</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
</TABLE>
		</TD>
		<TD width=\"70%\" vAlign=\"top\">
";
	$query = "SELECT name,class,base_level,job_level,online FROM $CONFIG_sql_dbname.char WHERE md5(`account_id`) =\"".mysql_res($GET_showuser)."\"";
	$sql->result = $sql->execute_query($query,'showuser.php');$sql->total_query++;
	if($sql->count_rows()) {
echo "			<TABLE width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"10\">
				<TR>
					<TD>
			<TABLE width=\"80%\" height=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"topic_title\">
				<TBODY>
					<TR align=\"center\" class=\"title_bar\" height=\"29\">
						<TD>
							<a class=\"m_title\">Character in user: $show_username</a>
						</TD>
					</TR>
					<TR>
						<TD>
							<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\">
								<TBODY>
									<TR align=\"center\" class=\"topic_title3\" style=\"font-weight: bold;\">
										<TD>No.</TD>
										<TD>Name</TD>
										<TD>Class</TD>
										<TD>Base Level</TD>
										<TD>Job Level</TD>
										<TD>Status</TD>
									</TR>
";
		$n=0;
		while ($c_row = $sql->fetch_row()) {
			$n++;
			$jobid="$c_row[class]";
			if($c_row[online])
				$online="<font class=\"status_on\">Online</font>";
			else
				$online="<font class=\"status_off\">Offline</font>";
echo "									<TR align=\"center\" class=\"topic_title4\">
										<TD>$n</TD>
										<TD>$c_row[name]</TD>
										<TD>$jobname[$jobid]</TD>
										<TD>$c_row[base_level]</TD>
										<TD>$c_row[job_level]</TD>
										<TD>$online</TD>
									</TR>
";
		}
echo "								</TBODY>
							</TABLE>
						</TD>
					</TR>
			</TABLE>
						</TD>
					</TR>
				</TBODY>
			</TABLE>
			<BR>
";
	}
echo "		</TD>
	</TR>
</TABLE>
";

	if(!empty($reply_signature)) {
		$reply_signature_ = replace_text($reply_signature);
opmain_body("Signature",0,'95%');
echo "			<TABLE width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\">
				<TR class=\"topic_title5\">
					<TD height=\"25\"></TD>
				</TR>
				<TR class=\"topic_title8\">
					<TD>$reply_signature_</TD>
				</TR>
				<TR class=\"topic_title5\">
					<TD height=\"25\"></TD>
				</TR>
			</TABLE>
";
clmain_body();
	}
echo "		</TD>
	</TR>
	<TR class=\"topic_title5\"><TD height=\"25\"></TD></TR>
</TABLE>
";
clmain_body();
} else {
	redir("index.php?act=idx","$lang[No_user]",3);
}
?>
