<?php
class Formulario extends Base {
    function validar() {
        $v = new Validation($_POST);
		$v->addRules('motivo', 'Motivo', array('required' => true) );
		$v->addRules('observacion', 'Observacion', array('required' => true) );
		$v->addRules('consultorio', 'Consultorio', array('required' => true, 'maxLength' => 30) );
		$v->addRules('medico', 'Medico', array('required' => true, 'maxLength' => 30) );
		$v->addRules('paciente', 'Paciente', array('required' => true, 'maxLength' => 30) );
		$v->addRules('fecha_inicio', 'Fecha asignada por IPS', array('required' => true, "date" => true));
    $v->addRules('fecha_pedida', 'Fecha de solicitud por el paciente ', array('required' => true, "date" => true));
    $v->addRules('fecha_solicitada', 'Fecha requerida por el paciente', array('required' => true, "date" => true));
		$v->addRules('hora_inicio', 'Hora inicio', array('required' => true, 'maxLength' => 30) );
		//$v->addRules('hora_fin', 'NULL', array('required' => true, 'maxLength' => 30) );

        $result = $v->validate();

        if ($result['messages'] == "") {//No hay errores de validacion
            return true;
        } else { //Errores de validaci贸n
            $r['error'] = true;
            $r['msg'] = $result['messages'];
            $r['bad_fields'] = $result['bad_fields'];
            $r['errors'] = $result['errors'];
            echo json_encode($r);
            exit(0);
        }
        return true;
    }
    
     private function ConvertirHoraNormal($hora)
    {
        $date = explode(" ", $hora);

        if ($date[1]=='PM') {
            $minutes=explode(":", $date[0]);
            $horaNormal=($minutes[0]+12).":".$minutes[1];
            $vh1=($minutes[0]+12).$minutes[1];
        }else{
          $horaNormal=$date[0];
          $minutes=explode(":", $date[0]);
          $vh1=($minutes[0]).$minutes[1];
        }

        return array('hora_numerica'=>$vh1,'hora'=>$horaNormal);
    }

// VALIDAR actividades
private function validar_cita($hora_inicia,$hora_fin,$fecha,$medico,$consultorio,$hi,$hf)
{

$db = $this->db;

$s="SELECT * FROM actividades
 WHERE medico='$medico' and consultorio='$consultorio' and fecha_inicio='$fecha'and hora_inicio_numerica='$hora_inicia'";

$r= $db->query($s);
if($db->num_rows($r)>0)
{$e.="HORA Y FECHA OCUPADA\n";}

/*
$s="SELECT * FROM actividades
 WHERE medico='$medico' and consultorio='$consultorio' and fecha_inicio='$fecha'and hora_inicio_numerica='$hora_inicia' and hora_fin_numerica='$hora_fin'";


$r= $db->query($s);

if($db->num_rows($r)>0)
 {$e.="HORA INICIA y HORA TERMINA , estan ocupadas \n";}

$s="SELECT * FROM actividades
 WHERE medico='$medico' and consultorio='$consultorio' and fecha_inicio='$fecha'and '$hora_inicia'>=hora_inicio_numerica and '$hora_inicia'< hora_fin_numerica";
//$e.=$s.'<br/>';

$r= $db->query($s);

 if($db->num_rows($r)>0)
 {$e.="HORA INICIA, Interfiere con otra cita \n";}


$s="SELECT * FROM actividades
 WHERE medico='$medico' and consultorio='$consultorio' and fecha_inicio='$fecha'and hora_inicio_numerica<'$hora_fin' and '$hora_fin'<=hora_fin_numerica";
//$e.=$s.'<br/>';
$r= $db->query($s);

 if($db->num_rows($r)>0)
 {$e.="HORA TERMINA, Interfiere con otra cita \n";}
*/
if ($e!="") {
    $result=array();
    $result["error"] = true;
    $result["msg"] = $e; 
    return $result; 
    exit(0);
}
    
}// FIN VALIDAR

function color($n)
{
   switch ($n) {
       case 3:
           return '#FFC107';
           break;
         case 4:
            return '#ff7c53';
           break;
         case 5:
            return '#c1abe8';
           break;
         case 6:
            return '#b2d985';
           break;
         case 7:
           return '#3da8fc';
           break;
       
       default:
            return '#ffffff';
           break;
   }
}
    function eliminar() {

        $id = $_POST['id'];
        $sql = "DELETE FROM actividades WHERE id=".$id;
        $this->db->query($sql);
        $result=array();
        $result["error"] = false;
        $result["msg"] = "Eliminado con exito"; 
        echo json_encode($result);
    }

