<?php
class MySQL {
	var $link = "";
	var $charset = "";
	var $result = "";
	var $total_query = "0";

	function Connect($host,$user,$pass) {
	error_reporting (null);
	global $CONFIG_mysql_charset;
	$this->link = mysql_connect($host,$user,$pass);
	$GLOBALS['link'] = $this->link;
	if(!$this->link)
		$this->error_found("01");
	switch($CONFIG_mysql_charset) {
		case auto:
			$this->charset = mysql_client_encoding($this->link);
			mysql_query("SET NAMES ".$this->charset."");
			break;
		case disable:
			break;
		default:
			mysql_query("SET NAMES ".$CONFIG_mysql_charset."");
			break;
	}
	return true;
	}

	function check_database($db) {
		if(!mysql_select_db($db,$this->link))
			$this->error_found("02",$db);
	}

	function error_found($array1,$array2='') {
		switch($array1) {
			case "01":
				$errors = "- MySQL Server isn't running.<br />\n";
				$errors .= "- MySQL username or password isn't correct.";
				break;
			case "02":
				$errors = "- <b>".$array2."</b> database not found.";
				break;
		}
die("<html>
<head>
<title>Please check these following errors</title>
</head>
<body>
<font color='red' face='Sylfaen' size='3'><B>Please check these following errors. [error code: ".$array1."]</B><br />
$errors
</font>
</body>
</html>
");
	}

	function execute_query($input_query,$page_src = 'none.php',$save_log = '1') {
	global $CONFIG_sql_cpdbname,$CP,$CONFIG_log_select,$CONFIG_log_insert,$CONFIG_log_update,$CONFIG_log_delete,$query_txt;
//	$start_time = getmicrotime();
	$die_message = "<form>MySQL query['<font color='red'>".$page_src."</red>'] :<br /><textarea name=\"query_error\" cols=\"65\" rows=\"7\" class=\"textinput\" readonly>".htmlspecialchars(stripslashes($input_query))."</textarea></form>";
		$analyze_query = strtolower(htmlspecialchars($input_query));

		if ($save_log) {
			if (strstr($analyze_query, 'select') !== false && $CONFIG_log_select) {
				$log_enable='1';
			}
			else if (strstr($analyze_query, 'insert') !== false && $CONFIG_log_insert) {
				$log_enable='1';
			}
			else if (strstr($analyze_query, 'update') !== false && $CONFIG_log_update) {
				$log_enable='1';
			}
			else if (strstr($analyze_query, 'delete') !== false && $CONFIG_log_delete) {
				$log_enable='1';
			}
			else $log_enable='0';

			if ($log_enable) {
				$user = $CP["login_name"];
				if(empty($user)) $user="-"; 
				mysql_query("INSERT INTO $CONFIG_sql_cpdbname.query_log (Date,User,IP,page,query) VALUES (NOW() ,'".mysql_res($user)."','".mysql_res($CP["ip_address"])."','".mysql_res($page_src)."','".mysql_res($input_query)."')");
			}
		}

		if ( !($result = mysql_query($input_query,( $this->link ? $this->link : $GLOBALS['link'] ) ))

			|| strstr($analyze_query,"union")
		) {
			echo $die_message;
			exit();
		}
//	$end_time = getmicrotime();
//	$time_result = $end_time - $start_time;
//	$query_txt .= $input_query."[<font color='red'>".$time_result."</font>]<br />\n";
/*	if($log_query)
		$sql->total_query++;*/
	return $result;
	}

	function count_rows($result='') {
		return $result?mysql_num_rows($result):mysql_num_rows($this->result);
	}

	function fetch_row($result='') {
		return $result?mysql_fetch_array($result):mysql_fetch_array($this->result);
	}

	function result($result='') {
		return $result?mysql_result($result,0):mysql_result($this->result,0);
	}
}
function getglobalvar($i) {
global $CONFIG_save_type;
	if($i==1) {
		global $STORED_loginname,$STORED_loginpass;
		if($CONFIG_save_type==1) {
			global $_SESSION;
			$STORED_loginname = $_SESSION['loginname'];
			$STORED_loginpass = $_SESSION['loginpass'];
		} else {
			global $_COOKIE;
			$STORED_loginname = $_COOKIE['loginname'];
			$STORED_loginpass = $_COOKIE['loginpass'];
		}
	} else if($i==2) {
		global $STORED_userlang,$STORED_usertheme;
		if($CONFIG_save_type==1) {
			global $_SESSION;
			$STORED_userlang = truestr($_SESSION['userlang']);
			$STORED_usertheme = truestr($_SESSION['usertheme']);
		} else {
			global $_COOKIE;
			$STORED_userlang = truestr($_COOKIE['userlang']);
			$STORED_usertheme = truestr($_COOKIE['usertheme']);
		}		
	}
}
function getmicrotime() {
	$mtime = microtime ();
	$mtime = explode (' ', $mtime);
	$mtime = $mtime[1] + $mtime[0];
return $mtime;
}
function length($var,$min,$max = '0') {
	if($max) {
		if(strlen($var) >= $min && strlen($var) <= $max)
			return 1;
		else
			return 0;
	} else {
		if(strlen($var) >= $min)
			return 1;
		else
			return 0;
	}
return 0;
}
function generate_password($len) {
global $CONFIG_security_mode;
	srand((double)microtime()*10000000);
	if ($CONFIG_security_mode=='1')
		$chars = "0123456789";
	else if ($CONFIG_security_mode=='2')
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	else
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$ret_str = "";
	$num = strlen($chars);
	for($i = 0; $i < $len; $i++) {
		$ret_str.= $chars[rand()%$num];
	}
return $ret_str; 
}
function SendMail($to,$subject,$messages) {
global $CONFIG_smtp_host,$CONFIG_smtp_port,$CONFIG_server_name,$CONFIG_admin_email;
	ini_set("SMTP",$CONFIG_smtp_host);
	ini_set("smtp_port",$CONFIG_smtp_port);
	ini_set("sendmail_from",$CONFIG_admin_email);

	$header .= "From: $CONFIG_server_name<$CONFIG_admin_email>\n";

	if(mail($to,$subject,$messages,$header,"-f$CONFIG_admin_email"))
		return true;
	else
		return false;
}
function cookie_remove() {
	CP_removeCookie("loginname");
	CP_removeCookie("loginpass");
	CP_removeCookie("userlang");
	CP_removeCookie("usertheme");
return true;
}
function checkmd5($md5_support,$check_string) {
	if($md5_support && !empty($check_string))
		$result_string = md5($check_string);
	else
		$result_string = $check_string;
return $result_string;
}
function get_cp_profile($account_id) {
global $CONFIG_sql_cpdbname,$CONFIG_account_id_start,$STORED,$display_name,$reply_avatar,$reply_avatar_url,$reply_avatar_width,$reply_avatar_height,$reply_post,$reply_number,$reply_joined,$reply_signature,$status_bar,
	$rank_title,$reply_imgroup,$reply_group;

	$sql = new MySQL;
	$query = "SELECT user_id,display_name,user_avatar,user_avatar_width,user_avatar_height,user_ranking,user_joined,user_signature_message,user_online FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id =\"".mysql_res($account_id)."\"";
	$sql->result = $sql->execute_query($query,'function.php');$sql->total_query++;
	if (!$sql->count_rows())
		return false;
	$row_user = $sql->fetch_row();
	$display_name = $row_user["display_name"];
	$reply_avatar = $row_user["user_avatar"];
	$reply_avatar_url = $row_user["user_avatar"];
	$reply_avatar_width = $row_user["user_avatar_width"];
	$reply_avatar_height = $row_user["user_avatar_height"];
	$reply_post = $row_user["user_ranking"];
	$reply_number = $row_user["user_id"] - $CONFIG_account_id_start;
	if ($reply_number < 1)
		$reply_number = "N/A";
	$reply_joined = get_date("j-M y",$row_user["user_joined"]);
	$reply_signature = $row_user["user_signature_message"];
	$user_status = $row_user["user_online"];
	if($reply_avatar != NULL)
		$reply_avatar = "<img width=\"$reply_avatar_width\" height=\"$reply_avatar_height\" border=\"0\" alt=\"user�avatar\" src=\"$reply_avatar\">";
	$status_bar = $user_status ? "\"theme/$STORED[THEME]/images/p_online.gif\" alt=\"Online\"" : "\"theme/$STORED[THEME]/images/p_offline.gif\" alt=\"Offline\"";
	$status_bar = "<img src=".$status_bar." border=\"0\">";
	$rank_title = get_ranktitle($reply_post);
	$reply_imgroup=checkprivilege_action($account_id,g_img);
	$reply_group = checkprivilege_action($account_id,g_title);
return true;
}
function set_tmpsqld($array1,$array2,$array3,$val) {
global $tmpsqld;
	$tmpsqld["".$array1.""] = array("".$array2."" => array("".$array3."" => "".$val.""));
return;
}
function get_tmpsqld($array1,$array2,$array3) {
global $tmpsqld;
	return $tmpsqld["".$array1.""]["".$array2.""]["".$array3.""];
}
function get_username($account_id) {
global $CONFIG_sql_dbname,$CONFIG_sql_cpdbname;
	if ($tmpsqld = get_tmpsqld("user_profile","display_name",$account_id))
		return $tmpsqld;
	$sql = new MySQL;
	$query = "SELECT display_name FROM $CONFIG_sql_cpdbname.user_profile WHERE user_id =\"".mysql_res($account_id)."\"";
	$sql->result = $sql->execute_query($query,'function.php',0);
	$row = $sql->fetch_row();
	if (!empty($row[display_name])) {
		set_tmpsqld("user_profile","display_name",$account_id,$row["display_name"]);
		return $row["display_name"];
	} else {
		$query = "SELECT userid FROM $CONFIG_sql_dbname.login WHERE account_id =\"".mysql_res($account_id)."\"";
		$sql->result = $sql->execute_query($query,'function.php',0);
		if ($sql->count_rows()) {
			$row = $sql->fetch_row();
			set_tmpsqld("user_profile","display_name",$account_id,$row["userid"]);
			return $row["userid"];
		} else
			return false;
	}
}
function get_displayname($string,$account_id) {
global $CONFIG_sql_dbname,$CONFIG_sql_cpdbname,$lang;
	if ($string)
		return $string;
	if ($tmpsqld = get_tmpsqld("user_profile","display_name",$account_id))
		return $tmpsqld;
	$sql = new MySQL;
	$query = "SELECT account_id,userid FROM $CONFIG_sql_dbname.login WHERE account_id =\"".mysql_res($account_id)."\"";
	$sql->result = $sql->execute_query($query,'function.php',0);
	if ($sql->count_rows()) {
		$row = $sql->fetch_row();
		set_tmpsqld("user_profile","display_name",$account_id,$row["userid"]);
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.user_profile SET display_name = \"".mysql_res($row['userid'])."\" WHERE user_id=\"".mysql_res($row['account_id'])."\"",'function.php',0);
		return $row["userid"];
	} else
		return $lang["Guest"];
}
function get_date($time_type,$time) {
global $CONFIG_time_offset,$CP;
	if ($time)
		$date =  date("$time_type",$time+(60*60*(get_timezone("$CP[time_offset]") - get_timezone("$CONFIG_time_offset"))) );
return $date;
}
function get_timezone($time_offset) {
	$mark = substr($time_offset,3,1);
	$hour = (int)substr($time_offset,4,2);
	$minute = (int)substr($time_offset,6,2);
	$minute = (float)($minute*100/60);
return "".$mark."".$hour.".".$minute."";
}
function getcastlename($castle_value) {
	$castle_name = explode("\r\n", file_get_contents("guild_castles_name.def"));
return $castle_name[$castle_value];
}
function exceptchars_decode($val) {
	$val = str_replace( "&lt;b&gt;", "<b>", $val );
	$val = str_replace( "&lt;/b&gt;", "</b>", $val );
	$val = str_replace( "&lt;br&gt;", "<br />", $val );
	$val = str_replace( "&lt;br /&gt;", "<br />", $val );
	$val = str_replace( "&lt;BR&gt;", "<br />", $val );
return $val;
}
function header_location($url) {
	if(header("location:".$url."") == false)
		redir("$url","",2);
}
function redir($url,$message,$time) {
global $lang;
if (empty($message))
	$message_ = "";
else {
	$message_ = "".htmlspecialchars($message)."<br /><br />";
	$message_ = exceptchars_decode($message_);
}
echo "
<meta http-equiv=\"refresh\" content=\"$time;URL=$url\">
	<TABLE width=\"100%\" height=\"85%\" align=\"center\">
		<TR>
			<TD valign=\"top\">
";
				opmain_body("CP Message",0,0);
echo "				<TABLE align=\"center\" cellpadding=\"4\" cellspacing=\"0\">
					<TR class=\"topic_title5\" align=\"center\">
						<TD height=\"20\"></TD>
					</TR>
					<TR class=\"topic_title6\">
						<TD width=\"250\" height=\"50\" align=\"center\" nowrap=\"nowrap\" align=\"center\">
							".$message_."
							".$lang[P_Wait]."
						</TD>
					</TR>
					<TR class=\"topic_title5\" align=\"center\">
						<TD height=\"20\"><B><a href=\"$url\">$lang[Wait]</a></B></TD>
					</TR>
				</TABLE>
";
			clmain_body();
echo "			</TD>
		</TR>
	</TABLE>
";
return true;
}
function redir_back($message) {
global $lang;
if (empty($message))
	$message_ = "";
else {
	$message_ = "".htmlspecialchars($message)."<br /><br />";
	$message_ = exceptchars_decode($message_);
}
echo "
	<TABLE width=\"100%\" height=\"85%\" align=\"center\">
		<TR>
			<TD valign=\"top\">
";
				opmain_body("CP Message",0,0);
echo "				<TABLE align=\"center\" cellpadding=\"4\" cellspacing=\"0\">
					<TR class=\"topic_title5\">
						<TD height=\"20\"></TD>
					</TR>
					<TR class=\"topic_title6\">
						<TD width=\"250\" height=\"50\" align=\"center\" nowrap=\"nowrap\" align=\"center\">
							$message_
						</TD>
					</TR>
					<TR class=\"topic_title5\" align=\"center\">
						<TD height=\"20\"><B><a href=\"javascript:history.back();\">$lang[Wait_Back]</a></B></TD>
					</TR>
				</TABLE>
";
			clmain_body();
echo "			</TD>
		</TR>
	</TABLE>
";
}
function opmain_body($title,$height = '0',$width = '100%') {
global $STORED;
echo "
<TABLE width=\"$width\" height=\"$height\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"topic_title\" align=\"center\">
	<TBODY>
		<TR class=\"title_bar\" height=\"29\">
			<TD>
				<a class=\"m_title\">&nbsp;<img src=\"theme/$STORED[THEME]/images/nav_m.gif\">&nbsp;".htmlspecialchars($title)."</a>
			</TD>
		</TR>
		<TR>
			<TD>
";
return true;
}
function clmain_body() {
echo  "			</TD>
		</TR>
	</TBODY>
</TABLE>
";
return true;
}
function checkprivilege($account) {
if(!$account)
	return 1;
global $CONFIG_sql_cpdbname;
$sql = new MySQL;
$query = "SELECT privilege FROM $CONFIG_sql_cpdbname.privilege WHERE account_id=\"".mysql_res($account)."\" ";
$sql->result = $sql->execute_query($query,'function.php',0);
if ($sql->count_rows()>0) {
	$row = $sql->fetch_row();
	$userprivilege = $row[privilege];
} else
	return 1;
return $userprivilege;
}
function checkprivilege_action($account,$checkaction) {
global $CONFIG_sql_cpdbname;
$sql = new MySQL;
$userprivilege = checkprivilege($account);
$query = "SELECT ".$checkaction." FROM $CONFIG_sql_cpdbname.groups WHERE g_id=\"".mysql_res($userprivilege)."\"";
$sql->result = $sql->execute_query($query,'function.php',0);
if ($sql->count_rows()>0) {
	$row = $sql->fetch_row();
	$usrprivilege = $row[$checkaction];
} else
	return 0;
return $usrprivilege;
}
function get_ranktitle($rank) {
global $CONFIG_sql_cpdbname;
$sql = new MySQL;
	$query = "SELECT title FROM $CONFIG_sql_cpdbname.rank_title WHERE min_post <= \"".mysql_res($rank)."\" ORDER by min_post DESC LIMIT 0,1";
	$sql->result = $sql->execute_query($query,'function.php',0);
	$row = $sql->fetch_row();
return $row[title];
}
function get_ip() {
if(getenv(HTTP_X_FORWARDED_FOR))
	$ipaddress = mysql_res( getenv(HTTP_X_FORWARDED_FOR) );
else
	$ipaddress = getenv("REMOTE_ADDR");
return $ipaddress;
}
function get_referer() {
	$Referer = $_SERVER['HTTP_REFERER'];
	if(empty($Referer)) $Referer = "index.php?act=idx";
return $Referer;
}
function get_cptitle($title) {
	global $CONFIG_server_name;
	if(empty($title))
		$title = "Unknow";
echo "	<title>$CONFIG_server_name :: [$title] ::</title>\n";
return true;
}
function emotions_name() {
	$emotions = array('24','angry','biggrin','blink','blush','closedeyes','cool','dry','happy','huh','laugh','mad','mellow','ninja','excl','ohmy','rolleyes','sad','sleep','smile','tongue','unsure','wacko','wink','wub');
return $emotions;
}
function emotions_select($id) {
global $STORED;
echo"<TABLE width=\"80%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" class=\"topic_title\">\n";
printf("<TBODY>\n");
printf("<TR>\n");
printf("<TD colspan=\"4\" align=\"center\">\n");
printf("<div class=\"title_face4\"><B>Clickable Smilies</B></div>\n");
printf("</TD>\n");
printf("</TR>\n");
$emotions = emotions_name();
	for($i=1;$i<=$emotions[0];$i++) {
		if((($i-1) % 4) == '0')
			printf("<TR align=\"center\">\n");
		printf("<TD><a href=javascript:emotion(\"$id\",\":%s:\")><img src=\"theme/%s/images/emotions/%s.gif\" border=\"0\"></a></TD>\n",$emotions[$i],$STORED[THEME],$emotions[$i]);
		if(($i % 4) == '0')
			printf("</TR>\n");
	}
printf("</TBODY>\n");
printf("</TABLE>\n");
}
function get_page($st,$per_page) {
	$page=($st/$per_page)+1;
return $page;
}
function get_selectpage($total,$per_page,$get_page,$url,$url2='',$name='') {
global $lang;
$page = ceil($total/$per_page);
	if(!$get_page) {
		$get_page = '1';
		$st='0';
	}
	if($page>1){
		if($name)
			print ("<a href=javascript:page_jump_('$name','$url2','$per_page','$page')>$lang[Page]:</a> ($page) ");
		else
			print ("<a href=javascript:page_jump('$url','$per_page','$page')>$lang[Page]:</a> ($page) ");
		for( $i=1,$z=0; $i<=$page; $i++) {
			if ($get_page - $i > 2) {
				if (!$z) {
					$z='1';
					$onclick = $name?"onclick=\"page_select('$name','".$url2."st=".$per_page*($i-1)."'); return false;\"":"";
					echo "<a href='$url&st=".$per_page*($i-1)."' title='Page: $i'".$onclick.">&laquo; First</a> ...\n";
				}
			} else if ($i - $get_page > 2) {
				$i = $page;
				$onclick = $name?"onclick=\"page_select('$name','".$url2."st=".$per_page*($i-1)."'); return false;\"":"";
				echo "... <a href='$url&st=".$per_page*($i-1)."' title='Page: $page'".$onclick.">Last &raquo;</a>\n";
			} else if ($get_page==$i) {
				echo "<B>[$i]</B>\n";
			} else {
				$onclick = $name?"onclick=\"page_select('$name','".$url2."st=".$per_page*($i-1)."'); return false;\"":"";
				echo "<a href='$url&st=".$per_page*($i-1)."'".$onclick.">$i</a>\n";
			}
		}
	}
return true;
}
function get_sselectpage($page,$per_page,$pageid) {
global $lang;
echo "\n(";
print ("<a href=javascript:page_jump('index.php?showtopic=$pageid','$per_page','$page')>$lang[Page]:</a> ");
	for ($t=1,$g=0; $t<=$page; $t++) {
		if ($t<=3)
			echo"<a href=index.php?showtopic=$pageid&st=".$per_page*($t-1).">$t</a>\n";
		else if ($t>3 && ($page-2)>3 && !$g) {
			$g=1;
			$t=$page-2;
			echo "...\n<a href=index.php?showtopic=$pageid&st=".$per_page*($t-1).">$t</a>\n";
		}
		else if ($t>3 && ($page-2)>3) {
			echo "<a href=index.php?showtopic=$pageid&st=".$per_page*($t-1).">$t</a>\n";
		}
		else if ($t>3 && ($page-2)<=3 && !$g) {
			$g=1;echo "...\n<a href=index.php?showtopic=$pageid&st=".$per_page*($t-1).">$t</a>\n";
		}
		else if ($t>3 && ($page-2)<=3) {
			echo "<a href=index.php?showtopic=$pageid&st=".$per_page*($t-1).">$t</a>\n";
		}
	}
echo ")";
return true;
}
function get_menuwb($forum_id,$type,$reply='') {
global $STORED;
switch($type) {
	case 1:
		print("<a href=\"index.php?act=post&f=$forum_id&code=00\" title=\"Start new topic\"><img src=\"theme/$STORED[THEME]/images/webboard/t_new.gif\" border=\"0\"></a>");
		break;
	case 2:
		print("$reply
		<a href=\"index.php?act=post&f=$forum_id&code=00\" title=\"Start new topic\"><img src=\"theme/$STORED[THEME]/images/webboard/t_new.gif\" border=\"0\"></a>");
		break;
}
return true;
}
function my_br2nl($val) {
	$val = str_replace( " <br /> ", "\n", $val );
	$val = str_replace( " <br> "  , "\n", $val );
	while( preg_match( " <br> ", $val ) ) {
		$val = preg_replace( " <br> "        , "\n"          , $val );
	}
return $val;
}
function isAlphaNumeric($val) {
	if (!preg_match("/^[a-zA-Z0-9]+$/", $val))
		return false;
return true;
}
function isMailform($email) {
	if (preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-z0-9]{1,4}$/", $email)) { 
		return true;
	}
