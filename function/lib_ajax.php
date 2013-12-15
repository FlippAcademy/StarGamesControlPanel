<?php
function get_postpreview($val) {
$val = str_replace( "symbol_and" , "&"  , $val );
$val = str_replace( "symbol_plus" , "+"  , $val );
if(!length($val,3))
echo "
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" class=\"topic_title\">
<TR class=\"topic_title5\"><TD><div class=\"title_face3\"><B>THE FOLLOWING ERROR(S) WERE FOUND</B></div></TD></TR>
<TR class=\"topic_title8\"><TD><div class=\"poststyle\">You must enter messages at least 3 characters</div></TD></TR>
</TABLE>
<br />
";
if($val) {
	$val = checkstring($val,1);
	$val = replace_text($val);
} else
	$val = "<br />";
echo "
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" class=\"topic_title\">
<TR class=\"topic_title5\"><TD><div class=\"title_face3\"><B>Post Preview</B></div></TD></TR>
<TR class=\"topic_title8\"><TD><div class=\"poststyle\">".$val."</div></TD></TR>
</TABLE>
<br /><br />
";
}

function get_poll_form() {
global $lang,$CP;
if(checkprivilege_action($CP[login_id],g_post_polls)) {
echo "
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
<TR class=\"topic_title6\"><TD width=\"30%\" align=\"right\"><div class=\"title_face4\">Poll Question</div></TD>
<TD width=\"70%\"><input name=\"t_p_title\" type=\"text\" size=\"40\" maxlength=\"50\" class=\"textinput\"> ( <a href=\"#\" onClick=\"close_poll_form(); return false;\">Close poll form</a> )</TD></TR>
";
for($tp=1;$tp<=10;$tp++) {
	if($tp!=1) {
 		$hide=$tp==2?'':'display:none;';
		$TR_hide=$tp==2?'':'display:none;';
	} else {
		$TR_hide='';
		$hide='display:none;';
	}
		$addchoiceid=$tp+100;
		$clrchoiceid=$tp+200;
		$meschoiceid=$tp+300;
echo "
<TR id=\"$tp\" class=\"topic_title6\" style=\"$TR_hide\"><TD align=\"right\"><div class=\"title_face4\">$tp.</div></TD>
	<TD>
		<input name=\"t_p_answer_$tp\" type=\"text\" size=\"40\" maxlength=\"50\" class=\"textinput\" id=\"$meschoiceid\">
		<font style=\"$hide\" id=\"$addchoiceid\">[<a href=\"#\" onClick=\"Change_Choice($tp); return false;\"><b>Add Poll Choice</b></a>]</font>
		<font style=\"display:none;\" id=\"$clrchoiceid\">[<a href=\"#\" onClick=\"Clear_Choice($tp); return false;\"><font color=\"red\"><b>X</b></a></font>]</font>
	</TD>
</TR>
";
	}
echo "</TABLE>";
} else
	echo "Sorry, but you do not have permission to use this feature.";
}

