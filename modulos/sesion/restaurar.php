<?php
class Formulario extends Base {
    function validar() {
        $v = new Validation($_POST);
		$v->addRules('validador_restaurar', 'Codigo de validacion', array('required' => true, 'length' => array(5, 15)));
        $v->addRules('correoR', 'Correo', array('required' => true));
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



    function restaurar_clave() {
        $this->validar();
        
        if (isset($_SESSION['usuario'])) {
            $r = array();
            $r['error'] = true;
            $r['msg'] = 'Acesso denegado';
            echo json_encode($r);
            exit();
        }

        if ($_SESSION['cap_code_restaurar'] <> strtoupper($_POST['validador_restaurar']) ) {
           $r = array();
           $r['error'] = true;
           $r['msg'] = 'Codigo de validacion incorrecto';
           echo json_encode($r);
           exit();
        }

        $DatosPersona = $this->db->select_row("SELECT * FROM  persona WHERE correo='$_POST[correoR]'");

        pre($DatosPersona);


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