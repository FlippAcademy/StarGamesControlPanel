<?php
if(!$SERVER['system_safe']) exit();
$HTTP_REFERER = get_referer();
redir("$HTTP_REFERER","$display",3);
?>