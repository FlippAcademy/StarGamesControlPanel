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
session_start();

function imagecreatefrombmpstring($im) { // Credits to [Lebref] and [Magical Tux] found in Freya forum
	$header = unpack("vtype/Vsize/v2reserved/Voffset", substr($im, 0, 14));
	$info = unpack("Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vimportant", substr($im, 14, 40));
	extract($info);
	extract($header);
	if($type != 0x4D42)
		return false;
	$palette_size = $offset - 54;
	$ncolor = $palette_size / 4;
	$imres=imagecreatetruecolor($width, $height);
	imagealphablending($imres, false);
	imagesavealpha($imres, true);
	$pal=array();
	if($palette_size) {
		$palette = substr($im, 54, $palette_size);
		$gd_palette = "";
		$j = 0; $n = 0;
		while($j < $palette_size) {
			$b = ord($palette{$j++});
			$g = ord($palette{$j++});
			$r = ord($palette{$j++});
			$a = ord($palette{$j++});
			if ( ($r == 255) && ($g == 0) && ($b == 255))
				$a = 127; // alpha = 255 on 0xFF00FF
			$pal[$n++] = imagecolorallocatealpha($imres, $r, $g, $b, $a);
		}
	}
	$scan_line_size = (($bits * $width) + 7) >> 3;
	$scan_line_align = ($scan_line_size & 0x03) ? 4 - ($scan_line_size & 0x03): 0;
	for($i = 0, $l = $height - 1; $i < $height; $i++, $l--) {
		$scan_line = substr($im, $offset + (($scan_line_size + $scan_line_align) * $l), $scan_line_size);
		if($bits == 24) {
			$j = 0; $n = 0;
			while($j < $scan_line_size) {
				$b = ord($scan_line{$j++});
				$g = ord($scan_line{$j++});
				$r = ord($scan_line{$j++});
				$a = 0;
				if ( ($r == 255) && ($g == 0) && ($b == 255))
					$a = 127; // alpha = 255 on 0xFF00FF
				$col=imagecolorallocatealpha($imres, $r, $g, $b, $a);
				imagesetpixel($imres, $n++, $i, $col);
			}
		}
		else if($bits == 8) {
			$j = 0;
			while($j < $scan_line_size) {
				$col = $pal[ord($scan_line{$j++})];
				imagesetpixel($imres, $j-1, $i, $col);
			}
		}
		else if($bits == 4) {
			$j = 0; $n = 0;
			while($j < $scan_line_size) {
				$byte = ord($scan_line{$j++});
				$p1 = $byte >> 4;
				$p2 = $byte & 0x0F;
				imagesetpixel($imres, $n++, $i, $pal[$p1]);
				imagesetpixel($imres, $n++, $i, $pal[$p2]);
			}
		}
		else if($bits == 1) {
			$j = 0; $n = 0;
			while($j < $scan_line_size) {
				$byte = ord($scan_line{$j++});
				$p1 = (int) (($byte & 0x80) != 0);
				$p2 = (int) (($byte & 0x40) != 0);
				$p3 = (int) (($byte & 0x20) != 0);
				$p4 = (int) (($byte & 0x10) != 0);
				$p5 = (int) (($byte & 0x08) != 0);
				$p6 = (int) (($byte & 0x04) != 0);
				$p7 = (int) (($byte & 0x02) != 0);
				$p8 = (int) (($byte & 0x01) != 0);
				imagesetpixel($imres, $n++, $i, $pal[$p1]);
				imagesetpixel($imres, $n++, $i, $pal[$p2]);
				imagesetpixel($imres, $n++, $i, $pal[$p3]);
				imagesetpixel($imres, $n++, $i, $pal[$p4]);
				imagesetpixel($imres, $n++, $i, $pal[$p5]);
				imagesetpixel($imres, $n++, $i, $pal[$p6]);
				imagesetpixel($imres, $n++, $i, $pal[$p7]);
				imagesetpixel($imres, $n++, $i, $pal[$p8]);
			}
		}
	}
	return $imres;
}

if (isset($_SESSION['emblems'])) {
	$emblems = $_SESSION['emblems'];
	if ($_GET['data'] && $emblems[$_GET['data']] != "") {
		$ebm = @gzuncompress(pack('H*', $emblems[$_GET['data']]));
		if (function_exists("gd_info")) {
			$im = imagecreatefrombmpstring($ebm);
			header('Content-Type: image/png');
			imagepng($im);
			imagedestroy($im);
		} else {
			header('Content-Type: image/bitmap');
			echo $ebm;
		}
	}
}

if (function_exists("gd_info")) {
	$im  = imagecreate (24, 24); 
	$bgc = imagecolorallocate($im, 0, 0, 0);
	imagefill($im, 0, 0, $bgc);
	header('Content-Type: image/png');
	imagepng($im);
	imagedestroy($im);
} else {
	$in = fopen("theme/$STORED[THEME]/images/no_emblema.bmp", "rb");
	$data = fread($in, 65535);
	header('Content-Type: image/bitmap');
	echo $data;
}
?>