    function asignar_tema() {

        $_SESSION['tema'] = $_POST['tema'];

        $result=array();
        $result["error"] = false;
        $result["msg"] = "Tema asignado con exito";
        echo json_encode($result);
    }

    function crear()
    {
       $datos = $_POST;
       $usuarios = $_POST['usuarios'];
       unset($datos['usuarios']);

       //$v= $this->validar_cita($vhora_inicio,$vhora_fin,$datos['fecha_inicio'],$medico,$consultorio,$vhora_inicio,$vhora_fin);
       //if ($v['error']) {
       //    echo json_encode(array('error'=>true,'mensaje'=>$v['msg']));
       //}else{
        $inser = array();
        $inser['titulo']=$datos['titulo'];
        $inser['descripcion']=$datos['descripcion'];
        $inser['fecha_inicio']=date("Y-m-d", strtotime($datos['fecha_inicio']));
        $inser['fecha_fin']=date("Y-m-d", strtotime($datos['fecha_fin']));
        //$inser['estado']=$datos['consultorio'];
        $inser['prioridad']=$datos['prioridad'];
        $inser['hora_inicio ']=9;
        $inser['hora_fin']=17;
        $inser['hora_inicio_numerica']=9;
        $inser['hora_fin_numerica']=17;
        //$inser['color'] =  $this->db->select_one("SELECT color FROM prioridad WHERE id='".$datos['prioridad']."' ");
        $inser['id_tema']=$datos['id_tema'];
        $inser['id_usuario'] = $_SESSION['persona_id'];
        
        $this->db->insert('actividades',$inser);
        $id_actividad = $this->db->last_insert_id();

        foreach ($_POST['usuarios'] as $key => $id) {
            $datos = array('id_usuario' =>$id ,'id_actividad' =>$id_actividad );
            $this->db->insert('responsables',$datos);
        }

        echo json_encode(array('error'=>false,'msg'=>'Actividad registrada con exito'));
       //}
    }


    function calendario() {
        
        $tema = $_SESSION['tema'];
        if ($tema==0) {
          $query=$this->db->select_all('SELECT * FROM actividades WHERE 1 ORDER BY hora_inicio');    
        }else{
          $query=$this->db->select_all('SELECT * FROM actividades WHERE id_tema='.$tema.' ORDER BY hora_inicio'); 
        }
             
        $datos = array();
        foreach ($query as $key => $val) {
            $d=array();
            $d['id']=$val['id'];
            $d['title']=$this->db->select_one("SELECT nombre FROM tema WHERE id='". $val['id_tema']."' ").'('.$val['titulo'].')';
            $d['start']=$val['fecha_inicio'].'T0'.$val['hora_inicio'].':00:00';
            $d['end']=$val['fecha_fin'].'T'.$val['hora_fin'].':00:00';
            
            if ($val['estado']==2) {
               $d['color']=$this->db->select_one("SELECT color FROM prioridad WHERE id='". $val['prioridad']."' ");
            }else{
               $d['color']=$this->db->select_one("SELECT color FROM estado_actividad WHERE id='". $val['estado']."' ");
            }
           
            $datos[]=$d;
         }         
        //PONER CODIGO AQUI
        echo json_encode($datos);
    }

    function listar() {
        
        $sql = "SELECT * FROM actividades c  WHERE c.fecha_inicio='$_POST[fecha]'  or ('$_POST[fecha]'>=c.fecha_inicio and '$_POST[fecha]'<=c.fecha_fin)  ORDER BY c.hora_inicio ASC";
        //echo $sql;
        $datos=$this->db->select_all($sql); 
        $d = array();
        foreach ($datos as $key => $val) {
          $val['tema'] = $this->db->select_one("SELECT nombre FROM tema WHERE id='".$val['id_tema']."' ");
          $val['comentarios'] = $this->db->select_one("SELECT count(*) as total FROM comentarios WHERE id_actividad='".$val['id']."' ");
          $val['prioridad'] = $this->db->select_one("SELECT nombre FROM prioridad WHERE id='".$val['prioridad']."' ");
          $d[]=$val;
        }
        //$datos['medico']= nombre_usuario($datos['medico']);    
        echo json_encode($d);
    }

}
//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA
 
$accion = ACCION;
$f = new Formulario();
$f->$accion();
?>