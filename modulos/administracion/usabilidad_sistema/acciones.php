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
    
   function table_usuarios($data)
    {
       $t="";
        if ($_POST['ano']==date('Y')) {
          $meses_g = $this->db->select_all("SELECT * FROM spgi.mes WHERE id<='".date('m')."' ORDER BY id ASC");
        }else{
          $meses_g = $this->db->select_all("SELECT * FROM spgi.mes ORDER BY id ASC");
        }
        $num=0;
        $meses_grafica = array();
        $roles_grafica = array();


        foreach ($data as $key => $v) {

          $rol = $v['nombre_rol'];
          $sql_sesiones = "SELECT count(*) as total,MONTH(admin_sesion.inicio) as mes FROM admin_sesion  
          WHERE  YEAR(admin_sesion.inicio)='$_POST[ano]' and admin_sesion.usuario='$v[identifica]' GROUP BY mes";

         // echo $sql_sesiones;
          $meses=array();
          $sesiones = $this->db->select_all($sql_sesiones);
          if ($sesiones) {
            $num++;
          
          $roles_grafica[$rol] = $roles_grafica[$rol]+1;
          

          $total_sesiones=0;
          foreach ($sesiones as $k => $val) {
            $meses[$val['mes']] = $val['total'];
            $total_sesiones = $total_sesiones+ $val['total'];

            $meses_grafica[$val['mes']] = $meses_grafica[$val['mes']]+$val['total'];
          }
           
            $t.='<tr>
                  <td>'.($num).'</td>
                  <td>'.$v['nombre1'].' '.$v['nombre2'].' '.$v['apellido1'].'</td>
                  <td>'.$rol.'</td><td>'.$v['correo'].'</td>';
                  

                  foreach ($meses_g as $key => $v) {
                     $t.='<td>'.$meses[$v['id']].'</td>';
                  }
                
                 $t.='<td>'.$total_sesiones.'</td>
              </tr>';

          }
        }
       

       ksort($meses_grafica);
       $g2 = array();
       foreach ($meses_grafica as $key => $v) {

         $nombre_mes = $this->db->select_one("SELECT nombre FROM spgi.mes WHERE id='".$key."' ORDER BY id ASC");
         if (count($meses_g)>6) {
              $nombre_mes = substr($nombre_mes, 0, 3);
         }

         $mini_g2 = array();
         $mini_g2['name'] = $nombre_mes;
         $mini_g2['y'] = $v;
         //$mini_g2['color'] =colores($key);

         //$g2[]=$mini_g2;

         $g[$nombre_mes][]=$v;

       }

         return array($t,$g,$g);
    }
   
 


/* Permite cargar la información inicial de la vista */
   function cargar() {
        $sql = "SELECT p.*,ar.nombre as nombre_rol
        FROM general.persona p , admin_usuario au,admin_rol ar
        WHERE p.estado=1 and p.id=au.persona_id  and ar.id=au.rol
        GROUP BY p.id
        ORDER BY ar.nombre";
        //echo $sql;
        $data = $this->db->select_all($sql);
        
        if ($_POST['ano']==date('Y')) {
          $meses = $this->db->select_all("SELECT * FROM spgi.mes WHERE id<='".date('m')."' ORDER BY id ASC");
        }else{
          $meses = $this->db->select_all("SELECT * FROM spgi.mes ORDER BY id ASC");
        }
        $data_tg = $this->table_usuarios($data);
        $t="";
        $t.='<table class="table table-bordered" id="tabla_usabilidad" style="font-size: 10px;">
          <thead>
              <tr>
                  <th>#</th>
                  <th>Usuario</th>
                  <th>Rol</th><th>Correo</th>';

                  foreach ($meses as $key => $v) {
                     if (count($meses)<=6) {
                       $mes = $v['nombre'];
                       $t.='<th>'.$mes.'</th>';
                     }else{
                      $mes = substr($v['nombre'], 0, 3);
                      $t.='<th>'.$mes.'</th>';
                     }
                     $categorias[]=$mes;
                  }
                  $t.='<th>Total Ingresos</th>
              </tr>
          </thead>';
          
           $t.='<tbody id="cuerpo">';
                     $t.=$data_tg[0];
          $t.='</tbody>
      </table>';
        $result=array();
        $result["error"] = false;
        $result["msg"] = "Información"; 
        $result["tabla"] = $t; 
        $result["g1"] = $data_tg[1];
        $result["g2"] =$data_tg[2];
        $result["categorias"] = $categorias; 
        echo json_encode($result);
   }



/* Permite ver el detalles de usabilidad por usuario */
   function ver_detalles_usuario() {
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