function get_page_select($name,$st) {
header("Content-type: text/xml;charset=windows-874");
$sql = new MySQL;
global $STORED,$lang,$CONFIG_show_ro_news_per,$CONFIG_sql_dbname,$CONFIG_sql_cpdbname,$CP;
$name = strip_tags($name);
if(!isset($st)) $st = 0;
$st = (int)$st;
switch($name) {
	case "news":
	case "readnews":
		$cols = '3';
		$limit = $name=='readnews'?15:$CONFIG_show_ro_news_per;
		$page = get_page($st,$limit);
		$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.mainnews";
		$sql->result = $sql->execute_query($query,'lib_ajax.php');
		$total = $sql->result();
		$query = "SELECT post_id,title,date FROM $CONFIG_sql_cpdbname.mainnews ORDER by date DESC LIMIT ".mysql_res($st).",".mysql_res($limit)."";
		$sql->result = $sql->execute_query($query,'lib_ajax.php');
		$totalp = $sql->count_rows();
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" align=\"center\">
";
		if ($totalp) {
			while ($row = $sql->fetch_row()) {
		if($trcolor!='topic_title4')
			$trcolor='topic_title4';
		else
			$trcolor='topic_title3';
		$date_news = get_date("d-m-Y",$row[date]);
echo "<TR class=\"$trcolor\" height=\"20\">
	<TD width=\"5%\" align=\"center\">
		<img src=\"theme/$STORED[THEME]/images/f_news.gif\">
	</TD>
	<TD>
		&nbsp;&nbsp;<a href=\"index.php?act=shownews&post_id=$row[post_id]\" title=\"$lang[Click_More]\">$row[title]</a>
	</TD>
	<TD width=\"25%\" align=\"center\">
		$date_news
	</TD>
";
	if($name == 'readnews') {
		$cols = '5';
echo "	<TD width=\"5%\" align=\"center\">
		<a href=\"index.php?act=action_news&code=03&post_id=$row[post_id]\"><img src=\"theme/$STORED[THEME]/images/edit.gif\" border=0 title=\"Edit\"></a>
	</TD>
	<TD width=\"5%\" align=\"center\">
		<a href=javascript:delete_post(\"index.php?act=action_news&code=05&post_id=$row[post_id]\")><img src=\"theme/$STORED[THEME]/images/drop.gif\" border=\"0\" title=\"Remove\"></a>
	</TD>
";
	}
echo " </TR>
";
			}
	if($trcolor!='topic_title3')
		$trcolor='topic_title3';
	else
		$trcolor='topic_title4';
echo "<TR class=\"$trcolor\" height=\"20\">
	<TD width=\"100%\" colspan=\"$cols\" align=\"right\" cellpadding=\"0\" cellspacing=\"0\">
";
		get_selectpage($total,$limit,$page,"index.php?act=idx",'',$name);
echo "	</TD>
</TR>
";
		} else {
echo "<TR class=\"topic_title5\" height=\"20\"><TD></TD></TR>
<TR class=\"topic_title4\" height=\"20\">
	<TD align=\"center\">
		<B>$lang[News_nf]</B>
	</TD>
</TR>
";
		}
echo "</TABLE>
";
		break;

	case "bugreport":
		$limit = '5';
		$IS_EDIT_NEWS = checkprivilege_action($CP[login_id],g_edit_news)?1:0;
		$page = get_page($st,$limit);
		$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.bugreport";
		$sql->result = $sql->execute_query($query,'lib_ajax.php');
		$total = $sql->result();

		$query = "SELECT post_id,poster,report,sex,ip,date FROM $CONFIG_sql_cpdbname.bugreport ORDER by post_id DESC LIMIT ".mysql_res($st).",".mysql_res($limit)."";
		$sql->result = $sql->execute_query($query,'lib_ajax.php');
		$totalp = $sql->count_rows();
echo "
<TABLE width=\"90%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
	<TR>
		<TD align=\"right\">
";
get_selectpage($total,$limit,$page,"index.php?act=bugreport",'','bugreport');
echo "		</TD>
	</TR>
</TABLE>
";
		$reply_num = $total-(($page-1)*$limit)+1;
		while ($row = $sql->fetch_row()) {
			$reply_num--;
			$reply_date = get_date("D-M-Y H:i:s",$row[date]);
			$reply_message = replace_text($row[report]);
			if ($row[sex] == 'M')
				$bsex = "<img src=\"theme/$STORED[THEME]/images/male.gif\" border=\"0\">";
			else
				$bsex = "<img src=\"theme/$STORED[THEME]/images/female.gif\" border=\"0\">";
echo "<br />
<TABLE width=\"90%\" border=\"0\" cellpadding=\"3\" cellspacing=\"1\" align=\"center\" class=\"emptytable3\">
	<TR class=\"topic_title8\">
		<TD width=\"25%\" align=\"center\" vAlign=\"top\">
			$bsex
		</TD>
		<TD width=\"75%\" height=\"100\" valign=\"top\">
			<a href=\"#$row[post_id]\" name=\"$row[post_id]\">$lang[Bug_postid] : #$reply_num</a><br />
			<div class=\"poststyle\">$reply_message</div>
		</TD>
	</TR>
	<TR class=\"topic_title5\">
		<TD width=\"25%\" align=\"center\">
			$row[poster]
		</TD>
		<TD width=\"75%\" align=\"right\">
";
			if ($IS_EDIT_NEWS) {
echo "			<a href=\"index.php?act=bugreport&code=02&post_id=$row[post_id]\" title=\"Edit\"><img src =\"theme/$STORED[THEME]/images/edit.gif\" border=\"0\"></a>
			&nbsp;&nbsp;&nbsp;<a href=\"index.php?act=bugreport&code=03&post_id=$row[post_id]\" title=\"Remove\"><img src =\"theme/$STORED[THEME]/images/drop.gif\" border=\"0\"></a>&nbsp;&nbsp;&nbsp;
";
			}
echo "			$lang[Date] : $reply_date IP : <a href=\"http://$row[ip]\" target=\"_blank\">$row[ip]
		</TD>
	</TR>
</TABLE>
<br />
";
		}
		break;
  }
}

function get_bb_code($id,$name) {
$id = strip_tags($id);
switch($name) {
	case "ffont":
		$font = array('Arial','Arial Black','Arial Narrow','Book Antiqua','Century Gothic','Comic Sans MS','Courier New','Franklin Gothic Medium','Garamond','Impact','Georgia','Lucida Console','Lucida Sans Unicode','Microsoft Sans Serif','Palatino Linotype','Tahoma','Times New Roman','Trebuchet MS','Verdana');
		for ($i=0;$i<19;$i++)
			echo "<a onclick=\"alterfont('$id','".$font[$i]."', 'font'); return false;\" href=\"#\" id=\"Button\"><div id=\"font-".$font[$i]."\" title=\"".$font[$i]."\" class='rte-normal' style='font-family:".$font[$i]."' onMouseout=\"switchbg_('font-".$font[$i]."',1);\" onMouseover=\"switchbg_('font-".$font[$i]."',0);\">".$font[$i]."</div></a>\n";
		break;
	case "fsize":
		$size = array('0','8','10','12','14','18','24','36');
		for ($i=1;$i<=7;$i++)
			echo "<a onclick=\"alterfont('$id','".$i."', 'size'); return false;\" href=\"#\" id=\"Button\"><div id=\"size-".$i."\" title=\"".$i."\" class='rte-normal' style='font-size:".$size[$i]."pt;font-family:Verdana' onMouseout=\"switchbg_('size-".$i."',1);\" onMouseover=\"switchbg_('size-".$i."',0);\">".$i."</div></a>\n";
		break;
	case "fcolor":
		$color = array('0','#000000','#A0522D','#556B2F','#006400','#483D8B','#000080','#4B0082','#2F4F4F','#8B0000','#FF8C00','#808000','#008000','#008080','#0000FF','#708090','#696969','#FF0000','#F4A460','#9ACD32','#2E8B57','#48D1CC','#4169E1','#800080','#808080','#FF00FF','#FFA500','#FFFF00','#00FF00','#00FFFF','#00BFFF','#9932CC','#C0C0C0','#FFC0CB','#F5DEB3','#FFFACD','#98FB98','#AFEEEE','#ADD8E6','#DDA0DD','#FFFFFF');
		echo "<TABLE border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">\n";
		for ($i=1;$i<=40;$i++) {
		if((($i-1) % 8)==0) echo "<TR>\n";
		echo "<TD onclick=\"alterfont('$id','".$color[$i]."', 'color');\"><TABLE id=\"color-".$i."\" style='background:".$color[$i].";border:1px solid #ffffff;' cellpadding=\"0\" cellspacing=\"0\" width='12' height='17' onMouseout=\"switchbd('color-".$i."',1);\" onMouseover=\"switchbd('color-".$i."',0);\"><TR><TD></TD></TR></TABLE></TD>\n";
		if(($i % 8)==0) echo "</TR>\n";
		}
		echo "</TABLE>\n";
		break;
}
}

function get_attn_reg($check,$val,$val2) {
header("Content-type: text/xml;charset=iso-8859-3");
global $CONFIG_sql_dbname,$lang;
$sql = new MySQL;
$check = strip_tags($check);
switch($check) {
	case "userid":
		$query = "SELECT userid FROM $CONFIG_sql_dbname.login WHERE userid = \"".mysql_res($val)."\"";
		if(!length($val,4,24))
			echo "- $lang[Reg_attn_1]";
		else if(!isAlphaNumeric($val))
			echo "- $lang[Reg_attn_11]";
		else if($sql->count_rows($sql->execute_query($query,'lib_ajax.php')))
			echo "- $lang[Reg_attn_2]";
		break;
	case "pass":
		if(!length($val,4,24))
			echo "- $lang[Reg_attn_3]";
		else if(!isAlphaNumeric($val))
			echo "- $lang[Reg_attn_12]";
		break;
	case "pass2":
		if($val != $val2)
			echo "- $lang[Reg_attn_4]";
		else if(!isAlphaNumeric($val))
			echo "- $lang[Reg_attn_13]";
		else if(!length($val,4,24))
			echo "- $lang[Reg_attn_5]";
		break;
	case "slspass":
		if(!length($val,4,24))
			echo "- $lang[Reg_attn_6]";
		else if(!isAlphaNumeric($val))
			echo "- $lang[Reg_attn_14]";
		break;
	case "slspass2":
		if($val != $val2)
			echo "- $lang[Reg_attn_7]";
		else if(!isAlphaNumeric($val))
			echo "- $lang[Reg_attn_15]";
		else if(!length($val,4,24))
			echo "- $lang[Reg_attn_8]";
		break;
	case "email":
		$query = "SELECT COUNT(*) FROM $CONFIG_sql_dbname.login WHERE email = \"".mysql_res($val)."\"";
		if(!isMailform($val))
			echo "- $lang[Reg_attn_9]";
		else if($sql->result($sql->execute_query($query,'lib_ajax.php')))
			echo "- $lang[Reg_attn_10]";
		break;
}
}

function get_quick_edit_form($reply_id) {
header("Content-type: text/xml;charset=windows-874");
global $CONFIG_sql_cpdbname,$STORED,$SERVER,$CONFIG_max_post_length,$CP;
$reply_id = (int)$reply_id;
$form_id = "quick_edit_form_".$reply_id."";
$sql = new MySQL;
$query = "SELECT reply_message,reply_edit_date,reply_edit_name,forum_id,topic_id FROM $CONFIG_sql_cpdbname.board_reply WHERE reply_id =\"".mysql_res($reply_id)."\"";
$sql->result = $sql->execute_query($query,'lib_ajax.php');
$row = $sql->fetch_row();
$IS_EDIT_POST = checkprivilege_action($CP[login_id],g_edit_posts)?1:0;
if(!empty($CP[login_id]) && ($CP[login_id] == $row[reply_user_id] || $IS_EDIT_POST)) {
echo "<form action=\"index.php?act=post&code=02&f=".$row[forum_id]."&t=".$row[topic_id]."&p=".$reply_id."\" method=\"post\" enctype=\"multipart/form-data\" id=\"$form_id\">
<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">
	<TR>
		<TD>
			<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" class=\"tablefill\">
			<input type=\"hidden\" name=\"p\" value=\"$reply_id\">
			<input type=\"hidden\" name=\"quick_edit\" value=\"1\">
				<TR class=\"topic_title5\">
					<TD>
";
get_bbcode($form_id,1);
$reply_message = my_br2nl($row[reply_message]);
echo "					</TD>
				</TR>
				<TR class=\"topic_title5\" align=\"center\">
					<TD>
						<textarea style=\"width:100%;\" name=\"t_mes\" cols=\"80\" rows=\"15\" class=\"textinput\">$reply_message</textarea>
					</TD>
				</TR>
				<TR class=\"topic_title5\" align=\"center\">
					<TD align=\"right\">
						<input type=\"button\" name=\"Complete\" value=\"Complete Edit\" class=\"textinput\" onclick=\"CheckPostlength('$form_id','$CONFIG_max_post_length'); save_quick_edit('$form_id','replyid_".$reply_id."');\">
						<input type=\"button\" name=\"Cancel\" value=\"Cancel Edit\" class=\"textinput\" onclick=\"restore_post('replyid_".$reply_id."');\">
						<input type=\"submit\" name=\"Submit\" value=\"Use Full Editor\" class=\"textinput\">
					</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
</TABLE>
</form>
";
} else {
	$reply_message = replace_text($row[reply_message]);
echo "<div class=\"poststyle\">$reply_message</div>";
	if (!empty($row[reply_edit_name])) {
		$reply_edit_date =  get_date("M j y, H:i:s A",$row[reply_edit_date]);
echo "<br /><br /><span class=\"edit\">This post has been edited by <B>$row[reply_edit_name]</B> on $reply_edit_date</span>";
	}
}
}

function get_save_quick_edit($val,$reply_id) {
header("Content-type: text/xml;charset=windows-874");
global $CONFIG_sql_cpdbname,$STORED,$SERVER,$CONFIG_max_post_length,$CP;
$reply_id = (int)$reply_id;
$val = iconv("UTF-8", "windows-874", $val);
$val = str_replace( "symbol_and" , "&"  , $val );
$val = str_replace( "symbol_plus" , "+"  , $val );
$form_id = "quick_edit_form_".$reply_id."";
$sql = new MySQL;
$query = "SELECT reply_id,reply_message,reply_edit_date,reply_edit_name FROM $CONFIG_sql_cpdbname.board_reply WHERE reply_id =\"".mysql_res($reply_id)."\"";
$sql->result = $sql->execute_query($query,'lib_ajax.php');
$row = $sql->fetch_row();
$IS_EDIT_POST = checkprivilege_action($CP[login_id],g_edit_posts)?1:0;
if(!empty($CP[login_id]) && ($CP[login_id] == $row[reply_user_id] || $IS_EDIT_POST) && length($val,3,$CONFIG_max_post_length)) {
	$val = checkstring($val,1);
	$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.board_reply SET reply_message = \"".$val."\", reply_ip=\"".$CP['ip_address']."\",reply_edit_name=\"".$CP['login_name']."\",reply_edit_date=\"".$CP['time']."\" WHERE reply_id = \"".mysql_res($row['reply_id'])."\"",'lib_ajax.php');
	$query = "SELECT reply_message,reply_edit_date,reply_edit_name FROM $CONFIG_sql_cpdbname.board_reply WHERE reply_id =\"".mysql_res($row['reply_id'])."\"";
	$sql->result = $sql->execute_query($query,'lib_ajax.php');
	$row = $sql->fetch_row();
	$reply_message = replace_text($row[reply_message],$img_num);
	$reply_edit_date =  get_date("M j y, H:i:s A",$row[reply_edit_date]);
echo "<div class=\"poststyle\">$reply_message</div>
<br /><br /><span class=\"edit\">This post has been edited by <B>$row[reply_edit_name]</B> on $reply_edit_date</span>
";
} else {
	$reply_message = replace_text($row[reply_message]);
echo "<div class=\"poststyle\">$reply_message</div>";
	if (!empty($row[reply_edit_name])) {
		$reply_edit_date =  get_date("M j y, H:i:s A",$row[reply_edit_date]);
echo "<br /><br /><span class=\"edit\">This post has been edited by <B>$row[reply_edit_name]</B> on $reply_edit_date</span>";
	}
 }
}

