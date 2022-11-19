<?php
require_once("php/clase_base.php");
class Clave extends clase_base
{
	function cambiar()
	{
		$db = $this->db;
		
		$r=array();
		$clave_actual = $db->select_one("SELECT clave FROM   general.persona WHERE identifica='$_SESSION[usuario]'");
		if( $clave_actual !=clave($_POST['clave1']) )
		{
			$r['error']=true;
			$r['msg']="La contraseña escrita no coinciden con la actual.";
		}
		else if ( $_POST['clave1'] == $_POST['clave2']  )
		{
			$r['error']=true;
			$r['msg']="La clave antigua y la nueva no pueden ser iguales.";		
		}
		else if( trim($_POST['clave2'])=="" )
		{
			$r['error']=true;
			$r['msg']="La nueva contraseña no puede estar en blanco.";
		}
		else if( $_POST['clave2'] != $_POST['clave3'] )
		{
			$r['error']=true;
			$r['msg']="Debe escribir dos veces la nueva contraseña";
		}
		else
		{
			$nueva_clave=clave($_POST['clave2']);
			$sql="UPDATE   general.persona SET clave='$nueva_clave'  WHERE identifica='$_SESSION[usuario]'";
			$db->query($sql);
			$r['error']=false;
			$r['msg']="La clave ha sido cambiada con éxito, se cerrara la sesión automáticamente para que vuelva a ingresar con su nueva clave";
			session_destroy();		
		}
		
		echo json_encode($r);
	}

}
$r=new Clave();
$accion=$_GET['_accion'];
$r->$accion();
?>