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
if(empty($STORED_usertheme)){
	$STORED['THEME'] = $CONFIG_default_theme;
} else {
	$dir = "theme/$STORED_usertheme";
	if (is_dir($dir)){
		$STORED['THEME'] = $STORED_usertheme;
	} else {
		if($CONFIG_save_type==1) {
			session_unregister(usertheme);
		} else {
			CP_removeCookie("usertheme");
		}
		$STORED['THEME'] = $CONFIG_default_theme;
	}
}
include_once "theme/$STORED[THEME]/theme.php";
?>