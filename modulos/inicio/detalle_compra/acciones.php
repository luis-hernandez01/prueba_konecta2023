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
        
        $v = new Validation($_POST);
    
    
    
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

/* Aceptar */
   // function aceptar() {
   //     $this->validar_token();       
   //     $this->validar();
   //     $db = $this->db; // HOMOLOGAR CODIGO EXTERNO


   //     //PONER CODIGO AQUI ATT : FABIO GARCIA :D
                          
   //     $result=array();
   //     $result["error"] = false;
   //     $result["msg"] = "PONER MENSAJE A ENVIAR AQUI."; 
   //     echo json_encode($result);
   // }

    /* Aceptar */
   function aceptar() {
       //$this->validar_token();       
       $this->validar();
       $db = $this->db; // HOMOLOGAR CODIGO EXTERNO

       $tabla2="productos";
        $sql = "SELECT * FROM $tabla2 WHERE id='$_GET[q]'";
        $stock = $this->db->select_row($sql);

       $cantidad=$_POST['cantidad'];
       if ($cantidad > $stock['stock']) {
           $result=array();
                   $result["error"] = true;
                   $result["msg"] = "La cantidad de productos solicitados supera el stock."; 
                   echo json_encode($result);
                   exit();
       }

       $insertar=array(); 
       $insertar['cantidad']= $_POST['cantidad'];
       $total=$_POST['cantidad']*$stock['precio'];
       $insertar['valor_total']= $total;
       $insertar['id_producto']=$_GET[q];

       $tabla="detalle";
        $this->db->insert($tabla,$insertar);


        $actuliza=array(); 
        

        $calculo=$stock['stock']-$_POST['cantidad'];
        $actuliza['stock']= $calculo;


        
         $this->db->update($tabla2,$actuliza,array('id'=>$_GET[q]));
       
           $result=array();
           $result["error"] = false;
           $result["msg"] = "Producto agregado con exito."; 
           echo json_encode($result);
   }

}
//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA
 
$accion = ACCION;
$f = new Formulario();
$f->$accion();
?>