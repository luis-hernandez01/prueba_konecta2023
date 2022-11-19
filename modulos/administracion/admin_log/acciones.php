<?php
require_once("php/formulario_basico.php");
//require_once("php/formulario_basicoSQLSERVER.php");
class Admin_log extends formulario_basico {

    function validar() {
        $v = new Validation($_POST);
		$v->addRules('id_persona', 'Persona', array('required' => true, 'integer' => true, 'maxLength' => 11) );
		$v->addRules('menu', 'Menu', array('required' => true, 'maxLength' => 145) );
		$v->addRules('archivo', 'Archivo', array('required' => true, 'maxLength' => 250) );
		$v->addRules('accion', 'Accion', array('maxLength' => 145) );
		$v->addRules('tipo', 'Tipo', array('required' => true, 'integer' => true, 'maxLength' => 11) );
		$v->addRules('mensaje', 'Mensaje', array('required' => true, 'maxLength' => 245) );
		$v->addRules('fechar', 'Fechar', array('required' => true) );

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

        if ($_GET["desde_id_persona"] != "" && $_GET["desde_id_persona"] != "NULL") { 
            $s.= " AND id_persona >= '$_GET[desde_id_persona]' ";
        }
        if ($_GET["hasta_id_persona"] != "" && $_GET["hasta_id_persona"] != "NULL") { 
            $s.= " AND id_persona <= '$_GET[hasta_id_persona]' ";
        }
        if ($_GET["menu"] != "" && $_GET["menu"] != "NULL") { 
            $s.= " AND menu LIKE '%" . str_replace(" ","%",$_GET['menu']) ."%' ";
        }
        $sql = "SELECT * FROM admin_log WHERE 1=1  $s ORDER BY id ASC ";
        return $sql;
    }

}
//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA


$_POST['id']= desencriptar_id($_POST['id'],TOKEN);
$_GET['id']= desencriptar_id($_GET['id'],TOKEN);


$accion = ACCION;
$f = new Admin_log("admin_log", "id", true);
$f->$accion();
?>