<?php
require_once("php/clase_base.php");

class Admin_permiso_seccion extends clase_base {

    function cargar() {
        $sql = "select admin.id, se.nombre from admin_permiso_seccion as admin, seccion as se, subseccion as su where admin.id_subseccion=su.id and su.id_seccion=se.id and admin.id_persona='$_POST[persona_id]'";
        $result1 = $this->db->select_all($sql);

        $sql = "select su.nombre from admin_permiso_seccion as admin, subseccion as su where admin.id_subseccion=su.id and admin.id_persona='$_POST[persona_id]'";
        $result2 = $this->db->select_all($sql);

        $result = array_merge($result1, $result2);

        echo json_encode($result);
    }

    function listarPersonas() {
        $q = str_replace(" ", "%", $_GET['q']);
        $q = strtoupper($q);

        $sql = "SELECT id, CONCAT_WS('',nombre1,' ',apellido1,' ',apellido2, ' [',identifica,']') as text 
                    FROM  general.persona
                    WHERE  CONCAT_WS('',nombre1,' ',apellido1,' ',apellido2, ' [',identifica,']') LIKE '%$q%'
                    ORDER BY nombre1
                    LIMIT 100";
        echo $this->db->select_json($sql);
    }


    
    function validar() {
        
        $v = new Validation($_POST);
    
    		$v->addRules('id_persona', 'Funcionario', array('required' => true) );
		$v->addRules('id_seccion', 'Sección', array() );
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

    /*function getSQL() {

         $s="";

        if ($_GET["funcionario"] != "" && $_GET["funcionario"] != "NULL") {
            $s = str_replace(" ", "%", $_GET['funcionario']);
            $sql_extra = " and CONCAT_WS(' ', p.nombre1,p.apellido1,p.apellido2) LIKE '%$s%' ";
        } 
        $sql = "SELECT aps.*, aps.id, CONCAT_WS(' ', p.nombre1,p.apellido1,p.apellido2) as funcionario FROM admin_permiso_seccion AS aps, general.persona as p WHERE aps.id_persona=p.id $s ORDER BY id ASC ";
        return $sql;
    }*/

/* Permite agregar un dato */
   function agregar() {
             
       //$this->validar();
       $db = $this->db; // HOMOLOGAR CODIGO EXTERNO

       pre($_POST);
            
       $result["error"] = false;
       $result["msg"] = "Registro agregado con exito."; 
       echo json_encode($result);
   }



/* Permite editar un registro */
   function modificar() {
       $this->validar_token();       
       $this->validar();
       $db = $this->db; // HOMOLOGAR CODIGO EXTERNO


       //PONER CODIGO AQUI ATT : FABIO GARCIA :D
                          
       $result=array();
       $result["error"] = false;
       $result["msg"] = "PONER MENSAJE A ENVIAR AQUI."; 
       echo json_encode($result);
   }


/* Permite eliminar un registro */
   function eliminar() {
       $this->validar_token();       
       $this->validar();
       $db = $this->db; // HOMOLOGAR CODIGO EXTERNO


       //PONER CODIGO AQUI ATT : FABIO GARCIA :D
                          
       $result=array();
       $result["error"] = false;
       $result["msg"] = "PONER MENSAJE A ENVIAR AQUI."; 
       echo json_encode($result);
   }


   function listar_seccion()
    {
        $db = $this->db;

        $sql="SELECT se.* FROM seccion se, tipo_seccion ts WHERE se.id_tipo_seccion=ts.id and ts.id=1";
$secciones= $db->select_all($sql);
$data_seciones=array();
foreach ($secciones as $key => $rw) {
    $sql="SELECT * FROM subseccion WHERE id_seccion='$rw[id]'";
    $subseccion= $db->select_all($sql);
    foreach ($subseccion as $k => $v) {
        $v['seccion_padre'] = $rw['nombre'];
        $v['id_seccion_padre'] = $rw['id'];
        $data_seciones[$rw['id']][]=$v;
    }
 }

      $html_one_arbol = crear_arbol_user($data_seciones,'arbol_dos',$_POST['id_persona']);
     
       $result["error"] = false;
       $result["data"] = $html_one_arbol; 
       echo json_encode($result);


    }

}


//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA
 
$accion = ACCION;
$f = new Admin_permiso_seccion("admin_permiso_seccion", "id", true);
$f->$accion();
?>


