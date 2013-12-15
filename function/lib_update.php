<?php
function connect_cp_update() {
global $CP;
	$update['host'] = $CP['cp_cpud_host'];
	$update['port'] = "80";
	$fp = fsockopen($update['host'], $update['port'], $errno, $errstr, 30);
	if (!$fp)
		return false;
	fclose($fp);
	return true;
}
function file_get_result($filename,$mode=0) {
global $CP;
	$update['host'] = $CP['cp_cpud_host'];
	$update['port'] = "80";
	$fp = fsockopen($update['host'], $update['port'], $errno, $errstr, 30);
	if (!$fp) {
		cp_update_set_msg(6,"Failed to connect server");
		exit();
		//return false;
	} else {
		$header = "GET /update_cp/".$filename." HTTP/1.1\r\n";
		$header .= "Host: ".$update['host']."\r\n";
		$header .= "Connection: Close\r\n\r\n";

		fwrite($fp, $header);

		$idx = 0;
		$result = "";
		while (!feof($fp)) {
			$result[$idx++] = fgets($fp, 1024);
		}

		for($i=0;$i<$idx;$i++) {
			if ($step > 1) {
				if ($mode)
					$contents[] = $result[$i];
				else
					$contents .= $result[$i];
			}
			$step = ($step==1)?2:$step;
			if (!$step && strstr(strtolower(htmlspecialchars($result[$i])),"content-type") !== false )
				$step = 1;
		}

		fclose($fp);

		return $contents;
	}
}

class CP_Update {
	var $version = "";
	function true_version($version) {
		if ( preg_match( "#{(.+?)}#is", $version ) ) {
			preg_replace_callback( "#{(.+?)}#is", array( $this, '_true_version') , $version );
		return $this->version;
		} else
			return 0;
	}

	function _true_version($matches=array()) {
		$this->version = $matches[1];
	}

	function version_data($version,$i) {
		$data = explode("r", $version);
		if ($i==1 && !$data[$i])
			return 1;
		if (!$data[$i])
			return false;
		return $data[$i];
	}

	function true_list_version($version) {
		for($i=0;$i<count($version);$i++) {
			if ($version[$i]) {
				$version[$i] =  str_replace( "\n", "", $version[$i] );
				$version[$i] =  str_replace( "\r", "", $version[$i] );
				$list_version[] =  $version[$i];
			}
		}
		return $list_version;
	}

	function true_list_update_file($list) {
		for($i=0;$i<count($list);$i++) {
			if ($list[$i]) {
				$list[$i] =  str_replace( "\n", "", $list[$i] );
				$list[$i] =  str_replace( "\r", "", $list[$i] );
				$list_update_file[] =  $list[$i];
			}
		}
		return $list_update_file;
	}

	function true_list_download_file($list,$version) {
		for($i=0;$i<count($list);$i++) {
			if ($list[$i]) {
				$list[$i] =  str_replace( ".", "-", $list[$i] );
				$list_download_file[] =  "bin/".$version."/".$list[$i].".tmp";
			}
		}
		return $list_download_file;
	}

	function count_list_update_file($cp_version,$cp_release,$list_version) {
		$sgcp = new CP_Update;
		$count_files = 0;
		for($i=0;$i<count($list_version);$i++) {
			$load_version = $sgcp->version_data($list_version[$i],0);
			$load_release = $sgcp->version_data($list_version[$i],1);
			if ($load_version && cp_check_version($cp_version,$cp_release,$load_version,$load_release)) {
				$list_update_file = file_get_result("bin/".$list_version[$i]."/list.sgcp",1);
				$list_update_file = $this->true_list_update_file($list_update_file);
				$list_download_file = $this->true_list_download_file($list_update_file,$list_version[$i]);
				for ($j=0;$j<count($list_download_file);$j++) {
					$count_files++;
				}
			}
		}
		return $count_files;
	}

