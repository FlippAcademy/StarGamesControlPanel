<?php
if(!$SERVER['system_safe']) exit();
if($GET_act=='readnews')
	$name = 'readnews';
else
	$name = 'news';
echo "<TR><TD id=\"$name\"></TD><TR>
<script type='text/javascript'>page_select('$name','0')</script>";
?>