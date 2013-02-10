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
$GET_account_id = (int)$GET_account_id;
$GET_char_id = (int)$GET_char_id;
if($STORED_loginname && $STORED_loginpass && !empty($char_manage_menu)) {
if ($CP[login_id]==$GET_account_id) {
if($GET_code==00) {
	$query = "SELECT char_id,account_id,char_num,name,class,zeny,last_map,last_x,last_y,save_map,save_x,save_y FROM $CONFIG_sql_dbname.char WHERE account_id =\"".mysql_res($GET_account_id)."\"";
	$sql->result = $sql->execute_query($query,'searching_char.php');$sql->total_query++;
echo "
<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"topic_title\" align=\"center\">
	<TBODY>
		<TR align=\"center\" class=\"title_bar\" height=\"29\">
			<TD>
				<a class=\"m_title\">Character in user: $CP[login_name]</a>
			</TD>
		</TR>
		<TR>
			<TD>
				<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" border=\"0\" align=\"center\">
					<TBODY>
";
	$total_zeny = 0;
	if ($sql->count_rows()) {
		DEFINE('manage_zeny',$CONFIG_manage_zeny_mode);
		if(manage_zeny) {
			if($CONFIG_max_zeny < 1 || $CONFIG_max_zeny > 2147483647)
				$CONFIG_max_zeny = 1000000000;
			$submit_action = "return CheckManagechar()";
		} else {
			$submit_action = "return false;";
		}
echo "					<form action=\"index.php?act=searching_char&code=03&account_id=$GET_account_id\" method=\"post\" enctype=\"multipart/form-data\" name=\"Searching_Char\" OnSubmit=\"".$submit_action."\">
";
		if(manage_zeny) {
echo "						<TR class=\"topic_title4\">
							<TD>
								<b>Total account zeny remain</b>: <input type=\"text\" value=\"0\" name=\"zeny_remain\" class=\"input_hidden\" size=\"15\" maxlength=\"255\" style=\"font-weight:bold\" readonly><br>
								<b>Maximum zeny per character</b>: <b>$CONFIG_max_zeny</b>
							</TD>
						<TR>
";
		}
		$n=0;
		while ($c_row = $sql->fetch_row()) {
			$n++;
			if(manage_zeny)
				$total_zeny = $total_zeny + $c_row[zeny];
			$jobid="$c_row[class]";
echo "						<input type=\"hidden\" name=\"char_id_".$n."\" value=\"$c_row[char_id]\">
						<TR class=\"topic_title3\" style=\"font-weight: bold;\">
							<TD colspan=\"2\">$n. ".htmlspecialchars($c_row[name])."</TD>
						</TR>
						<TR class=\"topic_title4\">
							<TD>- <b>Class</b>: $jobname[$jobid]</TD>
						<TR>
						<TR class=\"topic_title4\">
							<TD>- <b>Last Map</b>: $c_row[last_map] &lt;$c_row[last_x],$c_row[last_y]&gt;</TD>
						<TR>
						<TR class=\"topic_title4\">
							<TD>- <b>Save Map</b>: $c_row[save_map] &lt;$c_row[save_x],$c_row[save_y]&gt;</TD> 
						<TR>
";
			if(manage_zeny) {
echo "						<TR class=\"topic_title4\">
							<TD>- <b>Zeny</b>: <input name=\"zeny_".$n."\" id=\"zeny_".$n."\" type=\"text\" size=\"15\" class=\"textinput\" value=\"$c_row[zeny]\" onkeyup=\"Manage_account_zeny()\">
							<a href=\"#\" onclick=\"CheckZeny('zeny_".$n."'); return false;\">&lt;check zeny&gt;</a>
							</TD> 
						<TR>
";
			} else {
echo "						<TR class=\"topic_title4\">
							<TD>- <b>Zeny</b>: $c_row[zeny]</TD> 
						<TR>
";
			}
echo "						<TR class=\"topic_title4\">
							<TD>- <b>Slot</b>: 
								<select name=\"Change_Slot\" class=\"textinput\" OnChange=\"document.location.replace(''+this.value+'');\">
";
				for($i=0;$i<9;$i++) {
					$a = $i+1;
					if($c_row[char_num] == $i)
echo "									<option value=\"index.php?act=searching_char&account_id=$c_row[account_id]\" style=\"color:red;\" selected>".$a."</option>\n";
					else
echo "									<option value=\"index.php?act=searching_char&code=02&account_id=$c_row[account_id]&char_id=$c_row[char_id]&slot=$i\">".$a."</option>\n";
				}
echo "								</select>
							</TD> 
						<TR>
						<TR class=\"topic_title4\">
							<TD>- <b>Manage character</b>: 
								<select name=\"Action\" class=\"textinput\" OnChange=\"document.location.replace(''+this.value+'');\">
									<option value=\"index.php?act=searching_char\" selected>    Select Action</option>
									<option value=\"index.php?act=searching_char&code=01&module=01&account_id=$c_row[account_id]&char_id=$c_row[char_id]\">&lt;$lang[CM_Move_to_Savepoint]&gt;</option>
									<option value=\"index.php?act=searching_char&code=01&module=02&account_id=$c_row[account_id]&char_id=$c_row[char_id]\">&lt;$lang[CM_Reset_Hair1]&gt;</option>
									<option value=\"index.php?act=searching_char&code=01&module=03&account_id=$c_row[account_id]&char_id=$c_row[char_id]\">&lt;$lang[CM_Reset_Hair2]&gt;</option>
									<option value=\"index.php?act=searching_char&code=01&module=04&account_id=$c_row[account_id]&char_id=$c_row[char_id]\">&lt;$lang[CM_Reset_Clothes]&gt;</option>
									<option value=\"index.php?act=searching_char&code=01&module=05&account_id=$c_row[account_id]&char_id=$c_row[char_id]\">&lt;$lang[CM_Reset_Equip]&gt;</option>
								</select>
							</TD>
						<TR>
";
		}
		if(manage_zeny) {
echo "						<TR align=\"center\" class=\"topic_title3\">
							<TD><input type=\"submit\" name=\"Submit\" value=\"Save zeny\" class=\"textinput\"></TD>
						</TR>
						<input type=\"hidden\" name=\"total_zeny\" value=\"$total_zeny\">
						<input type=\"hidden\" name=\"total_char\" value=\"$n\">
";
		}
echo "					</form>
";
		if(manage_zeny) {
echo "<script type='text/javascript'>
var total_zeny = $total_zeny;
var total_char = $n;

function CheckZeny(x) {
var a = parseInt(document.getElementById(x).value);
var b = parseInt(document.Searching_Char.zeny_remain.value)
if(!a) a = 0;
if(!b) b = 0;
	document.getElementById(x).value = a + b;
	Manage_account_zeny();
}
function Manage_account_zeny() {
var zeny = total_zeny;
var zeny_check = 0;
	for(var i=1;i<=total_char;i++) {
		zeny_check = parseInt(document.getElementById('zeny_'+i+'').value);
		if(zeny_check < 0)
			document.getElementById('zeny_'+i+'').value = 0;
		if(zeny_check > total_zeny) {
			document.getElementById('zeny_'+i+'').value = total_zeny;
		}
		zeny = parseInt(zeny) - parseInt(eval('document.Searching_Char.zeny_'+i+'.value'));
		document.Searching_Char.zeny_remain.value=zeny;
	}
}
function CheckManagechar() {
var obj = document.Searching_Char;
var zeny = total_zeny;
	for(var i=1;i<=total_char;i++) {
		if(parseInt(document.getElementById('zeny_'+i+'').value) < 0) {
			alert('zeny was incorrect');
			document.getElementById('zeny_'+i+'').focus()
			return false;
		}
		zeny = zeny - eval('obj.zeny_'+i+'.value');
	}
	if(obj.zeny_remain.value != 0 || zeny != 0) {
		alert('Total account zeny remain must be zero');
		obj.zeny_1.focus();
		return false;
	} else {
		obj.Submit.disabled=true;
	}
}
</script>";
		}
	} else {
echo "
						<TR class=\"topic_title3\" height=\"25\">
							<TD></TD>
						</TR>
						<TR align=\"center\" class=\"topic_title4\">
							<TD><b>Character not found</b></TD>
						</TR>
";
	}
echo "					</TBODY>
				</TABLE>
			</TD>
		</TR>
	</TBODY>
</TABLE>
";
}
else if($GET_code==01 && $GET_module) {
	$query = "SELECT char_id,account_id,name,last_map,save_map,save_x,save_y,online FROM $CONFIG_sql_dbname.char WHERE account_id =\"".mysql_res($GET_account_id)."\" AND char_id =\"".mysql_res($GET_char_id)."\"";
	$sql->result = $sql->execute_query($query,'searching_char.php');
	if($sql->count_rows()) {
		$row = $sql->fetch_row();
		if($row[online]) {
			$display = sprintf($lang[CM_Char_Online],$row[name]);
			redir("index.php?act=searching_char&account_id=$row[account_id]",$display,3);
		} else {
			switch($GET_module) {
				case 01:
					if (!strstr($row["last_map"],"sec_pri"))
						$sql->execute_query("UPDATE $CONFIG_sql_dbname.char SET last_map=\"".mysql_res($row['save_map'])."\", last_x=\"".mysql_res($row['save_x'])."\", last_y=\"".mysql_res($row['save_y'])."\" WHERE account_id=\"".mysql_res($GET_account_id)."\" AND char_id=\"".mysql_res($GET_char_id)."\"",'searching_char.php');
					else
						redir("index.php?act=idx","$lang[Error]",3);
					break;
				case 02:
					$sql->execute_query("UPDATE $CONFIG_sql_dbname.char SET hair=\"1\" WHERE account_id=\"".mysql_res($GET_account_id)."\" AND char_id=\"".mysql_res($GET_char_id)."\"",'searching_char.php');
					break;
				case 03:
					$sql->execute_query("UPDATE $CONFIG_sql_dbname.char SET hair_color=\"0\" WHERE account_id=\"".mysql_res($GET_account_id)."\" AND char_id=\"".mysql_res($GET_char_id)."\"",'searching_char.php');
					break;
				case 04:
					$sql->execute_query("UPDATE $CONFIG_sql_dbname.char SET clothes_color=\"0\" WHERE account_id=\"".mysql_res($GET_account_id)."\" AND char_id=\"".mysql_res($GET_char_id)."\"",'searching_char.php');
					break;
				case 05:
					$sql->execute_query("UPDATE $CONFIG_sql_dbname.char SET weapon=\"0\", shield=\"0\", head_top=\"0\", head_mid=\"0\", head_bottom=\"0\" WHERE account_id=\"".mysql_res($GET_account_id)."\" AND char_id=\"".mysql_res($GET_char_id)."\"",'searching_char.php');
					$sql->execute_query("UPDATE $CONFIG_sql_dbname.inventory SET equip=\"0\" WHERE char_id=\"".mysql_res($GET_char_id)."\"");
					break;
			}
			header_location("index.php?act=searching_char&account_id=$GET_account_id");
		}
	} else {
		redir("index.php?act=idx","$lang[Error]",3);
	}
}
else if($GET_code==02 && $GET_slot >= 0 && $GET_slot <= 9) {
	$GET_slot = (int)$GET_slot;
	$query = "SELECT char_num,name,online FROM $CONFIG_sql_dbname.char WHERE account_id =\"".mysql_res($GET_account_id)."\" && char_id=\"".mysql_res($GET_char_id)."\"";
	$sql->result = $sql->execute_query($query,'searching_char.php');
	if($sql->count_rows()) {
		$row = $sql->fetch_row();
		if($row[online]) {
			$display = sprintf($lang[CM_Char_Online],$row[name]);
			redir("index.php?act=searching_char&account_id=$GET_account_id",$display,3);
		} else {
			$query = "SELECT char_id,name,online FROM $CONFIG_sql_dbname.char WHERE account_id =\"".mysql_res($GET_account_id)."\" && char_num=\"".mysql_res($GET_slot)."\"";
			$sql->result = $sql->execute_query($query,'searching_char.php');
			$row2 = $sql->fetch_row();
			if($row2[online]) {
				$display = sprintf($lang[CM_Char_Online],$row2[name]);
				redir("index.php?act=searching_char&account_id=$GET_account_id",$display,3);
			} else {
				$sql->execute_query("UPDATE $CONFIG_sql_dbname.char SET char_num=\"".mysql_res($GET_slot)."\" WHERE account_id=\"".mysql_res($GET_account_id)."\" AND char_id=\"".mysql_res($GET_char_id)."\"",'searching_char.php');
				if($sql->count_rows()) {
					$sql->execute_query("UPDATE $CONFIG_sql_dbname.char SET char_num=\"".mysql_res($row['char_num'])."\" WHERE account_id=\"".mysql_res($GET_account_id)."\" AND char_id=\"".mysql_res($row2['char_id'])."\"",'searching_char.php');
				}
				header_location("index.php?act=searching_char&account_id=$GET_account_id");
			}
		}
	} else {
		redir("index.php?act=idx","$lang[Error]",3);
	}
}
else if($GET_code==03 && $_POST['total_zeny'] = (int)$_POST['total_zeny'] && $_POST['total_char'] = (int)$_POST['total_char'] && $CONFIG_manage_zeny_mode) {
	$query = "SELECT name FROM $CONFIG_sql_dbname.char WHERE account_id =\"".mysql_res($GET_account_id)."\" && online=\"1\"";
	$sql->result = $sql->execute_query($query,'searching_char.php');
	if($sql->count_rows()) {
		$row = $sql->fetch_row();
		$display = sprintf($lang[CM_Char_Online],$row[name]);
		redir("index.php?act=searching_char&account_id=$GET_account_id",$display,3);
	} else {
		$query = "SELECT zeny FROM $CONFIG_sql_dbname.char WHERE account_id =\"".mysql_res($GET_account_id)."\"";
		$sql->result = $sql->execute_query($query,'searching_char.php');
		$total_char = $sql->count_rows();
		if($total_char == $_POST['total_char']) {
			$total_account_zeny = 0;
			$get_total_zeny = 0;
			while($row = $sql->fetch_row())
				$total_account_zeny = $total_account_zeny + $row[zeny];
			for($i=1;$i<=$total_char;$i++) {
				$_POST["zeny_".$i.""] = (int)$_POST["zeny_".$i.""];
				$get_total_zeny = $get_total_zeny + $_POST["zeny_".$i.""];
			}
			if($total_account_zeny == $_POST['total_zeny'] && $total_account_zeny == $get_total_zeny) {
				$error = 0;
				if($CONFIG_max_zeny < 1 || $CONFIG_max_zeny > 2147483647)
					$CONFIG_max_zeny = 1000000000;
				for($i=1;$i<=$total_char;$i++) {
					if($_POST["zeny_".$i.""] < 0 || $_POST["zeny_".$i.""] > $CONFIG_max_zeny) {
						$error = 1;
						$zeny_error = $CONFIG_max_zeny;
					}
				}
				if($error == 0) {
					for($i=1;$i<=$total_char;$i++) {
						$zeny = $_POST["zeny_".$i.""];
						$char_id = (int)$_POST["char_id_".$i.""];
						$sql->execute_query("UPDATE $CONFIG_sql_dbname.char SET zeny=\"".mysql_res($zeny)."\" WHERE account_id=\"".mysql_res($GET_account_id)."\" AND char_id=\"".mysql_res($char_id)."\"",'searching_char.php');
					}
					header_location("index.php?act=searching_char&account_id=$GET_account_id");
				} else {
					$display = sprintf($lang[CM_zeny_impossible],$zeny_error);
					redir("index.php?act=searching_char&account_id=$GET_account_id",$display,3);
				}
			} else {
				redir("index.php?act=searching_char&account_id=$GET_account_id","$lang[Error]",3);
			}
		} else {
			redir("index.php?act=searching_char&account_id=$GET_account_id","$lang[Error]",3);
		}
	}
}

} else {
	redir("index.php?act=idx","$lang[Error]",3);
}
}
?>