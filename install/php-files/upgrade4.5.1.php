<?php
/** This file isn't sql file for query. **/

if(!$SERVER['system_safe']) exit();
$version = "0692f36eb27607e4837760bbbf813d92";
$query_file = "upgrade4.5.1.php";
$query = "SELECT COUNT(*) FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"upgrade_sgcp\" AND memory_value1=\"".mysql_res($version)."\"";
$sql->result = $sql->execute_query($sql,$query_file,0);
if ($sql->result()) {
	$query = "SELECT topic_id,forum_id FROM $CONFIG_sql_cpdbname.board_topic";
	$sql->result = $sql->execute_query($sql,$query_file,0);
	while ($row = $sql->fetch_row()) {
		sql->execute_query("UPDATE $CONFIG_sql_cpdbname.board_reply SET forum_id=\"".mysql_res($row['forum_id'])."\" WHERE topic_id=\"".mysql_res($row['topic_id'])."\"",$query_file,0);
	}
	sql->execute_query("DELETE FROM $CONFIG_sql_cpdbname.memory WHERE memory_object =\"upgrade_sgcp\" AND memory_value1=\"".mysql_res($version)."\"",$query_file,0);
}
?>