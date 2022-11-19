<?php
require_once("php/formulario_basico.php");
//require_once("php/formulario_basicoSQLSERVER.php");
class Solicitar_documento extends formulario_basico {

    function validar() {
        $v = new Validation($_POST);

		$v->addRules('id_solicitante', 'Id solicitante', array('required' => true, 'integer' => true, 'maxLength' => 22) );
		$v->addRules('id_responsable_envia', 'Id responsable envia', array('integer' => true, 'maxLength' => 11) );
		$v->addRules('id_responsable_actual', 'Id responsable actual', array('integer' => true, 'maxLength' => 11) );


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

        if ($_GET["tiquete"] != "" && $_GET["tiquete"] != "NULL") { 
            $s.= " AND tiquete LIKE '%" . str_replace(" ","%",$_GET['tiquete']) ."%' ";
        }
        if ($_GET["tiquete_auxiliar"] != "" && $_GET["tiquete_auxiliar"] != "NULL") { 
            $s.= " AND tiquete_auxiliar LIKE '%" . str_replace(" ","%",$_GET['tiquete_auxiliar']) ."%' ";
        }
        if ($_GET["desde_id_responsable_actual"] != "" && $_GET["desde_id_responsable_actual"] != "NULL") { 
            $s.= " AND id_responsable_actual >= '$_GET[desde_id_responsable_actual]' ";
        }
        if ($_GET["hasta_id_responsable_actual"] != "" && $_GET["hasta_id_responsable_actual"] != "NULL") { 
            $s.= " AND id_responsable_actual <= '$_GET[hasta_id_responsable_actual]' ";
        }
        $sql = "SELECT * FROM solicitar_documento WHERE 1=1  $s ORDER BY id ASC ";
        return $sql;
    }

}
//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA


$_POST['id']= desencriptar_id($_POST['id'],TOKEN);
$_GET['id']= desencriptar_id($_GET['id'],TOKEN);


$accion = ACCION;
$f = new Solicitar_documento("solicitar_documento", "id", true);
$f->$accion();
?>