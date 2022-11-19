<?php
$cfg['db_host']="localhost";
$cfg['db_user']="root";
$cfg['db_password']="";
$cfg['db_database_name']="konecta";
$cfg['db_port']="3306";


$DE_DB = 'spgi';
$DA_DB = 'general';


if (isset($_SESSION['nombre_usuario'])) {$cfg['menu_inicio']="inicio";}else{$cfg['menu_inicio']="iniciar-sesion";}

define("EMPRESA","xxxxxx" );
define("EMPRESA_NIT","xxxx" );
define("NIT","xxxx" );
define("CODIGO_VALIDACION","xxxx" );
define("PDF_MODO_IMPRESION","I");
define("DA","general");
define("DE_DB","spgi");
define("NOMBRE_SISTEMA",'Gestión de Procesos');
define("SIGLA_SISTEMA",'GESPRO');

$DRAW_COLOR=array(204,204,204);
$FILL_COLOR=array(238,238,238);
$FILL_COLOR_TITULO=array(225,225,225);

define("FONDO_HTML","#eee");
define("FONDO_HTML_TITULO","#e1e1e1");
define("BORDE_HTML","#ccc");
define("DRAW_COLOR",$DRAW_COLOR);

define("RUTA_ARCHIVO","/Users/fabio/archivos/");
define("RUTA_TMP","/Users/fabio/tmp/");
?>