	function list_create_dir($path) {
		if (!$path)
			return;

		$path = preg_replace('/(\/){2,}|(\\\){1,}/','/',$path);	//only forward-slash
		$dirs = explode("/", $path);

		$path = "";
		for($i=0;$i<count($dirs)-1;$i++) {
			$path .= $dirs[$i]."/";
			if(!is_dir($path)) {
				if(!mkdir($path))
					exit();
			}
		}
	}
}

function cp_current_version() {
global $CP;
	$cp_version = file_get_result("check_version.php?version=".$CP[version]."&release=".$CP[release]."");
	$sgcp = new CP_Update;
	$cp_version = $sgcp->true_version($cp_version);
return $cp_version;
}

function cp_list_version() {
	$list_version = file_get_result("update_version.sgcp",1);
	$sgcp = new CP_Update;
	$list_version = $sgcp->true_list_version($list_version);
return $list_version;
}

function cp_check_version($version,$release,$version2,$release2) {
	if ($version == $version2 && $release < $release2)
		return true;
	else if ($version < $version2)
		return true;
	return false;
}

function backup_file($filename,$date) {
	$sgcp = new CP_Update;
	$bkup_dir = "tmp_update/backup";
	$dstdir = $bkup_dir."/".$date;

	// Check & Create dirs
	$sgcp->list_create_dir($dstdir."/".$filename);

	if (!$handle = fopen($dstdir."/".$filename, 'w')) {
		//echo "Cannot write file ($filename)<br />\n";
	} else {
		if ($contents = file_get_contents($filename)) {
			if (fwrite($handle, $contents) === FALSE) {
				//echo "Cannot write to file ($filename)";
			}
		}
		fclose($handle);
	}
}

function cp_update_set_msg($code,$msg) {
	switch ($code) {
		case 0:					// Status
			cp_update_set_msg_sub(0,$msg);
			break;
		case 1:					// Downloading
			cp_update_set_msg(0,1);
			cp_update_set_msg_sub(1,$msg);
			break;
		case 2:					// Updating
			cp_update_set_msg(0,2);
			cp_update_set_msg_sub(1,$msg);
			break;
		case 3:					// Percent completed
			cp_update_set_msg_sub(2,$msg);
			break;
		case 4:					// Failed to download
			cp_update_set_msg(0,4);
			cp_update_set_msg_sub(1,$msg);
			break;
		case 5:					// Failed to update
			cp_update_set_msg(0,5);
			cp_update_set_msg_sub(1,$msg);
			break;
		case 6:					// Failed to connect
			cp_update_set_msg(0,6);
			cp_update_set_msg_sub(1,$msg);
			break;
	}
}

function cp_update_set_msg_sub($code,$msg) {

	$dstdir = "tmp_update";
	$filename = $dstdir."/tmp.sgcp";

	if (!is_dir($dstdir))
		mkdir($dstdir);

	$contents = file_get_contents($filename);
	if (!$handle = fopen($filename, 'w+')) {
		//echo "Cannot write file ($filename)<br />\n";
	} else {
		if ($contents) {
			$tmp_msg = cp_update_replace_msg($code,$msg,$contents);
			if (fwrite($handle, $tmp_msg) === FALSE) {
			
			}
		} else {
			$tmp_msg = "".$code." = ".$msg."\r\n";
			if (fwrite($handle, $tmp_msg) === FALSE) {
			}
			
		}
		fclose($handle);
	}
}

function cp_update_replace_msg($code,$msg,$contents) {
	if (preg_match("#(".$code.") = .+?\r\n#is", $contents)) {
		$contents = preg_replace( "#(".$code.") = .+?\r\n#is","\\1 = ".$msg."\r\n", $contents );
	} else
		$contents .= "".$code." = ".$msg."\r\n";
return $contents;
}

function cp_update_get_msg($code,$contents) {
global $get_msg;
	if (preg_match("#".$code." = (.+?)\r\n#is", $contents)) {
		preg_replace_callback( "#".$code." = (.+?)\r\n#is", '_cp_update_get_msg' , $contents );
		global $get_msg;
		return $get_msg;
	} else
		return -1;
}

function _cp_update_get_msg($matches=array()) {
global $get_msg;
	$get_msg = $matches[1];
}

?>