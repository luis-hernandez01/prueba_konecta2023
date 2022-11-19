<?php
require_once("php/formulario_basico.php");
//require_once("php/formulario_basicoSQLSERVER.php");
class Productos extends formulario_basico {

    function validar() {
        $v = new Validation($_POST);
		$v->addRules('nombre_producto', 'Nombre producto', array('required' => true, 'maxLength' => 200) );
		$v->addRules('referencia', 'Referencia', array('required' => true) );
		$v->addRules('precio', 'Precio', array('required' => true, 'integer' => true, 'maxLength' => 11) );
		$v->addRules('peso', 'Peso', array('required' => true, 'integer' => true, 'maxLength' => 11) );
        $v->addRules('id_unidad_masa', 'Unidad de masa', array('required' => true) );
		$v->addRules('id_categoria', 'Categoria', array('required' => true) );
		$v->addRules('stock', 'Stock', array('required' => true, 'integer' => true, 'maxLength' => 11) );
		$v->addRules('fecha_creacion', 'Fecha creación', array('required' => true, 'date' => true) );

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

        if ($_GET["nombre_producto"] != "" && $_GET["nombre_producto"] != "NULL") { 
            $s.= " AND p.nombre_producto LIKE '%" . str_replace(" ","%",$_GET['nombre_producto']) ."%' ";
        }
        if ($_GET["referencia"] != "" && $_GET["referencia"] != "NULL") { 
            $s.= " AND p.referencia LIKE '%" . str_replace(" ","%",$_GET['referencia']) ."%' ";
        }
        $sql = "SELECT p.id, p.nombre_producto, p.referencia, p.precio, CONCAT(p.peso, u.sigla) As pesos, c.nombre AS nombre_categoria , p.stock,p.fecha_creacion FROM productos p, unidad_masa u, categoria c
            WHERE p.id_unidad_masa=u.id AND p.id_categoria=c.id  $s ORDER BY p.id ASC ";
        return $sql;
    }

}
//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA


$_POST['id']= desencriptar_id($_POST['id'],TOKEN);
$_GET['id']= desencriptar_id($_GET['id'],TOKEN);


$accion = ACCION;
$f = new Productos("productos", "id", true);
$f->$accion();
?>