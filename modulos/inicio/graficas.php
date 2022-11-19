<?php
class Formulario extends Base {
   
   private $en_terminos_BD = array();

   private function meses($nom)
   {
     $m = array('Ene' =>1, 
      'Feb' =>2, 
      'Mar' =>3, 
      'Abr' =>4, 
      'May' =>5, 
      'Jun' =>6, 
      'Jul' =>7, 
      'Ago' =>8, 
      'Sep' =>9, 
      'Oct' =>10, 
      'Nov' =>11, 
      'Dic' =>12);
     return $m[$nom];
   }

  private function meses_key($nom,$op=true)
   {
     $m = array(1=>'Enero', 
      2=>'Febrero', 
      3=>'Marzo', 
      4=>'Abril', 
      5=>'Mayo', 
      6=>'Junio', 
      7=>'Julio', 
      8=>'Agosto', 
      9=>'Septiembre', 
      10=>'Octubre', 
      11=>'Noviembre', 
      12=>'Diciembre');
     if ($op==true) {
       return substr($m[$nom], 0,3);
     }
     return $m[$nom];
   }

  
  function totales()
  {
    
     $total_expedientes = count(get_expedientes_rango(1));
     $total_expedientes_visita = count(get_expedientes_visita(1));

     $s_ct=" AND (ct.tipo_concepto_id<>3 and ct.tipo_concepto_id<>4) ";
     $total_expedientes_ct = count(get_expedientes_ct(1,$s_ct));

     $total_expedientes_activos = $this->db->select_one("SELECT count(*) FROM expedientes_seguimiento WHERE id_activo=1 ");
     
     $total_expedientes_actos = count(get_expedientes_actos(1));

        $result=array();
        $result["error"] = false;
        $result["total_expedientes"] = $total_expedientes_activos.' / '.$total_expedientes; 
        $result["total_expedientes_visita"] = $total_expedientes_visita; 
        $result["total_expedientes_ct"] = $total_expedientes_ct; 
        $result["total_expedientes_actos"] = $total_expedientes_actos; 
        echo json_encode($result);
  }


  function select_sectores()
  {
    $sectores_padres = $this->db->select_all("SELECT * FROM sectores_seguimiento ORDER BY orden");
    $sql_permiso = get_sql_sectores_seguimiento_idPersona($_SESSION['persona_id']);
    $op="";
      foreach ($sectores_padres as $key => $v) {           
          $sectores_hijos = $this->db->select_all("SELECT * FROM sub_sectores WHERE sectores_seguimiento_id='$v[id]' and $sql_permiso ORDER BY nombre");
          if ($sectores_hijos) {
             $op.="<div class='form-check form-check-success dropdown-item'>
                            <label class='form-check-label'>
                              <input type='checkbox' class='form-check-input' checked='' name='ck_$v[id]' value='$v[id]' onchange='graficar_metas_visita()' >
                               $v[nombre]
                            <i class='input-helper'></i></label>
                          </div>";
          }
      }
   
        $result=array();
        $result["error"] = false;
        $result["data"] = $op; 
        echo json_encode($result);

  }


