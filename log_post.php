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
if (count($_POST)) {
	$user = $CP[login_name];
	if(empty($user)) $user="-"; 
	foreach ($_POST as $key=>$val) {
		mysql_query("INSERT INTO $CONFIG_sql_cpdbname.post_log (Date,User,IP,url,val_name,val_input) VALUES (NOW() ,'".mysql_res($user)."','$CP[ip_address]','".mysql_res($_SERVER['REQUEST_URI'])."','".mysql_res($key)."','".mysql_res($val)."')");
	}
}
?>