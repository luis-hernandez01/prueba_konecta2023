<?php
require_once("php/formulario_basico.php");
//require_once("php/formulario_basicoSQLSERVER.php");
class Admin_guias extends formulario_basico {

    function validar() {
        $v = new Validation($_POST);
		$v->addRules('menu', 'Menu', array('required' => true) );
		$v->addRules('json', 'Json', array('required' => true) );
		$v->addRules('visible', 'Visible', array('required' => true) );

        $result = $v->validate();

        if ($result['messages'] == "") {//No hay errores de validacion
            return true;
        } else { //Errores de validación
            $r['error'] = true;
            $r['msg'] = $result['messages'];
            $r['bad_fields'] = $result['bad_fields'];
            $r['errors'] = $result['errors'];
            echo json_encode($r);
            exit(0);
        }
        return true;
    }

    function getSQL() {
        $s="";

        if ($_GET["menu"] != "" && $_GET["menu"] != "NULL") { 
            $s.= " AND menu = '$_GET[menu]'";
        }
        if ($_GET["visible"] != "" && $_GET["visible"] != "NULL") { 
            $s.= " AND visible = '$_GET[visible]'";
        }
        $sql = "SELECT * FROM admin_guias WHERE 1=1  $s ORDER BY id ASC ";
        return $sql;
    }

}
//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA


$_POST['id']= desencriptar_id($_POST['id'],TOKEN);
$_GET['id']= desencriptar_id($_GET['id'],TOKEN);


$accion = ACCION;
$f = new Admin_guias("admin_guias", "id", true);
$f->$accion();
?>