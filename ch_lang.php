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
require_once "memory.php";
if(empty($POST_ch_lang) && !$CONFIG_language_select_mode) exit();
$HTTP_REFERER = get_referer();
if($POST_ch_lang!='') {
	$dir = "lang/".$POST_ch_lang.".php";

	if (is_file($dir)) {
		$ch_lang = $POST_ch_lang;
	} else {
		$ch_lang = $CONFIG_language;
	}

	if($CONFIG_save_type==1) {
		$_SESSION["userlang"] = $ch_lang;
	} else {
		CP_setCookie("userlang",$ch_lang);
		//setcookie("userlang","$ch_lang",$CP[time]+60*60*24*30);
	}
}
header("location:$HTTP_REFERER");
?>