<?php
require_once("php/formulario_basico.php");

class Rol extends formulario_basico {

    function validar() {
        $v = new Validation($_POST);
        $v->addRule("nombre", "required", true, "Nombre");
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
      
        if ($_GET["nombre"] != "" && $_GET["nombre"] != "NULL") {
            $t = str_replace(" ", "%", $_GET['nombre']);
            $sql_extra = " WHERE nombre LIKE '%$t%' ";
        }
        $sql = "SELECT * FROM admin_rol $sql_extra ORDER BY nombre ";
 
        return $sql;
    }
    

}
$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA
$_POST['_usuario'] = $_SESSION['usuario'];
$_POST['_fecha'] = date("Y-m-d H:i:s");
$_POST['id']= desencriptar_id($_POST['id'],TOKEN);
$_GET['id']= desencriptar_id($_GET['id'],TOKEN);

$accion = ACCION;
$f = new rol("admin_rol", "id", true);
$f->$accion();

?>