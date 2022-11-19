<?php
class Formulario extends Base {
    function validar_token()
    {
        $hash = $_SERVER['HTTP_AUTHORIZATION'];
       
        if ($hash) {//No hay errores de validacion
             $_POST=desencriptar_post($_POST,$hash);
        }else { //Errores de validación
            $r['error'] = true;
            $r['msg'] = 'Error en TOKEN';
            echo json_encode($r);
            exit(0);
        }
        return true;
    }
    function validar() {
        
        $this->validar_token();       
        $v = new Validation($_POST);
    
        $v->addRules('nombre', 'Nombre', array('required' => true) );
        $v->addRules('apellido', 'Apellido', array('required' => true) );

    
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

/* Función inicial para probar el envío de variables */
   function aceptar() {
       $this->validar();
       $db = $this->db; // HOMOLOGAR CODIGO EXTERNO

       //PONER CODIGO AQUI ATT : FABIO GARCIA :D
                          
       $result=array();
       $result["error"] = false;
       $result["msg"] = "PONER MENSAJE A ENVIAR AQUI."; 
       echo json_encode($result);
   }

}
//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA


$accion = ACCION;
$f = new Formulario();
$f->$accion();
?>