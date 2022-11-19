<?php
header('Content-Type: text/html; charset=UTF-8');
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

session_name("sistema-de-xxx"); // NOMBRE DE LA SESION
session_start();

error_reporting(E_ERROR | E_PARSE );

require_once("configuracion.php");
require_once("php/db_mysql.php");
require_once("php/db_sqlserver.php");
require_once("php/funciones_general.php");
require_once("php/clase_base.php");


if (!getBrowser()) {
  require_once('navegador.php');exit(0);
}



date_default_timezone_set('America/Bogota'); /* Ajustar zona horaria a colombia */
setlocale(LC_ALL, "esm", "es_CO.utf8"); /* Configuracion regional a Español */

$db = new db_mysql();
@$db->pconnect($cfg['db_host'], $cfg['db_user'], $cfg['db_password'], $cfg['db_database_name'], $cfg['db_port']);

//$dbsql = new SQLSRV_DataBase($cfg_sql['UID'], $cfg_sql['PWD'], $cfg_sql['Database'], $cfg_sql['IntanciaSQL']);

if ($db->connect_error()) {
    echo "<h1>Error de conexión con el servidor de base de datos.</h1>";
    exit(0);
}




$db->set_charset("utf8");
/* * ***************************************************************************** */
if (isset($_SESSION['usuario']))
    $db->query("set @usuario='$_SESSION[usuario]'");
if (isset($_SESSION['sesion_id']))
    $db->query("set @sesion='$_SESSION[sesion_id]'");
if (isset($_GET['menu']))
    $db->query("set @menu='$_GET[menu]'");
/* * ***************************************************************************** */


if (!isset($_SESSION['acceso_menu'])) {
    $_SESSION['acceso_menu'] = array(1, 2);
}
//Aplicar
$__POST = $_POST;
$__GET = $_GET;


$_GET = array_map("escape_string", $_GET);
$_POST = array_map("escape_string", $_POST);


/* Guardar información de sesión en la base de datos */
// Pendiente evaluar efectos secuncario de rendimiento de este codigo
$fhoy = date('Y-m-d');
if ($_SESSION['usuario']) {

if (!$db->select_row("SELECT * FROM admin_sesion WHERE usuario='$_SESSION[usuario]' and DATE(inicio)='$fhoy' ")) {
    //Es la primera vez que se carga la pagina en el navegador
    

    $sid = session_id();
    $n = rand(1,5);
    $row = array();
    $row['usuario'] =  $_SESSION['usuario'];
    $row['session_id'] = $sid;
    $row['user_agent'] = $db->escape_string($_SERVER['HTTP_USER_AGENT']);
    $row['refer'] = $_SERVER['HTTP_REFERER'];
    $row['ip'] = $_SERVER['REMOTE_ADDR'];
    $row['inicio'] = date('Y-m-d H:i:s');
    $row['fin'] = date('Y-m-d H:i:s');
    $row['salida'] = "N";
    $row['jaz'] = $n;
    $db->insert("admin_sesion", $row);

    $id = $db->last_insert_id();
    $_SESSION['sesion_id'] = $id;
    $row = array();
    $_SESSION['nueva_session'] = true;  
    $row['old_session_id'] = $sid ;
    $row['session_id'] = $sid ;
    $row['user_agent'] = $db->escape_string($_SERVER['HTTP_USER_AGENT']);
    $row['refer'] = $_SERVER['HTTP_REFERER'] ;
    $row['ip'] = $_SERVER['REMOTE_ADDR'];
    $row['inico'] = date('Y-m-d H:i:s');
    $row['fin'] = date('Y-m-d H:i:s');
    $db->insert("admin_psesion",$row); 
    $_SESSION['sesion_db_id']=$db->last_insert_id();  




} else {
    $fin = date('Y-m-d H:i:s');
    $id=$_SESSION['sesion_db_id'];
    $sql="UPDATE admin_psesion SET fin='$fin' WHERE id='$id'";
    $db->query($sql);
}

 }

?>