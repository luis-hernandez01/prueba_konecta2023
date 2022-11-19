<?php
class Formulario extends Base {
    function validar() {
        $v = new Validation($_POST);
		$v->addRules('persona_id', 'Usuario', array('required' => true) );
        $v->addRules('clave', 'Contraseña nueva', array('required' => true, 'length' => array(5, 15)));
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


    function aceptar() {
        $this->validar();
        
        $nueva_clave=clave($_POST['clave']);
            $sql="UPDATE   general.persona SET clave='$nueva_clave'  WHERE id='$_POST[persona_id]'";
            $this->db->query($sql);
            $r['error']=false;
            $r['msg']="La clave ha sido cambiada con éxito";
        
        echo json_encode($r);
    }

}
//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA
 
$accion = ACCION;
$f = new Formulario();
$f->$accion();
?>