  function metas_visita()
  {
    $data = get_expedientes_visita(1);
    $grafica = array();
    $g=array();
    foreach ($data as $key => $v) {
       $grafica[$v['ano_fin'].'_'.$v['mes_fin']][]=$v;
       $grafica_clasificada[$v['ano_fin'].'_'.$v['mes_fin']][$v['tipo_visita_id']][]=$v;
       $anos[$v['ano_fin']]=$v['ano_fin'];
       $meses[$v['ano_fin']][$v['mes_fin']]=$v['mes_fin'];
       $tipos[$v['tipo_visita_id']]=$v['tipo_visita_id'];
       $tabla_datos[$v['tipo_visita_id']][]=$v;
       $eje_x[$v['ano_fin']."-".$v['mes_fin']."-01"] = $v['ano_fin']."-".$this->meses_key($v['mes_fin']);
    }
    
    //FORMATEO EL EJE X
    foreach ($eje_x as $k => $v) {
      $g['x'][]=$v;
    }

    // LLENAMOS LA GRAFICA

    $tipos_clasificacion = $this->db->select_all("SELECT * FROM tipo_visita");
    foreach ($anos as $key => $val) {
      foreach ($meses[$val] as $k => $v) {
        foreach ($tipos_clasificacion as $y => $r) {
          if ($tipos[$r['id']]) {
            $g[$r['nombre']][] = count($grafica_clasificada[$val.'_'.$v][$r['id']]);
          }  
        }
        
      }
    }

     $tabla='<div class="accordion accordion-bordered" id="accordion-2" role="tablist">';

        foreach ($tipos_clasificacion as $y => $r) {
          if ($tipos[$r['id']]) {
            $tabla.='<div class="card">
                        <div class="card-header" role="tab" id="heading-vs'.$r[id].'">
                          <h6 class="mb-0">
                            <a data-toggle="collapse" href="#collapse-vs'.$r[id].'" aria-expanded="false" aria-controls="collapse-4">
                              '.count($tabla_datos[$r['id']]).' - '.$r[nombre].'
                            </a>
                          </h6>
                        </div>
                        <div id="collapse-vs'.$r[id].'" class="collapse" role="tabpanel" aria-labelledby="heading-vs'.$r[id].'" data-parent="#accordion-2">
                          <div class="card-body">';
                             
                           $tabla.='<table class="table"><thead> <tr> <th>Año/Mes</th> <th>Total</th> <th>%</th> </tr> </thead><tbody>';
                             foreach ($anos as $key => $val) {
                                foreach ($meses[$val] as $k => $v) {
                                  $total_grande = count($tabla_datos[$r['id']]);
                                  $total=count($grafica_clasificada[$val.'_'.$v][$r['id']]);
                                  $tabla.='<tr> <td>'.$val.'/'.$this->meses_key($v,false).'</td> <td>'.$total.'</td> <td>'.number_format($total/$total_grande*100,2).'%</td> </tr>';                          
                                }
                             }
                             

                          $tabla.='</tbody></table></div>
                        </div>
                      </div>';
          }  
        }
                  
                   $tabla.=' </div>';
   
        $result=array();
        $result["error"] = false;
        $result["data"] = $g; 
        $result["tabla"] = $tabla; 
        echo json_encode($result);
  }









   function metas_ct()
  {
    $s_ct=" AND (ct.tipo_concepto_id<>3 and ct.tipo_concepto_id<>4) ";
    $data = get_expedientes_ct(1,$s_ct);
    $grafica = array();
    $g=array();
    
    foreach ($data as $key => $v) {
       $grafica[$v['ano_fin'].'_'.$v['mes_fin']][]=$v;
       $grafica_clasificada[$v['ano_fin'].'_'.$v['mes_fin']][$v['tipo_concepto_id']][]=$v;
       $anos[$v['ano_fin']]=$v['ano_fin'];
       $meses[$v['ano_fin']][$v['mes_fin']]=$v['mes_fin'];
       $tipos[$v['tipo_concepto_id']]=$v['tipo_concepto_id'];
       $tabla_datos[$v['tipo_concepto_id']][]=$v;
       $eje_x[$v['ano_fin']."-".$v['mes_fin']."-01"] = $v['ano_fin']."-".$this->meses_key($v['mes_fin']);
    }
    
    //FORMATEO EL EJE X
    foreach ($eje_x as $k => $v) {
      $g['x'][]=$v;
    }

    // LLENAMOS LA GRAFICA

    $tipos_clasificacion = $this->db->select_all("SELECT * FROM tipo_concepto");
    foreach ($anos as $key => $val) {
      foreach ($meses[$val] as $k => $v) {
        foreach ($tipos_clasificacion as $y => $r) {
          if ($tipos[$r['id']]) {
            $g[$r['nombre']][] = count($grafica_clasificada[$val.'_'.$v][$r['id']]);
          }  
        }
        
      }
    }

     $tabla='<div class="accordion accordion-bordered" id="accordion-2" role="tablist">';

        foreach ($tipos_clasificacion as $y => $r) {
          if ($tipos[$r['id']]) {
            $tabla.='<div class="card">
                        <div class="card-header" role="tab" id="heading-ct_'.$r[id].'">
                          <h6 class="mb-0">
                            <a data-toggle="collapse" href="#collapse-ct_'.$r[id].'" aria-expanded="false" aria-controls="collapse-4">
                              '.count($tabla_datos[$r['id']]).' - '.$r[nombre].'
                            </a>
                          </h6>
                        </div>
                        <div id="collapse-ct_'.$r[id].'" class="collapse" role="tabpanel" aria-labelledby="heading-ct_'.$r[id].'" data-parent="#accordion-2">
                          <div class="card-body">';
                             
                           $tabla.='<table class="table"><thead> <tr> <th>Año/Mes</th> <th>Total</th> <th>%</th> </tr> </thead><tbody>';
                             foreach ($anos as $key => $val) {
                                foreach ($meses[$val] as $k => $v) {
                                  $total_grande = count($tabla_datos[$r['id']]);
                                  $total=count($grafica_clasificada[$val.'_'.$v][$r['id']]);
                                  $tabla.='<tr> <td>'.$val.'/'.$this->meses_key($v,false).'</td> <td>'.$total.'</td> <td>'.number_format($total/$total_grande*100,2).'%</td> </tr>';                          
                                }
                             }
                             

                          $tabla.='</tbody></table></div>
                        </div>
                      </div>';
          }  
        }
                  
                   $tabla.=' </div>';
   

        $result=array();
        $result["error"] = false;
        $result["data"] = $g; 
        $result["tabla"] = $tabla; 
        echo json_encode($result);
  }


