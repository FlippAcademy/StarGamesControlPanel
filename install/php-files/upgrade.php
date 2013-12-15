<?php
/** This file isn't sql file for query. **/

if(!$SERVER['system_safe']) exit();
$CP_version = $CP['version'];
switch($CP_version) {
	case "0692f36eb27607e4837760bbbf813d92":
		require "install/php-files/upgrade4.5.1.php";
		break;
}
?>