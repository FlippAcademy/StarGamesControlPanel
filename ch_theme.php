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
require "memory.php";
if(empty($POST_ch_theme) && !$CONFIG_theme_select_mode)
	header("location:index.php?act=idx");
$HTTP_REFERER = get_referer();
if($POST_ch_theme!='') {
	$dir = "theme/$POST_ch_theme";

	if (is_dir($dir)) {
		$ch_theme = $POST_ch_theme;
	} else {
		$ch_theme = $CONFIG_default_theme;
	}

	if($CONFIG_save_type==1) {
		$_SESSION["usertheme"] = $ch_theme;
	} else {
		CP_setCookie("usertheme",$ch_theme);
	}
}
header("location:$HTTP_REFERER");
?>
