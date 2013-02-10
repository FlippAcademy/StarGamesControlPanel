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
require_once "function/lib_ajax.php";
include_once "gzip_header.php";
$sql = new MySQL;
switch($GET_module) {
	case "post_preview":
		$sql->Connect($CONFIG_sql_host,$CONFIG_sql_username,$CONFIG_sql_password);
		get_postpreview($POST_val);
		mysql_close($sql->link);
		break;
	case "show_poll_form":
		$sql->Connect($CONFIG_sql_host,$CONFIG_sql_username,$CONFIG_sql_password);
		require_once "user_profile.php";
		get_poll_form();
		mysql_close($sql->link);
		break;
	case "page_select":
		$sql->Connect($CONFIG_sql_host,$CONFIG_sql_username,$CONFIG_sql_password);
		require_once "user_profile.php";
		get_page_select($GET_name,$GET_st);
		mysql_close($sql->link);
		break;
	case "do_bb_code":
		get_bb_code($GET_form_id,$GET_name);
		break;
	case "check_reg":
		$sql->Connect($CONFIG_sql_host,$CONFIG_sql_username,$CONFIG_sql_password);
		get_attn_reg($GET_check,$POST_val,$POST_val2);
		mysql_close($sql->link);
		break;
	case "quick_edit_post":
		$sql->Connect($CONFIG_sql_host,$CONFIG_sql_username,$CONFIG_sql_password);
		require_once "user_profile.php";
		get_quick_edit_form($GET_p);
		mysql_close($sql->link);
		break;
	case "save_quick_edit":
		$sql->Connect($CONFIG_sql_host,$CONFIG_sql_username,$CONFIG_sql_password);
		require_once "user_profile.php";
		get_save_quick_edit($POST_val,$POST_p);
		mysql_close($sql->link);
		break;
	case "check_cp_update":
	case "refresh_cp_update":
	case "do_cp_update":
	case "status_cp_update":
	case "clear_cp_update":
		$sql->Connect($CONFIG_sql_host,$CONFIG_sql_username,$CONFIG_sql_password);
		require_once "user_profile.php";
		require_once "function/lib_update.php";
		switch($GET_module) {
			case "check_cp_update":
				get_cp_update();
				break;
			case "refresh_cp_update":
				refresh_cp_update($GET_code,$GET_position);
				break;
			case "do_cp_update":
				do_cp_update();
				break;
			case "status_cp_update":
				status_cp_update();
				break;
			case "clear_cp_update":
				clear_cp_update();
				break;
		}
		mysql_close($sql->link);
		break;
}
include_once "gzip_footer.php";
?>