return false;
}
function truestr($val) {
	$val2 = "";
	$len = strlen($val);
	for($i=0;$i<$len;$i++) {
		if (preg_match("/^[a-zA-Z0-9+-._]+$/", $val[$i]))
			$val2 .= $val[$i];
	}
	if (empty($val2)) {
		return false;
	}
return $val2;
}
function checkstring($val,$i='1') {
	if ($i) {
		$val = str_replace( "&#032;", " ", $val );
		$val = str_replace( "&"            , "&amp;"         , $val );
		$val = str_replace( "<!--"         , "&#60;&#33;--"  , $val );
		$val = str_replace( "-->"          , "--&#62;"       , $val );
		$val = preg_replace( "/<script/i"  , "&#60;script"   , $val );
		$val = str_replace( ">"            , "&gt;"          , $val );
		$val = str_replace( "<"            , "&lt;"          , $val );
		$val = str_replace( "\""           , "&quot;"        , $val );
		$val = preg_replace( "/\n/"        , " <br> "          , $val );
		$val = preg_replace( "/\\\$/"      , "&#036;"        , $val );
		$val = preg_replace( "/\r/"        , ""              , $val );
		$val = str_replace( "!"            , "&#33;"         , $val );
		$val = str_replace( "'"            , "&#39;"         , $val );
		$val = str_replace( '"'            , "&quot;"         , $val );

		$val = preg_replace( "#\(c\)#i"     , "&copy;" , $val );
		$val = preg_replace( "#\(tm\)#i"    , "&#153;" , $val );
		$val = preg_replace( "#\(r\)#i"     , "&reg;"  , $val );
	}
	while( preg_match( "#\[(url|img|b|u|i|s|email|list|indent|right|left|center)\]\[/\\1\]#is", $val ) ) {
		$val = preg_replace( "#\[(url|img|b|u|i|s|email|list|indent|right|left|center)\]\[/\\1\]#is", "", $val );
	}
	$val = mysql_res($val);
return $val;
}
function mysql_res($val) {
	if(function_exists("mysql_real_escape_string"))
		return mysql_real_escape_string($val);
	else
		return addslashes($val);
}
function replace_text($val) {
if ($val) {
	$val = str_replace(chr(13)," <br> ",$val);

	$val = replace_text_font_style($val,"b","b");
	$val = replace_text_font_style($val,"i","i");
	$val = replace_text_font_style($val,"u","u");
	$val = replace_text_font_style($val,"s","strike");
	$val = replace_text_font_style($val,"indent","blockquote");
	$val = replace_text_font_size($val);

	while ( preg_match( "#\[background=([^\]]+)\](.+?)\[/background\]#is", $val ) ) {
		$val = preg_replace( "#\[background=([^\]]+)\](.+?)\[/background\]#is","<span style='background-color:\\1'>\\2</span>", $val );
	}
	while ( preg_match( "#\[font=([^\]]+)\](.+?)\[/font\]#is", $val ) ) {
		$val = preg_replace( "#\[font=([^\]]+)\](.+?)\[/font\]#is","<font face=\"\\1\">\\2</font>", $val );
	}
	while ( preg_match( "#\[color=([^\]]+)\](.+?)\[/color\]#is", $val ) ) {
		$val = preg_replace( "#\[color=([^\]]+)\](.+?)\[/color\]#is","<span style='color:\\1'>\\2</span>", $val );
	}
	while( preg_match( "#\n?\[list\](.+?)\[/list\]\n?#is" , $val ) ) {
		$val = preg_replace_callback( "#\n?\[list\](.+?)\[/list\]\n?#is", 'regex_list', $val );
	}
	while( preg_match( "#\n?\[list=(a|A|i|I|1)\](.+?)\[/list\]\n?#is" , $val ) ) {
		$val = preg_replace_callback( "#\n?\[list=(a|A|i|I|1)\](.+?)\[/list\]\n?#is", 'regex_list', $val );
	}

	$val = preg_replace( "#\[(left|right|center)\](.+?)\[/\\1\]#is"  , "<div align=\"\\1\">\\2</div>", $val );

	$val = replace_text_image($val);
	$val = replace_text_email($val);
	$val = replace_text_email_($val);
	$val = replace_text_quote($val);
	$val = replace_text_quote_($val);
	$val = replace_text_code($val);
	$val = eregi_replace("(^|[>[:space:]\n])([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])([<[:space:]\n]|$)","<a href='\\2://\\3\\4' target='_blank'>\\2://\\3\\4</a>",$val);
	$val = eregi_replace("([_.[:alnum:]]+)@([^[:space:]]*)([[:alnum:]])([<[:space:]\n]|$)","<a href=mailto:\\1@\\2\\3>\\1@\\2\\3</a>", $val);
	$val = preg_replace( "#\[URL\s*=\s*(\S+?)\s*\](.*?)\[\/URL\]#ie","\"<a href=\\1 target='_blank'>\\2</a>\"", $val);
	$val = replace_emotions($val);
}
return $val;
}
function replace_emotions($val) {
global $STORED;
$emotions = emotions_name();
	for($i=0;$i<=$emotions[0];$i++)
		$val = eregi_replace(":".$emotions[$i].":", "<img src=\"theme/$STORED[THEME]/images/emotions/".$emotions[$i].".gif\" align=\"absmiddle\" border=\"0\">" , $val ) ;
return $val;
}
function replace_text_font_style($val,$find,$replace) {
	while( preg_match( "#\[".$find."\](.+?)\[/".$find."\]#is" , $val ) ) {
		$val = preg_replace( "#\[".$find."\](.+?)\[/".$find."\]#is"  , "<".$replace."> \\1 </".$replace.">", $val );
	}
return $val;
}
function replace_text_font_size($val) {
	while ( preg_match( "#\[size=(1|2|3|4|5|6|7)\](.+?)\[/size\]#is", $val ) ) {
		$val = preg_replace_callback( "#\[size=(1|2|3|4|5|6|7)\](.+?)\[/size\]#is", regex_font_size , $val );
	}
return $val;
}
function replace_text_email($val) {
	while ( preg_match( "#\[email\](\S+?)\[/email\]#i", $val ) ) {
		$val = preg_replace( "#\[email\](\S+?)\[/email\]#i", "<a href=\"mailto:\\1\"> \\1 </a>", $val );
	}
return $val;
}
function replace_text_email_($val) {
		$val = preg_replace( "#\[email\s*=\s*\&quot\;([\.\w\-]+\@[\.\w\-]+\.[\.\w\-]+)\s*\&quot\;\s*\](.*?)\[\/email\]#i", "<a href=\"mailto:\\1\"> \\2 </a>", $val );
		$val = preg_replace( "#\[email\s*=\s*([\.\w\-]+\@[\.\w\-]+\.[\w\-]+)\s*\](.*?)\[\/email\]#i", "<a href=\"mailto:\\1\"> \\2 </a>", $val );
return $val;
}
function replace_text_quote($val) {
	while ( preg_match( "#\[quote\s*\](.*?)\[\/quote\]#is", $val ) ) {
		$val = preg_replace( "#\[quote\s*\](.*?)\[\/quote\]#is","<TABLE border=\"0\" align=\"center\" width=\"95%\" cellpadding=\"3\" cellspacing=\"1\"><TR><TD class=\"poststyle\"><b>QUOTE</b></TD></TR><TR><TD id=\"QUOTE\"> \\1 </TD></TR></TABLE>", $val);
	}
return $val;
}
function replace_text_quote_($val) {
	while ( preg_match( "#\[quote\s*=\s*(.*?)s*\](.*?)\[\/quote\]#is", $val ) ) {
		$val = preg_replace( "#\[quote\s*=\s*(.*?)s*\](.*?)\[\/quote\]#is","<TABLE border=\"0\" align=\"center\" width=\"95%\" cellpadding=\"3\" cellspacing=\"1\"><TR><TD class=\"poststyle\"><b>QUOTE</b> (\\1)</TD></TR><TR><TD id=\"QUOTE\"> \\2 </TD></TR></TABLE>", $val);
	}
return $val;
}
function replace_text_code($val) {
	while ( preg_match( "#\[code\](.+?)\[/code\]#is", $val ) ) {
		$val = preg_replace( "#\[code\](.+?)\[/code\]#is","<TABLE border=\"0\" align=\"center\" width=\"95%\" cellpadding=\"3\" cellspacing=\"1\"><TR><TD class=\"poststyle\"><b>CODE</b></TD></TR><TR><TD id=\"CODE\"> \\1 </TD></TR></TABLE>", $val);
	}
	while ( preg_match( "#\[codebox\](.+?)\[/codebox\]#is", $val ) ) {
		$val = preg_replace( "#\[codebox\](.+?)\[/codebox\]#is","<TABLE border=\"0\" align=\"center\" width=\"95%\" cellpadding=\"3\" cellspacing=\"1\"><TR><TD class=\"poststyle\"><b>CODE</b></TD></TR><TR><TD id=\"CODE\"><div style='height:200px;white-space:pre;overflow:auto'> \\1 </div></TD></TR></TABLE>", $val);
	}
return $val;
}
function replace_text_image($val) {
	while ( preg_match( "#\[URL\s*=\s*(\S+?)\s*\]\[img\s*\](.*?)\[\/img\]\[\/URL\]#is", $val ) ) {
		$val = preg_replace_callback( "#\[URL\s*=\s*(\S+?)\s*\]\[img\s*\](.*?)\[\/img\]\[\/URL\]#is", 'regex_image_url' , $val);
	}
	while ( preg_match( "#\[img\s*\](.*?)\[\/img\]#is", $val ) ) {
		$val = preg_replace_callback( "#\[img\s*\](.*?)\[\/img\]#is", 'regex_image' , $val);
	}
return $val;
}
function regex_font_size($matches=array()) {
	$size = array('0','8','10','12','14','18','24','36');
	$array = $matches[1];
	return "<span style='font-size:".$size[$array]."pt;line-height:100%'>$matches[2]</span>";
}
function regex_image_url($matches=array()) {
	global $CP;
	if(!$CP['images_num'])
		$CP['images_num'] = 0;
	$CP['images_num']++;
	return "<span id=\"image_".$CP[images_num]."\"><a href=\"$matches[1]\" target=\"_blank\"><img name=\"user_posted_image_".$CP[images_num]."\" src=\"$matches[2]\" border=\"0\" alt=\"user�posted�image\"></a></span>";
}
function regex_image($matches=array()) {
	global $CP;
	if(!$CP['images_num'])
		$CP['images_num'] = 0;
	$CP['images_num']++;
	return "<span id=\"image_".$CP[images_num]."\"><img name=\"user_posted_image_".$CP[images_num]."\" src=\"$matches[1]\" border=\"0\" alt=\"user�posted�image\"></span>";
}
function regex_list($matches=array()) {
	if ( count( $matches ) == 3 ) {
		$type = $matches[1];
		$txt  = $matches[2];
	} else {
		$txt  = $matches[1];
	}
		
	if ( $txt == "" ) {
		return;
	}
		
	if ( $type == "" ) {
		$txt = regex_list_item($txt);
		return "<ul>$txt</ul>";
	} else {
		$txt = regex_list_item($txt);
		return "<ol type='$type'>$txt</ol>";
	}
}
function regex_list_item($txt) {
	$txt = preg_replace( "#\[\*\]#", "</li><li>" , trim($txt) );
		
	$txt = preg_replace( "#^</?li>#"  , "", $txt );
		
return str_replace( "\n</li>", "</li>", $txt."</li>" );
}
function check_category($forum_id) {
global $CONFIG_sql_cpdbname;
$forum_id = (int)$forum_id;
$sql = new MySQL;
$query = "SELECT category_id FROM $CONFIG_sql_cpdbname.forum WHERE forum_id = \"".mysql_res($forum_id)."\"";
$sql->result = $sql->execute_query($query,'function.php');
if ($sql->count_rows()) {
	$row = $sql->fetch_row();
	$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.memory WHERE memory_object = \"forum_category\" and memory_value1 = \"".mysql_res($row['category_id'])."\"";
	$sql->result = $sql->execute_query($query,'function.php');
	if (!$sql->result())
		return 0;
} else
	return 0;
return $row[category_id];
}
function get_categoryname($category_id) {
global $CONFIG_sql_cpdbname;
$category_id = (int)$category_id;
$sql = new MySQL;
$query = "SELECT memory_value3 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object = \"forum_category\" and memory_value1 = \"".mysql_res($category_id)."\"";
$sql->result = $sql->execute_query($query,'function.php');
if ($sql->count_rows()) {
	$row = $sql->fetch_row();
	return $row[memory_value3];
} else
	return "Unknow";
}
function get_forumname($forum_id) {
global $CONFIG_sql_cpdbname;
$forum_id = (int)$forum_id;
$sql = new MySQL;
$query = "SELECT category_id, forum_title FROM $CONFIG_sql_cpdbname.forum WHERE forum_id = \"".mysql_res($forum_id)."\"";
$sql->result = $sql->execute_query($query,'function.php');
if ($sql->count_rows()) {
	$row = $sql->fetch_row();
	$forumname=$row[forum_title];
} else
	return "Unknow";
return $forumname;
}
function get_topicname($topic_id) {
global $CONFIG_sql_cpdbname;
$topic_id = (int)$topic_id;
$sql = new MySQL;
$query = "SELECT topic_name FROM $CONFIG_sql_cpdbname.board_topic WHERE topic_id = \"".mysql_res($topic_id)."\"";
$sql->result = $sql->execute_query($query,'function.php');
if ($sql->count_rows()) {
	$row = $sql->fetch_row();
	$topicname=$row["topic_name"];
} else
	return "Unknow";
return $topicname;
}
function do_blacklist($state,$blacklist_code) {
switch($state) {
	case 1:
		CP_setCookie("IP_Blacklist",$blacklist_code);
		header("location:pageerr.php?code=01");
		break;
	case 2:
		$sql = new MySQL;
		global $CONFIG_sql_cpdbname;
		$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.memory WHERE memory_object=\"ip_blacklist\" AND memory_value3=\"".mysql_res($blacklist_code)."\"",'function.php');
		CP_removeCookie("IP_Blacklist");
		break;
	case 3:
		header("location:pageerr.php?code=01");
		break;
	case 4:
		CP_removeCookie("IP_Blacklist");
		break;
}
return true;
}
function get_members_menu($mode,$menu,$g_id) {
if($mode) {
	$get_menu = 0;
	if($mode == '2' && $g_id == '5') $get_menu = 1;
	else if($mode == '1') $get_menu = 1;
	if($get_menu) {
		global $lang,$IMG;
		switch($menu) {
			case char_manage:
				global $CP;
				$title = "Character Management";
				$mes = $lang[Char_Manage];
				$link = "index.php?act=searching_char&account_id=$CP[login_id]";
				break;
			case player_rank:
				$title = "Player Ranking";
				$mes = $lang[Player_Rank];
				$link = "index.php?act=p_rank";
				break;
			case guild_rank:
				$title = "Guild Ranking";
				$mes = $lang[Guild_Rank];
				$link = "index.php?act=g_rank";
				break;
			default:
				$title = "Unknow";
				$mes = "Unknow";
				$link = "index.php?act=idx";
				break;
		}
		return "$IMG[ARROW]<a href=\"$link\" title=\"$title\">$mes</a><br />";
	} else
		return "";
} else
	return "";
}
function upload_files($files) {
global $CONFIG_uploads_mode;
if(empty($files[name]) || !$CONFIG_uploads_mode) {
	$attachs_name = "";
	$upload_error ="";
} else {
	global $CP,$lang,$CONFIG_uploads_size,$CONFIG_uploads_folder;
	if (eregi(".htm|.html|.shtm|.shtml|.js|.jse|.vb|.php|.php3|.php4|.php5|.asp|.aspx|.jsp|.sql",$files[name])) {
		$upload_error = "<font color=\"red\">$lang[Upload_Type_Error]</font><br /><br />";
		$attachs_name = "";
	} else if (($files[size] > $CONFIG_uploads_size*1024 || $files[size] == 0) && !checkprivilege_action($CP[login_id],g_upload_nonlimit)) {
		$upload_error = "<font color=red>$lang[Upload_Limit]</font><br /><br />";
		$attachs_name = "";
	} else {
		$attachs_name = truestr($files['name']);
		if($attachs_name && move_uploaded_file($files['tmp_name'], "$CONFIG_uploads_folder/[".$CP[login_id]."]".$attachs_name."")) {
			$upload_error = "";
		} else {
			$upload_error = "<font color=red>There was a problem uploading your file.</font><br /><br />";
			$attachs_name = "";
		}
	}
}
return array('name' => $attachs_name, 'error' => $upload_error);
}
function upload_avatars($files) {
global $CP,$CONFIG_avatar_size,$CONFIG_avatar_folder,$CONFIG_upload_avatar,$POST_avatar_url,$lang;
if(empty($files[name]) || !$CONFIG_upload_avatar) {
	$avatar_url = $POST_avatar_url;
	$avatarname = "";
	$upload_error ="";
} else {
	if (!eregi(".gif|.jpg|.jpeg|.png|.bmp",$files[name])) {
		$avatar_url = $POST_avatar_url;
		$upload_error = "<font color=red>$lang[Upload_Type_Error]</font><br /><br />";
		$avatarname = "";
	} else if($files[size] > $CONFIG_avatar_size*1024) {
		$avatar_url = $POST_avatar_url;
		$upload_error = "<font color=red>$lang[Upload_Limit]</font><br /><br />";
		$avatarname="";
	} else {
		$error = 0;
		switch($files[type]) {
			case "image/gif":
				$avatarname="av-".$CP['login_id'].".gif";
				break;
			case "image/jpeg":
			case "image/pjpeg":
				$avatarname="av-".$CP['login_id'].".jpg";
				break;
			case "image/x-png":
			case "image/png":
				$avatarname="av-".$CP['login_id'].".png";
				break;
			case "image/x-MS-bmp":
				$avatarname="av-".$CP['login_id'].".bmp";
				break;
			default:
				$error = 1;
		}
		if(!$error && move_uploaded_file($files['tmp_name'], "$CONFIG_avatar_folder/".$avatarname."")) {
			$avatar_url = "$CONFIG_avatar_folder/".$avatarname."";
			$upload_error = "";
		} else {
			$avatar_url = "$POST_avatar_url";
			$upload_error = "<font color=red>There was a problem uploading your file.</font><br /><br />";
			$avatarname = "";
		}
	}
}
return array('name' => $avatarname, 'url' => $avatar_url, 'error' => $upload_error);
}
function get_forum_perm($matches=array()) {
global $forum_permission;
	$forum_permission = $matches[1];
return $forum_permission;
}
function check_forum_perm($forum_id,$g_id,$perm) {
global $CONFIG_sql_cpdbname;
$sql = new MySQL;
$query = "SELECT forum_perm FROM $CONFIG_sql_cpdbname.forum WHERE forum_id=\"".mysql_res($forum_id)."\"";
$sql->result = $sql->execute_query($query,'function.php');
if ($sql->count_rows()) {
	$row = $sql->fetch_row();
	if ( preg_match( "#\[g".$g_id."\](.+?)\[/g".$g_id."\]#is", $row[0] ) ) {
		preg_replace_callback( "#\[g".$g_id."\](.+?)\[/g".$g_id."\]#is", 'get_forum_perm' , $row[0] );
		global $forum_permission;
		switch($perm) {
			case "read_perm":
				if (!strstr($forum_permission,":show_perm:"))
					return false;
				break;
			case "reply_perm":
			case "start_perm":
				if (!strstr($forum_permission,":show_perm:"))
					return false;
				if (!strstr($forum_permission,":read_perm:"))
					return false;
				break;
		}
		if (strstr($forum_permission,":".$perm.":"))
			return true;
		else
			return false;
	} else
		return false;
} else
	return false;
}
function get_language_select() {
global $STORED,$SELECT_MENU;
$SELECT_MENU = 1;
echo "<TR align=\"center\">
<form method=\"post\" name=\"ch_lang\" action=\"ch_lang.php\">
	<TD>
		<select name=\"ch_lang\" onChange=\"if(document.ch_lang.ch_lang.value==-1){return false;}else{document.ch_lang.submit();}\" class=\"selectmenu\" style=\"width:90%\">
			<option value=\"-1\" selected>Select Your Language</option>
";
$dir = "lang/";
if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if (substr($file, strlen($file) - 4, 4) == ".php") {
				$file = substr($file, 0, strlen($file) - 4);
				$lang_name = $file;
				if ($lang_name==$STORED[LANG]) {
					$ADD_OPTION = "value=\"-1\" class=\"slbackground\"";
				} else {
					$ADD_OPTION = "value=\"$lang_name\"";
				}
echo "			<option ".$ADD_OPTION.">- $lang_name</option>\n";
			}
		}
	closedir($dh);
	}
}
echo "		</select>
	</TD>
