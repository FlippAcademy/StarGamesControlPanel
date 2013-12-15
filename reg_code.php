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
if ($_GET['sc']) {
	require_once "memory.php";
	$sql = new MySQL;
	$sql->Connect($CONFIG_sql_host,$CONFIG_sql_username,$CONFIG_sql_password);
	$query = "SELECT sc_code FROM $CONFIG_sql_cpdbname.security_code WHERE sc_id = \"".mysql_res($_GET['sc'])."\"";
	$sql->result = $sql->execute_query($query,'reg_code.php');
	$row = $sql->fetch_row();
	$sc_code = $row['sc_code'];
	$reg_str = "";
	for($i = 0; $i < 6; $i++) {
		$ret_str.= $sc_code[$i];
		$ret_str.=" ";
	}
	$sc_code = $ret_str;

	$im = imagecreate(85, 20);
	$bgcolor = imagecolorallocate($im, 255, 255, 255);	// Background Color
	$fontcolor = imagecolorallocate($im, 000, 000, 000);	// Font Color

	imagestring($im, 3, 5,5, $sc_code, $fontcolor);

	header("Content-type: image/png");
	imagepng($im);
	imagedestroy($im);
}
?>
