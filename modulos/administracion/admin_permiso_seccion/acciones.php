<?php
require_once("php/formulario_basico.php");
//require_once("php/formulario_basicoSQLSERVER.php");
class Admin_permiso_seccion extends formulario_basico {

    function validar() {
        $v = new Validation($_POST);
		$v->addRules('id_persona', 'Funcionario', array('required' => true) );

        $v->addRules('id_seccion', 'Sección', array() );
        $v->addRules('id_subseccion', 'Subsección', array('required' => true) );
        $v->addRules('fecha_inicio', 'Fecha inicio', array('required' => true, 'date' => true) );
        $v->addRules('fecha_final', 'Fecha fin', array('required' => true, 'date' => true) );
        $v->addRules('visible', 'Visible', array('required' => true) );
        $v->addRules('id_subseccion', 'Subsección', array('required' => true) );
        $v->addRules('fecha_inicio', 'Fecha inicio', array('required' => true, 'date' => true) );
        $v->addRules('fecha_final', 'Fecha fin', array('required' => true, 'date' => true) );
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

        if ($_GET["funcionario"] != "" && $_GET["funcionario"] != "NULL") {
            $s = str_replace(" ", "%", $_GET['funcionario']);
            $sql_extra = " and CONCAT_WS(' ', p.nombre1,p.apellido1,p.apellido2) LIKE '%$s%' ";
        } 
        $sql = "SELECT aps.*, CONCAT_WS(' ', p.nombre1,p.apellido1,p.apellido2) as funcionario FROM admin_permiso_seccion AS aps, general.persona as p WHERE aps.id_persona=p.id $s ORDER BY id ASC ";
        return $sql;
    }


    function listar_seccion()
    {
        $db = $this->db;

        $idseccion = $_POST['id_seccion'];
        $sql = "SELECT su.id AS codigo, su.nombre AS nombre 
                FROM
                    subseccion su
                WHERE
                     su.id='$idseccion' ORDER BY su.nombre";


        $rs= $db->select_all($sql);

       
        echo json_encode($rs);
    }

}
//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA


$_POST['id']= desencriptar_id($_POST['id'],TOKEN);
$_GET['id']= desencriptar_id($_GET['id'],TOKEN);


$accion = ACCION;
$f = new Admin_permiso_seccion("admin_permiso_seccion", "id", true);
$f->$accion();
?>