function get_cp_update() {
global $CP;
if (checkprivilege_action($CP[login_id],g_view_lastestcp)) {
	if (connect_cp_update()) {
		$sgcp = new CP_Update;
		$version = cp_current_version();
		$cp_version = $sgcp->version_data($version,0);
		$cp_release = $sgcp->version_data($version,1);

		$list_version = cp_list_version();
		$last_version = $list_version[count($list_version)-1];
		$load_version = $sgcp->version_data($last_version,0);
		$load_release = $sgcp->version_data($last_version,1);

		if ($load_version && cp_check_version($cp_version,$cp_release,$load_version,$load_release)) {
			echo "1";
		} else {
			echo "0";
		}
	} else
		echo "3";
 }
}

function status_cp_update() {
global $CP;
if (checkprivilege_action($CP[login_id],g_view_lastestcp)) {
	$dstdir = "tmp_update";
	$filename = $dstdir."/tmp.sgcp";
	$contents = file_get_contents($filename);
	$status = cp_update_get_msg(0,$contents);
	$status = (int)$status;
	echo $status;
 }
}

function clear_cp_update() {
global $CP;
if (checkprivilege_action($CP[login_id],g_view_lastestcp)) {
	cp_update_set_msg(0,0);
 }
}

function refresh_cp_update($code,$position) {
global $CP,$lang,$STORED;
header("Content-type: text/xml;charset=windows-874");
$code = (int)$code;
$position = (int)$position;
if (checkprivilege_action($CP[login_id],g_view_lastestcp)) {
	if ($code == 1) {
		$sgcp = new CP_Update;
		$version = cp_current_version();
		$cp_version = $sgcp->version_data($version,0);
		$cp_release = $sgcp->version_data($version,1);

		$list_version = cp_list_version();
		$last_version = $list_version[count($list_version)-1];

		$load_version = $sgcp->version_data($last_version,0);
		$load_release = $sgcp->version_data($last_version,1);
		if (!($load_version && cp_check_version($cp_version,$cp_release,$load_version,$load_release)))
			$code = 0;
	}
	if ($code == 2) {
		$dstdir = "tmp_update";
		$filename = $dstdir."/tmp.sgcp";
		$contents = file_get_contents($filename);
		$status = cp_update_get_msg(0,$contents);
		$status = (int)$status;
		if ($position == 1) {
			$file_process = cp_update_get_msg(1,$contents);
			switch ($status) {
				case 0:
					$msg_retern = "Connecting";
					break;
				case 1:
					$msg_retern = "Downloading: ".$file_process;
					break;
				case 2:
					$msg_retern = "Updating: ".$file_process;
					break;
				case 3:
					$msg_retern = "Update Completed";
					break;
				case 4:
					$msg_retern = "Failed to download: ".$file_process;
					break;
				case 5:
					$msg_retern = "Failed to update: ".$file_process;
					break;
				default:
					$msg_retern = "Your server dose not support this method";
			}
		} else if ($position == 2) {
			$percent = cp_update_get_msg(2,$contents);
			$percent = ($status != 0)?(int)$percent:0;
			$percent = ($percent < 0)?0:$percent;
			if ($percent > 0 && $status != 0) {
				$pwidth = $percent*200/100;
				$percent_return = $percent."% ";
				$percent_return .= "<img src=\"theme/".$STORED[THEME]."/images/webboard/bar_left.gif\" height=\"11\" width=\"4\" align=\"absmiddle\"><img src=\"theme/".$STORED[THEME]."/images/webboard/bar.gif\" height=\"11\" width=\"$pwidth\" align=\"absmiddle\"><img src=\"theme/".$STORED[THEME]."/images/webboard/bar_right.gif\" height=\"11\" width=\"4\" align=\"absmiddle\">";
			}
		}
	}
	$return = array(
		'0' => array(
			'0' => "".$lang['CPUD_current_version']."",
			'1' => "".$lang['CPUD_check_mes2']."",
			'2' => "<input name=\"Button\" value=\"".$lang['CPUD_check_button']."\" type=\"button\" onClick=\"this.disabled=true; check_cp_update();\" class=\"textinput\">",
			),
		'1' => array(
			'0' => "".sprintf($lang['CPUD_current_version2'],$cp_version,$cp_release)."
				".sprintf($lang['CPUD_last_version'],$load_version,$load_release)."",
			'1' => "".$lang['CPUD_check_mes3']."",
			'2' => "<input name=\"Button\" value=\"".$lang['CPUD_check_button2']."\" type=\"button\" onClick=\"this.disabled=true; do_cp_update();\" class=\"textinput\">",
			),
		'2' => array(
			'0' => "",
			'1' => "".$msg_retern."",
			'2' => "".$percent_return."",
			),
		'3' => array(
			'0' => "Failed to connect server. Please try again.",
			'1' => "".$lang['CPUD_check_mes2']."",
			'2' => "<input name=\"Button\" value=\"".$lang['CPUD_check_button']."\" type=\"button\" onClick=\"this.disabled=true; check_cp_update();\" class=\"textinput\">",
			)
	);
	echo $return[$code][$position];
 }
}

