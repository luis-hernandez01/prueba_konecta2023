<?php
require_once("php/formulario_basico.php");
//require_once("php/formulario_basicoSQLSERVER.php");
class Pre_publicacion extends formulario_basico {

    function validar() {
        $v = new Validation($_POST);
		$v->addRules('id_persona', 'Persona', array('required' => true, 'integer' => true, 'maxLength' => 11) );
		$v->addRules('tiquete', 'Tiquete', array('required' => true, 'maxLength' => 20) );
		$v->addRules('id_estado', 'Estado', array('required' => true, 'integer' => true, 'maxLength' => 11) );
		$v->addRules('fecha_limite', 'Fecha limite', array('required' => true) );

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
        if ($_GET["tiquete"] != "" && $_GET["tiquete"] != "NULL") { 
            $s.= " AND tiquete LIKE '%" . str_replace(" ","%",$_GET['tiquete']) ."%' ";
        }
        if ($_GET["desde_id_estado"] != "" && $_GET["desde_id_estado"] != "NULL") { 
            $s.= " AND id_estado >= '$_GET[desde_id_estado]' ";
        }
        if ($_GET["hasta_id_estado"] != "" && $_GET["hasta_id_estado"] != "NULL") { 
            $s.= " AND id_estado <= '$_GET[hasta_id_estado]' ";
        }
        if ($_GET["fecha_limite"] != "" && $_GET["fecha_limite"] != "NULL") { 
            $s.= " AND fecha_limite LIKE '%" . str_replace(" ","%",$_GET['fecha_limite']) ."%' ";
        }
        $sql = "SELECT * FROM pre_publicacion WHERE 1=1  $s ORDER BY id ASC ";
        return $sql;
    }

}
//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA


$_POST['id']= desencriptar_id($_POST['id'],TOKEN);
$_GET['id']= desencriptar_id($_GET['id'],TOKEN);


$accion = ACCION;
$f = new Pre_publicacion("pre_publicacion", "id", true);
$f->$accion();
?>