<?php
if(!$SERVER['system_safe']) exit();
if (count($_POST)) {
	$user = $CP[login_name];
	if(empty($user)) $user="-"; 
	foreach ($_POST as $key=>$val) {
		mysql_query("INSERT INTO $CONFIG_sql_cpdbname.post_log (Date,User,IP,url,val_name,val_input) VALUES (NOW() ,'".mysql_res($user)."','$CP[ip_address]','".mysql_res($_SERVER['REQUEST_URI'])."','".mysql_res($key)."','".mysql_res($val)."')");
	}
}
?>