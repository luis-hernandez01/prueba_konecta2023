<?php
if (!function_exists('is_login')) { include_once '404.php'; exit(); }
	ob_start();
	if (is_login()) { 
	  include_once 'ingreso.php';
	}else{ 
	  include_once 'ingresar.php';
	}
?>