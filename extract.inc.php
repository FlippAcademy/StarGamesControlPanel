<?php
extract($_GET, EXTR_PREFIX_ALL, "GET");
extract($_POST, EXTR_PREFIX_ALL, "POST");

extract($CONFIG, EXTR_PREFIX_ALL, "CONFIG");

?>