<?php
class Formulario extends Base {
    function validar() {
        $v = new Validation($_POST);
        $v->addRules('persona_id', 'Usuario', array('required' => true) );
        //$v->addRules('corrientes', 'corrientes', array('required' => true, 'maxLength' => 30) );

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
                    FROM general.persona
                    WHERE  CONCAT_WS('',nombre1,' ',apellido1,' ',apellido2, ' [',identifica,']') LIKE '%$q%'
                    ORDER BY nombre1
                    LIMIT 100";
        echo $this->db->select_json($sql);
    }

        function actualizar()
    {

        $corrientes = $_POST['corrientes'];
        $persona_id = $_SESSION['persona_id_corriente'];
        $sql = "DELETE FROM permiso_grupos WHERE id_persona='$persona_id' ";
        $this->db->query($sql);
        
        foreach ($corrientes as $key => $id) {
          $insert = array('grupos_id' =>$id,'id_persona' =>$persona_id,'id_usuario' =>$_SESSION['persona_id']);
          $this->db->insert('permiso_grupos',$insert);
        }
        //unset($_SESSION['persona_id_sector']);
        $result=array();
        $result["error"] = false;
        $result["msg"] = 'Cambio realizado con exito'; 
        echo json_encode($result);
    }



    function aceptar() {
        $this->validar();
        $id= $_POST['persona_id'];
        $_SESSION['persona_id_corriente']=$_POST['persona_id'];
        global $DE_DB;
        $sql = "SELECT g.*,d.sigla as dependencia,d.nombre as dependencia_nombre 
        FROM $DE_DB.grupos g, $DE_DB.dependencias d 
        WHERE d.id=g.dependencias_id and g.visible=1 ORDER BY d.orden,d.sigla";


        $grupos = $this->db->select_all($sql);
        $dependencias_grupos = array();
         foreach ($grupos as $key => $v) {
             $dependencias_grupos[$v['dependencia']]['nombre'] = $v['dependencia_nombre'];
             $dependencias_grupos[$v['dependencia']]['grupos'][] = $v;
         }

        $permiso_grupos = $this->db->select_all("SELECT * FROM permiso_grupos WHERE id_persona='$id' ");
        foreach ($permiso_grupos as $key => $v) {
           $corrientes_usuario[$v['grupos_id']]=$v;
        }
        
        //pre ($dependencias_grupos);
        foreach ($dependencias_grupos as $key => $val) {
            
            if (count($val['grupos'])>1) {
                    
             
               $class_categoria = str_replace(" ","_",$key);
               $se.='<div class="col-md-12 no_cerrar" style="    margin-top: 20px;">';

                  $se.='<nav aria-label="breadcrumb">';
                      $se.='<ol class="breadcrumb breadcrumb-custom" style="background-color: #336f32;">';
                        $se.='<li class="breadcrumb-item"><a href="#" style="color:white">Dependencia</a></li>';
                        $se.="<li class='breadcrumb-item active' aria-current='page' style='color:white'> <input style='width: 15px;height:15px;' class='p_corrientes' id='todos_$class_categoria' onchange=".'cambiar_todo("'.$class_categoria.'")'." type='checkbox'> <span>$val[nombre]</span> <b>[$key]</b> </li>";
                      $se.='</ol>';
                    $se.='</nav>';

                $se.='</div>';


                   foreach ($val['grupos'] as $k => $v) {
                        
                         $c="";
                         if ($corrientes_usuario[$v['id']]) {
                            $c = 'checked="true"';
                         }
                         
                         $class_categoria = str_replace(" ","_",$v['dependencia']);

                         $se.='<div class="col-md-12 no_cerrar">';
                            $se.='<div class="row no_cerrar" style="width:100%;border-bottom: 1px solid #028d3b;margin: 0px;">';
                               $se.='<div class="col-md-2"><input style="width: 15px;height:15px;" class="p_corrientes p_'.$class_categoria.'" value="'.$v['id'].'" name="corrientes[]" type="checkbox" '.$c.' "></div>';
                               $se.='<div class="col-md-10">'.$v['dependencia'].' - '.$v['sigla'].'</div>';
                            $se.='</div>';
                         $se.='</div>';
                   }

            }else{


                foreach ($val['grupos'] as $k => $v) {
                        
                         $c="";
                         if ($corrientes_usuario[$v['id']]) {
                            $c = 'checked="true"';
                         }
                         
                         $class_categoria = str_replace(" ","_",$v['sigla']);

                  $se.='<div class="col-md-12 no_cerrar" style="    margin-top: 20px;">';

                  $se.='<nav aria-label="breadcrumb">';
                      $se.='<ol class="breadcrumb breadcrumb-custom" style="background-color: #336f32;">';
                        $se.='<li class="breadcrumb-item"><a href="#" style="color:white">Dependencia</a></li>';
                        $se.='<li class="breadcrumb-item active" aria-current="page" style="color:white"> <input style="width: 15px;height:15px;" class="p_corrientes p_'.$class_categoria.'" value="'.$v['id'].'" name="corrientes[]" type="checkbox" '.$c.' "> '.$v['dependencia_nombre'].' <b>['.$v['sigla'].']</b></li>';
                      $se.='</ol>';
                    $se.='</nav>';

                   $se.='</div>';

                   }




            }
        }

        $result=array();
        $result["error"] = false;
        $result["msg"] = $se; 
        echo json_encode($result);
    }

}
//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA
 
$accion = ACCION;
$f = new Formulario();
$f->$accion();
?>
    