</form>
</TR>
";
return;
}
function get_theme_select() {
global $STORED,$SELECT_MENU;
$SELECT_MENU = 1;
echo "<TR align=\"center\">
<form method=\"post\" name=\"ch_theme\" action=\"ch_theme.php\">
	<TD>
		<select name=\"ch_theme\" onChange=\"if(document.ch_theme.ch_theme.value==-1){return false;}else{document.ch_theme.submit();}\" class=\"selectmenu\" style=\"width:90%\">
			<option value=\"-1\" selected>Select Your Theme</option>
";
$dir = "theme/";
if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if (is_dir("theme/$file")) {
				if ($file != "." && $file != "..") {
					$theme_name = $file;
					if ($theme_name==$STORED[THEME]) {
						$ADD_OPTION = "value=\"-1\" class=\"slbackground\"";
					} else {
						$ADD_OPTION = "value=\"$theme_name\"";
					}
echo "			<option ".$ADD_OPTION.">- $theme_name</option>\n";
				}
			}
		}
	closedir($dh);
	}
}
echo "		</select>
	</TD>
</form>
</TR>
";
return;
}
function get_rte($form_id,$id,$title,$title2,$width1,$width2) {
global $STORED;
$style_width = $width2?"width:".$width2.";":"";
echo "<a onclick=\"\" href=\"#\"><TD>
<div class=\"rte-menu\" onclick=\"Showbbcode('".$id."');event.cancelBubble=1;return false;\">
	<TABLE border=\"0\" cellspacing=\"0\" cellpadding=\"2\" class=\"rte-tbmenu\" style=\"width:".$width1.";\"><TR>
	<TD style=\"BACKGROUND-IMAGE: url(theme/$STORED[THEME]/images/bbcode/rte-dd-bg.gif);\" class=\"rte-menu-button\">
		<span title=\"$title2\">".$title."</span>
	</TD>
	<TD align=\"right\"><img src=\"theme/$STORED[THEME]/images/bbcode/icon_open.gif\"></TD>
	</TR></TABLE>
	<div id=\"".$id."\" class=\"rte-menu-hidden\" style=\"".$style_width."visibility:hidden;\"></div>
</div>
</TD>
</a>
";
}
function get_bbcode($id,$mode='0') {
global $STORED;
echo "<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><TR>
";
if (!$mode) {
echo "
<TD>
<TABLE border=\"0\" cellspacing=\"2\" cellpadding=\"0\"><TR>
";
	get_rte($id,'ffont','Fonts','Fonts','155px','140px');
	get_rte($id,'fsize','Sizes','Sizes','90px','75px');
	get_rte($id,'fcolor',"<div align=\"center\"><img src=\"theme/$STORED[THEME]/images/bbcode/rte-textcolor.gif\"></div>",'Text Color','20px','0');
echo "</TR></TABLE>
</TD>
<TD align=\"right\" width=\"100%\">
<TABLE border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"right\"><TR height=\"50%\">
<TD onMouseout=\"switchbg('ed-0_cmd_rte-resize-up',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-resize-up',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-resize-up\"><img alt=\"Resize smaller\" src=\"theme/$STORED[THEME]/images/bbcode/rte-resize-up.gif\" onclick=\"resize_post_form('$id','-');\"></div></TD>
</TR>
<TR height=\"50%\">
<TD align=\"right\" onMouseout=\"switchbg('ed-0_cmd_rte-resize-down',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-resize-down',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-resize-down\"><img alt=\"Resize larger\" src=\"theme/$STORED[THEME]/images/bbcode/rte-resize-down.gif\" onclick=\"resize_post_form('$id','+');\"></div></TD>
</TR>
</TABLE>
</TD>
";
}
echo "</TR></TABLE>

<TABLE border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><TR>
<TD>
<TABLE border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><TR>
<TD onMouseout=\"switchbg('ed-0_cmd_rte-bold',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-bold',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-bold\"><img alt=\"Bold\" src=\"theme/$STORED[THEME]/images/bbcode/rte-bold.png\" onclick='simpletag(\"$id\",\"b\")'></div></TD>
<TD onMouseout=\"switchbg('ed-0_cmd_rte-italic',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-italic',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-italic\"><img alt=\"Italic\" src=\"theme/$STORED[THEME]/images/bbcode/rte-italic.png\" onclick='simpletag(\"$id\",\"i\")'></div></TD>
<TD onMouseout=\"switchbg('ed-0_cmd_rte-underlined',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-underlined',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-underlined\"><img alt=\"Underline\" src=\"theme/$STORED[THEME]/images/bbcode/rte-underlined.png\" onclick='simpletag(\"$id\",\"u\")'></div></TD>
<TD onMouseout=\"switchbg('ed-0_cmd_rte-image-button',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-image-button',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-image-button\"><img alt=\"Insert Image\" src=\"theme/$STORED[THEME]/images/bbcode/rte-image-button.png\" onclick=\"tag_image('$id')\"></div></TD>
<TD onMouseout=\"switchbg('ed-0_cmd_rte-link-button',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-link-button',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-link-button\"><img alt=\"Insert Link\" src=\"theme/$STORED[THEME]/images/bbcode/rte-link-button.png\" onclick=\"tag_url('$id')\"></div></TD>
<TD onMouseout=\"switchbg('ed-0_cmd_rte-email-button',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-email-button',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-email-button\"><img alt=\"Insert Email Link\" src=\"theme/$STORED[THEME]/images/bbcode/rte-email-button.png\" onclick=\"tag_email('$id')\"></div></TD>
<TD onMouseout=\"switchbg('ed-0_cmd_rte-quote-button',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-quote-button',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-quote-button\"><img alt=\"Wrap in quote tags\" src=\"theme/$STORED[THEME]/images/bbcode/rte-quote-button.png\" onclick='simpletag(\"$id\",\"quote\")'></div></TD>
<TD onMouseout=\"switchbg('ed-0_cmd_rte-code-button',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-code-button',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-code-button\"><img alt=\"Wrap in code tags\" src=\"theme/$STORED[THEME]/images/bbcode/rte-code-button.png\" onclick='simpletag(\"$id\",\"code\")'></div></TD>
</TR></TABLE>
</TD>
<TD align=\"right\" width=\"100%\">
<TABLE border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"right\"><TR>
";
if (!$mode) {
echo "<TD onMouseout=\"switchbg('ed-0_cmd_rte-indent',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-indent',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-indent\"><img alt=\"Indent\" src=\"theme/$STORED[THEME]/images/bbcode/rte-indent.gif\" onclick='simpletag(\"$id\",\"indent\")'></div></TD>
<TD><img src=\"theme/$STORED[THEME]/images/bbcode/rte_dots.gif\" width=\"1\" height=\"20\"></TD>
<TD onMouseout=\"switchbg('ed-0_cmd_rte-left',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-left',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-left\"><img alt=\"Align Left\" src=\"theme/$STORED[THEME]/images/bbcode/rte-align-left.png\" onclick='simpletag(\"$id\",\"left\")'></div></TD>
<TD onMouseout=\"switchbg('ed-0_cmd_rte-centre',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-centre',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-centre\"><img alt=\"Align Center\" src=\"theme/$STORED[THEME]/images/bbcode/rte-align-center.png\" onclick='simpletag(\"$id\",\"center\")'></div></TD>
<TD onMouseout=\"switchbg('ed-0_cmd_rte-right',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-right',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-right\"><img alt=\"Align Right\" src=\"theme/$STORED[THEME]/images/bbcode/rte-align-right.png\" onclick='simpletag(\"$id\",\"right\")'></div></TD>
<TD><img src=\"theme/$STORED[THEME]/images/bbcode/rte_dots.gif\" width=\"1\" height=\"20\"></TD>
<TD onMouseout=\"switchbg('ed-0_cmd_rte-list-numbered',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-list-numbered',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-list-numbered\"><img alt=\"Insert List\" src=\"theme/$STORED[THEME]/images/bbcode/rte-list-numbered.gif\" onclick='tag_list(\"$id\",1)'></div></TD>
<TD onMouseout=\"switchbg('ed-0_cmd_rte-list',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-list',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-list\"><img alt=\"Insert List\" src=\"theme/$STORED[THEME]/images/bbcode/rte-list.gif\" onclick='tag_list(\"$id\")'></div></TD>
";
} else {
echo "<TD align=\"right\" width=\"100%\">
<TABLE border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"right\"><TR height=\"50%\">
<TD onMouseout=\"switchbg('ed-0_cmd_rte-resize-up',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-resize-up',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-resize-up\"><img alt=\"Resize smaller\" src=\"theme/$STORED[THEME]/images/bbcode/rte-resize-up.gif\" onclick=\"resize_post_form('$id','-');\"></div></TD>
</TR>
<TR height=\"50%\">
<TD align=\"right\" onMouseout=\"switchbg('ed-0_cmd_rte-resize-down',1);\" onMouseover=\"switchbg('ed-0_cmd_rte-resize-down',0);\"><div class=\"rte-normal\" id=\"ed-0_cmd_rte-resize-down\"><img alt=\"Resize larger\" src=\"theme/$STORED[THEME]/images/bbcode/rte-resize-down.gif\" onclick=\"resize_post_form('$id','+');\"></div></TD>
</TR>
</TABLE>
</TD>
";
}
echo "</TR></TABLE>
</TD>
</TR></TABLE>
<script type='text/javascript'>do_bbcode('$id');</script>
";
}
?>
