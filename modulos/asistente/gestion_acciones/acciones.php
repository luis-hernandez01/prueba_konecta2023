<?php

require_once("php/formulario_basico.php");

class Admin_accion extends formulario_basico {

    function validar() {
        $v = new Validation($_POST);

        $v->addRules('menu', 'Menu', array('required' => true));
        $v->addRules('accion', 'Accion', array('required' => true, 'maxLength' => 60));
        $v->addRules('tipo_accion', 'Tipo acción', array('required' => true));
        $v->addRules('archivo', 'Archivo', array('required' => true, 'maxLength' => 100));
        $v->addRules('requiere_permiso', 'Requiere permiso', array('required' => true, 'maxLength' => 1));

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
        $s = "";
        if ($_GET["menu"] != "" && $_GET["menu"] != "NULL") {
            $s.= " AND a.menu = '$_GET[menu]'";
        } else if (ACCION == "listar") {
            exit(0);
        }

        $sql = "SELECT a.*, m.nombre
                FROM admin_accion a, admin_menu m 
                WHERE a.menu=m.menu $s
                ORDER BY m.nombre, a.archivo
                ";
        return $sql;
    }

}

$_POST['id']= desencriptar_id($_POST['id'],TOKEN);
$_GET['id']= desencriptar_id($_GET['id'],TOKEN);

//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA

$accion = ACCION;
$f = new Admin_accion("admin_accion", "id", true);
$f->$accion();
?>