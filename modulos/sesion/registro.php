<?php
class Formulario extends Base {
    function validar() {
        $v = new Validation($_POST);
		$v->addRules('tipoide', 'Tipo de documento', array('required' => true) );
        $v->addRules('validador_registro', 'Codigo de validacion', array('required' => true, 'length' => array(5, 7)));
        $v->addRules('motivo', 'Motivo', array('required' => true) );
        $v->addRules('correo', 'Correo', array('required' => true) );
        $v->addRules('apellido1', 'Primer apellido', array('required' => true) );
        $v->addRules('nombre1', 'Primer nombre', array('required' => true) );
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

    function registro() {
        $this->validar();
        
        if (isset($_SESSION['usuario'])) {
            $r = array();
            $r['error'] = true;
            $r['msg'] = 'Acesso denegado';
            echo json_encode($r);
            exit();
        }

        if ($_SESSION['cap_code_registro'] <> strtoupper($_POST['validador_registro']) ) {
           $r = array();
           $r['error'] = true;
           $r['msg'] = 'Codigo de validacion incorrecto';
           echo json_encode($r);
           exit();
        }

        if (validar_documento($_POST['identifica'],'persona')) {
           
           $r['msg'] = 'Documento ya existe en el sistema.';
           $r['error'] = true;
           echo json_encode($r);
           exit(0);
        } 

        if (validar_correo($_POST['correo'],'persona')) {
        
          $r['msg'] = 'Correo ya existe en el sistema.';
          $r['error'] = true;
          echo json_encode($r);
          exit(0);
        }  
        $_POST['clave']=clave($_POST['identifica']);
        
        unset($_POST['validador_registro']);
        $this->db->insert('persona', $_POST);
         
        $id_persona = $this->db->last_insert_id();

        $data_sectores = array(1,4,6,7,8);

        foreach ($data_sectores as $k => $v) {
            $insert = array();
            $insert['sectores_id']=$v;
            $insert['persona_id']=$id_persona;
            $insert['id_usuario']=$id_persona;
            //pre($insert);
            $this->db->insert('permiso_usuario_sector',$insert);
        }


        $r['error']=false;
        $r['msg']="Registro realizado con exito, su usuario y clave con su numero de documento";
        echo json_encode($r);
    }

}
//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA
 
$accion = ACCION;
$f = new Formulario();
$f->$accion();
?>