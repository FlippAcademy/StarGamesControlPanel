<?php
if(!$SERVER['system_safe']) exit();
if(checkprivilege_action($CP[login_id],g_forum_manage)){
if($GET_manage && ($POST_c || $POST_f)) {
switch($GET_manage) {
	case newcategory:
		if(length($POST_category_name,1,50)) {
			$query = "SELECT memory_value1 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"forum_category\" ORDER by memory_value1 DESC";
			$sql->result = $sql->execute_query($query,'forum_manage.php');$sql->total_query++;
			$row = $sql->fetch_row();
			$newcategory_id = $row[memory_value1]+1;

			$count_category = $sql->execute_query("SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"forum_category\"",'forum_manage.php');
			$count_category = $sql->result($count_category);
			$count_category++;
			$POST_category_name = checkstring($POST_category_name,1);
			$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.memory (memory_object,memory_value1,memory_value2,memory_value3) VALUES (\"forum_category\",\"".mysql_res($newcategory_id)."\",\"".mysql_res($count_category)."\",\"".$POST_category_name."\")",'forum_manage.php');
		}
		break;
	case newforum:
		if(length($POST_forum_name,1,50) && length($POST_forum_description,0,100)) {
			$POST_forum_name = checkstring($POST_forum_name,1);
			$POST_forum_description = checkstring($POST_forum_description,1);
			$query = "SELECT g_id, g_title FROM $CONFIG_sql_cpdbname.groups ORDER by g_id";
			$sql->result = $sql->execute_query($query,'forum_manage.php');
			while($grow = $sql->fetch_row()) {
				$g_id = $grow[0];
				$forum_perm .= "[g".$g_id."]";
				if($_POST["show_forum_".$g_id.""])
					$forum_perm .= ":show_perm:";
				if($_POST["read_topics_".$g_id.""])
					$forum_perm .= ":read_perm:";
				if($_POST["reply_topics_".$g_id.""])
					$forum_perm .= ":reply_perm:";
				if($_POST["start_topics_".$g_id.""])
					$forum_perm .= ":start_perm:";
				$forum_perm .= "[/g".$g_id."]";
			}
			$sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.forum (category_id,forum_title,forum_description,forum_perm) VALUES (\"".mysql_res($POST_c)."\",\"".$POST_forum_name."\",\"".$POST_forum_description."\",\"".$forum_perm."\")",'forum_manage.php');
		}
		break;
	case editcategory:
		if(length($POST_category_name,1,50)) {
			$POST_category_name = checkstring($POST_category_name,1);
			$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.memory SET memory_value3=\"".$POST_category_name."\" WHERE memory_object=\"forum_category\" AND memory_value1=\"".mysql_res($POST_c)."\"");$sql->total_query++;
		}
		break;
	case editforum:
		if(length($POST_forum_name,1,50) && length($POST_forum_description,0,100)) {
			$POST_forum_name = checkstring($POST_forum_name,1);
			$POST_forum_description = checkstring($POST_forum_description,1);
			$query = "SELECT g_id, g_title FROM $CONFIG_sql_cpdbname.groups ORDER by g_id";
			$sql->result = $sql->execute_query($query,'forum_manage.php');
			while($grow = $sql->fetch_row()) {
				$g_id = $grow[0];
				$forum_perm .= "[g".$g_id."]";
				if($_POST["show_forum_".$g_id.""])
					$forum_perm .= ":show_perm:";
				if($_POST["read_topics_".$g_id.""])
					$forum_perm .= ":read_perm:";
				if($_POST["reply_topics_".$g_id.""])
					$forum_perm .= ":reply_perm:";
				if($_POST["start_topics_".$g_id.""])
					$forum_perm .= ":start_perm:";
				$forum_perm .= "[/g".$g_id."]";
			}
			$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.forum SET forum_title=\"$POST_forum_name\", forum_description=\"".$POST_forum_description."\", forum_perm=\"".$forum_perm."\" WHERE forum_id=\"".mysql_res($POST_f)."\"");$sql->total_query++;
		}
		break;
	case dropcategory:
		$query = "SELECT forum_id FROM $CONFIG_sql_cpdbname.forum WHERE category_id =\"".mysql_res($POST_c)."\"";
		$sql->result = $sql->execute_query($query,'forum_manage.php');
		while ($row = $sql->fetch_row()) {
			$query = "SELECT topic_id FROM $CONFIG_sql_cpdbname.board_topic WHERE forum_id =\"".$row['forum_id']."\"";
			$sql->result2 = $sql->execute_query($query,'forum_manage.php');
			while ($trow = $sql->fetch_row($sql->result2)) {
				$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id =\"".$trow['topic_id']."\" ",'forum_manage.php');
				$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.board_topic WHERE topic_id =\"".$trow['topic_id']."\" ",'forum_manage.php');
				$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.poll WHERE topic_id =\"".$trow['topic_id']."\" ",'forum_manage.php');
				$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.poll_vote WHERE topic_id =\"".$trow['topic_id']."\" ",'forum_manage.php');
				$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.voters WHERE topic_id =\"".$trow['topic_id']."\" ",'forum_manage.php');
			}
		}
		$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.forum WHERE category_id =\"".mysql_res($POST_c)."\" ",'forum_manage.php');
		$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"forum_category\" AND memory_value1=\"".mysql_res($POST_c)."\" ",'forum_manage.php');
		break;
	case dropforum:
		$query = "SELECT topic_id FROM $CONFIG_sql_cpdbname.board_topic WHERE forum_id =\"".mysql_res($POST_f)."\"";
		$sql->result = $sql->execute_query($query,'forum_manage.php');
		while ($row = $sql->fetch_row()) {
			$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.board_reply WHERE topic_id =\"".$row['topic_id']."\" ",'forum_manage.php');
			$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.board_topic WHERE topic_id =\"".$row['topic_id']."\" ",'forum_manage.php');
			$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.poll WHERE topic_id =\"".$row[topic_id]."\" ",'forum_manage.php');
			$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.poll_vote WHERE topic_id =\"".$row['topic_id']."\" ",'forum_manage.php');
			$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.voters WHERE topic_id =\"".$row['topic_id']."\" ",'forum_manage.php');
		}
		$sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.forum WHERE forum_id =\"".mysql_res($POST_f)."\" ",'forum_manage.php');
		break;
	case ordercategory:
		$query = "SELECT memory_value1 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object = \"forum_category\" AND memory_value2 =\"".mysql_res($POST_select_order)."\" LIMIT 0,1";
		$sql->result = $sql->execute_query($query,'forum_manage.php');
		$query = "SELECT memory_value2 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object = \"forum_category\" AND memory_value1 =\"".mysql_res($POST_c)."\" LIMIT 0,1";
		$sql->result2 = $sql->execute_query($query,'forum_manage.php');
		if ($sql->count_rows() && $sql->count_rows($sql->result2)) {
			$row = $sql->fetch_row();
			$category_id_old = $row["memory_value1"];
			$row2 = $sql->fetch_row($sql->result2);
			$category_order_new = $row2["memory_value2"];
			$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.memory SET memory_value2=\"".$category_order_new."\" WHERE memory_object=\"forum_category\" AND memory_value1=\"".$category_id_old."\"",'forum_manage.php');
		}
		$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.memory SET memory_value2=\"".mysql_res($POST_select_order)."\" WHERE memory_object=\"forum_category\" AND memory_value1=\"".mysql_res($POST_c)."\"",'forum_manage.php');
		break;
}
header_location("index.php?act=forum_manage");
}
else {
$count_category = $sql->execute_query("SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"forum_category\"",'forum_manage.php');
$count_category = $sql->result($count_category);

$query = "SELECT memory_value1,memory_value2,memory_value3 FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"forum_category\" ORDER by memory_value2 ASC";
$sql->result = $sql->execute_query($query,'forum_manage.php');$sql->total_query++;
opmain_body("Forum Management");
if($sql->count_rows()) {
	$IMG['EDIT'] = "<img src =\"theme/$STORED[THEME]/images/edit.gif\" border=\"0\" alt=\"Edit\">";
	$IMG['DELETE'] = "<img src =\"theme/$STORED[THEME]/images/drop.gif\" border=\"0\" alt=\"Delete\">";
	while ($row = $sql->fetch_row()) {
	$query = "SELECT forum_id,forum_title,forum_description FROM $CONFIG_sql_cpdbname.forum WHERE category_id=\"".$row['memory_value1']."\" ORDER by forum_id ASC";
	$sql->result2 = $sql->execute_query($query,'forum_manage.php');
	$form_name = "Category_".$row[memory_value1]."";
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<TBODY>
		<TR height=\"27\" class=\"title_bar2\">
			<form action=\"index.php?act=forum_manage&manage=ordercategory\" name=\"$form_name\" method=\"post\" enctype=\"multipart/form-data\">
			<input type=\"hidden\" name=\"c\" value=\"$row[memory_value1]\">
			<TD width=\"90%\">
				<select name=\"select_order\" onChange=\"document.$form_name.submit();\" class=\"selectmenu\">
";
	for($i=1;$i<=$count_category;$i++) {
		$selected = $i==$row[memory_value2]?" selected class=\"slbackground\"":"";
echo "					<option value=\"$i\"".$selected.">$i</option>
";
	}
echo "				</select>
				<font class=\"title_face\">Category: $row[memory_value3]</font>
			</TD>
			</form>
			<TD width=\"5%\" align=\"center\"><a href=\"index.php?act=forum_manage&code=editcategory&c=$row[memory_value1]\">$IMG[EDIT]</a></TD>
			<TD width=\"5%\" align=\"center\"><a href=\"index.php?act=forum_manage&code=dropcategory&c=$row[memory_value1]\">$IMG[DELETE]</a></TD>
		</TR>
";
	if($sql->count_rows($sql->result2)) {
		while ($frow = $sql->fetch_row($sql->result2)) {
			$forum_description = $frow[forum_description]?"<BR>$frow[forum_description]":"";
echo "		<TR class=\"topic_title7\">
			<TD><B>$frow[forum_title]</B> $forum_description</TD>
			<TD align=\"center\"><a href=\"index.php?act=forum_manage&code=editforum&f=$frow[forum_id]\">$IMG[EDIT]</a></TD>
			<TD align=\"center\"><a href=\"index.php?act=forum_manage&code=dropforum&f=$frow[forum_id]\">$IMG[DELETE]</a></TD>
		</TR>
";
		}
	} else {
echo "		<TR class=\"topic_title7\">
			<TD colspan=\"3\" align=\"center\"><B>There isn't a forum in this category</B></TD>
		</TR>
";
	}
echo "		<TR class=\"topic_title5\">
			<TD colspan=\"3\" align=\"right\">
				<input name=\"Button\" value=\"Create a New Forum\" type=\"button\" onClick=\"hyperlink('index.php?act=forum_manage&code=newforum&c=$row[memory_value1]');\" class=\"textinput\">
			</TD>
		</TR>
	</TBODY>
</TABLE>
<BR>
";
	}
} else {
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<TBODY>
		<TR height=\"27\" class=\"title_bar2\">
			<TD><div class=\"title_face\">CP Message</div></TD>
		</TR>
		<TR class=\"topic_title7\">
			<TD align=\"center\"><B>There isn't a category in this CP. Please create a new category.</B></TD>
		</TR>
	</TBODY>
</TABLE>
";
}
echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" class=\"emptytable3\">
	<TBODY>
	<form name=\"Category\">
		<TR height=\"27\" class=\"topic_title5\">
			<TD align=\"center\">
				<input name=\"Button\" value=\"Create a New Category\" type=\"button\" onClick=\"document.Category.Button.disabled=true; hyperlink('index.php?act=forum_manage&code=newcategory');\" class=\"textinput\">
			</TD>
		</TR>
	</form>
	</TBODY>
</TABLE>
";
clmain_body();
?>
<script language="JavaScript">
function CheckCategory(){var A1 = document.Category_Form.category_name.value; if (A1.length < 1) {alert("Please enter a category name");document.Category_Form.category_name.focus();return false;}else {document.Category_Form.Submit.disabled=true;return true;}}
function CheckForum(){var A1 = document.Forum_Form.forum_name.value;var A2 = document.Forum_Form.forum_description.value; if (A1.length < 1 || A1.length > 50) {alert("Please enter a forum name 1 - 50 characters");document.Forum_Form.forum_name.focus();return false;}else if (A2.length < 0 || A2.length > 100) {alert("Please enter a forum description 0 - 100 characters");document.Forum_Form.forum_description.focus();return false;}else {document.Forum_Form.Submit.disabled=true;return true;}}
</script>
<BR>
<?php
if($GET_code=="newcategory") {
opmain_body("Create a New Category");
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
	<TBODY>
	<form action=\"index.php?act=forum_manage&manage=newcategory\" method=\"post\" enctype=\"multipart/form-data\" name=\"Category_Form\" OnSubmit=\"return CheckCategory()\">
	<input type=\"hidden\" name=\"c\" value=\"newcategory\">
		<TR class=\"topic_title6\">
			<TD width=\"30%\"><div class=\"title_face4\"><B>Category Name</B></div></TD>
			<TD width=\"70%\"><input name=\"category_name\" type=\"text\" size=\"40\" maxlength=\"50\" class=\"textinput\" value=\"$category_name\"></TD>
		</TR>
			<TR class=\"topic_title5\">
				<TD width=\"100%\" colspan=\"2\" align=\"center\">
					<input type=\"submit\" name=\"Submit\" value=\"Create this category\" class=\"textinput\">
					<input type=\"reset\" name=\"Reset\" value=\"Reset\" class=\"textinput\">
				</TD>
		</TR>
	</form>
	</TBODY>
</TABLE>
";
clmain_body();
}
else if($GET_code=="newforum") {
$category_name = get_categoryname($GET_c);
$query = "SELECT g_id, g_title FROM $CONFIG_sql_cpdbname.groups ORDER by g_id";
$sql->result = $sql->execute_query($query,'forum_manage.php');
opmain_body("Create a New Forum");
echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<form action=\"index.php?act=forum_manage&manage=newforum\" method=\"post\" enctype=\"multipart/form-data\" name=\"Forum_Form\" OnSubmit=\"return CheckForum()\">
	<input type=\"hidden\" name=\"c\" value=\"$GET_c\">
	<TR>
		<TD>
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
	<TBODY>
		<TR class=\"topic_title5\">
			<TD height=\"27\" colspan=\"2\"><div class=\"title_face\">Create a forum in $category_name</div></TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD width=\"30%\"><div class=\"title_face4\"><B>Forum Name</B></div></TD>
			<TD width=\"70%\"><input name=\"forum_name\" type=\"text\" size=\"40\" maxlength=\"50\" class=\"textinput\"></TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD><div class=\"title_face4\"><B>Forum Description</B></div></TD>
			<TD><input name=\"forum_description\" type=\"text\" size=\"40\" maxlength=\"100\" class=\"textinput\"></TD>
		</TR>
	</TBODY>
</TABLE>
";
if($sql->count_rows()) {
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
	<TR class=\"topic_title5\">
		<TD height=\"27\" colspan=\"5\"><div class=\"title_face\">Permission</div></TD>
	</TR>
	<TR align=\"center\" class=\"topic_title6\" style=\"font-weight: bold;\">
		<TD>Groups</TD>
		<TD>Show Forum</TD>
		<TD>Read Topics</TD>
		<TD>Reply Topics</TD>
		<TD>Start Topics</TD>
	</TR>
";
	while($grow = $sql->fetch_row()) {
		switch($grow[0]) {
			case 1:
			case 2:
			case 4:
			case 5:
				$forum_perm = array(' checked',' checked',' checked',' checked');
				break;
			case 3:
			default:
				$forum_perm = array('','','','');
				break;
		}
		$show_forum_selected = $forum_perm[0];
		$read_topics_selected = $forum_perm[1];
		$reply_topics_selected = $forum_perm[2];
		$start_topics_selected = $forum_perm[3];
echo "	<TR align=\"center\" class=\"topic_title6\">
		<TD>$grow[1]</TD>
		<TD><input name=\"show_forum_".$grow[0]."\" type=\"checkbox\" value=\"1\"".$show_forum_selected."></TD>
		<TD><input name=\"read_topics_".$grow[0]."\" type=\"checkbox\" value=\"1\"".$read_topics_selected."></TD>
		<TD><input name=\"reply_topics_".$grow[0]."\" type=\"checkbox\" value=\"1\"".$reply_topics_selected."></TD>
		<TD><input name=\"start_topics_".$grow[0]."\" type=\"checkbox\" value=\"1\"".$start_topics_selected."></TD>
	</TR>
";
	}
echo "</TABLE>
";
}
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
	<TR class=\"topic_title5\">
		<TD width=\"100%\" align=\"center\">
			<input type=\"submit\" name=\"Submit\" value=\"Create this forum\" class=\"textinput\">
			<input type=\"reset\" name=\"Reset\" value=\"Reset\" class=\"textinput\">
		</TD>
	</TR>
</TABLE>
		</TD>
	</TR>
	</form>
</TABLE>
";
clmain_body();
}
else if($GET_code=="editcategory" && $GET_c) {
$category_name = get_categoryname($GET_c);
opmain_body("Edit Category");
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
	<TBODY>
		<form action=\"index.php?act=forum_manage&manage=editcategory\" method=\"post\" enctype=\"multipart/form-data\" name=\"Category_Form\" OnSubmit=\"return CheckCategory()\">
		<input type=\"hidden\" name=\"c\" value=\"$GET_c\">
		<TR class=\"topic_title5\">
			<TD height=\"27\" colspan=\"2\"><div class=\"title_face\">$category_name</div></TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD width=\"30%\"><div class=\"title_face4\"><B>Category Name</B></div></TD>
			<TD width=\"70%\"><input name=\"category_name\" type=\"text\" size=\"40\" maxlength=\"50\" class=\"textinput\" value=\"$category_name\"></TD>
		</TR>
			<TR class=\"topic_title5\">
				<TD width=\"100%\" colspan=\"2\" align=\"center\">
					<input type=\"submit\" name=\"Submit\" value=\"Edit this category\" class=\"textinput\">
					<input type=\"reset\" name=\"Reset\" value=\"Restore\" class=\"textinput\">
				</TD>
		</TR>
	</form>
	</TBODY>
</TABLE>
";
clmain_body();
}
else if($GET_code=="editforum" && $GET_f) {
$query = "SELECT forum_title, forum_description, forum_perm FROM $CONFIG_sql_cpdbname.forum WHERE forum_id=\"".mysql_res($GET_f)."\"";
$sql->result = $sql->execute_query($query,'forum_manage.php');
$frow = $sql->fetch_row();
$forum_name = $frow[forum_title];
$forum_description = $frow[forum_description];

$query = "SELECT g_id, g_title FROM $CONFIG_sql_cpdbname.groups ORDER by g_id";
$sql->result = $sql->execute_query($query,'forum_manage.php');
opmain_body("Edit Forum");
echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
	<form action=\"index.php?act=forum_manage&manage=editforum\" method=\"post\" enctype=\"multipart/form-data\" name=\"Forum_Form\" OnSubmit=\"return CheckForum()\">
	<input type=\"hidden\" name=\"f\" value=\"$GET_f\">
	<TR>
		<TD>
<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
	<TBODY>
		<TR class=\"topic_title5\">
			<TD height=\"27\" colspan=\"2\"><div class=\"title_face\">$forum_name</div></TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD width=\"30%\"><div class=\"title_face4\"><B>Forum Name</B></div></TD>
			<TD width=\"70%\"><input name=\"forum_name\" type=\"text\" size=\"40\" maxlength=\"50\" class=\"textinput\" value=\"$forum_name\"></TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD><div class=\"title_face4\"><B>Forum Description</B></div></TD>
			<TD><input name=\"forum_description\" type=\"text\" size=\"40\" maxlength=\"100\" class=\"textinput\" value=\"$forum_description\"></TD>
		</TR>
	</TBODY>
</TABLE>
";
if($sql->count_rows()) {
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
	<TR class=\"topic_title5\">
		<TD height=\"27\" colspan=\"5\"><div class=\"title_face\">Permission</div></TD>
	</TR>
	<TR align=\"center\" class=\"topic_title6\" style=\"font-weight: bold;\">
		<TD>Groups</TD>
		<TD>Show Forum</TD>
		<TD>Read Topics</TD>
		<TD>Reply Topics</TD>
		<TD>Start Topics</TD>
	</TR>
";
	while($grow = $sql->fetch_row()) {
		if ( preg_match( "#\[g".$grow[0]."\](.+?)\[/g".$grow[0]."\]#is", $frow[2] ) ) {
			preg_replace_callback( "#\[g".$grow[0]."\](.+?)\[/g".$grow[0]."\]#is", 'get_forum_perm' , $frow[2] );
			if (strstr($forum_permission,':show_perm:'))
				$show_forum_selected = " checked";
			else
				$show_forum_selected = "";
			if (strstr($forum_permission,':read_perm:'))
				$read_topics_selected = " checked";
			else
				$read_topics_selected = "";
			if (strstr($forum_permission,':reply_perm:'))
				$reply_topics_selected = " checked";
			else
				$reply_topics_selected = "";
			if (strstr($forum_permission,':start_perm:'))
				$start_topics_selected = " checked";
			else
				$start_topics_selected = "";
		} else {
			$show_forum_selected = "";
			$read_topics_selected = "";
			$reply_topics_selected = "";
			$start_topics_selected = "";
		}
echo "	<TR align=\"center\" class=\"topic_title6\">
		<TD>$grow[1]</TD>
		<TD><input name=\"show_forum_".$grow[0]."\" type=\"checkbox\" value=\"1\"".$show_forum_selected."></TD>
		<TD><input name=\"read_topics_".$grow[0]."\" type=\"checkbox\" value=\"1\"".$read_topics_selected."></TD>
		<TD><input name=\"reply_topics_".$grow[0]."\" type=\"checkbox\" value=\"1\"".$reply_topics_selected."></TD>
		<TD><input name=\"start_topics_".$grow[0]."\" type=\"checkbox\" value=\"1\"".$start_topics_selected."></TD>
	</TR>
";
	}
echo "</TABLE>
";
}
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
	<TR class=\"topic_title5\">
		<TD width=\"100%\" align=\"center\">
			<input type=\"submit\" name=\"Submit\" value=\"Edit this forum\" class=\"textinput\">
			<input type=\"reset\" name=\"Reset\" value=\"Restore\" class=\"textinput\">
		</TD>
	</TR>
</TABLE>
		</TD>
	</TR>
	</form>
</TABLE>
";
clmain_body();
}
else if($GET_code=="dropcategory" && $GET_c) {
$category_name = get_categoryname($GET_c);
opmain_body("Delete Category");
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
	<TBODY>
	<form action=\"index.php?act=forum_manage&manage=dropcategory\" method=\"post\" enctype=\"multipart/form-data\" name=\"Category_Form\">
	<input type=\"hidden\" name=\"c\" value=\"$GET_c\">
		<TR class=\"topic_title5\">
			<TD height=\"27\" colspan=\"2\"><div class=\"title_face\">$category_name</div></TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD width=\"30%\"><div class=\"title_face4\"><B>Category to remove:</B></div></TD>
			<TD width=\"70%\"><B>$category_name</B></TD>
		</TR>
			<TR class=\"topic_title5\">
				<TD width=\"100%\" colspan=\"2\" align=\"center\">
					<input type=\"submit\" name=\"Submit\" value=\"Delete this category\" class=\"textinput\">
				</TD>
		</TR>
	</form>
	</TBODY>
</TABLE>
";
clmain_body();
}
else if($GET_code=="dropforum" && $GET_f) {
$category_name = get_categoryname(check_category($GET_f));
$forum_name = get_forumname($GET_f);
opmain_body("Delete Forum");
echo "<TABLE width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\">
	<TBODY>
	<form action=\"index.php?act=forum_manage&manage=dropforum\" method=\"post\" enctype=\"multipart/form-data\" name=\"Forum_Form\">
	<input type=\"hidden\" name=\"f\" value=\"$GET_f\">
		<TR class=\"topic_title5\">
			<TD height=\"27\" colspan=\"2\"><div class=\"title_face\">$category_name -> $forum_name</div></TD>
		</TR>
		<TR class=\"topic_title6\">
			<TD width=\"30%\"><div class=\"title_face4\"><B>Forum to remove:</B></div></TD>
			<TD width=\"70%\"><B>$forum_name</B></TD>
		</TR>
			<TR class=\"topic_title5\">
				<TD width=\"100%\" colspan=\"2\" align=\"center\">
					<input type=\"submit\" name=\"Submit\" value=\"Delete this forum\" class=\"textinput\">
				</TD>
		</TR>
	</form>
	</TBODY>
</TABLE>
";
clmain_body();
}
}
} else {
	redir("index.php?act=idx","$lang[No_privilege]",3);
}
?>