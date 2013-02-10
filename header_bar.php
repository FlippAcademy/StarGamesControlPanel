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
if ($CONFIG_check_server) {
	if ($CONFIG_maintenance) {
		$accsrv = "<font class=\"status_main\">$lang[Mantenance]</font>";
		$charsrv = "<font class=\"status_main\">$lang[Mantenance]</font>";
		$mapsrv = "<font class=\"status_main\">$lang[Mantenance]</font>";
		$total_online = "<font class=\"status_main\">N/A</font>";
	} else {
		$query = "SELECT * FROM $CONFIG_sql_cpdbname.status";
		$sql->result = $sql->execute_query($query,'header_bar.php',0);$sql->total_query++;
		if (!$sql->count_rows()) {
			$sql->result = $sql->execute_query("INSERT INTO $CONFIG_sql_cpdbname.status ( `last_checked` , `login` , `char` , `map` ) VALUES (NOW() , 0, 0, 0)",'header_bar.php',0);$sql->total_query++;
		}
		$line = $sql->fetch_row();
		$timediff = $CP[time] - $line[0];
		if($timediff > $CONFIG_time_check_intervals) {
			$acc = @fsockopen ($CONFIG_server_ip, $CONFIG_loginport, $errno, $errstr, 1);
			$char = @fsockopen ($CONFIG_server_ip, $CONFIG_charport, $errno, $errstr, 1);
			$map = @fsockopen ($CONFIG_server_ip, $CONFIG_mapport, $errno, $errstr, 1);
			$acc = $acc?1:0;
			$char = $char?1:0;
			$map = $map?1:0;
			$sql->execute_query("UPDATE $CONFIG_sql_cpdbname.status SET `last_checked` = \"".$CP['time']."\" ,`login` = \"".mysql_res($acc)."\",`char` = \"".mysql_res($char)."\",`map` = \"".mysql_res($map)."\"",'header_bar.php',0);$sql->total_query++;
		} else {
			$acc = $line[1];
			$char = $line[2];
			$map = $line[3];
		}
		if (!$acc) {
			$accsrv="<font class=\"status_off\">$lang[Offline]</font>";
		} else {
			$accsrv="<font class=\"status_on\">$lang[Online]</font>";
		}
		if (!$char) {
			$charsrv="<font class=\"status_off\">$lang[Offline]</font>";
		} else {
			$charsrv="<font class=\"status_on\">$lang[Online]</font>";
		}
		if (!$map) {
			$mapsrv="<font class=\"status_off\">$lang[Offline]</font>";
		} else {
			$mapsrv="<font class=\"status_on\">$lang[Online]</font>";
		}
		$query = "SELECT COUNT(*) as total FROM `char` WHERE online = '1'";
		$sql->result = $sql->execute_query($query,'header_bar.php',0);$sql->total_query++;
		$row = $sql->fetch_row();
		$total_online = $row["total"];
		
		if (empty($total_online)) {
			$total_online = '0';
		}
	}
} else {
	$accsrv = "<font class=\"status_hide\">$lang[Hide]</font>";
	$charsrv = "<font class=\"status_hide\">$lang[Hide]</font>";
	$mapsrv = "<font class=\"status_hide\">$lang[Hide]</font>";
	$total_online = "<font class=\"status_hide\">N/A</font>";
}
?>