function do_cp_update() {
global $CP;
if (checkprivilege_action($CP[login_id],g_view_lastestcp)) {
	$sgcp = new CP_Update;
	$version = cp_current_version();
	$cp_version = $sgcp->version_data($version,0);
	$cp_release = $sgcp->version_data($version,1);

	$list_version = cp_list_version();

	if (!is_dir("tmp_update"))
		if(!mkdir("tmp_update"))
			exit();


	$count_files = $sgcp->count_list_update_file($cp_version,$cp_release,$list_version)*2;

	$date = date("Y-m-d_H_i_s");
	$count_success = 0;
	cp_update_set_msg(0,0);
	cp_update_set_msg(3,0);
	for($i=0;$i<count($list_version);$i++) {
		$load_version = $sgcp->version_data($list_version[$i],0);
		$load_release = $sgcp->version_data($list_version[$i],1);
		if ($load_version && cp_check_version($cp_version,$cp_release,$load_version,$load_release)) {
			$list_update_file = file_get_result("bin/".$list_version[$i]."/list.sgcp",1);
			$list_update_file = $sgcp->true_list_update_file($list_update_file);
			$list_download_file = $sgcp->true_list_download_file($list_update_file,$list_version[$i]);
			$tmp_list_update_file[$i] = $list_update_file;
			for ($j=0;$j<count($list_download_file);$j++) {
				cp_update_set_msg(1,$list_download_file[$j]);
				if (!$tmp_update_result[$i][$j] = file_get_result($list_download_file[$j])) {
					cp_update_set_msg(4,$list_download_file[$j]);
					exit();
				} else {
					$count_success++;
					$percent = $count_success/$count_files*100;
					$percent = (int)$percent;
					cp_update_set_msg(3,$percent);
				}
			}
		}
	}


	for($i=0;$i<count($list_version);$i++) {
		$load_version = $sgcp->version_data($list_version[$i],0);
		$load_release = $sgcp->version_data($list_version[$i],1);
		if ($load_version && cp_check_version($cp_version,$cp_release,$load_version,$load_release)) {
			$list_update_file = $tmp_list_update_file[$i];
			$list_download_file = $sgcp->true_list_download_file($list_update_file,$list_version[$i]);
			for ($j=0;$j<count($list_download_file);$j++) {

				// Check & Create dirs
				$sgcp->list_create_dir($list_update_file[$j]);

				// Backup files
				backup_file($list_update_file[$j],$date);

				if (!$handle = fopen($list_update_file[$j], 'w+')) {
					cp_update_set_msg(5,$list_update_file[$j]);
					exit();
				} else {
					cp_update_set_msg(2,$list_update_file[$j]);
					if (fwrite($handle, $tmp_update_result[$i][$j]) === FALSE) {
						cp_update_set_msg(5,$list_update_file[$j]);
						exit();
					} else {
						$count_success++;
						$percent = $count_success/$count_files*100;
						$percent = (int)$percent;
						cp_update_set_msg(3,$percent);
					}
				fclose($handle);
				}
			}
		}
	}
	cp_update_set_msg(0,3);
 }
}
?>