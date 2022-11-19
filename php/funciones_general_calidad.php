<?php
 
 function crear_arbol($data,$id){
    $ar = "";
    $ar.='<div id="'.$id.'"><ul>';
      foreach ($data as $k => $hijos) {
        
        $rw = $hijos[0];// TOMAR DATOS DEL PADRE
        $ar.="<li class='mt-3'>";

         $ar.="<table class='table table-striped'>
                   <tr style='background:#eee'>
                     <td width='30%' >
                     <input type='checkbox'  onclick='marcar($rw[id_seccion_padre])' id='menu_".$rw['id_seccion_padre']."' name='menu[".$rw['id_seccion_padre']."]' value='S'/>
                     <label for='menu_".$rw["id_seccion_padre"]."'>".$rw["seccion_padre"]."</label>
                     </td>
                     <td width='30%' >

                     <div class='form-group'>
                        <label for='exampleFormControlSelect1'>Estado</label>
                        <select required class='form-control' name='inicio_$rw[id_seccion_padre]' id='inicio_$rw[id_seccion_padre]' onchange='asignar_fecha_inicio($rw[id_seccion_padre])'>
                           ".get_llenar_combo('SELECT id, nombre FROM estado ORDER BY nombre',true)."
                        </select>
                      </div>
                     </td>

                  </tr>
               </table>";

               

            #HIJOS INICIO
            $ar.="<ul>";
            foreach ($hijos as $key => $val) {
               $ar.="<li class='mt-2'>";
               //$ar.="";

               $ar.="<table class='table table-bordered'>
                   <tr>
                     <td width='30%' >

                     <input type='checkbox' id='smenu_".$val['id']."' name='smenu[]' class='menu_$val[id_seccion_padre]' value='".$val['id']."'/>
                     <label for='smenu_".$val["id"] ."' >".$val["fecha_llegada"]."</label>
                     </td>

                     <td width='30%' >

                     <div class='form-group'>
                        <label for='exampleFormControlSelect1'>Estado</label>
                        <select required class='fecha_inicio_$val[id_seccion_padre] form-control' name='sinicio_".$val['id']."' id='sinicio_".$val['id']."'>
                          ".get_llenar_combo('SELECT id, nombre FROM estado ORDER BY nombre',true)."
                        </select>
                      </div>
                  </tr>
               </table>";

               
               

               $ar.="</li>";
               
            }
             $ar.="</ul>";
            #HIJOS FIN

        $ar.="</li>";    
      }

    $ar.="</ul></div>";

    return $ar;

 } 

  function crear_arbol_user($data,$id,$id_persona){
    $ar = "";
    $ar.='<div id="'.$id.'"><ul>';
      foreach ($data as $k => $hijos) {
        
        $rw = $hijos[0];// TOMAR DATOS DEL PADRE
        $ar.="<li class='mt-3'>";

         $ar.="<table class='table table-striped'>
                   <tr style='background:#eee'>
                     <td width='30%' >
                     <input type='checkbox'  onclick='marcarU($rw[id_seccion_padre])' id='umenu_".$rw['id_seccion_padre']."' name='umenu[".$rw['id_seccion_padre']."]' value='S'/>
                     <label for='umenu_".$rw["id_seccion_padre"]."'>".$rw["seccion_padre"]."</label>
                     </td>
                     <td width='30%' ><input class='form-control' name='uinicio_$rw[id_seccion_padre]' onchange='asignar_fecha_inicioU($rw[id_seccion_padre])' id='uinicio_$rw[id_seccion_padre]' type='date'></td>
                     <td width='30%' ><input class='form-control' onchange='asignar_fecha_finU($rw[id_seccion_padre])' name='ufin_$rw[id_seccion_padre]' id='ufin_$rw[id_seccion_padre]' type='date'></td>

                  </tr>
               </table>";

               

            #HIJOS INICIO
            $ar.="<ul>";
            foreach ($hijos as $key => $val) {
               $ar.="<li class='mt-2'>";
               //$ar.="";

               $ar.="<table class='table table-bordered'>
                   <tr>
                     <td width='30%' >
                     <input type='checkbox' id='smenu_".$val['id']."' name='smenu[]' class='umenu_$val[id_seccion_padre]' value='".$val['id']."'/>
                     <label for='smenu_".$val["id"] ."' >".$val["nombre"]."</label>
                     </td>
                     <td width='30%' ><input class='form-control ufecha_inicio_$val[id_seccion_padre]' name='sinicio_".$val['id']."' id='sinicio_".$val['id']."' type='date'></td>
                     <td width='30%' ><input class='form-control ufecha_fin_$val[id_seccion_padre]' name='sfin_".$val['id']."' id='sfin_".$val['id']."' type='date'></td>

                  </tr>
               </table>";
               

               $ar.="</li>";
               
            }


             $ar.="</ul>";

            #HIJOS FIN

        $ar.="</li>";    
      }

    $ar.="</ul></div>";
    $ar.='<button type="button" class="btn btn-success mt-2 text-center" onclick="guardar_global()">Guardar</button>';

    return $ar;

 }


?>