   function metas_actos()
  {
     $data = get_expedientes_actos(1);
    $grafica = array();
    $g=array();
   
     foreach ($data as $key => $v) {
       $grafica[$v['ano_fin'].'_'.$v['mes_fin']][]=$v;
       $grafica_clasificada[$v['ano_fin'].'_'.$v['mes_fin']][$v['tipo_acto_seguimiento_id']][]=$v;
       $anos[$v['ano_fin']]=$v['ano_fin'];
       $meses[$v['ano_fin']][$v['mes_fin']]=$v['mes_fin'];
       $tipos[$v['tipo_acto_seguimiento_id']]=$v['tipo_acto_seguimiento_id'];
       $tabla_datos[$v['tipo_acto_seguimiento_id']][]=$v;
       $eje_x[$v['ano_fin']."-".$v['mes_fin']."-01"] = $v['ano_fin']."-".$this->meses_key($v['mes_fin']);
    }
    
    //FORMATEO EL EJE X
    foreach ($eje_x as $k => $v) {
      $g['x'][]=$v;
    }

    // LLENAMOS LA GRAFICA

    $tipos_clasificacion = $this->db->select_all("SELECT * FROM tipo_acto_seguimiento");
    foreach ($anos as $key => $val) {
      foreach ($meses[$val] as $k => $v) {
        foreach ($tipos_clasificacion as $y => $r) {
          if ($tipos[$r['id']]) {
            $g[$r['nombre']][] = count($grafica_clasificada[$val.'_'.$v][$r['id']]);
          }  
        }
        
      }
    }

     $tabla='<div class="accordion accordion-bordered" id="accordion-2" role="tablist">';

        foreach ($tipos_clasificacion as $y => $r) {
          if ($tipos[$r['id']]) {
            $tabla.='<div class="card">
                        <div class="card-header" role="tab" id="heading-actos_'.$r[id].'">
                          <h6 class="mb-0">
                            <a data-toggle="collapse" href="#collapse-actos_'.$r[id].'" aria-expanded="false" aria-controls="collapse-4">
                              '.count($tabla_datos[$r['id']]).' - '.$r[nombre].'
                            </a>
                          </h6>
                        </div>
                        <div id="collapse-actos_'.$r[id].'" class="collapse" role="tabpanel" aria-labelledby="heading-actos_'.$r[id].'" data-parent="#accordion-2">
                          <div class="card-body">';
                             
                           $tabla.='<table class="table"><thead> <tr> <th>Año/Mes</th> <th>Total</th> <th>%</th> </tr> </thead><tbody>';
                             foreach ($anos as $key => $val) {
                                foreach ($meses[$val] as $k => $v) {
                                  $total_grande = count($tabla_datos[$r['id']]);
                                  $total=count($grafica_clasificada[$val.'_'.$v][$r['id']]);
                                  $tabla.='<tr> <td>'.$val.'/'.$this->meses_key($v,false).'</td> <td>'.$total.'</td> <td>'.number_format($total/$total_grande*100,2).'%</td> </tr>';                          
                                }
                             }
                             

                          $tabla.='</tbody></table></div>
                        </div>
                      </div>';
          }  
        }
                  
                   $tabla.=' </div>';
   

        $result=array();
        $result["error"] = false;
        $result["data"] = $g; 
        $result["tabla"] = $tabla; 
        echo json_encode($result);
  }

}
//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA
 
$accion = ACCION;
$f = new Formulario();
$f->$accion();
?>