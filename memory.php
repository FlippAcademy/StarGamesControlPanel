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
$php_version = phpversion();

error_reporting(E_ALL ^ E_NOTICE);

include_once "function/lib_memory.php";
// remove quotes added by php
if (get_magic_quotes_gpc()) {
    CP_arrayWalkRecursive($_GET, 'stripslashes', true);
    CP_arrayWalkRecursive($_POST, 'stripslashes', true);
    CP_arrayWalkRecursive($_COOKIE, 'stripslashes', true);
    CP_arrayWalkRecursive($_REQUEST, 'stripslashes', true);
}

include_once "config.php";
include_once "extract.inc.php";
include_once "function/function.php";
include_once "function/job_class.php";
include_once "function/cp.php";
include_once "lang.php";

$CP['ip_address'] = get_ip();
$CP['time'] = time();
?>