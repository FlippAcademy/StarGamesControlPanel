/*
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
*/
<?php
if(!$SERVER['system_safe']) exit();
if($CONFIG_save_type==1) {
	cookie_remove(1);
	session_start();
	session_register("loginname");
	session_register("loginpass");
	session_register("userlang");
	session_register("usertheme");
}
getglobalvar(2);
if (empty($STORED_userlang)) {
	$STORED['LANG'] = $CONFIG_language;
} else {
	$dir = "lang/".$STORED_userlang.".php";

	if (is_file($dir)){
		$STORED['LANG'] = $STORED_userlang;
	} else {
		if($CONFIG_save_type==1) {
			session_unregister(userlang);
		} else {
			CP_removeCookie("userlang");
		}
		$STORED['LANG'] = $CONFIG_language;
	}
}
include_once "lang/$STORED[LANG].php";
include_once "theme.php";
$IMG['ARROW'] = "<IMG src=\"".$url_safe."theme/$STORED[THEME]/menu/arrow_quickmenu.gif\" width=\"3\" height=\"5\" hspace=\"5\" border=\"0\">";
?>
