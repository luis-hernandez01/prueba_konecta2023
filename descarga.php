<?php
	require_once("php/validation.php");
	$r=obtener_ruta_menu(MENU,ACCION); 
	if($r['error']==false)
	{
		require_once($r['ruta']);
	}
	else
	{
		echo($r['msg']);
	}	
	
?>