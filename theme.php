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